<?php 
/** 
 * DB 
 * database Class to ease cintroll of database 
 * @package t3lam.cms 
 * @author Mr Mohammad Anzawi 
 * @copyright 2015 
 * @version 1.0.1 
 * @access public 
 */ 
 /** 
  * visit my blog on http://phptricks.org (arabic) 
  * Three new articles each month (articles and tutorials thorough and exhaustive) 
 */ 
class DB 
{ 
    /** 
     * @var $_instace type object 
     * store DB class object to allow one connection with database (deny duplicate) 
     * @access private 
     */ 
    private static $_instace; 
    
    /** 
     * @var $_pdo type object PDO object 
     * @var $_query type string store sql statement 
     * @var $_results type array store sql statement result 
     * @var $_count type int store row count for _results variable 
     * @var $_error type bool if cant fetch sql statement = true otherwise = false 
     */ 
    private $_pdo, 
            $_query, 
            $_results, 
            $_count, 
            $_error = false; 
    
    /** 
     * DB::__construct() 
     * Connect with database 
     * @access private 
     * @return void 
     */ 
    private function __construct() 
    { 
        try { 
             $this->_pdo = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD); 
              $this->_pdo->exec("set names " . CHARSET); 
        } catch(PDOException $e) { 
            die($e->getMessage()); 
        } 
    } 
    
    /** 
     * DB::get() 
     * return instace 
     * @return object 
     */ 
    public static function get() 
    { 
        if(!isset(self::$_instace)) { 
            self::$_instace = new DB(); 
        } 
        
        return self::$_instace; 
    } 
    
    /** 
     * DB::query() 
     * check if sql statement is prepare 
     * append value for sql statement if $parame is set 
     * featch results 
     * @param string $sql 
     * @param array $params 
     * @return mixed 
     */ 
    public function query($sql, $params = array()) 
    { 
        // set _erroe. true to that if they can not be false for this function to work properly, this function makes the value of _error false if there is no implementation of the sentence correctly 
        $this->_error = false; 
        // check if sql statement is prepared 
        $this->_query = $this->_pdo->prepare($sql); 
        // if $params isset 
        if(count($params)) { 
            /** 
             * @var $x type int 
             * counter 
             */ 
            $x = 1; 
            foreach($params as $param) { 
                // append values to sql statement 
                $this->_query->bindValue($x, $param); 
                
                $x++; 
            } 
        } 
        // check if sql statement executed 
        if($this->_query->execute()) { 
            // set _results = data comes 
            $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ); 
            // set _count = count rows comes 
            $this->_count = $this->_query->rowCount(); 
        } else { 
            // set _error = true if sql statement not executed 
            $this->_error = true; 
        } 
        
        return $this; 
    } 
    
    /** 
     * DB::action() 
     * do sql statements 
     * @uses action('table_name', 'SELECT *', array('id', '=', 5)) 
     * @param string $table 
     * @param string $action 
     * @param array $where 
     * @param array $moreWhere 
     * @return mixed 
     */ 
    private function action($table, $action, $where = array(), $moreWhere = array()) 
    { 
        // check if where = 3 fields (field, operator, value)) 
        if(count($where === 3)) { 
            $field = $where[0]; // name of feild 
            $operator = $where[1]; // operator 
            $value = $where[2]; // value of feild 
            /** 
             * @var $operators 
             * allowed operators 
             */ 
            $operators = array('=', '<', '>', '<=', '>=', 'BETWEEN', 'LIKE'); 
            // check if operator user set is allowed 
            if(in_array($operator, $operators)) { 
                // do sql statement 
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ? "; 
                // check if query is not have errors 
                if(!$this->query($sql, array($value))->error()) { 
                    return $this; 
                } 
            } 
        } 
        return false; 
    } 
    
    /** 
     * DB::insert() 
     * insert into database tables 
     * @param string $table 
     * @param array $values 
     * @return bool 
     */ 
    public function insert($table, $values = array()) 
    { 
        // check if $values set 
        if(count($values)) { 
            /** 
             * @var $fields type array 
             * store fields user want insert value for them 
             */ 
            $fields = array_keys($values); 
            /** 
             * @var $value type string 
             * store value for fields user want inserted 
             */ 
            $value = ''; 
            /** 
             * @var $x type int 
             * counter 
             */ 
            $x = 1; 
            foreach($values as $field) { 
                // add new value 
                $value .="?"; 
                
                if($x < count($values)) { 
                    // add comma between values 
                    $value .= ", "; 
                } 
                $x++; 
            } 
             // generate sql statement 
            $sql = "INSERT INTO {$table} (`" . implode('`,`', $fields) ."`)"; 
            $sql .= " VALUES({$value})"; 
            // check if query is not have an error 
            if(!$this->query($sql, $values)->error()) { 
                    return true; 
             } 
        } 
        
        return false; 
    } 
    
    /** 
     * DB::update() 
     * 
     * @param string $table 
     * @param array $values 
     * @param array $where 
     * @return bool 
     */ 
    public function update($table, $values = array(), $where = array()) 
    { 
        /** 
         * @var $set type string 
         * store update value 
         * @example "colomn = value" 
         */ 
        $set = ''; // initialize $set 
        $x = 1; 
        // initialize feilds and values 
        foreach($values as $i => $row) { 
            $set .= "{$i} = ?"; 
            // add comma between values 
            if($x < count($values)) { 
                $set .= " ,"; 
            } 
            $x++; 
        } 
        // generate sql statement 
        $sql = "UPDATE {$table} SET {$set} WHERE " . implode(" ", $where); 
        // check if query is not have an error 
        if(!$this->query($sql, $values)->error()) { 
            return true; 
        } 
        
        return false; 
    } 
    
    /** 
     * DB::delete() 
     * delete row from table 
     * @param string $table 
     * @param array $where 
     * @return bool 
     */ 
    public function delete($table, $where = array()) 
    { 
        // check if $where is set 
        if(count($where)) { 
            // call action method 
            if($this->action($table, "DELETE", $where)) { 
                return true; 
            } 
        } 
        return false; 
    } 
    
    /** 
     * DB::error() 
     * return _error variable 
     * @return bool 
     */ 
    public function error() 
    { 
        return $this->_error; 
    } 
    
    /** 
     * DB::getFirst() 
     * get first x rows 
     * @param string $table 
     * @param integer $countRow 
     * @param array $where 
     * @return 
     */ 
    public function getFirst($table,$countRow = 10, $where = array()) 
    { 
        if($results = $this->query("SELECT * FROM {$table}", $where)->results()) { 
            $resultsFirstRows = array(); 
            for($i = 0; $i < $countRow; $i++) { 
                $resultsFirstRows[$i] = $results[$i]; 
            } 
            return $resultsFirstRows; 
        } 
        
        return false; 
    } 
    
    /** 
     * DB::getLast() 
     * get last x rows 
     * @param string $table 
     * @param integer $countRow 
     * @param array $where 
     * @return 
     */ 
    public function getLast($table, $countRow = 10, $where = array()) 
    { 
        $resultsLastRows = array(); 
        if($results = $this->query("SELECT * FROM {$table} ", $where)->results()) { 
            for($i = count($results) - 1; $i > $countRow + 1; $i--) { 
                $resultsLastRows[$i] = $results[$i]; 
            } 
            return $resultsLastRows; 
        } 
        
        return false; 
    } 
    /** 
     * DB::error() 
     * return _results variable 
     * @return array 
     */ 
    public function results() 
    { 
        return $this->_results; 
    } 
    
    /** 
     * DB::error() 
     * return first key from results method 
     * @return string 
     */ 
    public function first() 
    { 
        $temp = $this->results(); 
        return $temp[0]; 
    } 
} 
