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

namespace PHPtricks\Orm\Relation;

use PHPtricks\Orm\Collection\Collection;
use PHPtricks\Orm\Model;

/**
 * Trait RelationProvider
 * @since Version 5.0.0
 *
 * @package PHPtricks\Orm\Relation
 */
trait RelationProvider
{

    /**
     * @var
     */
    private $_relationModel;
    /**
     * @var
     */
    private $_relationWithModel;

    /**
     * @var
     */
    private $_joinType = '';

    /**
     * @var
     */
    private $_allowedTyps = ['LEFT', 'RIGHT'];

    /**
     * @param  Model  $model
     *
     * @return $this
     */
    protected function make(Model $model)
    {
        $this->_relationModel = $model;

        return $this;
    }

    /**
     * @param  string  $model
     *
     * @return $this
     */
    protected function relatedWith(string $model) : RelationProvider
    {
        // create new object from model
        $this->_relationWithModel = new $model();

        return $this;
    }


    protected function join(string $type = '') : RelationProvider
    {
        // empty $type main cross join
        $this->_joinType = strtoupper($type);
        if( !in_array($type, $this->_allowedTyps) ) {
            $this->_joinType = "";
        }

        return $this;
    }

    protected function outer()
    {
        $this->_joinType .= ' OUTER';
    }

    /**
     * @param  string  $foreignId
     * @param  string|null  $id
     *
     * @return PHPtricks\Orm\Collection\Collection
     */
    protected function on(string $foreignId, string $id = null) : Collection
    {
        if ($id === null) {
            $id = $this->_relationModel->id();
        }

        $stmt = "SELECT * FROM {$this->_relationWithModel->table()} as main
                    {$this->_joinType} JOIN {$this->_relationModel->table()} as other
                    ON(main.{$id} = other.{$foreignId}){$this->getQueryAdditions()}";

        return $this->collection([
            'results' => $this->query($stmt)->results(),
            'table'   => null,
            'id'      => null,
        ]);
    }

    protected function fullOuterJoinOn(string $foreignId, string $id = null) : Collection
    {
        if ($id === null) {
            $id = $this->_relationModel->id();
        }

        $stmt = "SELECT * FROM {$this->_relationWithModel->table()} as main
                    LEFT OUTER JOIN {$this->_relationModel->table()} as other
                    ON(main.{$id} = other.{$foreignId}){$this->getQueryAdditions()}
                    
                    UNION

                    SELECT * FROM {$this->_relationWithModel->table()} as main
                    RIGHT OUTER JOIN {$this->_relationModel->table()} as other
                    ON(main.{$id} = other.{$foreignId}){$this->getQueryAdditions()}
                    ";

        return $this->collection([
            'results' => $this->query($stmt)->results(),
            'table'   => null,
            'id'      => null,
        ]);
    }

}