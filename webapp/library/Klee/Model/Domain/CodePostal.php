<?php

/**
 * Définit le domaine des Code postaux.
 *
 * @author ybaccala
 */
class Klee_Model_Domain_CodePostal extends Klee_Model_Domain_LibelleLong 
{
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	*/
	public function initValidators($element) {
		$options['max']=10;	
		if (!$element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('Digits', true);
		$element->addValidator('StringLength',true,$options);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_LibelleLong::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 10);
		$element->setAttrib('class', 'codePostal');
	}
}
