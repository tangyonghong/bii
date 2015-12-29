<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bii\Core\Exception;
/**
 * Description of ViewException
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class ViewException extends \Exception{
    protected $message ="";


    public function __construct($message='', $code=0, $previous=NULL) {
        $this->message ="<br/>View Render Excption:";
        parent::__construct($this->message.$message, $code, $previous);
    }
}

?>
