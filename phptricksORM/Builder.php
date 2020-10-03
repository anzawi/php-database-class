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

use PHPtricks\Orm\DDL\Base;

/**
 * Class Builder
 * @since version 5.0.0
 * @package PHPtricks\Orm
 */
class Builder extends Database
{

    use Base;

    /**
     * Builder constructor.
     */
    public function __construct()
    {
        /** @var PDO $db */
        $db         = parent::connect();
        $this->_pdo = $db->_pdo;
    }

}