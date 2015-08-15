<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of Request
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class Request {

    //这里初始化 $_GET $_REQUEST  $_POST
    public function __construct() {
        
    }

    public function getQuery($key, $defaultValue = '') {
        return isset($_GET[$key]) ? $_GET[$key] : $defaultValue;
    }

    public function getPost($key, $defaultValue = '') {
        return isset($_POST[$key]) ? $_POST[$key] : $defaultValue;
    }

}

?>
