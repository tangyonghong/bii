<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
namespace Bii\Core\Exception;
/**
 * Description of DbException
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class DbException extends \Exception{
    protected $message ="";


    public function __construct($message='', $code=0, $previous=NULL) {
        $this->message ="<br/>DataBase Excption:";
        parent::__construct($this->message.$message, $code, $previous);
    }
}

?>
