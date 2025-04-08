<?php

namespace Repository;

use Latte\Essential\Nodes\DefineNode;
use Nette\Utils\Strings;

/**
 * Příkazy do databáze
 *
 * @author Adam Novák
 */
class MainRepository
{
	private $query;
	
	function __construct(private \Nette\Database\Explorer $context, private $table = null) {
		$this->table = $table;
	}
	
	public function getById($id){
		return $this->getRows()->where('id = ?', $id)->fetch();
    }


	/**
	 *
	 * @param int|string $id
	 * @param string $column
	 * @return int (počet smazaných řádků)
	 */
	public function delete($id, $column = 'id')
	{
		return $this->findByColumn($column, $id)->getQuery()->delete();
	}

    /**
	 *
	 * @param string $column
	 * @param string|int $id
	 * @return \Repository\MainRepository
	 */
	public function findByColumn($column, $id)
	{
		$this->query = $this->findAll()->getQuery()->where($column, $id);
		return $this;
	}


	/**
	 *
	 * @param array|\Traversable|Selection array($column => $value)|\Traversable|Selection for INSERT ... SELECT $arr
	 * @return ActiveRow|int|bool Returns IRow or number of affected rows for Selection or table without primary key
	 */
	public function insert($arr)
	{
		return $this->context->table($this->table)->insert($arr);
	}


	/**
	 *
	 * @param array|\Traversable ($column => $value) $arr
	 * @param int|string $id
	 * @param string $column
	 */
	public function update($arr, $id, $column = 'id')
	{
		$this->context->table($this->table)->where($column, $id)->update($arr);
	}


	/**
	 *
	 * @param type $condition
	 * @param type $parameters
	 * @return \Nette\Database\Table\Selection
	 */
	protected function where($condition, $parameters = null)
	{
		if (is_null($this->query))
		{
			$this->query = $this->findAll()->getQuery();
		}
		if (is_null($parameters))
		{
			$this->query = $this->query->where($condition);
		} else
		{
			$this->query = $this->query->where($condition, $parameters);
		}
		return $this->query;
	}

    /**
	 *
	 * @param void
	 * @return \Repository\MainRepository
	 */
	public function findAll()
	{
		$this->query = $this->context->table($this->table);
		return $this;
	}

	/**
	 *
	 * @return \Nette\Database\Table\Selection
	 */
	public function getRows()
	{
		return $this->findAll()->getQuery();
	}


	public function getActiveRows($active = 1)
	{
		$this->query = $this->getRows()->where('is_active = ?', $active);
		return $this;
	}


	/**
	 * @param  string for example 'column1, column2 DESC'
	 * @return self
	 */
	public function order($columns)
	{
		$this->query = $this->getQuery()->order($columns);
		return $this;
	}


	/**
	 * Sets limit clause, more calls rewrite old values.
	 * @param  int
	 * @param  int
	 * @return self
	 */
	public function limit($limit, $offset = NULL)
	{
		$this->query = $this->getQuery()->limit($limit, $offset);
		return $this;
	}


	/**
	 *
	 * @return \Nette\Database\Table\ActiveRow
	 */
	public function fetch()
	{
		return $this->query->fetch();
	}


	/**
	 *
	 * @return Array
	 */
	public function fetchAll()
	{
		return $this->query->fetchAll();
	}


	/**
	 *
	 * @return int
	 */
	public function count()
	{
		return $this->query->count();
	}


	/**
	 *
	 * @return \Nette\Database\Table\Selection
	 */
	public function getQuery()
	{
		return $this->query;
	}

}
