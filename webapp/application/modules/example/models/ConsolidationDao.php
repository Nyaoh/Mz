<?php 

class Example_Model_ConsolidationDao
{
	public function getByConId() {
		$consolidation = new Application_Model_Consolidation();
		$consolidation->setId(1);
		$consolidation->setNom('Opérationnel');
		
		return $consolidation;
	}
}