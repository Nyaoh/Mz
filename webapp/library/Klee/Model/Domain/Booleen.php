<?php

/**
 * Définit le domaine des booléens utilisé par l'application.
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Domain_Booleen extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	if (! array_key_exists($field, $data)){
    		throw new Zend_Exception("$field n'existe pas.");
    	}
    	return $data[$field] === '0' ? 'Non' : 'Oui';
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
}
