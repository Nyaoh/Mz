<?php

/**
 * Domaine {NoDomain}.
 * Aucune vérification ou filtrage effectuées sur les champs.
 * 
 * @author AMORIN
 *
 */
class Klee_Model_Domain_NoDomain 
		extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
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
}
