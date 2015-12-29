<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Widget
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
namespace Bii\Core\Widget;

class BWidget {
    public $viewVars = array();
    
    public function run(){}
    
    public function showView($viewName="",$viewVars = array()){
           $tmp = explode('.', $viewName);
           $path = APP_PATH.'/widgets/view/';
           if(count($tmp)>1){
               $path.=implode("/", $tmp);
               $viewFile = $tmp[1];
           }else{
               $path.=$tmp[0];
               $viewFile = $tmp[0];
           }
           $path.=".php";
           if(!file_exists($path)){
               throw new ViewException("Could not found Widget View:".$viewFile);
           }
           !empty($viewVars) && extract($viewVars);
           include_once $path;
    }

}

?>
