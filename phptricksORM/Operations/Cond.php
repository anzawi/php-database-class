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

trait Cond
{

    /**
     * add limit rows to query
     *
     * @param  int  $limit
     *
     * @return $this
     */
    public function limit($limit)
    {
        $this->_query .= " LIMIT {$limit}";

        return $this;
    }

    /**
     * add OrderBy to query
     *
     * @param  string  $colName
     * @param  string  $type
     *
     * @return $this
     */
    public function orderBy($colName, string $type = 'ASC')
    {
        $this->_query    .= " ORDER BY {$colName} {$type}";
        $this->_ordering = true;

        return $this;
    }

    /**
     * add groupBy to query
     *
     * @param  string $colName
     *
     * @return $this
     */
    public function groupBy(string $colName)
    {
        $this->_query    .= " GROUP BY {$colName}";
        return $this;
    }

    /**
     * @param $offset
     *
     * @return $this
     */
    public function offset($offset)
    {
        $this->_query .= " OFFSET ".$offset;

        return $this;
    }

}