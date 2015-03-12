<?php

/**
 * Définit le domaine des mails utilisé par l'application.
 *
 * @author ybaccala
 * 
 */
class Klee_Model_Domain_Email extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('EmailAddress', true);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_LibelleLong::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 255);
		$element->setAttrib('class', 'email');
	}
}
