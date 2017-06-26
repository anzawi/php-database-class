<?php

namespace PHPtricks\Orm\DML;

trait Insert
{
	/**
	 * DB::insert()
	 * insert into database tables
	 * @param string $table
	 * @param array $values
	 * @return mixed
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
				return $this;
			}
		}

		return false;
	}

	/**
	 * get last inserted ID
	 * @return int
	 */
	public function lastInsertedId()
	{
		return $this->_pdo->lastInsertId();
	}

	/**
	 * insert or update if exists
	 * @param $values
	 * @param array $conditionColumn
	 * @return bool|mixed
	 */
	public function createOrUpdate($values, $conditionColumn = [])
	{
		// check if we have condition for update
		// the condition must be ([column_name, value])
		if(count($conditionColumn))
		{
			$column = $conditionColumn[0];
			$value  = $conditionColumn[1];
		}
		else // if no condition so search by ID
		{
			$column = $this->_idColumn;
			$value  = isset($value[$this->_ColumnsId]) ? $value[$this->_ColumnsId] : null;
		}

		// check if any records exists by condition
		$exists = $this->findBy($column, $value)->first()->results();
		// if exist so update the record's
		if(count($exists))
		{
			return $this->where($column, $value)
				->update($values);
		}
		// insert new record
		return $this->insert($values);
	}
}