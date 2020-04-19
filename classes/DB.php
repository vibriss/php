<?php
require_once 'functions/utils.php';

class DB {
    const CONFIG_FILENAME = 'config/db.ini';
    const PDO_ATTRS = [
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    ];
    
    private static $_instance = null;
    
    protected $_connection;

    private function __construct() {
        $db_config = parse_ini_file(self::CONFIG_FILENAME);

        $this->_connection = new PDO(
            "mysql:host={$db_config['host']};dbname={$db_config['dbname']}", 
            $db_config['login'],
            $db_config['password'],
            self::PDO_ATTRS
        );
    }
    
    public static function getInstance() {
        if (self::$_instance != null) {
                return self::$_instance;
        }
        self::$_instance = new self;
        return self::$_instance;
    }
    
    protected function _query($statement, $input_array) {
        $result = $this->_connection->prepare($statement);
        $result->execute($input_array);
        if (!$result) {
            throw new PDOException("ошибка работы с БД при выполнении $statement");
        }    
        return $result;
    }    
        
    public function select_one($statement, $input_array = [], $key = null) {
        $result = $this->_query($statement, $input_array)->fetch();
        if (!$result) {
            return null;
        }
        if (isset($key)) {
            return $result[$key];
        }
        return $result;
    } 
    
    public function select_all($statement, $input_array = [], $key = null) {
        $result = $this->_query($statement, $input_array)->fetchall();
        if (!$result) {
            return [];
        }
        if (isset($key)) {
            return array_column($result, $key);
        }
        return $result;
    } 
    
    public function insert($statement, $input_array = []) {
        $result = $this->_query($statement, $input_array);
        return $this->_connection->lastInsertId();
    } 
    
    public function update($statement, $input_array = []) {
        $result = $this->_query($statement, $input_array);
        return $result->rowCount();
    } 
    
    public function delete($statement, $input_array) {
        return $this->update($statement, $input_array);
    }
}