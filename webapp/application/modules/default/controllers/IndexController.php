<?php 

class IndexController extends Zend_Controller_Action
{
	public function indexAction() {
		
// 		$service = new Default_Service_FamilleTypePost();
		
// 		$this->view->ftpList = $service->getList();
		
		$this->view->foo = '\'Hello world !\'';
		
		$model = new Default_Service_Register();
		$this->view->form = new Default_Form_Register();
	}
}