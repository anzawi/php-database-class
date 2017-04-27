<?php

namespace PHPtricks\Orm\Providers;

trait Provider
{
	/**
	 * Connect database with mysql driver
	 * @param $null
	 */
	protected function mysql($null)
	{
		try
		{
			$this->_pdo = new \PDO("mysql:host=" . \config('host_name') . ";dbname=" .
				config('db_name'), \config('db_user'), \config('db_password'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}
	/**
	 * Connect database with sqlite driver
	 * @param $null
	 */
	protected function sqlite($null)
	{
		try
		{
			$this->_pdo = new \PDO("sqlite:" . \config('db_path'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Connect database with pgsql driver
	 * @param $null
	 */
	protected function pgsql($null)
	{
		try
		{
			$this->_pdo = new \PDO('pgsql:user='. \config('db_user') .'
          dbname=' . \config('db_name') . ' password='.\config('db_password'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Connect database with mssql driver
	 * @param $null
	 */
	protected function mssql($null)
	{
		try
		{
			$this->_pdo = new \PDO("mssql:host=" . \config('host_name') . ";dbname=" .
				\config('db_name'), \config('db_user'), \config('db_password'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Connect database with sybase driver
	 * @param $null
	 */
	protected function sybase($null)
	{
		try
		{
			$this->_pdo = new \PDO("sybase:host=" . \config('host_name') . ";dbname=" .
				\config('db_name'), \config('db_user'), \config('db_password'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		} catch(\PDOException $e) {
			die($e->getMessage());
		}
	}

	/**
	 * Connect database with oci driver
	 * @param $null
	 */
	protected function oci($null)
	{
		try{
			$conn = new \PDO("oci:dbname=".\config('tns'),
				\config('db_user'), \config('db_password'));
			$this->_pdo->exec("set names " . 'utf8');
			$this->_pdo->setAttribute( \PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION );
		}catch(\PDOException $e){
			die ($e->getMessage());
		}
	}

}