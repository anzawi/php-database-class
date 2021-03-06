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

namespace PHPtricks\Orm;

trait Variables
{

    /**
     * @var $_instance object
     * store DB class object to allow one connection with database (deny
     *     duplicate)
     * @access private
     */
    private static $_instance = null;
    protected
        /**
         * @var $_pdo object PDO object
         */
        $_pdo;

}