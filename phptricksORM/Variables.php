<?php

namespace PHPtricks\Orm;

trait Variables
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
}