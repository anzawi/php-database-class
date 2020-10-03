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

namespace PHPtricks\Orm\DDL;

/**
 * Trait Vars
 *
 * @package PHPtricks\Orm\DDL
 */
trait Vars
{

    /**
     * @var
     */
    protected $_table, $_errors;

    private
        /**
         * @var $_schema string store DDL sql query
         */
        $_schema;

}