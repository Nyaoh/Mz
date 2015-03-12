<?php

/**
 * Classe permettant de gérer un broker d'accès aux données
 *
 * @author AMORIN
 *
 */
class Klee_Service_Broker_GenericBroker implements Klee_Service_Broker_IBroker
{
	private $_tableName;
	private $_dbAdapter = null;
	private $_metadata = null;
	private $_foreignKeys = null;
	
	/**
	 * Constructeur de la classe
	 *
	 * @param string $tableName	Nom de la table
	 */
	public function __construct($tableName) {
		$this->_tableName = $tableName;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::init()
	 */
	public function init($dbAdapter) {
		$this->_dbAdapter = $dbAdapter;

		if (!isset($this->_metadata)) {
			$tableObject = new Klee_Service_Broker_TableObject(
					array('db' => $this->_dbAdapter, 'name' => $this->_tableName));

			if(!($data = Klee_Util_CustomCacheManager::load($this->_tableName))) {
				$data = $tableObject->info();
				Klee_Util_CustomCacheManager::save($data, $this->_tableName);
			}
			$this->_metadata = $data;
		}
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::delete()
	 */
	public function delete($primaryKey) {
    	$where = $this->_dbAdapter->quoteInto($this->_metadata['primary'][1] . ' = (?) ', $primaryKey);
    	$res = $this->_dbAdapter->delete($this->_tableName, $where);
    	if ($res !== 1) {
    		throw new Zend_Exception('delete.error');
    	}
		Klee_Util_CustomCacheManager::removeTableCache($this->_tableName);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::deleteByCriteria()
	 */
	public function deleteByCriteria($criteria) {
    	$where = '';
		foreach ($criteria as $key => $value) {
		    if (strlen($where) > 0) {
		        $where = $where . ' AND ';
		    }
		    $where = $where . $this->_dbAdapter->quoteInto($key . ' = (?) ', $value);
    	}
    	$res = $this->_dbAdapter->delete($this->_tableName, $where);
    	
    	if ($res !== 1) {
    		throw new Zend_Exception('delete.error');
    	}
		Klee_Util_CustomCacheManager::removeTableCache($this->_tableName);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::deleteAllByCriteria()
	 */
	public function deleteAllByCriteria(array $criteria) {
		assert(is_array($criteria) && count($criteria) > 0);
		$where = array();
		foreach ($criteria as $key => $value) {
			$where[$key . ' = ?'] = $value;
		}
    	$returnValue = $this->_dbAdapter->delete($this->_tableName, $where);
		Klee_Util_CustomCacheManager::removeTableCache($this->_tableName);
		return $returnValue;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::get()
	 */
	public function get($primaryKey) {
		$select = new Zend_Db_Select($this->_dbAdapter);

    	$result = $select->from($this->_tableName)
						->where($this->_metadata['primary'][1] . ' = ?', $primaryKey)
						->query()
						->fetchAll();
		return $this->retourResultatUnique($result);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::getAll()
	 */
	public function getAll($sort, $maxRows = null) {
		assert($sort != '' && $sort !== null);
		$select = new Zend_Db_Select($this->_dbAdapter);
    	$results = $select->from($this->_tableName)
    					  ->order($sort)
    					  ->limit($maxRows)
    					  ->query()
    					  ->fetchAll();
		return $results;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::getAllByCriteria()
	 */
	public function getAllByCriteria(array $criteria, $sort) {
		assert($sort != '' && $sort !== null);

		$select = new Zend_Db_Select($this->_dbAdapter);
    	$select->from($this->_tableName);
    	
    	Klee_Service_Broker_GenericBrokerHelper::addCriteriaToSelect($select, $criteria, $this->_metadata['metadata']);
    	
    	if (!is_array($sort)) {
    		$sort = array($sort);
    	}
   		$results = $select->order($sort)
    		   	->query()
    		   	->fetchAll();
		return $results;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::getByCriteria()
	 */
	public function getByCriteria($criteria) {
		$select = new Zend_Db_Select($this->_dbAdapter);
		$select->from($this->_tableName);
		foreach ($criteria as $key => $value){
    		$select->where($key.' = (?)', $value);
    	}
    	
    	$result = $select->query()->fetchAll();

    	return $this->retourResultatUnique($result);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::setParams()
	 */
	public function setParams(array $params) {
		foreach($params as $key => $value) {
			if($key == 'autoDateDebutTable' && $value) {
				self::$_autoDateDebutTableList[] = $this->_tableName;
			} else if ($key == 'autoDateFinTable' && $value) {
				self::$_autoDateFinTableList[] = $this->_tableName;
			}	
		}
		$this->calculateAutoDate($this->_tableName);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::isUsed()
	 */
	public function isUsed($primaryKey, $ignoreList = null) {
		if(!array_key_exists('foreignKeyList', $this->_metadata)) {
			$this->_metadata['foreignKeyList'] = $this->getForeignKeys();
			Klee_Util_CustomCacheManager::save($this->_metadata, $this->_tableName);
		}
		$foreignKeys = $this->_metadata['foreignKeyList'];
		
		if (count($foreignKeys) == 0) {
			return false;
		}

		$selectArray = array();
		foreach ($foreignKeys as $foreignKey) {
		    $tableName = $foreignKey['TABLE_NAME'];
		    
		    if (isset($ignoreList) && in_array($tableName, $ignoreList)) {
		        continue;
		    }
		    
		    // exists(select 1 FROM TEST where a = 1)
		    array_push($selectArray, sprintf('EXISTS(SELECT 1 FROM %s WHERE %s = %s)', 
		        $tableName, $foreignKey['COLUMN_NAME'], $primaryKey));
		}
		
		$sql = 'SELECT ' . implode(' + ', $selectArray) . ' as USED';
		$result = $this->_dbAdapter->query($sql)->fetchAll();
		
		$res = $result[0]['USED'];
		if ($res === '0') {
			return false;
		}
		return true;

	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::save()
	 */
	public function save($object, array $naturalKeys = array()) {	    
		$primaryKey = null;
		if (isset($object[$this->_metadata['primary'][1]])) {
			$primaryKey = $object[$this->_metadata['primary'][1]];
			if ($primaryKey === '') {
				$primaryKey = null;
				$object[$this->_metadata['primary'][1]] = null;
			}
		}
 
		$data = array();
		foreach (array_keys($this->_metadata['metadata']) as $key) {		
			if (substr($key, -14) == '_DATE_CREATION') {
				// La date de création n'est renseignée que lors de la création
	    		if (is_null($primaryKey)) {
					$data[$key] = Klee_Util_Date::getCurrentDatetime();
	    		}
	    		continue;
			}
			/*if (substr($key, -18) == '_DATE_MODIFICATION') {
				$data[$key] = Klee_Util_Date::getCurrentDatetime();
				continue;
			}*/
			if (array_key_exists($key, $object)){
				$data[$key] = $object[$key];
			}			
		}
    	if (is_null($primaryKey)) {
			$res = $this->_dbAdapter->insert($this->_tableName, $data);
			if (! $res) {
				throw new Klee_Util_UserException('problemeEnregistrementTable', array($this->_tableName));
			}
			//On renvoie l'id du dernier élément ajouté
			$primaryKey = $this->_dbAdapter->lastInsertId();		
		} else {
		    
			$where[$this->_metadata['primary'][1] . ' = (?)'] = $primaryKey;
			unset($data[$this->_metadata['primary'][1]]);
			
			foreach ($naturalKeys as $naturalKey) {
				$where[$naturalKey . ' = (?)'] = $data[$naturalKey];
				unset($data[$naturalKey]);
			}
			$res = $this->_dbAdapter->update($this->_tableName, $data, $where);

			if ($res > 1) {
				throw new Klee_Util_UserException('problemeEnregistrement');
			}
		}
		
		//On supprime la table du cache afin que les modifications soient prises en compte
		Klee_Util_CustomCacheManager::removeTableCache($this->_tableName);
		return $primaryKey;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::saveAll()
	 */
	public function saveAll(array $objects, array $naturalKeys = array()) {
		$idList = array();
		
		assert(is_array($objects));
		foreach ($objects as $object) {
			$idList[] = $this->save($object, $naturalKeys);
		}
		
		return $idList;
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::insertAll()
	 */
	public function insertAll(array $objects, $bufferSize = 500) {
		if(empty($objects)) {
			return;
		}
		
		$definitionInsert = 'insert into '. $this->_tableName . '(';
		$isNotFirst = false;
		foreach(array_keys($objects[0]) as $key) {
			if($isNotFirst) {
				$definitionInsert .= ', ';
			}
			$isNotFirst = true;
			$definitionInsert .= $key;
		}
		$definitionInsert .= ') values ';

		$index = 0;
		$isNotFirst = false;
		foreach($objects as $row) {
			if($index == 0) {
				$insertAll = $definitionInsert;
			}
			
			if($isNotFirst) {
				$insertAll .= ', ';
			}
			
			$isNotFirst = false;
			$insertAll .= '(';
			foreach($row as $colValue) {
				if($isNotFirst) {
					$insertAll .= ', ';
				}
				$isNotFirst = true;
				if (Klee_Util_String::isNullOrEmpty($colValue)) {
					$insertAll .=  '\\N';
				} else {
					$insertAll .= $this->_dbAdapter->quote($colValue);
				}
			}
			$insertAll .= ')';
			
			$index++;
			if($index == $bufferSize) {
				$this->_dbAdapter->query($insertAll);
				$index = 0;				
				$isNotFirst= false;
			}
		}
		if($index > 0) {
			$this->_dbAdapter->query($insertAll);
		}
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::update()
	 */
	public function update($object, array $criteria) {
		foreach ($criteria as $key => $value) {
			$where[$key . ' = ?'] = $value;
		}
		$this->_dbAdapter->update($this->_tableName, $object, $where);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::updateAll()
	 */
	public function updateAll(array $object, array $criteria) {
		$data = array();

		foreach (array_keys($this->_metadata['metadata']) as $key) {		
			if (substr($key, -18) == '_DATE_MODIFICATION') {
				$data[$key] = Application_Util_Date::getCurrentDatetime();
				continue;
			}

			if (array_key_exists($key, $object)){
				$data[$key] = $object[$key];
			}
		}

		foreach ($criteria as $key => $value) {
			$where[$key . ' = ?'] = $value;
		}

		$this->_dbAdapter->update($this->_tableName, $data, $where);
		Klee_Util_CustomCacheManager::removeTableCache($this->_tableName);
	}

	/* (non-PHPdoc)
	 * @see Klee_Service_Broker_IBroker::getColumnsByCriteria()
	 */
	public function getColumnsByCriteria(array $criteria, $sort, array $columns) {
		assert($sort != '' && $sort !== null);

		$count = null;
		$offset = null;

		$select = new Zend_Db_Select($this->_dbAdapter);
    	$select->from($this->_tableName,$columns);
    	foreach ($criteria as $key => $value) {
    		if ($key === Zend_Db_Select::LIMIT_COUNT){
    			$count = $value;
    		} elseif ($key === Zend_Db_Select::LIMIT_OFFSET){
    			$offset = $value;
    		} else {
    			$select->where($key . ' = ?', $value, $this->_metadata['metadata'][$key]['DATA_TYPE']);
    		}
    	}
   		$results = $select->order(array($sort))
				->limit($count, $offset)
    		   	->query()
    		   	->fetchAll();
		
		return $results;
	}
	
	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------
	
	/**
	 * Méthode permettant de connaître les clés étrangères d'une table
	 *
	 * @return array
	 */
	private function getForeignKeys() {	    
		if (! isset($this->_foreignKeys)) {
			$select = new Zend_Db_Select($this->_dbAdapter);
			$result = $select->from(array('k' => 'INFORMATION_SCHEMA.KEY_COLUMN_USAGE'), array('k.TABLE_NAME', 'k.COLUMN_NAME', 'k.REFERENCED_TABLE_NAME'))
							 ->joinInner(array('c' => 'INFORMATION_SCHEMA.TABLE_CONSTRAINTS'),
							 			 'k.CONSTRAINT_SCHEMA = c.CONSTRAINT_SCHEMA AND k.CONSTRAINT_NAME = c.CONSTRAINT_NAME',
							 			 array())
						     ->where("k.REFERENCED_TABLE_NAME = '" . $this->_tableName . "'")
						     ->where("c.CONSTRAINT_TYPE = 'FOREIGN KEY'")
						     ->where("k.TABLE_SCHEMA = '" . $this->_dbAdapter->dbName . "'")
						     ->query()
						     ->fetchAll();
			$this->_foreignKeys = $result;
		}

		return $this->_foreignKeys;
	}
	
	/**
	 * Methode permettant de retourner un et un seul objet (relève un exception sinon)
	 * 
	 * @param array $result		Tableau de données
	 * @throws Zend_Exception
	 * @return mixed
	 */
	private function retourResultatUnique(array $result) {
		switch (count($result)) {
			case 0 :
				return null;
			case 1 :
				return array_pop($result);
			default :
				throw new Klee_Util_UserException('resultatUnique');
		}
	}
}
