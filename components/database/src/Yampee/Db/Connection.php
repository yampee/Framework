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
 * Represents a database connection.
 */
class Yampee_Db_Connection
{
	/**
	 * @var PDO PDO instance
	 */
	private $pdo;

	/**
	 * @var string
	 */
	private $dsn;

	/**
	 * @var string
	 */
	private $username;

	/**
	 * @var string
	 */
	private $password;

	/**
	 * @var string
	 */
	private $charset;

	/**
	 * @var boolean If the connection is started or not
	 */
	private $started;

	/**
	 * Executed queries
	 * @var Yampee_Collection
	 */
	private $queries;

	/**
	 * @param $dsn
	 * @param $username
	 * @param $password
	 * @param $charset
	 */
	public function __construct($dsn, $username, $password, $charset)
	{
		$this->started = false;
		$this->dsn = $dsn;
		$this->username = $username;
		$this->password = $password;
		$this->charset = $charset;
		$this->queries = array();
	}

	/**
	 * Execute a query.
	 *
	 * @param       $sql
	 * @param array $parameters
	 * @return      array
	 */
	public function query($sql, array $parameters = array())
	{
		if (! $this->started) {
			$this->connect();
		}

		$time = microtime(true);

		$query = $this->pdo->prepare($sql);
		$query->execute($parameters);

		$this->queries[] = array(
			'sql' => $sql,
			'parameters' => $parameters,
			'time' => microtime(true) - $time
		);

		return $query;
	}

	/**
	 * Start the connection at the first query to speed up pages without SQL.
	 */
	private function connect()
	{
		$this->pdo = new PDO($this->dsn, $this->username, $this->password);
		$this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
		$this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

		$time = microtime(true);

		$this->pdo->query('SET NAMES '.$this->charset);

		$this->queries[] = array(
			'sql' => 'SET NAMES '.$this->charset,
			'parameters' => array(),
			'time' => microtime(true) - $time
		);

		$this->started = true;
	}

	/**
	 * @return string
	 */
	public function getCharset()
	{
		return $this->charset;
	}

	/**
	 * @return string
	 */
	public function getDsn()
	{
		return $this->dsn;
	}

	/**
	 * @return string
	 */
	public function getPassword()
	{
		return $this->password;
	}

	/**
	 * @return PDO
	 */
	public function getPdo()
	{
		return $this->pdo;
	}

	/**
	 * @return Yampee_Collection
	 */
	public function getQueries()
	{
		return $this->queries;
	}

	/**
	 * @return boolean
	 */
	public function getStarted()
	{
		return $this->started;
	}

	/**
	 * @return string
	 */
	public function getUsername()
	{
		return $this->username;
	}
}