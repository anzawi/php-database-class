<?php 
/** 
 * DB 
 * database Class to ease cintroll of database 
 * @author Mr Mohammad Anzawi 
 * @copyright 2015 
<<<<<<< HEAD
 * @version 1.1.0 
=======
 * @version 1.0.1 
>>>>>>> origin/master
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
<<<<<<< HEAD
            $_error = false,
            $_table,
            $_schema; 
=======
            $_error = false; 
>>>>>>> origin/master
    
    /** 
     * DB::__construct() 
     * Connect with database 
     * @access private 
     * @return void 
     */ 
    private function __construct() 
    { 
        try { 
<<<<<<< HEAD
             $this->_pdo = new PDO("mysql:host=" . HOST . ";dbname=" . DBNAME, USERNAME, PASSWORD); 
              $this->_pdo->exec("set names " . CHARSET); 
              $this->_pdo->setAttribute( PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION );
=======
             $this->_pdo = new PDO("mysql:host=" . HOSTNAME . ";dbname=" . DBNAME, USERNAME, PASSWORD); 
              $this->_pdo->exec("set names " . CHARSET); 
>>>>>>> origin/master
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
<<<<<<< HEAD

=======
>>>>>>> origin/master
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
<<<<<<< HEAD
    
    /** 
     * DB::insert() 
     * insert into database tables 
     * @param string $table 
     * @param array $values 
     * @return bool 
     */ 
    public function insert($values = array()) 
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
            $sql = "INSERT INTO {$this->_table} (`" . implode('`,`', $fields) ."`)"; 
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
    public function update($values = array(), $where = array()) 
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
        $sql = "UPDATE {$this->_table} SET {$set} WHERE " . implode(" ", $where); 
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
    public function delete($where = array()) 
    { 
        // check if $where is set 
        if(count($where)) { 
            // call action method 
            if($this->action($this->_table, "DELETE", $where)) { 
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
    public function getFirst($countRow = 10, $where = array()) 
    { 
        if($results = $this->query("SELECT * FROM {$this->_table}", $where)->results()) { 
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
    public function getLast($countRow = 10, $where = array()) 
    { 
        $resultsLastRows = array(); 
        if($results = $this->query("SELECT * FROM {$this->_table} ", $where)->results()) { 
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
        return $this->results()[0]; 

    }
    // create table 
    // alter table [
    //      add column
    //      remove column
    //      rename column
    // ] 
    // delete table
    // 

/*
        table('table')->schema([
            'column_name' => 'type',
            'column_name' => 'type|constraint',
            'column_name' => 'type|constraint,more_constraint,other_constraint',

        ])->create();

     */ 
    
    /*
        'id' => 'increments'
        mean -> this field is primary key, auto increment not null,  and unsigned
     */
    
    /**
     * set _table var value
     * @param  string $table the table name
     * @return object   retrun DB object
     */
    public function table($table)
    {
        $this->_table = $table;
        return $this;
    }

    /**
     * set _schema var value
     * @param  array  $structers the structer od table
     * @return object   retrun DB object
     */
    public function schema($structers = [])
    {
        if(count($structers)) // check if isset $structers
        {
            /**
             * to store columns structers
             * @var array
             */
            $schema = [];

            foreach($structers as $column => $options)
            {
                $type = $options; // the type is the prototype of column
                $constraints = ''; // store all constraints for one column

                // check if we have a onstraints
                if(!strpos($options, '|') === false)
                {
                    
                    $constraints = explode('|', $options); // the separator to constraints is --> | <--
                    $type = $constraints[0]; // the type is first key
                    unset($constraints[0]); // remove type from onstraints
                    $constraints = implode(' ', $constraints); // convert constraints to string
                    $constraints = strtr($constraints, [
                        'primary' => 'PRIMARY KEY', // change (primary to PRIMARY KEY -> its valid constraint in sql)
                        'increment' => 'AUTO_INCREMENT', // same primary
                        'not_null' => 'NOT NULL', // same primary
                    ]);
                }
                
                // checck if type is increments we want to change it to integer and  and add some constraints like primary key ,not null, unsigned and auto increment
                ($type == 'increments'? $type = "INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL": null);
                
                // check if type of column is string change it to valid sql type (VARCHAR and set lingth)
                // ['username' => 'string:255'] convert to username VARCHAR(255)
                if(strpos($type, 'string') !== false)
                {
                    $type = explode(':', $type); 
                    $type = "VARCHAR({$type[1]})";
                }

                // check if column has a default value
                // ['username' => 'string:255|default:no-name'] convert to username VARCHAR(255) DEFAULT 'no name'
                if(strpos($constraints, 'default') !== false)
                {
                    preg_match("/(:)[A-Za-z0-9]+/", $constraints, $match);
                    $match[0] = str_replace(':', '', $match[0]);
                    $temp = str_replace('-', ' ', $match[0]);
                    $constraints = str_replace(":".$match[0] , " '{$temp}' ", $constraints);
                }

                // add key to schema var contains column _type constraints
                // ex: username VARCHAR(255) DEFUALT 'no name' NOT NULL
                $schema[] = "$column $type " . $constraints;
                
            }

            // set _scema the all columns structure
            $this->_schema = '(' . implode(",", $schema) . ')';
          
            return $this; // return DB object
        }

        return null; // return null
    }

    /**
     * this methode to run sql statment and create table
     * @param  string $createStatement its create statement -> i mean you can change it to ->  CREATE :table IF NOT EXIST
     * @return bool
     */
    public function create($createStatement = "CREATE TABLE :table")
    {
        $createStatement = str_replace(':table', $this->_table, $createStatement);
        try
        {
            $this->_pdo->exec($createStatement . $this->_schema);
        }
        catch(PDOException $e)
        {
             print $e->getMessage();
                return false;
        }

        return true;
    }

    public function drop()
    {
        try
        {
           $this->_pdo->exec("DROP TABLE {$this->_table}");
        }
        catch(PDOException $e)
        {
             die($e->getMessage());
        }

        return true;
    }

// "ALTER TABLE ADD COULMN (COLUM_NAME TYPE AND CONTRIANTE)"
// "ALTER TABLE DROP COULMN COLUM_NAME"
// "ALTER TABLE RENAME COULMN (COLUM_NAME TYPE AND CONTRIANTE)"
// 
// table('table')->alterSchema(['add', 'column_name', 'type'])->alter();
// table('table')->alterSchema(['drop', 'column_name'])->alter();
// table('table')->alterSchema(['rename', 'column_name','new_name' ,'type'])->alter();
// table('table')->alterSchema(['modify', 'column_name', 'new_type'])->alter();

    public function alterSchema($schema = [])
    {
        if(count($schema))
        {

            $function = $schema[0]."Column";

            unset($schema[0]);

            call_user_func_array([$this, $function], [$schema]);

            return $this;
        }

        return null;
    }

    public function alter()
    {
        try
        {
           $this->_pdo->exec("ALTER TABLE {$this->_table} {$this->_schema}");
        }
        catch(PDOException $e)
        {
             die($e->getMessage());
        }
    }

    public function addColumn($options = [])
    {
       if(count($options) === 2)
            $this->_schema = "ADD COLUMN {$options[1]} {$options[2]}";
    }

    public function dropColumn($options = [])
    {
        if(count($options) === 1)
            $this->_schema = "DROP COLUMN {$options[1]}";
    }

    public function renameColumn($options = [])
    {
        if(count($options) === 3)
            $this->_schema = "CHANGE {$options[1]} {$options[2]} {$options[3]}";
    }

    public function typeColumn($options = [])
    {
        if(count($options) === 2)
            $this->_schema = "MODIFY {$options[1]} {$options[2]}";
    }
}
=======
    
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
>>>>>>> origin/master
