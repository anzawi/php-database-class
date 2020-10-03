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

/**
 * *
 *  * please don't remove this comment block
 *  *
 *  * @author phptricks Team - Mohammad Anzawi
 *  * @author_uri https://phptricks.org
 *  * @uri https://github.com/anzawi/php-database-class
 *  * @version 4.1.0
 *  * @licence MIT -> https://opensource.org/licenses/MIT
 *  * @package PHPtricks\Database
 *
 */

namespace PHPtricks\Orm\Providers;

trait Provider
{

    /**
     * Connect database with mysql driver
     *
     * @param $null
     */
    protected function mysql($null)
    {
        $this->_pdo = new \PDO("mysql:host=".\config('host_name').";dbname=".
                               config('db_name'), \config('db_user'),
            \config('db_password'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Connect database with sqlite driver
     *
     * @param $null
     */
    protected function sqlite($null)
    {
        $this->_pdo = new \PDO("sqlite:".\config('db_path'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Connect database with pgsql driver
     *
     * @param $null
     */
    protected function pgsql($null)
    {
        $this->_pdo = new \PDO('pgsql:user='.\config('db_user').'
          dbname='.\config('db_name').' password='.\config('db_password'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Connect database with mssql driver
     *
     * @param $null
     */
    protected function mssql($null)
    {
        $this->_pdo = new \PDO("mssql:host=".\config('host_name').";dbname=".
                               \config('db_name'), \config('db_user'),
            \config('db_password'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Connect database with sybase driver
     *
     * @param $null
     */
    protected function sybase($null)
    {
        $this->_pdo = new \PDO("sybase:host=".\config('host_name').";dbname=".
                               \config('db_name'), \config('db_user'),
            \config('db_password'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

    /**
     * Connect database with oci driver
     *
     * @param $null
     */
    protected function oci($null)
    {
        $conn = new \PDO("oci:dbname=".\config('tns'),
            \config('db_user'), \config('db_password'));
        $this->_pdo->exec("set names ".'utf8');
        $this->_pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);
    }

}