<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Url 路由配置
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class Url {

    static $routeConfig = array('rewrite', 'params');
    static $module = '';
    static $controller = '';
    static $action = '';
    static $mca = '';
    static $regex = "#<.*?>#";

    public static function setParamsByUri() {
        $routeRule = Bii::app()->getConfig('routeRule');
        if (empty($routeRule)) {
            $routeRule = 'params';
        }
        if (!in_array($routeRule, self::$routeConfig)) {
            exit('routeRule params  not valid');
        }

        if ($routeRule == "params") {
            $module = Bii::app()->request->getQuery('m');
            if (empty($module)) {
                self::$module = Bii::app()->getConfig('defaultModule');
            }
            $controller = Bii::app()->request->getQuery('c');
            if (empty($controller)) {
                self::$controller = Bii::app()->getConfig('defaultController');
            }
            $action = Bii::app()->request->getQuery('a');
            if (empty($action)) {
                self::$action = Bii::app()->getConfig('defaultAction');
            }
        } else { //伪静态模式           
            $url = Bii::app()->request->getQuery('_url');
            if ($url != '/') {
                $url = '/' . $url;
            }
            $server = Bii::app()->request->getServerInfo('HTTP_HOST');
            $wholeUrl = $server . $url;
            self::findMCA($wholeUrl);
        }
    }

    // 根据伪静态url找到对应的模块; M C A
    private static function findMCA($url) {
        $routeConfig = Bii::app()->getConfig('routeConfig');
        foreach ($routeConfig as $route => $mca) {
            if (strpos($route, "<") === FALSE) { //如果路由配置没< >  说明是简单的路由 直接用==
                if ($route == $url) {
                    self::$mca = $mca;
                    break;
                } else {
                    continue;
                }
            }
            $reg = self::$regex; /* 找出 路由中的 <page:\d+> 这种  "#<.*?>#"; */
            preg_match_all($reg, $route, $match);

            foreach ($match[0] as $m) {
                $newString = str_replace('>', '', $m);
                $newString = str_replace(':', '>', $newString);
                $newString = '(?' . $newString . ')';
                $string = str_replace($m, $newString, $route);
            }

            $newRegex = str_replace(".", "\.", $string);
            $newRegex = str_replace("/", "\/", $newRegex);
            $newRegex = '/' . $newRegex . "$/";


            preg_match_all($newRegex, $url, $match);
            if (empty($match[0])) {
                continue;
            }

            if (!empty($match)) {
                foreach ($match as $k => $v) {
                    if (!is_numeric($k)) {
                        $_GET[$k] = addslashes($v[0]);
                    }
                }
                self::$mca = $mca;
                break;
            }
        }

        if (empty(self::$mca)) {
            print_r($url . ' 404 not found');
            ;
            exit;
        }
        $temp = explode('/', self::$mca);
        array_shift($temp);
        list(self::$module, self::$controller, self::$action) = $temp;
    }

    // Url::createAbsoluteUrl(array('/home/default/index',array('id'=>5)));
    //  $wwwDomainName."/news/<id:\d+>.html"=>"/home/news/show",
    public static function createAbsoluteUrl(array $routeArray) {
        $routeConfig = Bii::app()->getConfig('routeConfig');
        if (empty($routeArray)) {
            return '';
        }
        $mca = $routeArray[0];
        $site = isset($routeArray[1]['site']) ? $routeArray[1]['site'] : '';
        if (empty($site))
            return '';
        unset($routeArray[1]['site']);
        foreach ($routeConfig as $route => $tempMca) {
            $returnUrl = $route;
            if ($mca == $tempMca) {
                // 相同的key  这个可以比
                if (strpos($route, "<") === FALSE && empty($routeArray[1])) { //如果路由配置没< >  说明是简单的路由 直接用==
                    return $returnUrl;
                } else {  // 路由配置 中有 <>
                    $reg = "/<(.*?):/"; /* 找出 路由中的 <page:\d+> 这种  "#<.*?>#"; */
                    preg_match_all($reg, $route, $match);
                    preg_match_all(self::$regex, $route, $match1);
                    $newMatch = array();
                    foreach ($match[0] as $k => $v) {
                        $newMatch[$match1[0][$k]] = str_replace('<', '', $v);
                        $newMatch[$match1[0][$k]] = str_replace(':', '', $newMatch[$match1[0][$k]]);
                    }
                    
                    $diff = array_diff($newMatch,array_keys($routeArray[1]));
                    if(empty($diff)){
                        // 说明两个数组相等
                        foreach ($newMatch as $replace=>$value){
                            $returnUrl = str_replace($replace, $routeArray[1][$value], $returnUrl);
                        }
                        return $returnUrl;
                    }
                   
                    
                }
            }
        }
        return '';
    }

}
?>

