<?php
define("CONFIG_PATH", __DIR__);
return array(
    'basePath'=> __DIR__.'/..',
    'db'=> require CONFIG_PATH.'/db/dbConfig.php',
    'logDb'=> require CONFIG_PATH.'/db/logDbConfig.php',
    'defaultModule'=>'home',
    'defaultController'=>'default',
    'defaultAction'=>'index',
    'import'=>array(
        "app.modules.*",
        "app.extension.*",
        "app.models.*",
    ),
    'routeRule'=>'rewrite',
    'routeConfig'=>  require __DIR__.'/route.php',
);
?>
