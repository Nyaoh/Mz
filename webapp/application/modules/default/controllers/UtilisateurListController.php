<?php 

class UtilisateurListController extends Zend_Controller_Action
{
	/* (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		if ($this->getRequest()->isXmlHttpRequest()) {
			$this->getHelper('viewRenderer')->setNoRender();
			$this->getHelper('layout')->disableLayout();
			$this->getResponse()->setHeader('Content-Type', 'application/json');
		
			return;
		}
	}
	
	public function indexAction() {
		// Chargement des données de la première page.
		// Les pages suivantes sont chargées en AJAX.
		
		$service = new Default_Service_ServiceUtilisateur();
		$dataInfo = $service->getUtilisateurList();
		
		$this->view->list 			= $dataInfo['list'];
		$this->view->pageNumberList = $this->getPageList($dataInfo['count'], $dataInfo['page']);
	}
	
	public function loadAction() {
		// La limite est définie dans le contrôleur.
		
		$currentPage = (int) $this->getRequest()->getParam('PN');
		
		$service = new Default_Service_ServiceUtilisateur();
		$data = $service->getUtilisateurList();
		$dataCount = $service->getUtilisateurListCount();
		
		$limit = 10;
		
		// HTML final.
		$html = '';
		
		// HTML pour la liste.
		$dataInfo = $service->getUtilisateurList($limit, $currentPage);
		$this->view->list = $dataInfo['list'];
		$html .= $this->view->render('/utilisateur-list/grid.phtml');
		
		// HTML pour la pagination.
		// @TOOD: Créer un helper pour la pagination.
		$this->view->pageNumberList = $this->getPageList($dataInfo['count'], $dataInfo['page']);
		$html .= $this->view->render('/utilisateur-list/bloc/pagination.phtml');
		
		$response = array('valid' => true, 'html' => $html);
		
		$this->getResponse()->setBody(Zend_Json::encode($response));
	}
	
	private function getPageList($dataCount, $currentPage) {
		$nbItemPerPage = 10;
		$nbPageMax = round($dataCount / $nbItemPerPage);
		
		$pageList = array();
		
// 		$currentPage = 1;	// @TODO
		$nbPageBefore = 2;
		$nbPageAfter = 2;
		$showFirstPage = true;
		$showLastPage = true;
		
		$startIndex = 1;
		if ($currentPage - $nbPageBefore > 1) {
			$startIndex = $currentPage - $nbPageBefore;
		}
		
		$stopIndex = $nbPageMax;
		if ($currentPage + $nbPageAfter < $nbPageMax) {
			$stopIndex = $currentPage + $nbPageAfter;
		}
		
		$html = '';
		if ($showFirstPage && $startIndex > 1) {
			if ($currentPage === 1) {
				$pageList[] = array(
						'libelle' 	=> 1,
						'clickable'	=> false,
						'selected'	=> true
				);
			} else {
				$pageList[] = array(
						'libelle' 	=> 1,
						'clickable'	=> true,
						'selected'	=> false
				);
			}
		}
		
		if (($showFirstPage && $startIndex > 2) || (! $showFirstPage && $startIndex > 1)) {
			$pageList[] = array(
					'libelle' 	=> null,
					'clickable'	=> false,
					'selected'	=> false
			);
		}
		
		for ($i = $startIndex; $i <= $stopIndex; $i++) {
			if ($currentPage === $i) {
				$pageList[] = array(
						'libelle' 	=> $i,
						'clickable'	=> false,
						'selected'	=> true
				);
			} else {
				$pageList[] = array(
						'libelle' 	=> $i,
						'clickable'	=> true,
						'selected'	=> false
				);
			}
		}
		
		if (($showLastPage && $stopIndex < $nbPageMax - 2) || (! $showLastPage && $stopIndex < $nbPageMax - 1)) {
			$pageList[] = array(
					'libelle' 	=> null,
					'clickable'	=> false,
					'selected'	=> false
			);
		}
		
		if ($showLastPage && $stopIndex < $nbPageMax) {
			if ($currentPage === $nbPageMax) {
				$pageList[] = array(
						'libelle' 	=> $nbPageMax,
						'clickable'	=> false,
						'selected'	=> true
				);
			} else {
				$pageList[] = array(
						'libelle' 	=> $nbPageMax,
						'clickable'	=> true,
						'selected'	=> false
				);
			}
		}
		
		return $pageList;
	}
}