<?php

namespace PHPtricks\Orm\DDL;

trait Alter
{
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
}