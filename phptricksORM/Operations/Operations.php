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

namespace PHPtricks\Orm\Operations;

trait Operations
{

    use Where;
    use Cond;
    use parseWhere;
    use Other;

    /**
     * get count of rows for last select query
     *
     * @return int
     */
    public function count()
    {
        //	    var_dump($this->results());
        //	    return count($this->results());
        $results = (array) $this->results();

        return isset($results[0]) ? count($this->_results) : 1;
    }

}
