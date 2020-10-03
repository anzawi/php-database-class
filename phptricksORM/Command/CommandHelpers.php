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

namespace PHPtricks\Orm\Command;

class CommandHelpers
{

    /**
     * @var
     */
    private $_dir;
    /**
     * the file name
     *
     * @var string
     */
    private $_fileName = 'migrated.json';

    /**
     * CommandHelpers constructor.
     */
    public function __construct()
    {
        $this->_dir = config('directories.migrated-file').$this->_fileName;
    }

    /**
     * we want to mark migrated classes ,In order not to be migrate again
     * this helper function write in migrated.json file.
     *
     * @param $migrationClassName
     */
    public function setMigrationAsMigrated($migrationClassName): void
    {
        if ( ! file_exists($this->_dir)) {
            $this->createFile();
        }

        $migrated                      = $this->getFileContent();
        $migrated[$migrationClassName] = true;
        $migrated                      = json_encode($migrated);
        file_put_contents($this->_dir, $migrated);
    }

    /**
     * Generate migrated.json file
     */
    private function createFile(): void
    {
        @mkdir(dirname($this->_dir), 0755, true);
        file_put_contents($this->_dir, '{}');
    }

    /**
     * get migrated.json content
     *
     * @return array
     */
    private function getFileContent(): array
    {
        @$migrated = file_get_contents($this->_dir);

        return json_decode($migrated, true);
    }

    /**
     * check if class migrated or not
     *
     * @param $migrationClassName
     *
     * @return bool
     */
    public function isMigrated($migrationClassName): bool
    {
        if ( ! file_exists($this->_dir)) {
            return false;
        }

        $migrated = $this->getFileContent();

        return isset($migrated[$migrationClassName]);
    }

    /**
     * delete migrated.json file
     * @param  bool  $delete
     */
    public function clear(bool $delete = false): void
    {
        $path = config('directories.migrated-file');

        if ($delete) {
            if (is_dir($path)) {
                unlink($path);
            }
        }
        if ( ! $delete) {
            if (file_exists($this->_dir)) {
                file_put_contents($this->_dir, '{}');
            }
        }
    }

}