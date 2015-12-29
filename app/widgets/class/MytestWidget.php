<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bii\App\Widget;
/**
 * Description of MytestWidget
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class MytestWidget extends \Bii\Core\Widget\BWidget{
    
    public $name = "tangyonghong";
    public $age = 10;
    
    public function run(){

          $this->showView("my_test_widget",array("widgetVars"=>array("hello","widget")));
    }
    
    
}

?>
