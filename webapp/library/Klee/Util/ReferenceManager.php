<?php

/**
 * Classe gérant le chargement des données de ref.
 * @author rgrange
 *
 */
class Klee_Util_ReferenceManager 
{
		
	const CODE_KEY = 'key';
	const CODE_VALUE = 'value';
	
	private static $_instance;
	private $_referenceConfig;
	
	private static $_colTable = 'table';
	private static $_colLibelle = 'libelle';
	private static $_colOrdre = 'ordre';
	private static $_colOrdreDeux = 'ordreDeux';
	
	/**
     * Returns an instance of Application_Util_ReferenceManager
     * @return Klee_Util_ReferenceManager
	 */
	public static function getInstance() {
        if (is_null(self::$_instance)){
        	self::$_instance = new Klee_Util_ReferenceManager();
        }
        
        return self::$_instance;
	}
	
	/**
	 * Constructeur.
	 */
	public function __construct()	{
		$this->_dbAdapter = Zend_Registry::get('dbReadAdapter');
		// TODO : récupération de l'id de l'utilisateur.
		$this->_userId = 0;
		
		if(!($data = Klee_Util_CustomCacheManager::load('REFERENCE_CONFIG'))) {
			$data = array();
			$handle = fopen(APPLICATION_PATH . '/configs/reference.ini','r');
			while ($row = fgets($handle)) {
				$row = trim($row);
				if(!empty($row) && ! Klee_Util_String::startWith($row, ';')) {
					$line = explode(';', Klee_Util_MbString::convertEncoding($row)) ;
					// TABLE_NAME;PK;LIBELLE;ORDRE					
					$tabChar = explode('_', $line[2]);
					if ($tabChar[count($tabChar) - 1] == 'LIBELLE'){
					    $line[2] .= '_' . Klee_Util_Context::getLocale();
					} 
					
					$data[$line[1]] = array(self::$_colTable=> $line[0], self::$_colLibelle => $line[2] , self::$_colOrdre => $line[3]);
					if(isset($line[4])) {
						$data[$line[1]][self::$_colOrdreDeux] = $line[4];
					}
				}
			}			
			Klee_Util_CustomCacheManager::save($data, 'REFERENCE_CONFIG');
		}
		$this->_referenceConfig = $data;
	}
	
	/**
	 * Charge la données de ref à partir de son identifiant.
	 * @param string $pkName Nom de la PK.
	 * @param unknown $pkValue Valeur de la PK.
	 * @return array Enregistrement.
	 */
	public function getObject($pkName, $pkValue) {
		$referenceMap = $this->getReferenceMap($pkName);
		assert(array_key_exists($pkValue, $referenceMap));
		return $referenceMap[$pkValue];
	}
	
	/**
	 * Renvoie la liste des enregistrements.
	 * @param string $pkName Nom de la PK.
	 * @return array Liste d'enregistrements
	 */
	public function getObjectList($pkName) {
		return array_values($this->getReferenceMap($pkName));
	}
	
	/**
	 * Renvoie la liste des enregistrements correspondants au critère de recherche.
	 * @param string $pkName Nom de la PK.
	 * @param array $criteria Critere de recherche.
	 * @return array liste d'enregsitrmeemnts filtrés.
	 */
	public function getObjectListByCriteria($pkName, array $criteria) {
		//on recupere la liste de ref non filtree
        $objectList = $this->getObjectList($pkName);

        //liste de ref filtree
        $filteredObjectList = array();

        foreach($objectList as $object) {
            //booleen : true si l'element de la liste de ref respecte tous les criteres, false sinon
            $isValidObject = true;
            
            foreach($criteria as $key => $criterion) {
                //si au moins un des criteres n'est pas respecté, on passe à l'element suivant de la liste de ref
                if($object[$key] != $criterion) {
                    $isValidObject = false;
                    break;
                }
            }
            //si l'element respecte tous les criteres, on l'ajoute a la liste de ref filtree
            if($isValidObject) {
                $filteredObjectList[] = $object;
            }
        }
        
        return $filteredObjectList;
	}
	
	/**
	 * Renvoie l'enregistrement correspondants au critère de recherche.
	 * @param string $pkName Nom de la pK.
	 * @param array $criteria Critère de recherche.
	 * @throws Application_Util_UserException Si plusierus envregistrements sont retournés.
	 * @return array Enregsitrememnt.
	 */
	public function getObjectByCriteria($pkName, array $criteria) {
		$list = $this->getObjectListByCriteria($pkName, $criteria);
		if (count($list) > 1) {
			throw new Klee_Util_UserException('resultatUnique');
		} else if (empty($list)) {
			return null;
		}
		return array_pop($list);
	}

	/**
	 * Renvoie le libellé de l'enregistrmemnt correspondant.
	 * @param string $pkName Nom de la pk.
	 * @param unknown_type $pkValue Valeur de la pk.
	 * @return string Libellé de l'enregistrement.
	 */
	public function getLibelle($pkName, $pkValue) {
		$row = $this->getObject($pkName, $pkValue);
		return $row[$this->_referenceConfig[$pkName][self::$_colLibelle]];		
	}
	
	/**
	 * Chargement de la liste de ref pour un select.
	 * @param string $pkName Nom de la pk.
	 * @return array liste
	 */
	public function getReferenceListeForSelect($pkName) {
		$libelleName = $this->_referenceConfig[$pkName][self::$_colLibelle];
		$option = array();
		foreach($this->getObjectList($pkName) as $row) {
			$option[] = array(self::CODE_KEY => $row[$pkName], self::CODE_VALUE => $row[$libelleName]);
		}
		return $option;
	}
	
	/**
	 * Chargement de la liste de référence avec des critères pour un select.
	 * 
	 * @param string $pkName  Nom de la pk.
	 * @param array $criteria Critères de recherche.
	 * @return array liste
	 */
	public function getReferenceListForSelectByCriteria($pkName, array $criteria) {
		$libelleName = $this->_referenceConfig[$pkName][self::$_colLibelle];
		$option = array();
		foreach($this->getObjectListByCriteria($pkName, $criteria) as $row) {
			$option[] = array(self::CODE_KEY => $row[$pkName], self::CODE_VALUE => $row[$libelleName]);
		}
		return $option;
	}
		
	
	/**
	 * Charge la map des données de référence : PK/ROW
	 * @param string $pkName Nom de la PK.
	 * @return array map des donénes de ref.
	 */
	private function getReferenceMap($pkName) {
		$config = $this->_referenceConfig[$pkName];
		$table = $config[self::$_colTable];		
		if(!($referenceMap = Klee_Util_CustomCacheManager::loadReferenceTable($table))){		
			$ordre = isset($config[self::$_colOrdreDeux]) ? array($config[self::$_colOrdre], $config[self::$_colOrdreDeux]) : $config[self::$_colOrdre];
			$datas = Klee_Service_Broker_BrokerManager::getBroker($this, $table)->getAll($ordre);
			$referenceMap = array();
			foreach($datas as $data) {
				$referenceMap[$data[$pkName]] = $data;
			}				
			Klee_Util_CustomCacheManager::saveReferenceTable($referenceMap, $table);
		}
		return $referenceMap;
	}
}
