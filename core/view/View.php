<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bii\Core\View;
/**
 * Description of View
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */

//视图处理类
class View {
    public $view = null;
    public static $static = null;
    public $viewVars = array();
    
    public static function getSingleInstance(){
        if(self::$static == null){
            self::$static = new self;
        }
        return self::$static;
    }
    
    public function assign($key,$value){
        $this->viewVars[$key] = $value;
    }
    
    public function render($path="",$viewVars = array()){
        
         $this->_render($path, $viewVars);
    }
    
    
    public function renderPart($path="",$viewVars = array()){
        $this->_render($path, $viewVars);
    }
    
    private function _render($path="",$viewVars = array()){
        if(empty($path)){
            $module = Bii::app()->controller->moduleName;
            $controller = Bii::app()->controller->controllerName;
            $action = Bii::app()->controller->actionName;
            $path = "/{$module}/{$controller}/{$action}";
        }else{
            $tmp = explode("/", $path);
            if(count($tmp)!= 4){
                throw new ViewException("Could not found path:".$path);
            }
            list($null,$module,$controller,$action) = $tmp;
            unset($null);
        }

        $module = strtolower($module);
        $controller = strtolower($controller);
        $action = strtolower($action);
        if(!file_exists(APP_PATH."/views".$path.'.php')){
            throw new ViewException("views :".$action. " not found");
        }
        $this->viewVars = array_merge($this->viewVars,$viewVars);
        extract($this->viewVars);
        include_once APP_PATH."/views".$path.'.php';
    }
    
    public function widget($widgetClass,$classVars=array()){
           $tmp = explode('.', $widgetClass);
           $path = APP_PATH.'/widgets/class/';
           if(count($tmp)>1){
               $path.=implode("/", $tmp);
               $widgetClassName = $tmp[1];
           }else{
               $path.=$tmp[0];
               $widgetClassName = $tmp[0];
           }
           $path.=".php";
           if(!file_exists($path)){
               throw new ViewException("Could not found Widget Class:".$widgetClass);
           }
           include_once $path;
           $widgetObject = new $widgetClassName;
           if(!empty($classVars) && is_array($classVars)){
               foreach($classVars as $k=>$v)
                   $widgetObject->$k = $v;
           }
           $widgetObject->run();
    }
}

?>
