<?php 

class Default_Model_Entier
{
	const MIN_VALUE = 0;
	const MAX_VALUE = 100;
	
	private $_value;
	
	public function __construct($value) {
		$this->_value = $value;
	}
	
	public function getValue() {
		return $this->_value;
	}
	
	public function setValue($value) {
		$this->_value = $value;
	}
}