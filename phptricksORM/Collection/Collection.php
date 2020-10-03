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

namespace PHPtricks\Orm\Collection;

use PHPtricks\Orm\Database;

/**
 * Class Collection
 *
 * @package PHPtricks\Orm\Collection
 */
class Collection extends Database
{

    /**
     * Collection constructor.
     *
     * @param $data
     * @param  null  $connection
     */
    public function __construct($data, $connection = null)
    {
        if (isset($connection)) {
            $this->_table    = $data['table'];
            $this->_results  = $data['results'];
            $this->_idColumn = $data['id'];
            $this->_pdo      = $connection->_pdo;
        } else {
            if (is_array($data)) {
                $this->_results = isset($data['results']) ? $data['results']
                    : $data;
            } else {
                $this->_results = $data;
            }
        }
    }

    /**
     * @return mixed|null
     */
    public function first()
    {
        return isset($this->_results[0]) ? $this->_results[0] : null;
    }

    /**
     * @param  int  $count
     *
     * @return array|mixed|null
     */
    public function last($count = 0)
    {
        $reverse = array_reverse($this->results());

        if ( ! $count) {
            return isset($reverse[0]) ? $reverse[0] : null;
        }

        $lastRecords = [];
        $j           = 0;

        for ($i = 0; $i < $count; $i++) {
            $lastRecords[$j] = $reverse[$i];
            $j++;
        }

        return $lastRecords;
    }

    /**
     * @param  callable  $callback
     *
     * @return $this
     */
    public function each(callable $callback)
    {
        foreach ($this->results() as $key => $value) {
            $callback($value, $key);
        }

        return $this;
    }

    /**
     * @param  callable|null  $callback
     *
     * @return $this
     */
    public function filter(callable $callback = null)
    {
        if ($callback) {
            return new static(array_filter($this->results(), $callback));
        }

        // exclude null and empty
        return new static(array_filter($this->results()));
    }

    /**
     * @param  callable  $callback
     *
     * @return $this
     */
    public function map(callable $callback)
    {
        $keys    = $this->keys()->all();
        $results = array_map($callback, $this->results(), $keys);

        return new static(array_combine($keys, $results));
    }

    /**
     * @return mixed
     */
    public function all()
    {
        return $this->results();
    }

    /**
     * @return $this
     */
    public function keys()
    {
        return new static(array_keys($this->results()));
    }

    /**
     * @return false|string
     */
    public function __toString()
    {
        return $this->toJson();
    }

    /**
     * @return false|string
     */
    public function toJson()
    {
        return json_encode($this->results());
    }

    
    /**
     * @return false|string
     */
    public function toJsonFormatted()
    {
        return '<pre>' . json_encode($this->results()) . '</pre>';
    }

    /**
     * @param $items
     *
     * @return $this
     */
    public function merge($items)
    {
        return new static(
            array_merge(
                $this->results(),
                $this->toArray($items)
            )
        );
    }

    /**
     * @param $items
     *
     * @return array|mixed
     */
    protected function toArray($items = null)
    {
        return (array)$items->all();
    }

    public function results()
    {
        return $this->_results;
    }

    public function count()
    {
        return count($this->results());
    }
}
