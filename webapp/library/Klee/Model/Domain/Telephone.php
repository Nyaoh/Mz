<?php

/**
 * Définit le domaine des téléphones.
 *
 * @author ybaccala
 */
class Klee_Model_Domain_Telephone extends Klee_Model_Domain_LibelleLong 
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$element->addValidator('StringLength',true,array('max' => 20));
		$element->addValidator('Telephone',true);
	}
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_LibelleLong::initOtherDecorators()
	*/
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 10);
		$element->setAttrib('class', 'telephone');
		$element->setAttrib('class', 'number-only');
	}
}
