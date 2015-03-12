<?php

/**
 * Définit le domaine des listes de référence utilisées par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_ListeReference extends Klee_Model_Domain_Abstract 
{

    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	if(is_array($data[$field])){
    		return self::formatDataList($data[$field], $field, $view);
    	} else if(strpos($data[$field], ',')) {
    		return self::formatDataList(explode(',', $data[$field]), $field, $view);
    	} else {
    		return Klee_Model_Domain_Helper_DomainHelper::escapeData(Klee_Util_ReferenceManager::getInstance()->getLibelle($field, $data[$field]), $view);
    	}
    }
    
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {		
		parent::initOtherDecorators($element);
		
		if ($element instanceof  Zend_Form_Element_Multi) {
			$dataToCheck = $element->getAttrib('selectFilter');
			if (! is_null($dataToCheck)) {
				$element->setMultiOptions(Klee_Util_ReferenceManager::getInstance()->getReferenceListForSelectByCriteria($element->getName(), $dataToCheck));
			} else {
				$element->setMultiOptions(Klee_Util_ReferenceManager::getInstance()->getReferenceListeForSelect($element->getName()));
			}
		}
	}

	/**
	 * Enter description here ...
	 * 
	 * @param array $dataList		Données brutes.
	 * @param string $field			Nom du champ à afficher.
	 * @param Zend_View $view		La vue
	 * @return mixed
	 */
	private static function formatDataList($dataList, $field, $view) {
		$libelleList=array();
		foreach($dataList as $value){
			$libelleList[] = Klee_Util_ReferenceManager::getInstance()->getLibelle($field, $value);
		}
		return Klee_Model_Domain_Helper_DomainHelper::escapeData(join(', ',$libelleList), $view);
	}
}
