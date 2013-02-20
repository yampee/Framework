<?php

/*
 * Yampee
 * Open source web development framework for PHP 5.2.4 or newer.
 *
 * @package Yampee
 * @author Titouan Galopin <galopintitouan@gmail.com>
 * @link http://titouangalopin.com
 */

/**
 * Database queries builder
 */
class Yampee_Db_QueryBuilder
{
	/**
	 * @var Yampee_Db_Manager
	 */
	private $manager;

	/**
	 * @var string
	 */
	private $select;

	/**
	 * @var string
	 */
	private $insert;

	/**
	 * @var string
	 */
	private $update;

	/**
	 * @var string
	 */
	private $delete;

	/**
	 * @var array
	 */
	private $set = array();

	/**
	 * @var string
	 */
	private $from;

	/**
	 * @var string
	 */
	private $innerJoin;

	/**
	 * @var string
	 */
	private $leftJoin;

	/**
	 * @var string
	 */
	private $where;

	/**
	 * @var string
	 */
	private $groupBy;

	/**
	 * @var string
	 */
	private $having;

	/**
	 * @var string
	 */
	private $orderBy;

	/**
	 * @var integer
	 */
	private $limit;

	/**
	 * @var integer
	 */
	private $offset;

	/**
	 * @var Yampee_Collection
	 */
	private $parameters;

	/**
	 * Constructor
	 * @param Yampee_Db_Manager $manager
	 */
	public function __construct(Yampee_Db_Manager $manager)
	{
		$this->manager = $manager;
		$this->parameters = array();
	}

	/**
	 * @param $fields
	 * @return Yampee_Db_QueryBuilder
	 * @throws LogicException
	 */
	public function select($fields)
	{
		$this->parameters = array();

		if (! empty($this->delete) || ! empty($this->update)) {
			throw new LogicException('You cannot use the select() method: query type already defined in QueryBuilder.');
		}

		$this->select = (string) $fields;

		return $this;
	}

	/**
	 * @param $table
	 * @return Yampee_Db_QueryBuilder
	 * @throws LogicException
	 */
	public function insert($table)
	{
		$this->parameters = array();

		if (! empty($this->select) || ! empty($this->delete)) {
			throw new LogicException('You cannot use the insert() method: query type already defined in QueryBuilder.');
		}

		$this->insert = (string) $table;
		$this->from = (string) $table;

		return $this;
	}

	/**
	 * @param $table
	 * @return Yampee_Db_QueryBuilder
	 * @throws LogicException
	 */
	public function update($table)
	{
		$this->parameters = array();

		if (! empty($this->select) || ! empty($this->delete)) {
			throw new LogicException('You cannot use the update() method: query type already defined in QueryBuilder.');
		}

		$this->update = (string) $table;
		$this->from = (string) $table;

		return $this;
	}

	/**
	 * @return Yampee_Db_QueryBuilder
	 * @throws LogicException
	 */
	public function delete()
	{
		$this->parameters = array();

		if (! empty($this->select) || ! empty($this->update)) {
			throw new LogicException('You cannot use the delete() method: query type already defined in QueryBuilder.');
		}

		$this->delete = 'delete';

		return $this;
	}

	/**
	 * @param $fieldName
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function set($fieldName, $value)
	{
		$this->set[(string) $fieldName] = $value;

		return $this;
	}

	/**
	 * @param $table
	 * @return Yampee_Db_QueryBuilder
	 */
	public function from($table)
	{
		$this->from = (string) $table;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function innerJoin($value)
	{
		$this->innerJoin = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function leftJoin($value)
	{
		$this->leftJoin = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function where($value)
	{
		if (! empty($this->where)) {
			$this->andWhere((string) $value);
		} else {
			$this->where = (string) $value;
		}

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function andWhere($value)
	{
		if (! empty($this->where)) {
			$this->where .= ' AND '.(string) $value;
		} else {
			$this->where = (string) $value;
		}

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function orWhere($value)
	{
		if (! empty($this->where)) {
			$this->where .= ' OR '.(string) $value;
		} else {
			$this->where = (string) $value;
		}

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function groupBy($value)
	{
		$this->groupBy = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function having($value)
	{
		$this->having = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function orderBy($value)
	{
		$this->orderBy = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function limit($value)
	{
		$this->limit = (string) $value;

		return $this;
	}

	/**
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function offset($value)
	{
		$this->offset = (string) $value;

		return $this;
	}

	/**
	 * @param $name
	 * @param $value
	 * @return Yampee_Db_QueryBuilder
	 */
	public function setParameter($name, $value)
	{
		$this->parameters[(string) $name] = (string) $value;

		return $this;
	}

	/**
	 * Execute the query and return the statement.
	 * @return array
	 * @throws LogicException
	 */
	public function execute()
	{
		$query = false;

		if (! empty($this->select)) {
			if (empty($this->select)) {
				throw new LogicException(sprintf(
					'You must select fields to extract datas from "%s".', $this->select
				));
			}

			$query = 'SELECT ';
			$query .= $this->select;

			if (! empty($this->from)) {
				$query .= ' FROM '.$this->from;
			}

			if (! empty($this->innerJoin)) {
				$query .= ' INNER JOIN '.$this->innerJoin;
			}

			if (! empty($this->leftJoin)) {
				$query .= ' LEFT JOIN '.$this->leftJoin;
			}

			if (! empty($this->where)) {
				$query .= ' WHERE '.$this->where;
			}

			if (! empty($this->groupBy)) {
				$query .= ' GROUP BY '.$this->groupBy;
			}

			if (! empty($this->having)) {
				$query .= ' HAVING '.$this->having;
			}

			if (! empty($this->orderBy)) {
				$query .= ' ORDER BY '.$this->orderBy;
			}

			if (! empty($this->limit)) {
				$query .= ' LIMIT '.$this->limit;
			}

			if (! empty($this->offset)) {
				$query .= ' OFFSET '.$this->limit;
			}
		} elseif (! empty($this->insert)) {
			if (empty($this->set)) {
				throw new LogicException(sprintf(
					'Database insertion in "%s" can not be empty.', $this->insert
				));
			}

			$query = 'INSERT INTO ';
			$query .= $this->insert;
			$query .= ' SET ';

			foreach ($this->set as $name => $value) {
				$query .= $name.' = :'.$name.', ';
				$this->setParameter($name, $value);
			}

			$query = substr($query, 0, -2);
		} elseif (! empty($this->update)) {
			if (empty($this->set)) {
				throw new LogicException(sprintf(
					'Database update in "%s" can not be empty.', $this->insert
				));
			}

			$query = 'UPDATE ';
			$query .= $this->update;
			$query .= ' SET ';

			foreach ($this->set as $name => $value) {
				$query .= $name.' = :'.$name.', ';
				$this->setParameter($name, $value);
			}

			$query = substr($query, 0, -2);

			if (! empty($this->where)) {
				$query .= ' WHERE '.$this->where;
			}

			if (! empty($this->having)) {
				$query .= ' HAVING '.$this->having;
			}
		} elseif (! empty($this->delete)) {
			$query = 'DELETE FROM ';
			$query .= $this->from;

			if (empty($this->from)) {
				throw new LogicException(sprintf(
					'You must provide a table name to delete datas.'
				));
			}

			if (! empty($this->where)) {
				$query .= ' WHERE '.$this->where;
			}

			if (! empty($this->having)) {
				$query .= ' HAVING '.$this->having;
			}
		}

		if (! $query) {
			throw new LogicException(
					'Query type can not ne found in QueryBuilder::execute(). '.
					'Use select(), update(), delete() or insert().'
			);
		}

		return $this->manager->query($query, $this->parameters);
	}
}