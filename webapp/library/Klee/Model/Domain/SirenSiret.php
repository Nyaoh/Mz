<?php

/**
 * Définit le domaine des SIREN/SIRET utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_SirenSiret extends Klee_Model_Domain_Abstract
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) 
	{
		$element->addValidator('Digits', true);
		$element->addValidator('StringLength',true,array('min' => 14, 'max' => 14));
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}

}
