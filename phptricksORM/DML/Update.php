<?php

namespace PHPtricks\Orm\DML;

trait Update
{

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


	private function getNewValues()
	{
		return $this->_newValues;
	}
}