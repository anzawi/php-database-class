<?php

namespace PHPtricks\Orm\Operations;

trait Cond
{
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
	 * add OrderBy to query
	 * @param string $colName
	 * @param string $type
	 * @return $this
	 */
	public function orderBy($colName, $type = 'ASC')
	{
		$this->_query .= " ORDER BY {$colName} {$type}";
		$this->_ordering = true;
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
}