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

/**
 * Trait Where
 *
 * @package PHPtricks\Orm\Operations
 */
trait Where
{

    /**
     * add where condition to sql statement
     *
     * @param  string  $field  field name from table
     * @param  string  $operator  operator (= , <>, .. etc)
     * @param  mix  $value  the value
     *
     * @return object        this class
     */
    public function where($field, $operator, $value = false)
    {
        /**
         * if $value is not set then set $operator to (=) and
         * $value to $operator
         */
        if ($value === false) {
            $value    = $operator;
            $operator = "=";
        }

        if ( ! is_numeric($value)) {
            $value = "'$value'";
        }

        $this->_query .= " $this->_where other.$field $operator $value";
        $this->_where = "AND";

        return $this;
    }

    /**
     * between condition
     *
     * @param  string  $field  table field name
     * @param  arrya  $values  ['from', 'to']
     *
     * @return object        this class
     */
    public function whereBetween($field, array $values = [])
    {
        if (count($values)) {
            $this->_query
                          .= " $this->_where other.$field BETWEEN '$values[0]' and '$values[1]'";
            $this->_where = "AND";
        }

        return $this;
    }

    /**
     * Like whare
     *
     * @param  string  $field  database field name
     * @param  string  $value  value
     *
     * @return object    this class
     */
    /**
     * we can do that with where() methode
     * $db->table('test')->where('name', 'LIKE', '%moha%');
     */
    public function likeWhere($field, string $value)
    {
        $this->_query .= " $this->_where other.$field LIKE '%$value%'";
        $this->_where = "AND";

        return $this;
    }


    /**
     * add OR condition to sql statement
     *
     * @param  string  $field  field name from table
     * @param  string  $operator  operator (= , <>, .. etc)
     * @param  mix  $value  the value
     *
     * @return object        this class
     */
    public function orWhere($field, $operator, $value = false)
    {
        /**
         * if $value is not set then set $operator to (=) and
         * $value to $operator
         */
        if ($value === false) {
            $value    = $operator;
            $operator = "=";
        }

        $this->_query .= " OR other.$field $operator '$value'";
        $this->_where = "AND";

        return $this;
    }

    /**
     * add in condition to query
     *
     * @param  string  $field  field name from table
     * @param  array  $value  the values
     *
     * @return object        this class
     */
    public function in($field, array $values = [])
    {
        if (count($values)) {
            $this->_query .= " $this->_where other.$field IN (".implode(",",
                    $values).")";
            $this->_where = "AND";
        }

        return $this;
    }

    /**
     * add not in condition to query
     *
     * @param  string  $field  field name from table
     * @param  array  $value  the values
     *
     * @return object        this class
     */
    public function notIn($field, array $values = [])
    {
        if (count($values)) {
            $this->_query .= " $this->_where other.$field NOT IN (".implode(",",
                    $values).")";
            $this->_where = "AND";
        }

        return $this;
    }

}