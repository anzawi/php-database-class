<?php

/**
 * please don't remove this comment block
 *
 * @author phptricks Team - Mohammad Anzawi
 * @author_uri https://phptricks.org
 * @uri https://github.com/anzawi/php-database-class
 * @version 4.0.0
 * @licence MIT -> https://opensource.org/licenses/MIT
 * @package PHPtricks\Database
 */

namespace PHPtricks\Orm;

use \PHPtricks\Orm\Collection\Collection;

/**
 * Class Database
 * @package PHPtricks\Orm
 */
class Database implements \IteratorAggregate, \ArrayAccess
{
	use \PHPtricks\Orm\Variables;
	use \PHPtricks\Orm\Providers\Provider;
	use \PHPtricks\Orm\DDL\Base;
	use \PHPtricks\Orm\DML\Main;
	use \PHPtricks\Orm\Operations\Main;


	/**
	 * Database constructor.
	 * @param array $data query results if exists
	 * @param array $info store current table name and id columns name
	 *
	 * DON'T pass parameters to __construct.
	 */
    protected function __construct()
    {
	    // class correct method as database driver selected in config file
	    call_user_func_array([$this, \config()], [null]);
    }

    // foreach results
	public function getIterator()
	{
		$o = new \ArrayObject($this->_results);
		return $o->getIterator();
	}

    /**
     * DB::connect()
     * return instance
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
}

