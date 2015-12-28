<?php


list($a,$b,$c) = explode('.',$_SERVER['HTTP_HOST']);
define('MAIN_DOMAIN', $a);
define('DOMAIN_NAME', '.'.$b.'.'.$c);

$wwwDomainName = "www".DOMAIN_NAME;

return array(
    $wwwDomainName."/" =>'/home/default/index',
    
    $wwwDomainName."/news/list-<type:\d+>-<page:\d+>.html"=>"/home/news/list", // 长的Url写前面
    $wwwDomainName."/news/list.html"=>"/home/news/list",
    $wwwDomainName."/news/<id:\d+>.html"=>"/home/news/show",
);

?>
