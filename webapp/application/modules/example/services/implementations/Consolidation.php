<?php 

class Example_Service_Implementation_Consolidation
{
	private $_id;
	
	public function __construct($id) {
		$this->_id = $id;
	}
	
	public function getId() {
		return $this->_id;
	}
}