<?php 

set_time_limit(0);
define("WEB_PATH",__DIR__);
define("CORE_PATH",  dirname(__DIR__).'/core/');
define("APP_PATH", dirname(__DIR__).'/app');
$config = APP_PATH."/config/main.php";
require_once  CORE_PATH."Bii.php";
 Bii::createWebApp($config)->run();
?>