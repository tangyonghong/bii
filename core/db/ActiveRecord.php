<?php
namespace Bii\Core\Db;
/**
 * Description of ActiveRecord
 * @author tangyonghong <tangyonghong@kugou.net>
 * @copyright (c) year, Tangyonghong
 */
class BiiPDO {
    
    public static $conn = array();
    public $config = array();
    private $dbConn = null;
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
    
    private function connection(){
       $host = $this->config['host'];
       $dbName = $this->config['dbName'];
       $user = $this->config['user'];
       $passwd = $this->config['password'];
       $charset = $this->config['charset'];
       $dsn = "mysql:host={$host};dbname={$dbName}";
       try{
         $this->dbConn = new PDO($dsn,$user,$passwd); 
       }catch(PDOException $e){
            echo $e->getMessage();
            exit;
       }
       $this->dbConn->exec("set names $charset");
    }
    
    public function execute($sql){
        try{
            $res = $this->dbConn->exec($sql);
        } catch (DbException $ex) {
            echo $ex->getMessage();
            exit;
        }
        
    }
    
    public function query($sql){
        try{
            $rs = $this->dbConn->query($sql);
            if(empty($rs) ){
                exit('SQL running Error ');
            }
            $rs->setFetchMode(PDO::FETCH_ASSOC);
            $result = $rs->fetchAll();
        } catch (DbException $ex) {
            echo $ex->getMessage();
            exit;
        }
        return $result;
    }
}

class ActiveRecord {
    
    public static $dbSet ;  //指定数据库配置  
    public static $tableName;
    public static $isReadOrWrite = 2;  //1为读库 2为写库
    private static $sql = "";
    private static $queryArray = array();

    const IS_READ = 1;
    const IS_WRITE = 2;
   
    //根据语句选择写库或读库
    final private static function chooseReadOrWriteConfig(){
        
    }

    final private static function getDbConnection(){
        $config = self::getDbConfig();
        $key = static::$dbSet.'_'.self::$isReadOrWrite.'_'.$config['dbKey'];
        $dbConnection = BiiPDO::getSingleInstance($key, $config['config']);
        return $dbConnection; 
    }
   
    final private static function getDbConfig(){
       $dbConfig = \Bii\Core\Bii::app()->getConfig(static::$dbSet);
       if(empty($dbConfig)){
           throw new DbException("请指定数据库配置!!");
       }
       
       $dbKey = 0;
       if(static::$isReadOrWrite==static::IS_WRITE){ //如果为只读
             $dbConfig = $dbConfig['write'];
       }else{ 
             $dbKey = 1;
             $tmp = $dbConfig['read'];
             $readCount = count($tmp);
             $dbConfig = $tmp[rand(0, $readCount-1)];
       }
       
       return array('dbKey'=>$dbKey,'config'=>$dbConfig);
    }

    private static function setWrite(){
          static::$isReadOrWrite = static::IS_WRITE;
    }
    
    private static function setRead(){
         static::$isReadOrWrite = static::IS_READ;
    }

     public static function setTableName($tableName){
        static::$tableName = $tableName;
    }
    
     public static function findAll(Array $queryArray=array()){
         self::setRead();
         self::$queryArray = $queryArray;
         self::bulidQuerySql();
         $dbConnection = self::getDbConnection();
         $res = $dbConnection->query(self::$sql);
         return $res;
          
    }
    
    public static function find(Array $queryArray=array()){
         self::setRead();
         $queryArray['limit'] = 1;
         self::$queryArray = $queryArray;
         self::bulidQuerySql();
         $dbConnection = self::getDbConnection();
         $res = $dbConnection->query(self::$sql);
         return $res;
        
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
     
     private static function bulidQuerySql(){
         self::$sql =" SELECT ";
         if(isset(self::$queryArray['columns']) && !empty(self::$queryArray['columns'])){
             self::$sql.= self::$queryArray['columns']."";
         }else{
             self::$sql.= ' * ';
         }
         
         self::$sql.=" FROM ".static::$tableName." ";
         
         if(isset(self::$queryArray['conditions']) && !empty(self::$queryArray['conditions'])){
             self::$sql.= " WHERE ".self::$queryArray['conditions']." ";
         }
         
         
         if(isset(self::$queryArray['group']) && !empty(self::$queryArray['group'])){
             self::$sql.=" GROUP BY ".self::$queryArray['group'];
         }
         
         if(isset(self::$queryArray['order']) && !empty(self::$queryArray['order'])){
             self::$sql.=" ORDER BY ".self::$queryArray['order'];
         }
         
         if(isset(self::$queryArray['offset']) && !isset(self::$queryArray['limit'])){
             throw new DbException("Sql 语句中没有 limit");
         }
         
         if(isset(self::$queryArray['limit']) && !empty(self::$queryArray['limit']) && !isset(self::$queryArray['offset'])){
             self::$sql.=" Limit  ".self::$queryArray['limit'];
         }
         
         if(isset(self::$queryArray['limit']) && isset(self::$queryArray['offset'])){
             self::$sql.=" Limit  ".self::$queryArray['offset']." , ".self::$queryArray['limit'];
         }
         self::$sql = addslashes(self::$sql);
     }
     
     public static function getQuerySql(){
         return self::$sql;
     }
    
    
}

?>

