<?php

namespace PHPtricks\Orm\Collection;

use \PHPtricks\Orm\Database;

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
