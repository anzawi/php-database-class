<?php

namespace PHPtricks\Orm\DDL;

/**
 * this trait for (create and drop table's)
 * @package PHPtricks\Orm\DDL
 */

trait Create
{
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
}