<?php

/**
 * Définit le domaine des tonnes utilisé par l'application.
 *
 * @author ehangard
 */
class  Klee_Model_Domain_Nombre extends Klee_Model_Domain_Abstract
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {
		$filter = new Zend_Filter_PregReplace(array('match' => '/,/','replace' => '.'));
		$element->addFilter($filter);
		
		$filter = new Zend_Filter_Callback(array(__CLASS__, 'emptyToNullFilter'));
		$element->addFilter($filter);
	}
	
	/**
	 * Retourn null si $value est une chaine vide, la chaine sinon
	 * @param string $value chaine a evaluer
	 */
	public static function emptyToNullFilter($value) {
	    return ($value === '') ? null : $value; 
	}

}
