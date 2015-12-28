<?php

//数据库配置文件
$dbArray = array(
    'write' => array(
        'host' => '127.0.0.1',
        'user' => 'root',
        'password' => 'root',
        'dbName' => 'test',
        'charset' => 'utf8',
    ),
    'read' => array(
        //============ 写一库 =====================//
            array(
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'root',
                'dbName' => 'test',
                'charset' => 'utf8',
            ),
        //============== 写 二库 =====================//
            array(
                'host' => '127.0.0.1',
                'user' => 'root',
                'password' => 'root',
                'dbName' => 'test',
                'charset' => 'utf8',
            ),
    ),
);
return $dbArray;
?>