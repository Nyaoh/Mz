<?php

/**
 * Définit le domaine des Libelle.
 *
 * @author ybaccala
 */
class Klee_Model_Domain_RaisonSociale extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface 
{
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	*/
	public function initValidators($element) {
		$options['max']=80;	
		if (!$element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addValidator('StringLength',true,$options);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_LibelleLong::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 80);
		$element->setAttrib('class', 'libelle');
	}
}