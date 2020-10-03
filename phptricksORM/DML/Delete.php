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

/**
 * Trait Delete
 *
 * @package PHPtricks\Orm\DML
 */
trait Delete
{

    /**
     * delete from table
     *
     * @return bool
     */
    public function delete()
    {
        $results = (array) $this->_results;

        // check if its empty
        if ( ! count($results)) {
            // try to call select() method
            try {
                $results = (array) $this->select()->results();
                if (count($results) == 1) {
                    $results = $results[0];
                }
            } catch (\Exception $e) {
                return false;
            }
        }

        if ($this->count() == 1) {
            return $this->remove($results);
        }

        for ($i = 0; $this->count() > $i; $i++) {
            $this->remove($results[$i]);
        }

        return true;
    }

    /**
     * @param $data
     *
     * @return mixed
     */
    private function remove($data)
    {
        $this->_where = "WHERE";
        $x            = 1;

        foreach ($data as $i => $row) {
            if ( ! is_numeric($row)) {
                $this->_where .= " {$i} = '{$row}'";
            } else {
                $this->_where .= " {$i} = {$row}";
            }
            // add comma between values
            if ($x < count((array) $data)) {
                $this->_where .= " AND";
            }
            $x++;
        }

        $sql = "DELETE FROM $this->_table ".$this->_where;

        return $this->query($sql);
    }

}