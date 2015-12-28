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

class NewsController extends WebBaseController{
    
    public function beforeAction() {
       
    }


    public function actionList() {
         echo " news list ";
    }
    
    
    public function actionShow(){
        $newId = Bii::app()->request->getQuery('id');
        print_r($_GET);
        echo "news detail :".$newId;
    }
}

?>
