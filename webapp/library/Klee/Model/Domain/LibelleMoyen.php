<?php

/**
 * Définit le domaine des libellés moyens utilisé par l'application.
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Domain_LibelleMoyen extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
	    $options['max']=100;
		
		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('StringLength',true,$options);

	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {
		$filter = new Zend_Filter_StringTrim();

		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addFilter($filter);
	}
}
