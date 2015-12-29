<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace  Bii\Home\Controller;
/**
 * Description of DefaultController
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */

class NewsController extends \Bii\Core\Controller\WebBaseController{
    
    public function beforeAction() {
       
    }


    public function actionList() {
         echo " news list ";
    }
    
    
    public function actionShow(){
        $newId = \Bii\Core\Bii::app()->request->getQuery('id');
        print_r($_GET);
        echo "news detail :".$newId;
    }
    
    public function actionAdd(){
        echo 'add action  ';
        echo \Bii\Core\Url\Url::createAbsoluteUrl(array("/home/news/add",array('site'=>'vistor')));
    }
}

?>
