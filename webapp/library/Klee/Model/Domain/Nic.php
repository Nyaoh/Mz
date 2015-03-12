<?php

/**
 * Définit le domaine des SIRET utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Nic extends Klee_Model_Domain_Abstract
{
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) 
	{
		$element->addValidator('Digits', true);
		$options = array('min' => 5, 'max' => 5);
		$element->addValidator('StringLength',true,$options);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initOtherDecorators()
	*/
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 5);
	}
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}

}
