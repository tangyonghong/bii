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
class DefaultController extends WebBaseController {

    public function beforeAction() {
        
    }

    public function actionIndex() {
         $url = Url::createAbsoluteUrl(array("/home/news/show",array('site'=>'www','id'=>'100')));
         echo $url;
          echo '<br/>';
         echo Url::createAbsoluteUrl(array("/home/news/list",array('site'=>'www')));
         echo '<br/>';
         echo Url::createAbsoluteUrl(array("/home/news/list",array('site'=>'www','page'=>10,'type'=>20,)));
    }

}

?>
