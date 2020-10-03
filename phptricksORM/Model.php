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

use PHPtricks\Orm\DML\Delete;
use PHPtricks\Orm\DML\Insert;
use PHPtricks\Orm\DML\Paginate;
use PHPtricks\Orm\DML\Query;
use PHPtricks\Orm\DML\Update;
use PHPtricks\Orm\DML\Vars;
use PHPtricks\Orm\Operations\Operations;
use PHPtricks\Orm\Relation\RelationProvider;

/**
 * Class Model
 * @since version 5.0.0
 * @package PHPtricks\Orm
 */
class Model extends Database
{

    use Vars;
    use Query;
    use Insert;
    use Paginate;
    use Update;
    use Delete;
    use Operations;
    use RelationProvider;

    /**
     * Model constructor.
     */
    public function __construct()
    {
        /** @var PDO $db */
        $db         = parent::connect();
        $this->_pdo = $db->_pdo;
    }

    /**
     * change id columns name
     *
     * @param  string  $idName
     */
    public function idName($idName = "id")
    {
        $this->_idColumn = $idName;

        return $this;
    }

    public function id()
    {
        return $this->_idColumn;
    }

    public function results()
    {
        $returnedResults       = $this->_results;
        $returnedResults['re'] = $this;

        return $returnedResults;
    }

    /*** New */
    protected function getQueryAdditions()
    {
        return $this->_query;
    }

}