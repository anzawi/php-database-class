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

/**
 * Get Config from database_config file in easy
 */
function config($path = '')
{
    $config = include(dirname(__FILE__).'/database_config.php');
    if (strpos($path, ".") !== false) {
        $path = explode(".", $path);
    }

    if (is_array($path) && count($path)) {
        foreach ($path as $setting) {
            if (isset($config[$setting])) {
                $config = $config[$setting];
            }
        }

        return $config;
    } else {
        if (isset($config[$path])) {
            return $config[$path];
        }

        $configValue = isset($config['connections'][$config['default']][$path])
            ?
            $config['connections'][$config['default']][$path] : null;
        if ($path) {
            if ( ! is_null($configValue)) {
                return $configValue;
            }
        }
    }

    return $config['default'];
}


/**
 * Generate Column Name when use Database::dataView() method
 *
 * convert column_name and columnName to Column Name
 */
function getColumnName($columnName = '')
{
    return ucwords(str_replace("_", "",
        implode(" ", preg_split('/(?=[A-Z]|_)/', $columnName))));
}
