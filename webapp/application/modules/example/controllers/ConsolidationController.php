<?php 

class Example_ConsolidationController extends Zend_Controller_Action
{
	public function detailAction() {
		$id = $this->getRequest()->get('id');
		$dao = new Example_Model_ConsolidationDao();
// 		$consolidation = $dao->getByConId();
		
		$this->view->id = $id;
// 		$this->view->consolidation = $consolidation;	

		$consolidation = new Application_Model_Consolidation();
		$consolidation->setId(1);
		$consolidation->setNom('Opérationnel');
		
		$this->view->consolidation = $consolidation;
	}
	
	public function listAction() {
		
	}
}