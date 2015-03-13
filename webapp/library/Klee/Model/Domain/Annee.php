<?php

/**
 * Définit le domaine des années utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Annee extends Klee_Model_Domain_Abstract
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

	}
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::formatSort()
	 */
	public function formatSort($field) {
		unset($field);
		return array("asSorting"=>array("desc","asc","asc"),"sClass"=>array("right"));
	}
}
