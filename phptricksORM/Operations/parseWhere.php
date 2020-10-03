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
 * Trait parseWhere
 *
 * @package PHPtricks\Orm\Operations
 */
trait parseWhere
{

    /**
     * @var string[]
     */
    private $__whereTypes = ['AND', 'OR'];

    /**
     * How to use
     * $con = [
     *  [
     *      'sex', '=', 'female'
     *  ],
     * 'AND' => [
     *      'position', '=', 'manager'
     *  ]
     * ];
     * $db->table('table_name')->parseWhere($con)->select();
     */
    public function parseWhere(array $cons, $type = "AND")
    {
        $this->_query .= " {$type} (";

        foreach ($cons as $con => $st) {
            if (is_array($st)) {
                if ( ! is_numeric($st[2])) {
                    $st[2] = "'$st[2]'";
                } else {
                    $st[2] = "`$st[2]`";
                }

                if (strtolower($con) === 'none' || $con === 0) {
                    $this->_query .= " `{$st[0]}` $st[1] $st[2] ";
                } else {
                    if ($this->con($con)) {
                        $this->_query .= " {$con} `{$st[0]}` $st[1] $st[2] ";
                    }
                }
            } else {
                $this->_query .= " `{$cons[0]}` $cons[1] $cons[2] ";
                break;
            }
        }

        $this->_query .= ')';

        return $this;
    }

    /**
     * @param $con
     *
     * @return bool
     */
    private function con($con)
    {
        return in_array(strtoupper($con), $this->__whereTypes);
    }

}
