<?php 

class Example_ConsolidationController extends Zend_Controller_Action
{
	public function detailAction() {
		$id = $this->getRequest()->get('id');
		$dao = new Example_Model_ConsolidationDao();
		$consolidation = $dao->getByConId();
		
		$this->view->id = $id;
		$this->view->consolidation = $consolidation;	

// 		$consolidation = new Application_Model_Consolidation();
// 		$consolidation->setId(1);
// 		$consolidation->setNom('Opérationnel');
		
		$foo = new Default_Model_Foo(42);
		
// 		$this->view->consolidation = $consolidation;

		$service = new Example_Service_Implementation_Consolidation(43);
		echo $service->getId();
	}
	
	public function listAction() {
		
	}
}