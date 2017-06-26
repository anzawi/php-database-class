<?php

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
		'mysql' => [
			'driver' => 'mysql',
			'host_name' => 'localhost',
			'db_name' => 'database_name',
			'db_user' => 'database_username',
			'db_password' => 'database_user_password'
		],

		// PostgreSQL
		'pgsql' => [
			'driver' => 'pgsql',
			'host_name' => 'localhost',
			'db_name' => 'database_name',
			'db_user' => 'database_username',
			'db_password' => 'database_user_password'
		],

		// SQLite
		'sqlite' => [
			'db_path' => 'my/database/path/database.db',
		],

		//	MS SQL Server
		'mssql' => [
			'driver' => 'mssql',
			'host_name' => 'localhost',
			'db_name' => 'database_name',
			'db_user' => 'database_username',
			'db_password' => 'database_user_password'
		],

		//	MS SQL Server
		'sybase' => [
			'driver' => 'sybase',
			'host_name' => 'localhost',
			'db_name' => 'database_name',
			'db_user' => 'database_username',
			'db_password' => 'database_user_password'
		],

		// Oracle Call Interface
		'oci' => [
			'tns' => '
					DESCRIPTION =
					    (ADDRESS_LIST =
					      (ADDRESS = (PROTOCOL = TCP)(HOST = yourip)(PORT = 1521))
					    )
					    (CONNECT_DATA =
					      (SERVICE_NAME = orcl)
					    )
					  )',

			'db_user' => 'database_username',
			'db_password' => 'database_user_password'
		]
	],


	"pagination" => [
		"no_data_found_message" => "Oops, No Data Found to show ..",
		"records_per_page"      => 10,
		"link_query_key"        => "page"
	],

	"search" => [
		"key" => "search",
		"method" => "get"
	]
];
