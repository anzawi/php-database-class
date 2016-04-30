<?php

namespace PHPtricks\Database;

include __DIR__ . "/config_function.php";

class Database
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
        $_query = '',
        $_results,
        $_count,
        $_error = false,
        $_schema,
        $_where = "WHERE",
        $_sql;

    protected $_table;

    /**
     * DB::__construct()
     * Connect with database
     * @access private
     * @return void
     */
    protected function __construct()
    {
        call_user_func_array([$this, \config()], [null]);
    }

    protected function mysql($null)
    {
        try
        {
            $this->_pdo = new \PDO("mysql:host=" . \config('host_name') . ";dbname=" .
                config('db_name'), \config('db_user'), \config('db_password'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

    protected function sqlite($null)
    {
        try
        {
            $this->_pdo = new \PDO("sqlite:" . \config('db_path'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }


    protected function pgsql($null)
    {
        try
        {
            $this->_pdo = new \PDO('pgsql:user='. \config('db_user') .'
          dbname=' . \config('db_name') . ' password='.\config('db_password'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }


    protected function mssql($null)
    {
        try
        {
            $this->_pdo = new \PDO("mssql:host=" . \config('host_name') . ";dbname=" .
                \config('db_name'), \config('db_user'), \config('db_password'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }

    protected function sybase($null)
    {
        try
        {
            $this->_pdo = new \PDO("sybase:host=" . \config('host_name') . ";dbname=" .
                \config('db_name'), \config('db_user'), \config('db_password'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        } catch(\PDOException $e) {
            die($e->getMessage());
        }
    }


    protected function oci($null)
    {
        try{
            $conn = new \PDO("oci:dbname=".\config('tns'),
                \config('db_user'), \config('db_password'));
            $this->_pdo->exec("set names " . 'utf8');
            $this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
        }catch(\PDOException $e){
            die ($e->getMessage());
        }
    }

    /**
     * DB::connect()
     * return instace
     * @return object
     */
    public static function connect()
    {
        if(!isset(self::$_instace)) {
            self::$_instace = new Database();
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
    public function query($sql, $params = [])
    {
        $this->_query = "";
        $this->_where = "WHERE";
        // set _erroe. true to that if they can not be false for this function to work properly, this function makes the value of _error false if there is no implementation of the sentence correctly
        $this->_error = false;
        // check if sql statement is prepared
        $query = $this->_pdo->prepare($sql);
        // if $params isset
        if(count($params)) {
            /**
             * @var $x int
             * counter
             */
            $x = 1;
            foreach($params as $param) {
                // append values to sql statement
                $query->bindValue($x, $param);

                $x++;
            }
        }
        // check if sql statement executed
        if($query->execute()) {
            $this->_sql = $query;
            // set _results = data comes
            try
            {
                $this->_results = $query->fetchAll(\config('fetch'));
            }
            catch(\PDOException $e){}
            // set _count = count rows comes
            $this->_count = $query->rowCount();
        } else {
            // set _error = true if sql statement not executed
            $this->_error = true;
        }

        return $this;
    }


    /**
     * DB::insert()
     * insert into database tables
     * @param string $table
     * @param array $values
     * @return bool
     */
    public function insert($values = [])
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
    public function update($values = [])
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
        $sql = "UPDATE {$this->_table} SET {$set} " . $this->_query;
        // check if query is not have an error
        if(!$this->query($sql, $values)->error()) {
            return true;
        }

        return false;
    }

    /**
     * select from database
     * @param  array  $fields fields we need to select
     * @return array  result of select
     */
    public function select($fields = ['*'])
    {
        $sql = "SELECT " . implode(', ', $fields)
            . " FROM {$this->_table} {$this->_query}";

        $this->_query = $sql;
        return $this->query($sql)->results();
    }

    /**
     * delete from table
     * @return bool
     */
    public function delete()
    {
        $sql = "DELETE FROM $this->_table " . $this->_query;
        $delete = $this->query($sql);

        if($delete) return true;

        $this->_error = true;
        return false;
    }

    /**
     * find single row from table via id
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function find($id)
    {
        $find = $this->where("id", $id)
            ->select();

        $this->_query = '';
        $this->_where = "WHERE";
        return isset($find[0]) ? $find[0] : [];
    }

    /**
     * add where condition to sql statement
     * @param  string  $field    field name from table
     * @param  string  $operator operator (= , <>, .. etc)
     * @param  mix $value    the value
     * @return object        this class
     */
    public function where($field, $operator, $value = false)
    {
    	/**
    	 * if $value is not set then set $operator to (=) and
    	 * $value to $operator
    	 */
        if($value === false)
        {
            $value = $operator;
            $operator = "=";
        }

        if(!is_numeric($value))
            $value = "'$value'";

        $this->_query .= " $this->_where $field $operator $value";
        $this->_where = "AND";
        return $this;
    }

    /**
     * between condition
     * @param  string $field  table field name
     * @param  arrya $values ['from', 'to']
     * @return object        this class
     */
    public function whereBetween($field, $values = [])
    {
    	if(count($values))
    	{
    		$this->_query .=
    			" $this->_where $field BETWEEN '$values[0]' and '$values[1]'";
       		$this->_where = "AND";
    	}

        return $this;
    }

    /**
     * Like whare
     * @param  string $field database field name
     * @param  string $value value
     * @return object 	this class
     */
    /**
     * we can do that with where() methode
     * $db->table('test')->where('name', 'LIKE', '%moha%');
     */
    public function likeWhere($field, $value)
    {

        $this->_query .= " $this->_where $field LIKE '%$value%'";
        $this->_where = "AND";
        return $this;
    }


    /**
     * to add OR condition
     * where() method add (AND) this method add (OR)
     * @param  [type]  $field    [description]
     * @param  [type]  $operator [description]
     * @param  boolean $value    [description]
     * @return [type]            [description]
     */
    public function orWhere($field, $operator, $value = false)
    {
        if($value === false)
        {
            $value = $operator;
            $operator = "=";
        }

        $this->_query .= " OR $field $operator '$value'";
        $this->_where = "AND";
        return $this;
    }

    /**
     * [in description]
     * @param  [type] $field  [description]
     * @param  array  $values [description]
     * @return [type]         [description]
     */
    public function in($field, $values = [])
    {
    	if(count($values))
    	{
    		$this->_query .= " $field IN (" . implode(",", $values) . ")";
    	}
    }

    /**
     * [notIn description]
     * @param  [type] $field  [description]
     * @param  array  $values [description]
     * @return [type]         [description]
     */
    public function notIn($field, $values = [])
    {
    	if(count($values))
    	{
    		$this->_query .= " $field NOT IN (" . implode(",", $values) . ")";
    	}
    }

	/**
	 * get first row from query results
	 * @return array
	 */
    public function first()
    {
        $first = $this->select();
        if(count($first))
            return $first[0];

        return [];
    }

    /**
     * [limit description]
     * @param  [type] $limit [description]
     * @return [type]        [description]
     */
    public function limit($limit)
    {
    	$this->_query .= " LIMIT " . (int)$limit;
    	return $this;
    }
    /**
     * [offset description]
     * @param  [type] $offset [description]
     * @return [type]         [description]
     */
    public function offset($offset)
    {
    	$this->_query .=" OFFSET " .$offset;
        return $this;
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
     * set _table var value
     * @param  string $table the table name
     * @return object - DBContent
     */
    public function table($table)
    {
        $this->_table = $table;
        return $this;
    }

    public function results()
    {
        return $this->_results;
    }


    /**
     * Show last query
     * @return string
     */
    public function showMeQuery()
    {
    	return $this->_sql;
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
                    preg_match("/(:)[A-Za-z0-9](.*)+/", $constraints, $match);

                    $match[0] = str_replace(':', '', $match[0]);
                    $temp = str_replace('-', ' ', $match[0]);
                    $constraints = str_replace(":" . $match[0] , " '{$temp}' ", $constraints);
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
    public function create($createStatement = "CREATE TABLE :table ")
    {
        $createStatement = str_replace(':table', $this->_table, $createStatement);

        try
        {
            $this->_pdo->exec($createStatement . $this->_schema);
        }
        catch(\PDOException $e)
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
        catch(\PDOException $e)
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
        catch(\PDOException $e)
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

    public function showMeSchema()
    {
        return $this->_schema;
    }
}