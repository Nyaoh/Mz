<?php

/**
 * Contrôleur abstrait permettant de gérer le détail d'un élément.
 * 
 * @author AMORIN
 *
 */
abstract class Klee_Module_Commun_Controller_AbstractDetailController extends Zend_Controller_Action
{
    /**
     * Est-ce que le formulaire contient un fichier à uploader.
     *
     * @var boolean
     */
    protected $_containFile = false;
    
    /**
     * Liste des informations sur l'élément.
     * 
     * @var array
     */
    protected $_element = null;
    
    /**
     * Paramètres du formulaire.
     *
     * @var array
     */
    protected $_formParams = array();
    
    /**
     * Est-ce que pour le détail d'un élément, il faut se baser sur l'url ou sur une autre source (ex: contexte).
     * Si {false} se base sur l'url, sinon, implémenter la méthode {getIdElementFromOtherSource()}.
     * 
     * @var boolean
     */
    protected $_isIdElementFromOtherSource = false;
    
    /**
     * Si {null}, redirection vers le contrôleur {<nom_contrôleur>-list}.
     * Sinon, redirection vers $_redirectionAfterSave.
     *
     * @var string
     */
    protected $_redirectionAfterSave = null;
    
    /**
     * Si {null}, redirection vers le contrôleur {<nom_contrôleur>-list}.
     * Sinon affiche message de succes.
     *
     * @var string
     */
    protected $_messageSuccess = null;
    
    /**
     * Vue à utiliser pour le rendu dans le cas où la sauvegarde ne s'est pas faite.
     * @see $this->render()
     * @var array
     */
    protected $_viewDetail = array(
    		'action' => 'detail',
    		'name' => null,
    		'noController' => false);
    
    // ------------------------------------------------------------------------
    // Liste des paramètres pour l'upload d'un fichier.
    // ------------------------------------------------------------------------

    /**
     * Nom de la colonne qui permet de savoir si l'élément est associé à un fichier.
     * 
     * @var string
     */
    protected $_columnIsFile = 'FIC_ID';
    
    /**
     * Nom de la colonne pour le type mime du fichier.
     *
     * @var string
     */
    protected $_columnTypeMime = 'FIC_TYPE_MIME';
    
    /**
     * Nom de la colonne qui permet de connaître la localisation du fichier dans le répertoire /wdir.
     * 
     * @var string
     */
    protected $_columnPath = 'FIC_CHEMIN';
    
    /**
     * Identifiant de la colonne sur laquelle se base le fichier.
     * 
     * @var string
     */
    protected $_columnPrimaryKey = null;
    
    /**
	 * Nom de la colonne pour la taille du fichier.
	 * 
	 * @var string
	 */
    protected $_columnSize = 'FIC_POIDS';
    
    /**
     * Nom de la colonne qui permet de connaître le titre du fichier.
     * 
     * @var string
     */
    protected $_columnTitle = 'FIC_TITRE';
    
    /**
     * Nom de la colonne dans laquelle on stock le fichier (blob).
     *
     * @var string
     */
    protected $_columnBlob = 'FII_FICHIER';
    
    /**
     * Nom du dossier vers lequel on upload.
     * Situé juste après {/wdir/files/} dans l'arborescence (ce chemin est défini dans application.ini).
     *
     * @var string
     */
    protected $_downloadFolder;
    
    /**
     * Nom de l'élément HTML qui va stocker le fichier.
     *
     * @var string
     */
    protected $_elementFilename = 'UPLOAD';
    
    /**
     * Est-ce qu'on stock le fichier sous forme de blob.
     * 
     * @var boolean
     */
    protected $_isBlob = false;
        
    // ------------------------------------------------------------------------
    // / Liste des paramètres pour l'upload d'un fichier.
    // ------------------------------------------------------------------------
    
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
	    
		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input-facebook.css', 'screen');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input-mac.css', 'screen');
		$this->view->headLink()->prependStylesheet($this->view->baseUrl() . '/static/css/token-input.css', 'screen');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/scriptAutocompleteMultiple.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/autoComplete.js');
		$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/jquery.tokeninput.js');
		
		if ($this->_containFile) {
			// Script permettant de gérer le message de confirmation en cas d'upload d'un nouveau fichier.
			$this->view->headScript()->prependFile($this->view->baseUrl() . '/static/js/scriptDetail.js');
		}
		
		$this->view->inlineScript()->prependScript(
				'jQuery(document).ready(function(){
				$my.messageAnnulation = "' . $this->view->translate('message.annuler') . '";
			});');
		
		$prefixe = Klee_Module_Commun_Controller_PrefixeTranslationHelper::getPrefixForTranslation($this->getRequest());
		$this->view->prefixe = $prefixe;
		
		$flashMessenger = $this->_helper->getHelper('FlashMessenger');
        if($flashMessenger->hasMessages()) {
            $this->view->messages = $flashMessenger->getMessages();
        }
		
	}
	
	/**
	 * Action {detail}.
	 * Affichage des données.
	 */
	public function detailAction() {
		$request = $this->getRequest();
		$form = $this->getForm($request);
		$this->doProcessDetail($request, $form);
	}
	
	/**
	 * Action {download}.
	 * Téléchargement d'un fichier.
	 */
	public function downloadAction() {
		$idElement = $this->getRequest()->get('idElement');
		$element = $this->get($idElement);
	
		// get the absolute path of the requested file
		$filepath = realpath(Zend_Registry::get('uploadDir') . $this->_downloadFolder . '/' . $element[$this->_columnPath]);
	
		if ((false !== $filepath) && is_file($filepath)) {
	
			$this->_helper->layout()->disableLayout();
			$this->_helper->viewRenderer->setNoRender();
			$this->getFrontController()->setParam('disableOutputBuffering', true);
	
			while (@ob_end_clean());
				
			$baseNameArray = explode('_', basename($filepath));
			unset($baseNameArray[0]);
			$baseName = implode('_', $baseNameArray);
				
			$this	->getResponse()
					->clearHeaders()
					->setHeader('Content-Type', 'application/octet-stream')
					->setHeader('Content-Disposition', 'attachment; filename="' . utf8_decode($baseName) . '"')
					->setHeader('Content-Transfer-Encoding', 'Binary')
					->setHeader('Content-Length', filesize($filepath))
					->sendHeaders();
	
			readfile($filepath);
			exit();
	
		} else {
			throw new Exception(sprintf('The specified file (%s) is not available to download', $element[$this->_columnTitle]));
		}
	}
	
	/**
	 * Action {index}.
	 * Redirection vers l'action {detail}.
	 */
	public function indexAction() {
		$this->_forward('detail');
	}

	/**
	 * Action {save}.
	 * Sauvegarde les données.
	 */
	public function saveAction() {
		$request = $this->getRequest();
		$form = $this->getForm($request);
		$this->doProcessDetail($request, $form);

		$isErreur = false;
		$isFile = false;
		try {
			if ($form->isValidPartial($request->getPost())) {
				$values = array();

				$fileInfo = array();
				if ($this->_containFile) {				
					$fileInfo = $this->doProcessUploadFile($form, $this->_containFile);

					if (! empty($fileInfo)) {
						$values['IS_NEW_FILE'] = true;
						$isFile = true;
						$values += $fileInfo;
					}					
				}
				
				// /!\ : A faire après avoir géré le fichier.
				$values += $form->getValues();

				$this->save($values);

				$controllerNameTab = explode('-', $request->getControllerName());
				
				if (is_null($this->_redirectionAfterSave)) {
					$this->_redirect($this->_helper->url->url(array('module' => $request->getModuleName(), 'controller' => implode('-', array_slice($controllerNameTab, 0, -1)) . '-list', 'action' => 'index'), null, true));
				} else {
				    if (!is_null($this->_messageSuccess)) {
				    	$this->addMessage($this->_messageSuccess);	
				    }			    
					$this->_redirect('/' . strtolower(Klee_Util_Context::getLocale()) . $this->_redirectionAfterSave);
				}			
			} else {
				foreach ($form->getMessages() as $key => $value) {
					foreach ($value as $message) {
						Klee_Module_Commun_Controller_HelperException::printInvalidFormError($form, $key, $message);
					}
				}
			}
		} catch (Klee_Util_UserException $ex) {
		    $isErreur = true;
			foreach ($ex->getMessageList() as $key => $message) {
				Klee_Module_Commun_Controller_HelperException::printExceptionFormError($form, $key, $message);
			}
		} catch (Exception $ex) {
		    $isErreur = true;
			Klee_Module_Commun_Controller_HelperException::printExceptionFormError($form, null, $ex->getMessage());
		}
		
		// On supprime le fichier sauvegardé sur le serveur, s'il y a une erreur.
		if ($isErreur && $isFile && ! $this->_isBlob) {
		    Application_Facade_Administration::getServiceFichierWrite()->deleteFichierOnServer($values[$this->_columnPath]);
		}
		
		$this->view->form = $form;

		$this->render($this->_viewDetail['action'], $this->_viewDetail['name'], $this->_viewDetail['noController']);
	}
	
	protected function addMessage($message) {
	    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
	    $flashMessenger->addMessage($message);
	}
	
	/**
	 * Action permettant d'afficher une image stockée en base.
	 */
	public function showAction() {
		$idElement = $this->getRequest()->getParam('idElement', null);
		$element = Application_Facade_Administration::getServiceFichierRead()->getFichierImage($idElement);

		$response = $this->getResponse();

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	
		$response
				->clearHeaders()
				->clearBody();
	
		$response
				->setHeader('Content-Type', $element['FII_TYPE_MIME'])
				->setHeader('Content-Transfer-Encoding', 'Binary')
				->setHeader('Content-Length', $element['FII_POIDS'])
				->setBody($element['FII_FICHIER']);
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------

	/**
	 * @param int $idElement Identifiant de l'élément cherché.
	 * @return array
	 */
	abstract protected function get($idElement);
	
	/**
	 * Retourne l'idElement à utiliser pour l'action détail.
	 * 
	 * @return NULL
	 */
	protected function getIdElementFromOtherSource() {
		return null;
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
	 * Retourne le script permettant de récupérer un fichier.
	 */
	protected function getFileScript() {
		return 'initDownloadFile("' . $this->_element[$this->_columnTitle] . '", "' . $this->_helper->url->url(array('action' => 'download', 'idElement' => $this->_element[$this->_columnPrimaryKey])) . '");';
	}
	
	/**
	 * Sauvegarde.
	 * 
	 * @param array $values Liste des éléments du formulaire.
	 */
	abstract protected function save(array $values);

	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Mise en place du détail d'un élément.
	 * 
	 * @param Zend_Controller_Request_Abstract $request Requête.
	 * @param Zend_Form $form							Formulaire.
	 */
	private function doProcessDetail(Zend_Controller_Request_Abstract $request, Zend_Form $form) {
		$scriptDownloadFile = '';
		$scriptConfirmationUploadNouveauFichier = '';

		if ($this->_isIdElementFromOtherSource) {
			$idElement = $this->getIdElementFromOtherSource();
		} else {
			$idElement = $request->getParam('idElement');
		}
		
		if (! is_null($idElement)) {
			$this->_element = $this->get($idElement);
		  
			$form->setDefaults($this->_element);
			 
			if ($this->_containFile) {
				if (null !== $this->_element[$this->_columnIsFile]) {
					// Ajout d'un flag sur le formulaire (présence d'un fichier).
					$form->addElement('hidden', 'IS_FILE', array('value' => 1));
		    
					// Script permettant de confirmer l'écrasement d'un fichier déjà uploadé.
					$scriptConfirmationUploadNouveauFichier = 'initConfirmationUploadNouveauFichier();';

					$scriptDownloadFile = $this->getFileSCript();
				}
			}
		}
		 
		$this->view->form = $form;
		$this->view->containFile = $this->_containFile;
		 
		$this->view->inlineScript()->prependScript('
				' . $this->getInlineScript() . '
	  
				' . $scriptDownloadFile . '
	  
				' . $scriptConfirmationUploadNouveauFichier . '
		');
	}
	
	/**
	 * Processus d'upload d'un fichier.
	 * Retourne les infos sur le fichier.
	 * 
	 * @param Zend_Form $form Formulaire.
	 * @param boolean $isFile Est-ce qu'il y a déjà un fichier.
	 * @return array
	 */
	private function doProcessUploadFile(Zend_Form $form, $isFile) {
		$elementFilename = $this->_elementFilename;
		$upload = $form->$elementFilename->getTransferAdapter();
		
		// Cas où il n'y a pas de fichier d'uploadé, on passe tout le processus de vérification.
		if (! $upload->isUploaded()) {
			if ($isFile) {
				return array();
			} else {
				throw new Klee_Util_UserException('emptyFile', array(), $this->_elementFilename);
			}
		}

		if (! $this->_isBlob) {
			// Chemin vers lequel le fichier sera uploadé.
			// Du type: /wdir/files/document/<$this->_downloadFolder>/<annee>/<mois>
			$currentDate = Klee_Util_Date::getCurrentDate();
			$downloadFolder = $this->_downloadFolder;
			if ($downloadFolder !== '') {
				$downloadFolder .= '/';
			}
			
			// Stocké dans une arborescence de la forme : {/AAAA/MM/NOM_FICHIER}
			$colPath = substr($currentDate, 0, 4) . '/' . substr($currentDate, 5, 2);
			$dirPath = Zend_Registry::get('uploadDir') . $downloadFolder . $colPath;
	
			// Création des dossiers si ceux-ci n'existent pas.
			if (! is_dir($dirPath)) {
				mkdir($dirPath, 0755, true);
			}
	
			$upload->setDestination(realpath($dirPath));
		}

		$fileInfo = $upload->getFileInfo();
		$title = $fileInfo[$this->_elementFilename]['name'];
		
		if (! $upload->isValid()) {
		    throw new Klee_Util_UserException('erreur.message.fichier.notValid');
		}

		if (! $upload->receive()) {
			Klee_Util_CustomLog::error("erreur.message.fichier.receiveError<br />" . print_r($upload->getMessages(), 1));
			throw new Klee_Util_UserException('erreur.message.fichier.receiveError');
		}
		
		$fileInfo = $upload->getFileInfo();
		
		$result = array(
			$this->_columnTypeMime 	=> $upload->getMimeType(),
			$this->_columnSize 		=> $fileInfo[$this->_elementFilename]['size'],
			$this->_columnTitle 	=> $title
		);
		
		if ($this->_isBlob) {
			$result[$this->_columnBlob] = file_get_contents($fileInfo[$this->_elementFilename]['tmp_name']);
		} else {
			$result[$this->_columnPath] = $colPath . '/' . $fileInfo[$this->_elementFilename]['name'];
		}

		return $result;
	}
	
	/**
	 * Retourne un objet formulaire.
	 * 
	 * @param Zend_ControllerRequest $request Objet requête.
	 * @return Zend_Form
	 */
	private function getForm($request) {
		$arrayControllerName = explode('-', $request->getControllerName());
		$instance = 'Application_Module_' . ucfirst($request->getModuleName()) . '_Forms_' . implode('', array_map('ucfirst', $arrayControllerName)) . 'Form';
		$form = new $instance(null, $this->_formParams);
		$form->setAttrib('id', 'formDetail');
		
		if ($this->_containFile) {
			$form->addElement('hidden', 'IS_FILE', array('value' => 0));
// 			if ($request->getParam('IS_FILE') && $request->getParam('IS_FILE') === '1') {
// 				$form->getElement($this->_elementFilename)->setRequired(false);
// 			}
		}
		
		// Ajout d'un décorateur pour placer les messages d'erreurs au dessus du formulaire.
		$form->setDecorators(array(
				'FormElements',
				array('FormErrors', array('placement' => 'prepend')),
				'Form'));
		
		$form->setMethod('post');
		$form->setAction($this->_helper->url->url(array('action' => 'save')));
		return $form;
	}
}
