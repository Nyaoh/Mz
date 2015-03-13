<?php 

class Application_Model_Consolidation
{
	private $_id;
	
	private $_nom;
	
	public function getId() {
		return $this->_id;
	}
	
	public function setId($id) {
		$this->_id = $id;
	}
	
	public function getNom() {
		return $this->_nom;
	}
	
	public function setNom($nom) {
		$this->_nom = $nom;
	}
}