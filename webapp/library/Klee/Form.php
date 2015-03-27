<?php 

interface Klee_IForm
{
	public function init();
}

abstract class Klee_Form implements Klee_IForm
{
	protected $_elementList = array();
	
	protected $_isValid = false;
	
	protected $_messageList = array();
	
	/**
	 * Constructeur de la classe.
	 */
	public function __construct() {
		$this->init();
	}
	
	public function add(Klee_Form_Element $element) {
		$this->_elementList[$element->getName()] = $element;
	}
	
	public function get($name) {
		if (isset($this->_elementList[$name])) {
			return $this->_elementList[$name];
		}
		
		return null;
	}
	
	public function isValid(array $dataList = array()) {
		$this->_isValid = false;
	
		foreach ($this->_elementList as $element) {
			$value = isset($dataList[$element->getName()]) ? $dataList[$element->getName()] : null;
	
			$this->_isValid &= $element->isValid($value);
		}
	}
}