<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of DefaultController
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */

class DefaultController extends WebBaseController{
    
    public function beforeAction() {
       
    }


    public function actionIndex() {
         Test::find(); 
         $this->view->assign("test","bbbbbbbb");
          
          $this->view->render();
    }
}

?>
