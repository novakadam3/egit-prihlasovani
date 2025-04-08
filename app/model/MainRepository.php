<?php

namespace Repository;

use Latte\Essential\Nodes\DefineNode;
use Nette\Utils\Strings;

#table innicial_educations
define('ZAKLADNI_SKOLA', 1);
define('NEDOKONCENA_STREDNI_SKOLA', 2);
define('VYUCNI_LIST_KC', 3);
define('INIT_VYUCNI_LIST', 4);
define('INIT_MATURITA', 5);

# table grades
define('PRIJETI_OD_PRVNI_ROCNÍK', 1);
define('NASTUP_DO_VYSIHO_ROCNÍKU', 2);

# table final_educations
define('FINAL_MATURITA', 1);
define('FINAL_VYUCNI_LIST', 2);

# table statuses
define('FORMULAR_VYPLNEN', 1);
define('PRIHLASKA_POSLANA', 2);
define('PRIHLASKA_PRIJATA_PODATELNA', 3);
define('PRIHLASKA_PRIJATA_OSR', 13);
define('ZPRACOVANA_DIPSY', 4);
define('ZPRACOVANA_VSECHNY_PRILOHY_OK', 5);
define('KONAL_PRIJIMACKY', 6);
define('RIZENI_ZASTAVENO', 7);
define('NEPRIJAT', 8);
define('PRIJAT', 9);
define('PRIHLASKA_VYGENEROVANA', 10);
define('PRIHLASKA_STORNOVANA', 11);
define('PRIHLASKA_ZPETVZATA', 12);
define('POTVRZENY_ZAJEM', 14);
define('ODVOLANY_ZAJEM', 15);
define('VZDALA_SE_PRAVA', 16);

#table origins
define('FORM_SSGH_STARY', 1);
define('FORM_SSGH_NOVY', 2);
define('FORM_MD_STARY', 3);
define('FORM_MD_NOVY', 4);
define('MIMO_ZONU', 5);
define('RUCNI_DIPSY', 6);
define('MIMO_DIPSY', 7);
define('TO_CHCES', 8);

# table applicationtypes
define('ELEKTRONICKA', 1);
define('HYBRIDNI', 2);
define('PAPIROVA', 3);

#table attendances
define('ST', 1);
define('CT', 2);
define('PA', 3);
define('1_MES', 4);
define('1_DOCHAZKOVA_SKUP', 6);

#table attendances_priorities
define('NEJVETSI_ZAJEM', 1);
define('OK', 2);
define('NEMOHU', 3);

#table capacity
define('DOSTATECNY_POCET', 1);
define('ZACINA_SE_PLNIT', 2);
define('POSLEDNI_VOLNA_MISTA', 3);
define('CEKAME_NA_OTEVRENI_DK', 4);
define('POZASTAVENI_NASTUPU', 5);
define('CEKAME_NA_OTEVRENI_DK_2', 6);

#table communications
define('NEVYBRAN', 1);
define('MODRY_PRUH', 2);
define('POSTA', 3);
define('DATOVKA', 4);
define('DIPSY', 5);

#table dipsyanswers_results
define('PRIJAT_NA_PRIO_SKOLU', 1);
define('NEPRIJAT_NEDOSTATECNA_KAPACITA', 2);
define('NEPRIJAT_NESPLNENI_PODMINEK', 3);

# table documenttypes
define('POTVRZENI_OD_DOKTORA', 0);
define('KOPIE_VYSVEDCENI', 1);
define('KOPIE_VYUCNIHO_LISTU', 2);
define('NEDOKONCENA_VYSVEDCENI', 3);
define('DUMMY', 4);
define('KOPIE_VYSVEDCENI_FINAL', 6);
define('POLOLETNI_VYSVEDCENI', 7);
define('PRIHLASKA', 8);
define('OPRAVNENI_UREDNÍ_OSOBY', 9);
define('SMLOUVA', 10);
define('JINE_DOCUMENTTYPE', 13);
define('PPP', 14);
define('ZPP', 15);
define('OPC', 16);
define('PPS', 17);
define('ZP', 18);

# table filestatuses
define('NENAHRANO', 1);
define('NEZKONTROLOVANO', 2);
define('NEDOSTATECNE', 3);
define('SCHVALENO', 4);
define('SMAZANO', 5);
define('PRIJATO', 6);
define('SSL_DOC', 7);
define('NERELEVANTNI', 8);
define('STAZENO_Z_DIPSY', 10);
define('STAZENO_Z_DIPSY_NENALEZENO', 11);

# table how_did_you_hear
define('VYHLEDAVAC', 1);
define('SOCIALNI_SIT', 2);
define('VE_SKOLE', 3);
define('ATLAS_SKOLSTVI', 4);
define('KATALOG_PPP', 5);
define('INTERNET', 6);
define('DOPORUCENI', 7);
define('JINE', 8);

# table languages
define('NEMCINA', 1);
define('SPANELSTINA', 2);
define('RUSTINA', 3);
define('ANGLICTINA', 4);

# table omjstatuses
define('NEMEL', 0);
define('SPLNIL', 1);
define('NESPLNIL_NEPROSEL', 2);
define('NESPLNIL_NEDOSTAVIL_SE', 3);

# table optional_languages
define('OPT_NEMCINA', 1);
define('OPT_SPANELSTINA', 2);
define('OPT_RUSTINA', 3);

# table types
define('DENNI', 1);
define('DALKOVE', 2);
define('KOMBINOVANE', 3);

# table previousschooltypes
define("JINE_PREVIOUSSCHOOLTYPE", 27);

/**
 * Příkazy do databáze
 *
 * @author Adam Novák
 */
class MainRepository
{
	use \Nette\SmartObject;
	/** @var \Nette\Database\Context */
	protected $context;
	protected $table;
	protected $active = "active";
	protected $top = 'attribute_top';
	protected $query;
	
	function __construct(\Nette\Database\Explorer $db, $table = null) {
		$this->context = $db;
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
		$this->query = $this->findAll()->active($active)->getQuery();
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

	public function editOrAdd($arr, $id = null, $column = 'id')
	{
		if (is_null($id))
		{
			return $this->insert($arr)->id;
		} else
		{
			$this->update($arr, $id, $column);
			return $id;
		}
		
	}

}
