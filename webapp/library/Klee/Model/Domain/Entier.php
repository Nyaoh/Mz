<?php

/**
 * Domaine pour les entiers.
 *
 * @author ehangard
 * @version 1.0
 */
class Klee_Model_Domain_Entier extends Klee_Model_Domain_Abstract
{
	protected $_isInclusive = true;
	
	public function isInclusive() {
		return $this->_isInclusive;
	}
	
	protected $_maxValue = 2147483648;
	
	public function getMaxValue() {
		return $this->_maxValue;
	}
	
	protected $_minValue = -2147483648;
	
	public function getMinValue() {
		return $this->_minValue;
	}
	
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::formatSort()
	 */
	public function formatSort($field) {
		unset($field);
		return array('asSorting' => array('desc', 'asc', 'asc'), 'sClass' => array('right'));
	}
	
	public function getValidatorList() {
		return array(
				new Klee_Plugin_Validator_Int(),
				new Klee_Plugin_Validator_Between(array('inclusive' => $this->isInclusive(), 'min' => $this->getMinValue(), 'max' => $this->getMaxValue()))
		);
	}
	
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$element->addValidators($this->getValidatorList());
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initFilters()
	 */
	public function initFilters($element) {
	    $filter = new Klee_Model_Filter_Entier();

	    $element->addFilter($filter);
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 11);
	}
}
