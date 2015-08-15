<?php
$this->renderPart("/home/public/head",array("head"=>"head data is good"));
echo " default index view <br/>";
echo $test;

$this->widget("MytestWidget",array("name"=>"test good","age"=>30));


$this->renderPart("/home/public/footer",array("head"=>"head data is good"));

echo '<br/>test Param:'.Bii::app()->request->getQuery('test');
?>
