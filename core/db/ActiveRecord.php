<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of ActiveRecord
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class MyPDO {
    
    public static $conn = array();
    public $config = array();
    private $conn = null;
    final public static function getSingleInstance($key,$config) {
       if(!isset(self::$conn[$key])){
           self::$conn[$key] = new self($config);
       }
       return self::$conn[$key];
    }
    
    public function __construct($config) {
        //初始化参数
        $this->config = $config;
        $this->connection();
    }
    
    public function connection(){
       $host = $this->config['host'];
       $dbName = $this->config['dbName'];
       $user = $this->config['user'];
       $passwd = $this->config['password'];
       $charset = $this->config['charset'];
       $dsn = "mysql:host={$host};dbname={$dbName}";
       try{
         $pdo = new PDO($dsn,$user,$passwd); 
       }catch(PDOException $e){
            echo $e->getMessage();
            exit;
       }
         $pdo->exec("set names $charset");
         $this->conn = $pdo;
    }
    
    public function execute($sql){
        
    }
    
    public function query($sql){
        
    }
}

class ActiveRecord {
    
    public static $dbSet ;  //指定数据库配置  
    public static $tableName;
    public static $isReadOrWrite = 2;  //1为读库 2为写库
    const IS_READ = 1;
    const IS_WRITE = 2;
   
    //根据语句选择写库或读库
    final private static function chooseReadOrWriteConfig(){
        
    }

    final private static function getDbConnection(){
        $config = self::getDbConfig();
        $key = self::$dbSet.'_'.self::$isReadOrWrite.'_'.$config['dbKey'];
        $dbConnection = MyPDO::getSingleInstance($key, $config['config']);
        return $dbConnection; 
    }
   
    final private static function getDbConfig(){
       $dbConfig = Bii::app()->getConfig(self::$dbSet);
       if(empty($dbConfig)){
           throw new DbException("请指定数据库配置!!");
       }
       $dbKey = 0;
       if(self::$isReadOrWrite==self::IS_READ){ //如果为只读
             $dbConfig = $dbConfig['read'];
       }else{ 
             $tmp = $dbConfig['write'];
             $keySet = shuffle(array_keys($tmp));
             $dbKey = $keySet[0];
             $dbConfig = $tmp[$dbKey];
       }
       return array('dbKey'=>$dbKey,'config'=>$dbConfig);;
    }

    private static function setWrite(){
          self::$isReadOrWrite = self::IS_WRITE;
    }
    
    private static function setRead(){
         self::$isReadOrWrite = self::IS_READ;
    }

    final public static function setTableName($tableName){
        self::$tableName = $tableName;
    }
    
    final public static function findAll(Array $queryArray=array()){
         self::setRead();
          
    }
    
    public static function find(Array $queryArray=array()){
         self::setRead();
         
    }
    
    public static function update($updateArray,$condition=""){
         self::setWrite();
    }
     
   public static function delete($condition=""){
          self::setWrite();
    }
     
    public static function insert($data=array()){
          self::setWrite();
     }
     
    public static function count($condition){
         self::setRead();
     }
     
    public static function increment($array,$condition){
            self::setWrite();  
     }
        
    public static function multiInsert($array){
          self::setWrite();
     }
     
    public static function querySql($sql){
          self::setRead();   
    }
         
    public static function executeSql($sql){
             self::setWrite();
     }
    
    
}

?>
