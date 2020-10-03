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

return [

    /*
    |--------------------------------------------------------------------------
    | PDO Fetch Style
    |--------------------------------------------------------------------------
    |
    | By default, database results will be returned as instances of the PHP
    | stdClass object; however, you may desire to retrieve records in an
    | array format for simplicity. Here you can tweak the fetch style.
    |
    */

    'fetch' => PDO::FETCH_OBJ, // for array -> PDO::FETCH_ASSOC,

    /*
    |--------------------------------------------------------------------------
    | Default Database Connection Name
    |--------------------------------------------------------------------------
    |
    | Here you may specify which of the database connections below you wish.
    */

    'default' => 'mysql',


    /*
    |--------------------------------------------------------------------------
    | Database Connections
    |--------------------------------------------------------------------------
    |
    | Here are each of the database connections setup for your application.
    | Of course, examples of configuring each database platform that is
    | supported by PHPtricks/database class is shown below to make development simple.
    |
    |
    | All database work in HPtricks/database is done through the PHP PDO facilities
    | so make sure you have the driver for your particular database of
    | choice installed on your machine before you begin development.
    |
    */

    'connections' => [
        // MySQL 3.x/4.x/5.x
        'mysql'  => [
            'driver'      => 'mysql',
            'host_name'   => '192.168.10.10',
            'db_name'     => 'test',
            'db_user'     => 'homestead',
            'db_password' => 'secret',
        ],

        // PostgreSQL
        'pgsql'  => [
            'driver'      => 'pgsql',
            'host_name'   => 'localhost',
            'db_name'     => 'database_name',
            'db_user'     => 'database_username',
            'db_password' => 'database_user_password',
        ],

        // SQLite
        'sqlite' => [
            'db_path' => 'my/database/path/database.db',
        ],

        //	MS SQL Server
        'mssql'  => [
            'driver'      => 'mssql',
            'host_name'   => 'localhost',
            'db_name'     => 'database_name',
            'db_user'     => 'database_username',
            'db_password' => 'database_user_password',
        ],

        //	MS SQL Server
        'sybase' => [
            'driver'      => 'sybase',
            'host_name'   => 'localhost',
            'db_name'     => 'database_name',
            'db_user'     => 'database_username',
            'db_password' => 'database_user_password',
        ],

        // Oracle Call Interface
        'oci'    => [
            'tns' => '
					DESCRIPTION =
					    (ADDRESS_LIST =
					      (ADDRESS = (PROTOCOL = TCP)(HOST = yourip)(PORT = 1521))
					    )
					    (CONNECT_DATA =
					      (SERVICE_NAME = orcl)
					    )
					  )',

            'db_user'     => 'database_username',
            'db_password' => 'database_user_password',
        ],
    ],


    "pagination" => [
        "no_data_found_message" => "Oops, No Data Found to show ..",
        "records_per_page"      => 10,
        "link_query_key"        => "page",
    ],

    // Directories for Commands
    'directories' => [
        'create' => __DIR__.'/../Migrations/create/',
        'alter'  => __DIR__.'/../Migrations/alter/',
        'drop'   => __DIR__.'/../Migrations/drop/',
        'migrated-file' =>  __DIR__.'/../Migrations/temp/',
        'models' =>  __DIR__.'/../Models/'
    ],
];
