<?php
/**
 * *
 *  * please don't remove this comment block
 *  *
 *  * @author phptricks Team - Mohammad Anzawi
 *  * @author_uri https://phptricks.org
 *  * @uri https://github.com/anzawi/php-database-class
 *  * @version 5.0.0
 *  * @licence MIT -> https://opensource.org/licenses/MIT
 *  * @package PHPtricks\Orm
 *
 */

namespace PHPtricks\Orm\DML;

trait Vars
{

    protected
        /**
         * @var $_table string current table name
         */
        $_table,
        /**
         * @var $_results array store sql statement result
         */
        $_results = [],
        /**
         * @var $_idColumn string|null id columns name for current table by default is id
         */
        $_idColumn = "id";
    private
        /**
         * @var $_query string store sql statement
         */
        $_query = '',
        /**
         * @var $_count int store row count for _results variable
         */
        $_count,
        /**
         * @var $_error bool if cant fetch sql statement = true otherwise = false
         */
        $_error = false,
        /**
         * @var $_where string where type to using by default = WHERE
         */
        $_where = "WHERE",
        /**
         * @var $_sql string save query string
         */
        $_sql,
        /**
         * @var $_colsCount integer columns count for query results
         * using into dataView() method to generate columns
         */
        $_colsCount = -1,
        /**
         * @var $_newValues null to save new value to use save() method
         */
        $_newValues = null,
        /**
         * @var bool
         */
        $_ordering = false;

}