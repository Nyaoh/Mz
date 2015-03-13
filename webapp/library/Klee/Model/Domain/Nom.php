<?php

/**
 * Définit le domaine des noms utilisé par l'application.
 *
 * @author ybaccala
 * 
 */
class Klee_Model_Domain_Nom extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		
		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('Nom', true, array('allowWhiteSpace' => true));
		$options['max']=35;
		$element->addValidator('StringLength',true,$options);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {
		$filter = new Klee_Model_Filter_Nom(true);

		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addFilter($filter);
	}
}
