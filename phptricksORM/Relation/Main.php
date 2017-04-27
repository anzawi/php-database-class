<?php

/**
 * Relation between tables
 *
 * this part is cumming soon
 */

/*****************************************************************************
 *
 * How to use :
 * $db = PHPtricks\Orm\Database::connect();
 * $db->table("blog")->join("comments", ["comments.id", "=", blog.id], "left");
 *
 * sql = SELECT * FROM blog LEFT JOIN comments ON comments.id = blog.id

	public function join($table, $condition = [], $join = '')
	{
		// make sure the $condition has 3 indexes (`table_one.field`, operator, `table_tow.field`)
		if(count($condition) == 3)
			$this->_query .= strtoupper($join) . // convert $join to upper case (left -> LEFT)
				" JOIN {$table} ON {$condition[0]} {$condition[1]} {$condition[2]}";

		// that's it now return object from this class
		return $this;
	}
 */