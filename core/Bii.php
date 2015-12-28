<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Bii
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class Bii {
    private static $singleInstance = null;
    private static $config = '';
    private static $corePath;

    private static  function getSingleInstance(){
        if(self::$singleInstance == null){
            self::$singleInstance = new self;
        }
        return self::$singleInstance;
    }

    public static function app(){
         return self::getSingleInstance();
    }
    
    public static function createWebApp($configPath){
         self::$config = include $configPath;
         self::$corePath = __DIR__;
         return self::app();
         //加载配置文件中的数据
    }
    
    //读取配置文件中内容
    public  function  getConfig($key=''){
        if(empty($key)){
            return false;
        }
        if(isset(self::$config[$key])){
            return self::$config[$key];
        }else{
            return false;
        }
    }

    //根据读取的配置文件可以call_user_func();
    public function run(){
        //加载核心类与应用程序类
        $this->loadCoreAndAppFile();
        //加载系统组件
        $this->loadSystemCompoent();
        //根据路由配置 
        $this->selectRoute();
    }
    
    //获取url参数 获取路由
    public function selectRoute(){
        Url::setParamsByUri();
        $module = Url::$module;
        if(!file_exists(APP_PATH."/modules/".$module)){
             exit("此模块{$module}不存在");
        }
        
        $controller = Url::$controller;
        $controllerFile =  ucfirst($controller)."Controller.php";
        if(!file_exists(APP_PATH."/modules/".$module."/".$controllerFile)){
             exit("此Controller:{$controller}不存在");
        }
        
        $action = Url::$action;
        $actionName =  "action".ucfirst($action);
        $controllerClassName = ucfirst($controller)."Controller";
        $controllerObject = new $controllerClassName;
        if(!method_exists($controllerObject,$actionName)){
             exit("$controllerClassName 不存在 {$action} Action!");
        }
        
        $controllerObject->moduleName = $module;
        $controllerObject->controllerName = $controller;
        $controllerObject->actionName = $action;
        
        $controllerObject->view = View::getSingleInstance();
        $this->controller = $controllerObject;
        //函数回调
        try{
            $controllerObject->beforeAction();
            $controllerObject->init();
            $controllerObject->$actionName();
            $controllerObject->endAction();
        }catch(Exception $e){
             echo $e->getMessage();
             exit;
        }
    }

  
    private function loadCoreAndAppFile(){
        $this->_loadCoreFile();
        $this->_loadAppFile();
    }

        //加载核心类
    private function _loadCoreFile(){
        require_once self::$corePath.'/import/import.php';
        import::loadFile(self::$corePath);
    }
    
    //根据app下的config的import配置 加载APP目录下的文件
    private function _loadAppFile(){
        
        $appPath = defined("APP_PATH") ? APP_PATH : $this->getConfig("bathPath");
        if(!$appPath){
            exit("请定义app所在的目录");
        }
        if(!defined("APP_PATH"))
            define ("APP_PATH", $appPath);
        $importDirs = $this->getConfig('import');
        foreach($importDirs as $v){
            $importPath = str_replace("app", $appPath, $v);
            $importPath = str_replace(".", "/", $importPath);
            $importPath = str_replace("*", "", $importPath);
            import::loadFile($importPath);
        }
    }
    //获取URL中的参数
   
    //加载系统组件
    public function loadSystemCompoent(){
        $this->request = new Request();
    }
}

?>

