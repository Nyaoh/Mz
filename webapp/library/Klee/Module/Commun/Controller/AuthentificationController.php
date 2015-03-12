<?php 

abstract class Klee_Module_Commun_Controller_AuthentificationController extends Klee_Module_Commun_Controller_AbstractDetailController
{
    protected $_redirectionAfterSave = '/accueil/';
    
    /**
     * /!\ A renseigner dans le contrôleur enfant.
     * 
     * @var Service d'authentification.
     */
    protected $_service;

	/**
	 * Action {logout}.
	 */
	public function logoutAction() {
		$this->_service->logout();
		$this->_redirect($this->_helper->url->url(array('module' => 'accueil', 'controller' => 'authentification', 'action' => 'index')));
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see AbstractDetailController::get()
	 */
	protected function get($idElement) {
		unset($idElement);
		return array();
	}
	
	/* (non-PHPdoc)
	 * @see AbstractDetailController::save()
	 */
	protected function save(array $values) {
		$authenticateParamList = Zend_Registry::get('authenticateParamList');

        $this->_service->login($values[$authenticateParamList['identityColumn']], $values[$authenticateParamList['credentialColumn']]);
	}
}