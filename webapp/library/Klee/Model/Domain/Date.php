<?php

/**
 * Définit le domaine des dates utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Date extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
    const MYSQL_DATE = 'yyyy-MM-dd';

    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	unset($view);
    
    	$value = $data[$field];
    
    	if ($value === '0000-00-00') {
    		return '';
    	} elseif ($value === null) {
    		return null;
    	}
    
    	return Klee_Util_Date::printDate($value);
    }
    
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {
		$filter = new Klee_Model_Filter_LocaleDate();

		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas un Zend_Form_Element.");
		}
		$element->addFilter($filter);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		if (! $element instanceof Zend_Form_Element) {
			throw new Zend_Exception("$element n'est pas une date.");
		}
		$element->addValidator('Date', true, array('format'=>self::MYSQL_DATE));
	}
	
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->helper = 'formDate';
	}
}
