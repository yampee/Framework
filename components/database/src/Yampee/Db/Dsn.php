<?php

/*
 * Yampee Components
 * Open source web development components for PHP 5.
 *
 * @package Yampee Components
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Render a DSN string using given arguments and parameters.
 */
class Yampee_Db_Dsn
{
	/**
	 * @var string
	 */
	protected $driver;

	/**
	 * @var string
	 */
	protected $database;

	/**
	 * @var string
	 */
	protected $username;

	/**
	 * @var string
	 */
	protected $password;

	/**
	 * @var string
	 */
	protected $host;

	/**
	 * @var integer
	 */
	protected $port;

	/*
	 * Drivers constants
	 */
	const DRIVER_DBLIB = 'dblib';
	const DRIVER_FIREBIRD = 'firebird';
	const DRIVER_INFORMIX = 'informix';
	const DRIVER_MSSQL = 'mssql';
	const DRIVER_MYSQL = 'mysql';
	const DRIVER_OCI = 'oci';
	const DRIVER_ODBC = 'odbc';
	const DRIVER_PGSQL = 'pgsql';
	const DRIVER_SQLITE = 'sqlite';
	const DRIVER_SYBASE = 'sybase';

	/**
	 * Available drivers.
	 * @var array
	 */
	protected $availableDrivers = array(
		self::DRIVER_DBLIB, self::DRIVER_FIREBIRD, self::DRIVER_INFORMIX, self::DRIVER_MSSQL, self::DRIVER_MYSQL,
		self::DRIVER_OCI, self::DRIVER_ODBC, self::DRIVER_PGSQL, self::DRIVER_SQLITE, self::DRIVER_SYBASE,
	);

	/**
	 * Constructor.
	 *
	 * @param string  $driver
	 * @param string  $database
	 * @param string  $username
	 * @param string  $password
	 * @param string  $host
	 * @param integer $port
	 * @throws        RuntimeException
	 */
	public function __construct($driver, $database, $username = null, $password = null, $host = 'localhost', $port = null)
	{
		if (! in_array($driver, $this->availableDrivers)) {
			throw new RuntimeException(sprintf('PDO driver "%s" is not supported', $driver));
		}

		if (! in_array($driver, PDO::getAvailableDrivers())) {
			throw new RuntimeException(sprintf('PDO driver "%s" is not loaded', $driver));
		}

		$this->setDatabase($database);
		$this->setDriver($driver);

		if ($host) {
			$this->setHost($host);
		}

		if ($port) {
			$this->setPort($port);
		}

		if ($username) {
			$this->setUsername($username);
		}

		if ($password) {
			$this->setPassword($password);
		}
	}

	/**
	 * Render the DSN on string usage.
	 *
	 * @return string
	 */
	public function __toString()
	{
		return $this->render();
	}

	/**
	 * Render the DSN using the given driver and parameters.
	 * @return string
	 */
	public function render()
	{
		$dsn = $this->driver.':';

		switch( $this->driver ) {
			case 'firebird':
				return $dsn.$this->renderDsnForFirebird();
			case 'informix':
				return $dsn.$this->renderDsnForInformix();
			case 'oci':
				return $dsn.$this->renderDsnForOci();
			case 'odbc':
				return $dsn.$this->renderDsnForOdbc();
			case 'pgsql':
				return $dsn.$this->renderDsnForPgsql();
			case 'sqlite':
				return $dsn.$this->renderDsnForSqlite();
			case 'mysql':
			case 'mssql':
			case 'sybase':
			case 'dblib':
			default:
				return $dsn.$this->renderDsnForDefault();
		}
	}

	/**
	 * @param string $database
	 * @return Yampee_Db_Dsn
	 */
	public function setDatabase($database)
	{
		$this->database = $database;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDatabase()
	{
		return $this->database;
	}

	/**
	 * @param string $driver
	 * @return Yampee_Db_Dsn
	 */
	public function setDriver($driver)
	{
		$this->driver = $driver;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getDriver()
	{
		return $this->driver;
	}

	/**
	 * @param string $host
	 * @return Yampee_Db_Dsn
	 */
	public function setHost($host)
	{
		$this->host = $host;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getHost()
	{
		return $this->host;
	}

	/**
	 * @param string $password
	 * @return Yampee_Db_Dsn
	 */
	public function setPassword($password)
	{
		$this->password = $password;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @param int $port
	 * @return Yampee_Db_Dsn
	 */
	public function setPort($port)
	{
		$this->port = $port;

		return $this;
	}

	/**
	 * @return int
	 */
	public function getPort()
	{
		return $this->port;
	}

	/**
	 * @param string $username
	 * @return Yampee_Db_Dsn
	 */
	public function setUsername($username)
	{
		$this->username = $username;

		return $this;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}

	/**
	 * Render a DSN for a non-specific driver.
	 * @return string
	 */
	protected function renderDsnForDefault()
	{
		$port = ! empty($this->port) ? $this->port : null;

		return $this->renderDsnParts(array(
			'host' => $this->host,
			'port' => $port,
			'dbname' => $this->database,
		));
	}

	/**
	 * Render a DSN for Firebird
	 * @return string
	 */
	protected function renderDsnForFirebird()
	{
		$host = ! empty($this->host) ? $this->host : null;
		$port = ! empty($this->port) ? $this->port : null;

		return $this->renderDsnParts(array(
			'DataSource' => $host,
			'Port' => $port,
			'Database' => $this->database,
			'User' => $this->username,
			'Password' => $this->password
		));
	}

	/**
	 * Render a DSN for Informix
	 * @return string
	 */
	protected function renderDsnForInformix()
	{
		$host = ! empty($this->host) ? $this->host : null;
		$port = ! empty($this->port) ? $this->port : null;

		return $this->renderDsnParts(array(
			'host' => $host,
			'service' => $port,
			'database' => $this->database
		), '; ');
	}

	/**
	 * Render a DSN for OCI
	 * @return string
	 */
	protected function renderDsnForOci()
	{
		$dbname = $this->database;
		$port = $this->port ? ':'.$this->port : '';

		if($this->host) {
			$dbname = '//'.$this->host.$port.'/'.$this->database;
		}

		return 'dbname='.$dbname;
	}

	/**
	 * Render a DSN for ODBC
	 * @return string
	 */
	protected function renderDsnForOdbc()
	{
		return $this->database;
	}

	/**
	 * Render a DSN for PostGreSQL
	 * @return string
	 */
	protected function renderDsnForPgsql()
	{
		$host = ! empty($this->host) ? $this->host : null;
		$port = ! empty($this->port) ? $this->port : null;

		return $this->renderDsnParts(array(
			'host' => $host,
			'port' => $port,
			'dbname' => $this->database,
			'user' => $this->username,
			'password' => $this->password
		), ' ');
	}

	/**
	 * Render a DSN for SQLite
	 * @return string
	 */
	protected function renderDsnForSqlite()
	{
		return $this->database;
	}

	/**
	 * Flattens Map of DSN Parts using a Delimiter.
	 * @access public
	 * @param array $map DSN Parts Map
	 * @param string $delimiter Delimiter between DSN Parts
	 * @return string
	 */
	protected function renderDsnParts($map, $delimiter = ';')
	{
		$list = array();

		foreach($map as $key => $value) {
			if(! is_null($value)) {
				$list[] = $key.'='.$value;
			}
		}

		return implode($delimiter, $list);
	}
}