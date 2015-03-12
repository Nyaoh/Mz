<?php

/**
 * Contrôleur abstrait permettant de gérer le détail d'un élément.
 * 
 * @author AMORIN
 *
 */
abstract class Klee_Module_Commun_Controller_AbstractDetailMultiUploadController extends Zend_Controller_Action
{
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
     * Affichage d'un message de succès après sauvegarde des données.
     * Utilisation du composant FlashMessenger.
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
     * Est-ce que le formulaire contient un fichier à uploader.
     *
     * @var boolean
     */
    protected $_containFile = FALSE;
    
    /**
     * Liste des éléments de type fichier dans le formulaire.
     * 
     * @var array
     */
    protected $_uploadFileList = array();
    
    /**
     * Liste des colonnes de la base de données référençant les différentes
     * informations sur les éléments ayant pour domaine {Fichier}.
     *
     * @var array
     */
    protected $_columnListFichier = array(
    	'MIME_TYPE'	=> 'FIC_TYPE_MIME',
    	'SIZE'		=> 'FIC_POIDS',
    	'TITLE'		=> 'FIC_TITRE',
    	'PATH'		=> 'FIC_CHEMIN'
    );
    
    /**
     * Liste des colonnes de la base de données référençant les différentes
     * informations sur les éléments ayant pour domaine {FichierImage}.
     * 
     * @var array
     */
    protected $_columnListFichierImage = array(
    	'MIME_TYPE'	=> 'FII_TYPE_MIME',
    	'SIZE'		=> 'FII_POIDS',
    	'TITLE'		=> 'FII_TITRE',
    	'BLOB'		=> 'FII_FICHIER'
    );
    
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
		$url = $this->_helper->url->url(array('module' => 'administration', 'controller' => 'actualite-list', 'action' => 'index'), NULL, TRUE);

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
	
	// ------------------------------------------------------------------------
	// Public methods.
	// ------------------------------------------------------------------------
	
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
		$idFile = $this->getRequest()->getParam('idFile');
		$fichier = Klee_Facade_Fichier::getServiceFichier()->getFichier($idFile);

		// get the absolute path of the requested file
		$filepath = realpath(Zend_Registry::get('uploadDir') . $this->_downloadFolder . '/' . $fichier[$this->_columnListFichier['PATH']]);

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
// 		$isFile = false;
		try {
			if ($form->isValidPartial($request->getPost())) {
				$fileInfoList = array();
				$values = array();
				
				if ($this->_containFile) {
					$values = $this->doCheckUploadFileList($form);
					
// 					Zend_Debug::dump($this->_uploadFileList);die;
// 					foreach ($this->_uploadFileList as $uploadFile) {
// 						$fileInfo = $this->doProcessUploadFile($form, $uploadFile);
						
// // 						Zend_Debug::dump($fileInfo);

// 						if (! empty($fileInfo)) {
// 							$values['FILE_LIST'][$uploadFile['COLUMN_IS_FILE']] = array(
// 								'IS_NEW_FILE' 	=> TRUE,
// 								'INFO'			=> $fileInfo	
// 							);
// // 							$isFile = true;
// 						}
// 					}
				}
				
				// /!\ : A faire après avoir géré le fichier.
				$values += $form->getValues();
				
// 				if (FALSE === $form->hasErrors()) {
// 					$form->clearErrorMessages();
// 				}
				
// 				Zend_debug::dump($form->getErrorMessages());
// 				Zend_debug::dump('---');
// 				Zend_debug::dump($form->getErrors());
				
// 				Zend_Debug::dump($values);
// 				die;
				
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
		// @TODO: suppression à faire.
// 		if ($isErreur && $isFile && ! $this->_isBlob) {
// 		    Application_Facade_Administration::getServiceFichierWrite()->deleteFichierOnServer($values[$this->_columnPath]);
// 		}
		
		$this->view->form = $form;

		$this->render($this->_viewDetail['action'], $this->_viewDetail['name'], $this->_viewDetail['noController']);
	}
	
	/**
	 * @param Zend_Form $form
	 * @return multitype:
	 */
	private function doCheckUploadFileList(Zend_Form $form) {
		$exceptionPool = new Klee_Util_UserException();
		$fileInfoList = array();
		
		foreach ($this->_uploadFileList as $file) {
			$fileInfo = $this->doCheckUploadFile($form, $file, $exceptionPool);
			if (FALSE === empty($fileInfo)) {
				$fileInfoList[$file['NAME']] = $fileInfo;
			}
		}
		
		$exceptionPool->throwIfError();
		
		return $fileInfoList;
	}
	
	/**
	 * @param Zend_Form $form
	 * @param unknown $file
	 * @param Klee_Util_UserException $exceptionPool
	 */
	private function doCheckUploadFile(Zend_Form $form, $file, Klee_Util_UserException $exceptionPool) {
		$upload = $form->$file['NAME']->getTransferAdapter();

		$form->$file['NAME']->removeValidator('Upload');

		if (FALSE === $upload->isUploaded($file['NAME'])) {
			if ($form->$file['NAME']->isRequired() && '0' === $form->$file['HIDDEN_NAME']->getValue()) {
				$exceptionPool->addMessage('validator.notEmpty.isEmpty', array(), $file['NAME']);
			}
			return array();
		} else {
			if (FALSE === $upload->isValid($file['NAME'])) {
				foreach (array_values($upload->getMessages()) as $message) {
					$exceptionPool->addMessage(NULL, array(), $file['NAME']);
					return array();
				}
			}
		}

		if (FALSE === $file['IS_BLOB']) {
			$currentDate = Klee_Util_Date::getCurrentDate();
			
			$columnPath = substr($currentDate, 0, 4) . '/' . substr($currentDate, 5, 2);
			$chemin = Zend_Registry::get('uploadDir');
			if (NULL != $this->_downloadFolder) {
				$chemin .= $this->_downloadFolder . '/' . $columnPath;
			} else {
				$chemin .= $columnPath;
			}

			if (FALSE === is_dir($chemin)) {
				mkdir($chemin, 0755, TRUE);
			}

			$upload->setDestination($chemin);
		}
		
		$fileInfo = $upload->getFileInfo($file['NAME']);
		$title = $fileInfo[$file['NAME']]['name'];

		if (FALSE === $upload->receive($file['NAME'])) {
			throw new Klee_Util_UserException('erreur.message.fichier.receiveError');
		}

		$fileInfo 	= $upload->getFileInfo($file['NAME']);
		$mimeType 	= $upload->getMimeType($file['NAME']);
		$size 		= $fileInfo[$file['NAME']]['size'];
		
		if (TRUE === $file['IS_BLOB']) {
			$result = array(
				$this->_columnListFichierImage['MIME_TYPE'] => $mimeType,
				$this->_columnListFichierImage['SIZE']		=> $size,
				$this->_columnListFichierImage['TITLE']		=> $title,
				$this->_columnListFichierImage['BLOB']		=> file_get_contents($fileInfo[$file['NAME']]['tmp_name'])
			);
		} else {
			$result = array(
				$this->_columnListFichier['MIME_TYPE']		=> $mimeType,
				$this->_columnListFichier['SIZE']			=> $size,
				$this->_columnListFichier['TITLE']			=> $title,
				$this->_columnListFichier['PATH']			=> $columnPath . '/' . $fileInfo[$file['NAME']]['name']
			);
		}
		
		return $result;
	}
	
	protected function addMessage($message) {
	    $flashMessenger = $this->_helper->getHelper('FlashMessenger');
	    $flashMessenger->addMessage($message);
	}
	
	/**
	 * Action permettant d'afficher une image stockée en base.
	 */
	public function showAction() {
		$idFile = $this->getRequest()->getParam('idFile', NULL);
		$fichierImage = Klee_Facade_Fichier::getServiceFichier()->getFichierImage($idFile);

		$response = $this->getResponse();

		$this->_helper->layout()->disableLayout();
		$this->_helper->viewRenderer->setNoRender(true);
	
		$response
				->clearHeaders()
				->clearBody();
	
		$response
				->setHeader('Content-Type', $fichierImage['FII_TYPE_MIME'])
				->setHeader('Content-Transfer-Encoding', 'Binary')
				->setHeader('Content-Length', $fichierImage['FII_POIDS'])
				->setBody($fichierImage['FII_FICHIER']);
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
	 * Retourne l'objet formulaire utilisé pour le détail.
	 * Méthode extraite depuis {getForm()}.
	 *
	 * @param Zend_Controller_Request_Abstract $request Requête.
	 * @return Zend_Form
	 */
	protected function getFormObject(Zend_Controller_Request_Abstract $request) {
		$arrayControllerName = explode('-', $request->getControllerName());
		$instance = 'Application_Module_' . ucfirst($request->getModuleName()) . '_Forms_' . implode('', array_map('ucfirst', $arrayControllerName)) . 'Form';
		return new $instance(null, $this->_formParams);
	}
	
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
		/*
		 * Les informations sur le détail de l'élément à consulter se base soir sur :
		 * - Le paramètre {idElement} de l'url;
		 * - Le paramètre retourné par la méthode {getIdElementFromOtherSource()}.
		 */	
		if ($this->_isIdElementFromOtherSource) {
			$idElement = $this->getIdElementFromOtherSource();
		} else {
			$idElement = $request->getParam('idElement');
		}
		
		$scriptDownloadFile = '';
		$scriptConfirmationUploadNouveauFichier = '';
		
		if (NULL !== $idElement) {
			$this->_element = $this->get($idElement);
		  
			$form->setDefaults($this->_element);	
		}
		
		if (! empty($this->_uploadFileList)) {
			foreach ($this->_uploadFileList as $uploadFile) {
				if (! isset($uploadFile['HIDDEN_NAME'])) {
					continue;
				}
				
				// Ajout d'un flag au niveau du formulaire pour indiquer la présence ou non du fichier.
				$isFile = $this->_element[$uploadFile['NAME']] !== NULL ? 1 : 0;
				$form->addElement('hidden', $uploadFile['HIDDEN_NAME'], array('value' => $isFile));
					
// 				// Script permettant de confirmer l'écrasement d'un fichier déjà uploadé.
// 				// $scriptConfirmationUploadNouveauFichier = 'initConfirmationUploadNouveauFichier();';
// 				// @TODO: gestion de la confirmation d'upload d'un nouveau fichier.
					
				// Gestion de l'affichage des images.
				if (NULL !== $this->_element && NULL !== $this->_element[$uploadFile['NAME']] && TRUE === $uploadFile['IS_BLOB']) {
					$scriptDownloadFile .= 'showFileImage("' . $uploadFile['NAME'] . '", "' . $this->_helper->url->url(array('action' => 'show', 'idFile' => $this->_element[$uploadFile['NAME']])) . '");';
				}
				
				// Gestion du téléchargement des images.
				// @TODO
				
// 				$scriptDownloadFile .= $this->getFileScript();
// 				// @TODO: gestion du téléchargement ou de l'affichage des fichiers déjà enregistrés.
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
	 * Retourne un objet formulaire.
	 * 
	 * @param Zend_ControllerRequest $request Objet requête.
	 * @return Zend_Form
	 */
	private function getForm($request) {
		$arrayControllerName = explode('-', $request->getControllerName());
		$instance = 'Application_Module_' . ucfirst($request->getModuleName()) . '_Forms_' . implode('', array_map('ucfirst', $arrayControllerName)) . 'Form';
		$form = $this->getFormObject($request);
		$form->setAttrib('id', 'formDetail');
		
		if ($this->_containFile) {
			$form->addElement('hidden', 'IS_FILE', array('value' => 0));
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
