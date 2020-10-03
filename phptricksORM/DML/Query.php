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

trait Query
{

    /**
     * find single row from table via id
     *
     * @param  int  $id  [description]
     *
     * @return Collection or object (as you choice from config file)  results
     *     or empty
     */
    public function find($id)
    {
        return $this->where($this->_idColumn, $id)
                    ->first();
    }

    /**
     * Get First record Only
     */
    public function first($fields = ['*'], $last = false)
    {
        $results = $this->select($fields, $last);

        return $this->collection([
            'results'   => isset($results[0]) ? $results[0] : [],
            'table'     => $this->_table,
            'id'        => $this->_idColumn,
            'relations' => $this->_relations,
        ]);
    }

    /**
     * select from database
     *
     * @param  array  $fields  fields we need to select
     *
     * @return Collection result of select as Collection object
     */
    public function select($fields = ['*'], $last = false)
    {
        if ($fields === true) {
            $fields = ['*'];
            $last   = true;
        }
        if ($fields != ['*'] && ! is_null($this->_idColumn)) {
            if ( ! in_array($this->_idColumn, $fields)) {
                $fields[$this->_idColumn];
            }
        }

        if ( ! $last) {
            $sql = "SELECT ".implode(', ', $fields)
                   ." FROM {$this->_table} as other {$this->_query}";
        } else {
            //$this->_query .= ($this->_ordering == false ? " ORDER BY {$this->_idColumn} DESC" : '');
            $sql = "SELECT * FROM (
                        SELECT ".implode(', ', $fields)."  
                        FROM {$this->_table} as other
                        
                         {$this->_query}  
                        ) sub  ORDER by {$this->id()} ASC";
        }


        $this->_query    = $sql;
        $this->_ordering = false;


        return $this->collection([
            'results'   => $this->query($sql)->results(),
            'table'     => $this->_table,
            'id'        => $this->_idColumn,
            'relations' => $this->_relations,
        ]);
    }

    /**
     * DB::query()
     * check if sql statement is prepare
     * append value for sql statement if $params is set
     * fetch results
     *
     * @param  string  $sql
     * @param  array  $params
     *
     * @return mixed
     */
    protected function query($sql, $params = [])
    {
        /*
          // uncomment this line to see your query
           var_dump($sql);
         */

        $this->_query = "";
        $this->_where = "WHERE";
        // set _error. true to that if they can not be false for this function to work properly, this function makes the
        // value of _error false if there is no implementation of the sentence correctly
        $this->_error = false;
        // check if sql statement is prepared
        $query = $this->_pdo->prepare($sql);
        // if $params isset
        if (count($params)) {
            /**
             * @var $x int
             * counter
             */
            $x = 1;
            foreach ($params as $param) {
                // append values to sql statement
                $query->bindValue($x, $param);

                $x++;
            }
        }

        // check if sql statement executed
        if ($query->execute()) {
            try {
                $this->_results = $query->fetchAll(\config('fetch'));
            } catch (\PDOException $e) {
            }

            $this->_sql = $query;
            // set _results = retrieved data


            // set _count = count rows comes
            $this->_count = $query->rowCount();
        } else {
            $this->_error = true;
        }


        return $this;
    }

    /**
     * find records by columns
     * USING :
     * $db->findBy('username', 'ali')->first(); // or select() or paginate()
     *
     * @param $column
     * @param $value
     *
     * @return mixed
     */
    public function findBy($column, $value)
    {
        return $this->where($column, $value);
    }

}