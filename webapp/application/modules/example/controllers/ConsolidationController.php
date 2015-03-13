<?php 

class Example_ConsolidationController extends Zend_Controller_Action
{
	public function detailAction() {
		$id = $this->getRequest()->get('id');
		
		$this->view->id = $id;
	}
	
	public function listAction() {
		
	}
}