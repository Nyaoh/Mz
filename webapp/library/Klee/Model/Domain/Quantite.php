<?php

/**
 * Définit le domaine des quantites.
 *
 * @author ybaccala
 */
class Klee_Model_Domain_Quantite extends Klee_Model_Domain_LibelleLong 
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	*/
	public function initValidators($element) {
		if (!$element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('Digits', true);
		$options['max']=6;
		$element->addValidator('StringLength', true, $options);
	}
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_LibelleLong::initOtherDecorators()
	*/
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 6);
		$element->setAttrib('class', 'quantite');
	}
}
