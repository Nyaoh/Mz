<?php

/**
 * Contrôleur abstrait permettant de gérer la liste et le détail.
 * 
 * @author AMORIN
 *
 */
abstract class Klee_Module_Commun_Controller_AbstractListController extends Zend_Controller_Action
{
	/**
	 * Nom de la colonne pour le nom du fichier.
	 *
	 * @var string
	 */
	const COL_PATH = 'ELT_PATH';
	
	/**
	 * Nombre maximal de ligne à récupérer en base.
	 * 
	 * @var int
	 */
	const MAX_ROWS = 500;
	
	/**
	 * La colonne par défaut pour le tri et la direction du tri.
	 *
	 * @var array
	 */
	protected $_aaSorting = array();
	
	/**
	 * Données à charger dans l'autocomplete.
	 * 
	 * @var array
	 */
	protected $_autocompleteData = array();
	
	/**
	 * Est-ce qu'il faut vérifier si l'objet est utilisé avant de demander confirmation
	 * à l'utilisateur pour la suppression.
	 * 
	 * Si {true} : implémenter la méthode protected function checkIsUsed($idElement) dans le contrôleur enfant.
	 * @see checkIsUsed
	 * 
	 * @var boolean
	 */
	protected $_checkIsUsed = false;
	
	/**
	 * @var string Clé servant à identifier les liens de la colonne {action} de la datatable.
	 */
	protected $_idLink;
	
	/**
	 * Est-ce qu'il est possible de créer de nouveaux éléments.
	 * 
	 * @var boolean
	 */
	protected $_isCreate = true;
	
	/**
	 * Est-ce qu'il est possible de supprimer un élément.
	 *  
	 * @var boolean
	 */
	protected $_isDelete = false;
	protected $_isDeleteField = 'isDelete';
	
	/**
	 * Est-ce qu'il y a un formulaire de filtrage des données de la liste.
	 *
	 * @var boolean
	 */
	protected $_isFiltre = true;
	
	/**
	 * Paramètres à envoyer au formulaire de filtrage.
	 * 
	 * @var array
	 */
	protected $_filtreParams = array();
	
	/**
	 * Est-ce qu'il est possible de mettre à jour un élément.
	 * 
	 * @var boolean
	 */
	protected $_isUpdate = true;
	protected $_isUpdateField = 'isUpdate';
		
	/**
	 * Est-ce qu'il faut ajouter la colonne {action} à la datatable.
	 *
	 * @var boolean
	 */
	protected $_requireActionForDatatable = true;
	
	/**
	 * Liste des traductions utilisées pour la datatable.
	 * 
	 * @var array
	 */
	protected $_translations = array();
	
	/**
	 * nombre total d'élément.
	 *
	 * @var string
	 */
	protected $_totalRow = 0;

	/* (non-PHPdoc)
	 * @see Zend_Controller_Action::init()
	 */
	public function init() {
		parent::init();
		
		if ($this->getRequest()->isXmlHttpRequest()) {
			/*
			 * Désactivation du rendu classique et specification du type de contenu "json".
			*/
			$this->getHelper('viewRenderer')->setNoRender();
			$this->getHelper('layout')->disableLayout();
			$this->getResponse()->setHeader('Content-Type', 'application/json');
		
			return;
		}

		$prefixe =Klee_Module_Commun_Controller_PrefixeTranslationHelper::getPrefixForTranslation($this->getRequest());
		$this->view->prefixe = $prefixe;
		
		// /!\ : Traduit au niveau de la vue.
		$this->view->placeholder('titre')->set($prefixe . 'titre');
		$this->view->placeholder('sous-titre')->set($prefixe . 'sous-titre');
		
		$this->_translations = array(
				'display' 			=> $this->view->translate($prefixe . 'display'),
				'aucuneElement' 	=> $this->view->translate($prefixe . 'noElement'),
				'nbElement'			=> $this->view->translate($prefixe . 'nbElement'),
				'noEntries' 		=> $this->view->translate($prefixe . 'noEntries'),
				'emptyTable' 		=> $this->view->translate($prefixe . 'emptyTable'),
				'element'			=> $this->view->translate($prefixe . 'element'),
				'previousPage' 		=> $this->view->translate($prefixe . 'previousPage'),
				'nextPage' 			=> $this->view->translate($prefixe . 'nextPage'),
				'lastPage' 			=> $this->view->translate($prefixe . 'lastPage'),
				'firstPage' 		=> $this->view->translate($prefixe . 'firstPage'),
				'boutonEditer' 		=> $this->view->translate($prefixe . 'boutonEditer'),
				'boutonSupprimer' 	=> $this->view->translate($prefixe . 'boutonSupprimer')
				);
				

		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input-facebook.css', 'screen');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input-mac.css', 'screen');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input.css', 'screen');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/scriptAutocompleteMultiple.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/autoComplete.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/jquery.tokeninput.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/jquery.tree.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/jquery.ui/jquery-ui-1.11.0.custom.js');
		
		if ($this->_isDelete) {
			// Script permettant de gérer le clic sur l'action {delete} d'une liste.
			$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/scriptList.js');
		}
	}
	
	/**
	 * Action {index}.
	 */
	public function indexAction() {
		$criteria = array();
		
		$this->view->isCreate = $this->_isCreate;
		$this->view->isDelete = $this->_isDelete;
		
		if ($this->_isFiltre) {
			$criteria = $this->getFormFiltreCriteria();
		}
		
		$criteria[Zend_Db_Select::LIMIT_COUNT] = self::MAX_ROWS;
		$criteria[Zend_Db_Select::LIMIT_OFFSET] = null;

		$datas = $this->getList($criteria);
		$aaData = $this->formatDataForList($datas);
		$aoColumns = $this->formatColumnHeaderForList();
		
		
		if ( $this->getRequest()->getParam('export') && $this->getRequest()->getParam('export')==='csv' ) {
			$enteteColumns = $this->formatColumnHeaderForExport();
		    Klee_Util_ExportHelper::prepareExport($this->formatDataForCsv($datas), $enteteColumns, explode('_', get_called_class()), $this->view);
			exit(0);
		}
		
		$script = '
				jQuery(document).ready(function(){
						$my.translations 	= ' . Zend_Json::encode($this->_translations) . ';
	   					$my.aaData 			= ' . Zend_Json::encode($aaData) . ';
	   					$my.aoColumns 		= ' . Zend_Json::encode($aoColumns) . ';
	   					$my.aaSorting 		= ' . Zend_Json::encode($this->_aaSorting) . ';
	   					$my.fnRowCallback	= ' . $this->fnRowCallback() . ';
						' . $this->getScriptForInitDatatable() . ' 
						' . $this->getScriptForDeleteAction() . '
						' . $this->getInlineScript();
		//Si aucune donnée n'est renvoyée, on cache la pagination	
		if (empty($datas)) {
			$script .= 'jQuery(".pagination-semi").hide()';
		}
    	$script .= '});';
    	
		$this->view->inlineScript()->prependScript($script);
	}
	
	/**
	 * Vérifie si l'objet est référencé ou non.
	 */
	public function isUsedAction() {
		$idElement = $this->getRequest()->getParam('idElement');
		$result = $this->checkIsUsed($idElement);
		
		$this->getResponse()->setBody(Zend_Json::encode(array('isUsed' => $result)));
	}
	
	/**
	 * Action {delete}.
	 * Suppression d'un objet à partir de son identifiant.
	 */
	public function deleteAction() {
		$request = $this->getRequest();
		
		$idElement = $request->getParam('idElement');
		if (! $this->checkIsUsed($idElement)) {
			$this->delete($idElement);
		} else {
			$this->getResponse()->setBody(Zend_Json::encode(array('isUsed' => $this->checkIsUsed($idElement))));
		}
		
		// Redirection vers la page appelante.
		$referer = $request->getHeader('referer');
		$this->_redirect($referer);
	}
	
	// ------------------------------------------------------------------------
	// Protected methods
	// ------------------------------------------------------------------------

	/**
	 * @param int $idElement identifiant de l'élément à vérifier.
	 * @return boolean
	 */
	protected function checkIsUsed($idElement) {
		return false;
	}
	
	/**
	 * Suppression d'un objet.
	 * 
	 * @param int $idElement Identifiant de l'élément.
	 */
	protected function delete($idElement) {
	}
	
	/**
	 * Javascript à rajouter.
	 * 
	 * @return string
	 */
	protected function getInlineScript() {
		return '';
	}
	
	/**
	 * @param array $criteria Critères.
	 */
	abstract protected function getList(array $criteria);

	/**
	 * La méthode permettant d'afficher la datatable est initialisée ici. Ainsi on peut choisir un autre
	 * mode de rendu depuis un contrôleur enfant (si par exemple on a pas besoin de la pagination).
	 * 
	 * @return string
	 */
	protected function getScriptForInitDatatable() {
		return 'initDataTable();';
	}
	
	/**
	 * Retourne les informations sur les données :
	 * nom des champs et domaine.
	 * 
	 * @return array
	 */
	abstract protected function getTableInfo();
	
	protected function fnRowCallback() {
		return 'function( nRow, aData, iDisplayIndex, iDisplayIndexFull ) {}';
	}
	
	// ------------------------------------------------------------------------
	// Private methods
	// ------------------------------------------------------------------------
	
	/**
	 * Liste des en-têtes de colonne pour la liste.
	 * 
	 * @return array
	 */
	private function formatColumnHeaderForList() {
		$aoColumns = array();

		$prefixe = Klee_Module_Commun_Controller_PrefixeTranslationHelper::getPrefixForTranslation($this->getRequest());

		foreach (array_keys($this->getTableInfo()) as $columnName) {
			$tempColumnName = $prefixe . $columnName;
			$aoColumns[] = array('sTitle' => $this->view->translate($this->view->escape($tempColumnName)));
		}

		// Affichage de la colonne {action}.
		if ($this->_isUpdate || $this->_isDelete) {
			$tempColumnName = $prefixe . 'action';
			$aoColumns[] = array('sTitle' => $this->view->translate($this->view->escape($tempColumnName)));
		}
		
		return $aoColumns;
	}
	
	private function formatColumnHeaderForExport(){
	    $aoColumns = array();
	    
	    $prefixe = Klee_Module_Commun_Controller_PrefixeTranslationHelper::getPrefixForTranslation($this->getRequest());
	    
	    foreach (array_keys($this->getTableInfo()) as $columnName) {
	    	$tempColumnName = $prefixe . $columnName;
	    	$aoColumns[] = $this->view->translate($this->view->escape($tempColumnName));
	    }
	    
	    return $aoColumns;
	}
	
	/**
	 * Retourne les données formattées.
	 *
	 * @return array
	 */
	private function formatDataForList($datas) {
		$aaData = array();
	
		foreach ($datas as $row) {
			$line = array();
			foreach ($this->getTableInfo() as $columnName => $domain) {
				if ($domain === 'DownloadLink') {
					$row['downloadUrl'] = $this->_helper->url->url(array('action' => 'download'), null, false);
				}
				$line[] = $this->view->formatData($row, $columnName, $domain);
			}
			
			$row[$this->_isDeleteField] = !(array_key_exists($this->_isDeleteField, $row)) ? $this->_isDelete : !$row[$this->_isDeleteField];
			$row[$this->_isUpdateField] = !(array_key_exists($this->_isUpdateField, $row)) ? $this->_isUpdate : !$row[$this->_isUpdateField];
			
			// Affichage de la colonne {action}.
			if ($row[$this->_isUpdateField]|| $row[$this->_isDeleteField]) {
				$line[] = $this->view->formatDataAction($row, 
						$this->_idLink, 
						'Code', 
						$this->getArgumentForAction($row[$this->_isUpdateField], $row[$this->_isDeleteField]));
			} else if ($this->_isUpdate || $this->_isDelete) {
				$line[] = '';
			}
			$aaData[] = $line;
		}

		
		return $aaData;
	}
	
	/**
	 * Retourne les données formattées.
	 *
	 * @return array
	 */
	private function formatDataForCsv($datas) {
		$aaData = array();
	
		foreach ($datas as $row) {
			$line = array();
			foreach ($this->getTableInfo() as $columnName => $domain) {
				if ($domain === 'DownloadLink') {
					$row['downloadUrl'] = $this->_helper->url->url(array('action' => 'download'), null, false);
				}
				$line[] = $this->view->formatData($row, $columnName, $domain);
			}
			$aaData[] = $line;
		}
	
	
		return $aaData;
	}
	
	/**
	 * Retourne un tableau avec les arguments pour la colonne action du datatable.
	 *
	 * @return array $arguments La liste d'arguments
	 */
	private function getArgumentForAction($isUpdate, $isDelete) {
		$arguments = array();
		
		$controllerArray = explode('-', $this->getRequest()->getControllerName());
		$controllerDetailName = implode('-', array_slice($controllerArray, 0, -1)) . '-detail';
		
		// Le lien remplace "-list" par "-detail" pour le nom du contrôleur.
		if ($isUpdate) {
			$arguments['urlEdit'] = $this->_helper->url->url(array('controller' => $controllerDetailName, 'action' => 'detail'), null, false);
			$arguments['translateEditer'] = $this->view->translate($this->_translations['boutonEditer']);
		}
		
		if ($isDelete) {
			$arguments['urlDelete'] = $this->_helper->url->url(array('controller' => $this->getRequest()->getControllerName(), 'action' => 'delete'), null, false);
			$arguments['translateSupprimer'] = $this->view->translate($this->_translations['boutonSupprimer']);
		}
		
		return $arguments;
	}
	
	/**
	 * Retourne la liste des critères de tri du formulaire de filtrage.
	 * 
	 * @return array
	 */
	private function getFormFiltreCriteria() {
		$request = $this->getRequest();
		$arrayControllerName = explode('-', $request->getControllerName());
		$instance = 'Application_Module_' . ucfirst($request->getModuleName()) . '_Forms_' . implode('', array_map('ucfirst', $arrayControllerName)) . 'Form';
		$formFiltre = new $instance(null, $this->_filtreParams);
		
		$formFiltre->setDefaults($this->getRequest()->getParams());
		
		$data = null;
		$libelleList = $this->getRequest()->getParam('MCF_LIBELLE');

		if (! is_null($libelleList)) {
			foreach (explode(',', $libelleList) as $libelle) {
				$data[] = array(
						'name' => $libelle,
						'id' => $libelle,
						'title' => $libelle,
						'nameResized' => $libelle);
			}
		}
	
		$this->_autocompleteData = $data;

		$this->view->formFiltre = $formFiltre;
	
		$criteria = array();

		$request = $this->getRequest();
		if ($request->isPost()) {
			if ($formFiltre->isValid($request->getPost())) {
					
				$values = $formFiltre->getValues();
				foreach ($values as $key => $value) {
					$element = $formFiltre->getElement($key);
						
					if (! Klee_Util_String::isNullOrEmpty($value)) {
						if (is_null($element->operator)) {
							$criteria[$key] = $value;
						} else {
							$criteria[$key] = array('value' => $value,
									'operator' => $element->operator);
						}
					}
				}
			}
		}
	
		return $criteria;
	}
	
	/**
	 * Récupération du script à insérer dans la page pour initialiser l'action {delete} de la datatable.
	 */
	private function getScriptForDeleteAction() {
		$variableForDeleteScript = '';
		if ($this->_isDelete) {
			$variableForDeleteScript = '
					$my.urlIsUsed = "' . $this->_helper->url->url(array('action' => 'is-used'), null, false) . '";
					$my.urlDelete = "' . $this->_helper->url->url(array('action' => 'delete'), null, false) . '";
					$my.boutonSupprimer = "' . $this->view->translate('boutonSupprimer') . '";
					$my.boutonAnnuler = "' . $this->view->translate('boutonAnnuler') . '";
					$my.checkIsUsed = "' . (int) $this->_checkIsUsed . '"; // /!\ FALSE set la variable à "0" dans le javascript
					initDelete();
					';
		}
		
		return $variableForDeleteScript;
	}
}
