<?php

/**
 * Définit le domaine des SIREN utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Siren extends Klee_Model_Domain_Abstract
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) 
	{
		$element->addValidator('Digits', true);
		$element->addValidator('StringLength',true,array('min' => 9, 'max' => 9));
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
		$element->setAttrib('maxlength', 9);
	}
	
}
