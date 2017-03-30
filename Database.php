<?php

/**
 * please don't remove this comment block
 *
 * @author phptricks Team - Mohammad Anzawi
 * @author_uri https://phptricks.org
 * @uri https://github.com/anzawi/php-database-class
 * @version 3.2.0
 * @licence MIT -> https://opensource.org/licenses/MIT
 * @package PHPtricks\Database
 */


// include config() function file
include __DIR__ . "/config_function.php";

/**
 * Class Database
 * @package PHPtricks\Database
 */
class Database implements \IteratorAggregate, \ArrayAccess
{
    /**
     * @var $_instance object
     * store DB class object to allow one connection with database (deny duplicate)
     * @access private
     */
    private static $_instance;

    private
	    /**
	     *  @var $_query string store sql statement
	     */
        $_query = '',
	    /**
	     *  @var $_count int store row count for _results variable
	     */
        $_count,
	    /**
	     *  @var $_error bool if cant fetch sql statement = true otherwise = false
	     */
        $_error = false,
	    /**
	     *  @var $_schema string store DDL sql query
	     */
        $_schema,
	    /**
	     *  @var $_where string where type to using by default = WHERE
	     */
        $_where = "WHERE",
	    /**
	     *  @var $_sql string save query string
	     */
        $_sql,
	    /**
	     *  @var $_colsCount integer columns count for query results
	     * using into dataView() method to generate columns
	     */
	    $_colsCount = -1,
	    /**
	     * @var $_newValues null to save new value to use save() method
	     */
		$_newValues = null,
        
        $_ordering = false;

    protected
        /**
         *  @var $_pdo object PDO object
         */
        $_pdo,
	    /**
	     * @var $_table string current table name
	     */
	    $_table,
	    /**
	     * @var $_results array store sql statement result
	     */
	    $_results,
	    /**
	     * @var $_idColumn string|null id columns name for current table by default is id
	     */
	    $_idColumn = "id";

	/**
	 * Database constructor.
	 * @param array $data query results if exists
	 * @param array $info store current table name and id columns name
	 *
	 * DON'T pass parameters to __construct.
	 */
    protected function __construct($data = [], $info = [])
    {
	    // class correct method as database driver selected in config file
	    call_user_func_array([$this, \config()], [null]);

	    // check if data is sent
	    if($data)
	    {
		    // set id ,table name and results after that return sent data
		    $this->_idColumn = $info['id'];
		    $this->_table = $info['table'];
		    return $this->_results = $data;
	    }
    }


	/**
	 * @param $prop
	 * @return mixed
	 */
    public function __get($prop)
    {
        return isset($this->_results->$prop) ? $this->_results->$prop : null;
    }

    public function __set($prop, $value)
    {
		if (isset($this->_results->$prop))
	    {
		    if(!is_null($this->_newValues))
		        $this->_newValues->$prop = $value;
		    else
		    {
			    $this->_newValues = new \stdClass();
			    $this->_newValues->$prop = $value;
		    }
	    }
    }

    // foreach results
	public function getIterator()
	{
		$o = new \ArrayObject($this->_results);
		return $o->getIterator();
	}

	/**
	 * Connect database with mysql driver
	 * @param $null
	 */
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
	/**
	 * Connect database with sqlite driver
	 * @param $null
	 */
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

	/**
	 * Connect database with pgsql driver
	 * @param $null
	 */
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

	/**
	 * Connect database with mssql driver
	 * @param $null
	 */
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

	/**
	 * Connect database with sybase driver
	 * @param $null
	 */
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

	/**
	 * Connect database with oci driver
	 * @param $null
	 */
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
	    // do deny duplicate connection
	    // check if $_instance is null or not
	    // if null so connect database
	    // otherwise return current connection object
        if(!isset(self::$_instance) || self::$_instance == null) {
            self::$_instance = new Database();
        }

        return self::$_instance;
    }

    /**
     * DB::query()
     * check if sql statement is prepare
     * append value for sql statement if $params is set
     * fetch results
     * @param string $sql
     * @param array $params
     * @return mixed
     */
    public function query($sql, $params = [])
    {
        //echo $sql;
        $this->_query = "";
        $this->_where = "WHERE";
        // set _error. true to that if they can not be false for this function to work properly, this function makes the
	    // value of _error false if there is no implementation of the sentence correctly
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
	    if($query->execute())
	    {
		    try
		    {
			    $this->_results = $query->fetchAll(\config('fetch'));
		    }
		    catch (\PDOException $e) {}

		    $this->_sql = $query;
		    // set _results = data comes


		    // set _count = count rows comes
		    $this->_count = $query->rowCount();

	    }
	    else
	    	$this->_error = true;


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
         * @example "column = value"
         */
        $set = ''; // initialize $set
        $x = 1;
        // initialize fields and values
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

    public function save()
    {
	    $x = 1;
	    $this->_query = "WHERE";
        
        foreach($this->results() as $i => $row)
        {
            if(!is_numeric($row))
                $this->_query .= " {$i} = '{$row}'";
            else
		      $this->_query .= " {$i} = {$row}";
		    // add comma between values
		    if($x < count((array)$this->results())) {
			    $this->_query .= " AND";
		    }

		    $x++;
	    }

	    return $this->update((array)$this->getNewValues());
    }

    /**
     * select from database
     * @param  array  $fields fields we need to select
     * @return Database result of select as Database object
     */
    public function select($fields = ['*'], $last = false)
    {
        if($fields === true)
        {
            $fields = ['*'];
            $last = true;
        }
	    if($fields != ['*'] && !is_null($this->_idColumn))
	    {
			if(!in_array($this->_idColumn, $fields))
			{
				$fields[$this->_idColumn];
			}
	    }

        if(!$last)
            $sql = "SELECT " . implode(', ', $fields)
                . " FROM {$this->_table} {$this->_query}";
        else
        {
            //$this->_query .= ($this->_ordering == false ? " ORDER BY {$this->_idColumn} DESC" : '');
            $sql = "SELECT * FROM (
                        SELECT " . implode(', ', $fields) . "  
                        FROM {$this->_table}
                        
                         {$this->_query}  
                        ) sub  ORDER by id ASC";
        }
            

        $this->_query = $sql;
        $this->_ordering = false;

        return $this->collection([
            'results' => $this->query($sql)->results(),
            'table'   => $this->_table,
            'id'      => $this->_idColumn
        ]);

        // return new Database($this->query($sql)->results(), ['table' => $this->_table, 'id' => $this->_idColumn]);
    }

    protected function collection($collection)
    {
        return new Collection($collection, self::$_instance); 
    }

    protected function getCollection($table)
    {
        if(isset($this->__cach[md5($table)]))
        {
            return true;
        }

        return false;
    }

    /**
     * delete from table
     * @return bool
     */
    public function delete()
    {
        $results = (array)$this->_results;
	    if($this->count() == 1)
	    {
		    return $this->remove($results);
	    }

	    for($i = 0; $this->count() > $i; $i++)
	    {
		    $this->remove( $results[$i]);
	    }

	    return true;
    }

    private function remove($data)
    {
	    $this->_where = "WHERE";
	    $x = 1;

	    foreach($data as $i => $row)
	    {
            if(!is_numeric($row))
		      $this->_where .= " {$i} = '{$row}'";
            else
                $this->_where .= " {$i} = {$row}";
		    // add comma between values
		    if($x < count((array)$data)) {
			    $this->_where .= " AND";
		    }
		    $x++;
	    }

	    $sql = "DELETE FROM $this->_table " . $this->_where;
	    return $this->query($sql);
    }
    /**
     * find single row from table via id
     * @param  int $id [description]
     * @return Database or object (as you choice from config file)  results or empty
     */
    public function find($id)
    {
        return $result = $this->where($this->_idColumn, $id)
            ->first();
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
	 * add OR condition to sql statement
	 * @param  string  $field    field name from table
	 * @param  string  $operator operator (= , <>, .. etc)
	 * @param  mix $value    the value
	 * @return object        this class
	 */
    public function orWhere($field, $operator, $value = false)
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

        $this->_query .= " OR $field $operator '$value'";
        $this->_where = "AND";
        return $this;
    }

    /**
     * add in condition to query
     * @param  string  $field    field name from table
     * @param  array $value   the values
     * @return object        this class
     */
    public function in($field, $values = [])
    {
    	if(count($values))
    	{
    		$this->_query .= " $this->_where $field IN (" . implode(",", $values) . ")";
            $this->_where = "AND";
    	}

    	return $this;
    }

	/**
	 * add not in condition to query
	 * @param  string  $field    field name from table
	 * @param  array $value   the values
	 * @return object        this class
	 */
    public function notIn($field, $values = [])
    {
    	if(count($values))
    	{
    		$this->_query .= " $this->_where $field NOT IN (" . implode(",", $values) . ")";
            $this->_where = "AND";
    	}

    	return $this;
    }

    public function orderBy($colName, $type = 'ASC')
    {
        $this->_query .= " ORDER BY {$colName} {$type}";
        $this->_ordering = true;
        return $this;
    }

	/**
	 * get first row from query results
	 * @return Database
	 */

	public function first()
	{
		$results = $this->select()->results();

		if(count((array)$results))
		{
            return $this->collection([
                'results' => $results[0],
                'table'   => $this->_table,
                'id'      => $this->_idColumn
            ]);
		}

        return $this->collection([
            'results' => [],
            'table'   => $this->_table,
            'id'      => $this->_idColumn
        ]);
	}

	public function firstRecord()
	{
		$results = (array)$this->_results;

		if(count($results))
		{
			return isset($results[0]) ? $results[0] : $results;
		}

		return [];
	}

	/**
	 * add limit rows to query
	 * @param int $from
	 * @param int $to
	 * @return $this
	 */
    public function limit($from = 0, $to = 0)
    {
        if(!$to)
        {
            $this->_query .= " LIMIT {$from}";
        }
        else
        {
            $this->_query .= " LIMIT {$from}, {$to}";
        }
    	    
    	return $this;
    }

	/**
	 * @param $offset
	 * @return $this
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

	/**
	 * change id columns name
	 * @param string $idName
	 */
    public function idName($idName = "id")
    {
	    $this->_idColumn = $idName;

	    return $this;
    }

    public function results()
    {
        return $this->_results;
    }


	/**
	 *
	 * New In V.2.1.0
	 *
	 */

	/**
	 * @sense v.2.1.0
	 * pagination functionality
	 * @param int $recordsCount count records per page
	 * @return array
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Database\Database::connect();
	 * $results = $db->table("blog")->paginate(15);
	 *
	 * var_dump($results);
	 *
	 * now add to url this string query (?page=2 or 3 or 4 .. etc)
	 * see (link() method to know how to generate navigation automatically)
	 */
	public function paginate($recordsCount = 0, $last = false)
	{
        if($recordsCount === true)
        {
            $last = true;
            $recordsCount = 0;
        }

		if($recordsCount === 0)
			$recordsCount = config("pagination.records_per_page");

		// this method accept one argument must be an integer number .
		if(!is_integer($recordsCount))
		{
			trigger_error("Oops, the records count must be an integer number"
					. "<br> <p><strong>paginate method</strong> accept one argument must be"
					." an <strong>Integer Number</strong> , " . gettype($recordsCount) . " given!</p>"
					. "<br><pre>any question? contact me on team@phptricks.org</pre>", E_USER_ERROR);
		}
		// check current page
		$startFrom = isset($_GET[config("pagination.link_query_key")]) ?
			($_GET[config("pagination.link_query_key")] - 1) * $recordsCount : 0;

		// get pages count rounded up to the next highest integer
		$this->_colsCount = ceil(count($this->select()->results()) / $recordsCount);

		// return query results
		return $this->limit($startFrom, $recordsCount)->select(['*'], $last);
	}

	/**
	 * view query results in table
	 * we need to create a simple table to view results of query
	 * @return string (html)
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Database\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->select();
	 * echo $db->dataView();
	 */
	public function dataView()
	{
		// get columns count to create the table
		$colsCount = count($this->firstRecord());
		// if no data received so return no data found!
		if($colsCount <= 0)
		{
			return config("pagination.no_data_found_message");
		}

		// to fix for counter -> on array we want to counter from columns count -1
		// on object we want the records count
		if(is_array($this->_results) && isset($this->_results[0]) && is_array($this->_results[0])) $colsCount -= 1;
		// get Columns name's
		$colsName = array_keys((array)$this->firstRecord());

		// init html <table> tag
		$html = "<table border=1><thead><tr>";

		/**
		 * create table header
		 * its contain table columns names
		 */
		foreach ($colsName as $colName)
		{
			$html .= "<th>";
			// get column name
			/**
			 * the getColumnName() function define in (config_function.php) file
			 * this function replace (_) to space for example (column_name -> Column Name)
			 * of separate words (columnName -> Column Name)
			 */
			$html .= getColumnName($colName);
			$html .= "</th>";
		}

		// end table header tag and open table body tag
		$html .= "</tr></thead><tbody>";

		// loop all results to create the table (tr's and td's)
		foreach ((array)$this->results() as $row)
		{
			$row = (array)$row; // make sure the $row is array and not an object

			if(count($row) > 1)
			{
				$html .= "<tr>"; // open tr tag
				// loop all columns in row to create <td>'s tags
				for ($i = 0; $i <= $colsCount; $i++)
				{
					$html .= "<td>";
					$html .= $row[$colsName[$i]]; // get current data from the row
					$html .= "</td>";
				}

				$html .= "</tr>";
			}
			else // first method is called not select
			{
				$html .= "<td>";
				$html .= $row[0]; // get current data from the row
				$html .= "</td>";
			}
		}

		$html .= "</tbody></table>";

		return $html; // return created table
	}

	/**
	 * create pagination list to navigate between pages
	 * @return string (html)
	 */
	/**
	 * How to Use:
	 *
	 * $db = PHPtricks\Database\Database::connect();
	 * $db->table("blog")->where("vote", ">", 2)->paginate(5);
	 * echo $db->link();
	 */
	public function link()
	{
		// get current url
		$link = $_SERVER['PHP_SELF'];

		// current page
		$currentPage =
			(isset($_GET[config("pagination.link_query_key")]) ?
			$_GET[config("pagination.link_query_key")]
			: 1);
		/**
		 * $html var to store <ul> tag
		 */
		$html = '';
		if($this->_colsCount > 0) // check if columns count is not 0 or less
		{
			$operator = $this->checkAndGetUriQuery();

			$html = "<ul class=\"pagination\">";
			// loop to get all pages
			for ($i = 1; $i <= $this->_colsCount; $i++)
			{
				// we need other pages link only ..
				if($i == $currentPage)
				{
					$html .= "<li>{$i}</li>";
				}
				else
				{
					$html .= "<li><a href=\"{$link}{$operator}" .
						config("pagination.link_query_key") .
						"={$i}\">{$i}</a></li>";
				}
			}

			 $html .= "</ul>";
		}

		return $html;
	}

	/**
	 * check if we have a string query in current uri other (pagination key)
	 * if not so return (?) otherwise we want to reorder a string query to keep other keys
	 * @return string
	 */
	private function checkAndGetUriQuery()
	{
		$get = $_GET;
		// remove pagination key from query string
		unset($get[config("pagination.link_query_key")]);
		// init query string and set init value (?)
		$queryString = "?";
		// check if we have other pagination key in query string
		if(count($get))
		{
			// reorder query string to keep other keys
			foreach ($get as $key => $value)
			{
				$queryString .= "{$key}" .
					(!empty($value) ? "=" : "") . $value . "&";
			}

			return $queryString;
		}


		return "?";
	}

	/**
	 * @return int pages count when use paginate() method
	 */
	public function pagesCount()
	{
		if($this->_colsCount < 0)
			return null;

		return $this->_colsCount;
	}

	/**
	 * get count of rows for last select query
	 * @return int
	 */
	public function count()
	{
		$results = (array)$this->results();
		return isset($results[0]) ? count($this->_results) : 1;
	}
	/**
	 * Join's
	 */
	/**
	 * make join between tables
	 * @param string $table
	 * @param array $condition
	 * @param string $join
	 * @return $this
	 */
	/**
	 * How to use :
	 * $db = PHPtricks\Database\Database::connect();
	 * $db->table("blog")->join("comments", ["comments.id", "=", blog.id], "left");
	 *
	 * sql = SELECT * FROM blog LEFT JOIN comments ON comments.id = blog.id
	 */
	public function join($table, $condition = [], $join = '')
	{
		// make sure the $condition has 3 indexes (`table_one.field`, operator, `table_tow.field`)
		if(count($condition) == 3)
			$this->_query .= strtoupper($join) . // convert $join to upper case (left -> LEFT)
				" JOIN {$table} ON {$condition[0]} {$condition[1]} {$condition[2]}";

		// that's it now return object from this class
		return $this;
	}

	/**
	 * check if table is exist in database
	 * @param string $table
	 * @return bool
	 */
	public function tableExist($table = '')
	{
		$table = $this->query("SHOW TABLES LIKE '{$table}'")->results();

		if(!is_null($table) && count($table))
			return true;

		return false;
	}

	/**
	 * End Added in V.2.1.0
	 */


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
     * @param  array  $structures the structer od table
     * @return object   retrun DB object
     */
    public function schema($structures = [])
    {
        if(count($structures)) // check if isset $structers
        {
            /**
             * to store columns structers
             * @var array
             */
            $schema = [];

            foreach($structures as $column => $options)
            {
                $type = $options; // the type is the prototype of column
                $constraints = ''; // store all constraints for one column

                // check if we have a constraints
                if(!strpos($options, '|') === false)
                {

                    $constraints = explode('|', $options); // the separator to constraints is --> | <--
                    $type = $constraints[0]; // the type is first key
                    unset($constraints[0]); // remove type from constraints
                    $constraints = implode(' ', $constraints); // convert constraints to string
                    $constraints = strtr($constraints, [
                        'primary' => 'PRIMARY KEY', // change (primary to PRIMARY KEY -> its valid constraint in sql)
                        'increment' => 'AUTO_INCREMENT', // same primary
                        'not_null' => 'NOT NULL', // same primary
                    ]);
                }

                // check if type is 'increments' we want to change it to integer and add some constraints like primary key ,not null, unsigned and auto increment
                ($type == 'increments'? $type = "INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL": null);

                // check if type of column is string change it to valid sql type (VARCHAR and set length)
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

            // set _schema the all columns structure
            $this->_schema = '(' . implode(",", $schema) . ')';

            return $this; // return DB object
        }

        return null; // return null
    }

    /**
     * this method to run sql statement and create table
     * @param  string $createStatement its create statement -> i mean you can change it to ->  CREATE :table IF NOT EXIST
     * @return bool
     */
    public function create($createStatement = "CREATE TABLE") // you can use (CREATE TABLE IF NOT EXIST)
    {
    	$createStatement = $createStatement . " :table ";
	    // check if table is not exist
	    // by default in (try catch) block we can detect this problem
	    // but if you want to display a custom error message you can uncomment
	    // this (if) block and set your error message
	    /*if($this->tableExist($this->_table))
	    {
	    	print ("Oops.. the table {$this->_table} already Exists in "
			    . config('host_name') . "/" . config("db_name"));
		    die;
	    }*/

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

// "ALTER TABLE ADD COLUMN (COLUMN_NAME TYPE AND CONSTRAINT)"
// "ALTER TABLE DROP COLUMN COLUMN_NAME"
// "ALTER TABLE RENAME COLUMN (COLUMN_NAME TYPE AND CONSTRAINT)"
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
	    // check if table is not exist
	    // by default in (try catch) block we can detect this problem
	    // but if you want to display a custom error message you can uncomment
	    // this (if) block and set your error message
	    /*if(!$this->tableExist($this->_table))
	    {
	    	print ("Oops.. cant alter table {$this->_table} because is not Exists in "
			    . config('host_name') . "/" . config("db_name"));
		    die;
	    }*/
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

	/**
	 * Whether a offset exists
	 * @link http://php.net/manual/en/arrayaccess.offsetexists.php
	 * @param mixed $offset <p>
	 * An offset to check for.
	 * </p>
	 * @return boolean true on success or false on failure.
	 * </p>
	 * <p>
	 * The return value will be casted to boolean if non-boolean was returned.
	 * @since 5.0.0
	 */
	public function offsetExists($offset)
	{
		return isset($this->_results[$offset]);
	}

	/**
	 * Offset to retrieve
	 * @link http://php.net/manual/en/arrayaccess.offsetget.php
	 * @param mixed $offset <p>
	 * The offset to retrieve.
	 * </p>
	 * @return mixed Can return all value types.
	 * @since 5.0.0
	 */
	public function offsetGet($offset)
	{
		return $this->_results[$offset];
	}

	/**
	 * Offset to set
	 * @link http://php.net/manual/en/arrayaccess.offsetset.php
	 * @param mixed $offset <p>
	 * The offset to assign the value to.
	 * </p>
	 * @param mixed $value <p>
	 * The value to set.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetSet($offset, $value)
	{
		if (isset($this->_results[$offset]))
		{
			if(!is_null($this->_newValues))
				$this->_newValues[$offset] = $value;
			else
			{
				$this->_newValues = [];
				$this->_newValues[$offset]= $value;
			}
		}
	}

	/**
	 * Offset to unset
	 * @link http://php.net/manual/en/arrayaccess.offsetunset.php
	 * @param mixed $offset <p>
	 * The offset to unset.
	 * </p>
	 * @return void
	 * @since 5.0.0
	 */
	public function offsetUnset($offset)
	{
		return null;
	}

	private function getNewValues()
	{
		return $this->_newValues;
	}
}

class Collection extends Database
{
    public function __construct($data, $connection = null)
    {
        if(isset($connection))
         {
            $this->_table = $data['table'];
            $this->_results = $data['results'];
            $this->_idColumn = $data['id'];
            $this->_pdo = $connection->_pdo;
        }
        else
            $this->_results = $data;
    }

    public function all()
    {
        return $this->results();
    }

    public function empty()
    {
        return empty($this->_results);
    }

    public function first()
    {
        return isset($this->_results[0]) ? $this->_results[0] : null;
    }

    public function last($count = 0)
    {
        $reverse = array_reverse($this->results());

        if(!$count)
        {
            return isset($reverse[0]) ? $reverse[0] : null;
        }

        $lastRecords = [];
        $j = 0;

        for($i = 0; $i < $count; $i++)
        {
            $lastRecords[$j] = $reverse[$i];
            $j++;
        }

        return $lastRecords;
    }

    public function each(callable $callback)
    {
        foreach ($this->results() as $key => $value)
        {
            $callback($value, $key);
        }

        return $this;
    }

    public function filter(callable $callback = null)
    {
        if($callback)
        {
            return new static(array_filter($this->results(), $callback));
        }

        // exclude null and empty
        return new static(array_filter($this->results()));
    }

    public function keys()
    {
        return new static(array_keys($this->results()));
    }

    public function map(callable $callback)
    {
        $keys = $this->keys()->all();
        $results = array_map($callback, $this->results(), $keys);

        return new static(array_combine($keys, $results));
    }


    public function toJson()
    {
        return json_encode($this->results());
    }

    public function __toString()
    {
        return $this->toJson();
    }

    public function merge($items)
    {
        return new static(
            array_merge(
                    $this->results(), 
                    $this->toArray($items)
                )
            );
    }

    protected function toArray($items)
    {
        if(!is_array($items) && $items instanceof Collection)
            return $items->all();

        return $items;
    }
}
