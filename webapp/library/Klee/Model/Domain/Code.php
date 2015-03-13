<?php

/**
 * Définit le domaine des codes utilisé par l'application.
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Domain_Code extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	return Klee_Model_Domain_Helper_DomainHelper::escapeData(parent::formatData($data, $field, $view), $view);
    }
    
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$element->addValidator('StringLength',true,array('min' => 1, 'max' => 20));
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
		$element->setAttrib('maxlength', 20);
	}
}
