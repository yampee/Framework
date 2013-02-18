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
 * Entry point for external scripts.
 */
class Yampee_Db_Manager
{
	const MYSQL_DATE_PATTERN = '#^([1-3][0-9]{3,3})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][1-9]|3[0-1])$#';
	const MYSQL_DATETIME_PATTERN = '#^([1-3][0-9]{3,3})-(0?[1-9]|1[0-2])-(0?[1-9]|[1-2][1-9]|3[0-1])\s([0-1][0-9]|2[0-4]):([0-5][0-9]):([0-5][0-9])$#';

	/**
	 * @var Yampee_Db_Connection
	 */
	protected $connection;

	/**
	 * Constructor
	 *
	 * @param $dsn
	 * @param $username
	 * @param $password
	 * @param $charset
	 */
	public function __construct($dsn, $username = null, $password = null, $charset = 'UTF8')
	{
		if ($dsn instanceof Yampee_Db_Dsn) {
			$this->connection = new Yampee_Db_Connection($dsn->render(), $username, $password, $charset);
		} else {
			$this->connection = new Yampee_Db_Connection($dsn, $username, $password, $charset);
		}
	}

	/**
	 * Create a query builder object.
	 *
	 * @return Yampee_Db_QueryBuilder
	 */
	public function createQueryBuilder()
	{
		return new Yampee_Db_QueryBuilder($this);
	}

	/**
	 * @param       $query
	 * @param array $parameters
	 * @return array
	 */
	public function query($query, array $parameters = array())
	{
		$results = $this->connection->query($query, $parameters);

		try {
			$results = $results->fetchAll();
		} catch (PDOException $e) {
			return $results;
		}

		$records = array();

		foreach ($results as $result) {
			$record = new Yampee_Db_Record();

			foreach ($result as $field => $value) {
				$record->$field = $this->cast($value);
			}

			$records[] = $record;
		}

		return $records;
	}

	/**
	 * Insert a record in the database.
	 *
	 * @param                  $table
	 * @param Yampee_Db_Record $record
	 * @return array
	 */
	public function insert($table, Yampee_Db_Record $record)
	{
		$qb = $this->createQueryBuilder()
			->insert($table);

		foreach ($record->toArray() as $field => $value) {
			$qb->set($field, $this->uncast($value));
		}

		return $qb->execute();
	}

	/**
	 * Cast a string value in the right type based on its format.
	 *
	 * @param $value
	 * @return mixed
	 */
	private function cast($value)
	{
		if (is_numeric($value)) {
			return (float) $value;
		} elseif (preg_match(self::MYSQL_DATE_PATTERN, $value, $matched)) {
			$date = new DateTime();
			$date->setDate($matched[1], $matched[2], $matched[3]);
			$date->setTime(0, 0, 0);
			return $date;
		} elseif (preg_match(self::MYSQL_DATETIME_PATTERN, $value, $matched)) {
			$date = new DateTime();
			$date->setDate($matched[1], $matched[2], $matched[3]);
			$date->setTime($matched[4], $matched[5], $matched[6]);
			return $date;
		}

		return $value;
	}

	/**
	 * Uncast a value to store it in MySQL.
	 *
	 * @param $value
	 * @return mixed
	 */
	private function uncast($value)
	{
		if ($value instanceof DateTime) {
			return $value->format('Y-m-d H:i:s');
		}

		return $value;
	}
}