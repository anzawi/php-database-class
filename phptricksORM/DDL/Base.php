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

namespace PHPtricks\Orm\DDL;

/**
 * Trait Base
 *
 * @package PHPtricks\Orm\DDL
 */
trait Base
{

    use Vars;
    use Create;
    use Alter;

    /**
     * set _schema var value
     *
     * @param  array  $structures  the structure of table
     *
     * @return object return Collection object
     */
    public function schema($structures = [])
    {
        if (count($structures)) // check if isset $structures
        {
            /**
             * to store columns structures
             *
             * @var array
             */
            $schema = [];

            foreach ($structures as $column => $options) {
                $type        = $options; // the type is the prototype of column
                $constraints = ''; // store all constraints for one column

                // check if we have a constraints
                if ( ! strpos($options, '|') === false) {
                    $constraints = explode('|',
                        $options); // the separator to constraints is --> | <--
                    $type        = $constraints[0]; // the type is first key
                    unset($constraints[0]); // remove type from constraints
                    $constraints = implode(' ',
                        $constraints); // convert constraints to string
                    $constraints = strtr($constraints, [
                        'primary'   => 'PRIMARY KEY',
                        // change (primary to PRIMARY KEY -> its valid constraint in sql)
                        'increment' => 'AUTO_INCREMENT', // same primary
                        'not_null'  => 'NOT NULL', // same primary
                    ]);
                }

                // check if type is 'increments' we want to change it to integer and add some constraints like primary key ,not null, unsigned and auto increment
                ($type == 'increments' ?
                    $type = "INT UNSIGNED PRIMARY KEY AUTO_INCREMENT NOT NULL"
                    : null);

                // check if type of column is string change it to valid sql type (VARCHAR and set length)
                // ['username' => 'string:255'] convert to username VARCHAR(255)
                if (strpos($type, 'string') !== false) {
                    $type = explode(':', $type);
                    $type = "VARCHAR({$type[1]})";
                }

                // check if column has a default value
                // ['username' => 'string:255|default:no-name'] convert to username VARCHAR(255) DEFAULT 'no name'
                if (strpos($constraints, 'default') !== false) {
                    preg_match("/(:)[A-Za-z0-9](.*)+/", $constraints, $match);

                    $match[0]    = str_replace(':', '', $match[0]);
                    $temp        = str_replace('-', ' ', $match[0]);
                    $constraints = str_replace(":".$match[0], " '{$temp}' ",
                        $constraints);
                }

                // add key to schema var contains column _type constraints
                // ex: username VARCHAR(255) DEFUALT 'no name' NOT NULL
                $schema[] = "$column $type ".$constraints;
            }

            // set _schema the all columns structure
            $this->_schema = '('.implode(",", $schema).')';

            return $this; // return DB object
        }

        return null; // return null
    }

    /**
     * check if table is exist in database
     *
     * @param  string  $table
     *
     * @return bool
     */
    /*    public function tableExist($table = '')
        {
            $table = $this->query("SHOW TABLES LIKE '{$table}'")->results();

            if (!is_null($table) && count($table)) {
                return true;
            }

            return false;
        }*/

    /**
     * @return mixed
     */
    public function showMeSchema()
    {
        return $this->_schema;
    }

    /**
     * @param  string  $error
     *
     * @return string
     */
    protected function setError(string $error)
    {
        return $this->_errors[] = $error;
    }

    /**
     * @return string
     */
    public function getErrors()
    {
        return implode('\n', $this->_errors);
    }

}