<?php

namespace PHPtricks\Orm\DML;

trait Insert
{
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
}