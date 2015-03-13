<?php 

class FamilleTypePosteController extends Zend_Controller_Action
{
	/**
	 * @var Default_Service_IFamilleTypePost
	 */
	protected $_service;
	
	public function init() {
		Zend_Debug::dump($this->getRequest()->getParams());
		
		$this->_service = new Default_Service_ServiceFamilleTypePost();
	}
	
	public function indexAction() {
		$this->view->ftpList = $this->_service->findAllFamilleTypePoste();
	}
	
	public function detailAction() {
		die('default/famille-type-poste/detail');
	}
}