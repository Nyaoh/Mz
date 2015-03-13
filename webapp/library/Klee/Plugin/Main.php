<?php

/**
 * Plugin ajoutant les traitements antérieurs au rendu MVC.
 * Prend en charge l'aiguillage entre l'application et le mode CLI.
 * Prend en charge l'identification de l'utilisateur et l'assignation des rôles.
 * Initialise le cache.
 */
class Klee_Plugin_Main extends Zend_Controller_Plugin_Abstract
{
	/**
	 * Liste des pages n'ayant pas besoin d'accès spécifique.
	 * 
	 * @var array
	 */
	protected static $_publicPageList = array(
			'accueil_authentification');
	
	/**
	 * Liste des ressources qui sont publiques (page de diagnostique, page de réinitialisation des paramètres).
	 * 
	 * @var array
	 */
	protected static $_publicRessourceList = array(
			'administration_reinitialisation', 
			'administration_diagnostique');
	
	/**
	 * Données sur la page d'accueil (nom du module, du contrôleur et une liste d'actions).
	 * 
	 * @var array
	 */
	protected static $_pageAccueil = array(
			'module' => 'accueil',
			'controller' => 'index',
			'actionList' => array('index'));
	
	/**
	 * Données sur la page d'authentification (nom du module, du contrôleur et une liste d'actions).
	 * 
	 * @var array
	 */
	protected static $_pageAuthentification = array(
			'module' => 'accueil',
			'controller' => 'authentification',
			'actionList' => array('index'));
	
	/**
	 * Nom du module courant.
	 * 
	 * @var string
	 */
	private static $_module = null;
	
	private static $_isCli = null;
	
	/**
	 * Liste des droits de l'application.
	 * 
	 * @var array
	 */
	private static $_rightList;
	
	/**
	 * @var Zend_Auth
	 */
	private $_auth = null;
	
	/**
	 * Fichier XML de navigation.
	 * 
	 * @var Zend_Config
	 */
	private static $_xmlConfig;
	
	
	
	/**
	 * Constructeur.
	 */
	public function __construct() {
		self::$_xmlConfig = self::loadXmlConfig();
	}
	
	
	
	/* (non-PHPdoc)
	 * @see Zend_Controller_Plugin_Abstract::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
// 	    Zend_Navigation_Page::setDefaultPageType('mvc');
		$front = Zend_Controller_Front::getInstance();
		
	    $layout = $front->getParam('bootstrap')->getResource('layout');
	    $view = $layout->getView();
	    	
	    $sysLanguage = $request->getParam('sys-language');
	    Klee_Util_Context::setLocale($sysLanguage);
	    
	    if (! self::isCli($request)) {
	    	Zend_Navigation_Page::setDefaultPageType('mvc');
	    	
	    	// Initialisation des droits.
	    	self::getRightList();
	
	    	/*$resource = $request->getModuleName() . '_' . $request->getControllerName();
	    	$droits = Application_Util_Context::getDroit();
    	
	    	$hasIdentity = Zend_Auth::getInstance()->hasIdentity();
	    	
	    	// Cas où on arrive sur le contrôleur d'erreur, aucune redirection (afin de voir l'exception).
	    	if ($request->getControllerName() !== 'error') {
	    		$redirector = Zend_Controller_Action_HelperBroker::getStaticHelper('redirector');
	
	    		if (! $hasIdentity) {
	    			if (! self::isPublicPage($request->getModuleName(), $request->getControllerName())) {
		    			// Si l'utilisateur n'est pas connecté, on le redirige vers la page de connexion
		    			// (dans le cas où il essaye d'accéder à une page différente de la page de connexion).
		    			$redirector->setGotoSimple(self::$_pageAuthentification['controller'], self::$_pageAuthentification['module']);
	    			}
	    		} else {
	    			// Si un utilisateur connecté essaye d'accéder au formulaire de connexion, on le redirige vers l'accueil en mode connecté.
	    			if ($request->getModuleName() === self::$_pageAccueil['module'] 
	    					&& $request->getControllerName() === 'authentification' 
	    					&& in_array($request->getActionName(), self::$_pageAccueil['actionList'])) {
	    				$redirector->setGotoSimple(self::$_pageAccueil['controller'], self::$_pageAccueil['module']);
	    			}
	    			
	    			// Si l'utilisateur n'a pas le droit d'accéder à la ressource -> droit.insuffisant
	    			if (! self::isAllowed($resource, $droits, $request) && ! in_array($resource, self::$_publicRessource)) { 
	    				throw new Klee_Util_UserException('droit.insuffisant');
	    			}
	    		}
	    	}*/
	    	
	    	// Navigation.
	    	$navigation = new Zend_Navigation(self::$_xmlConfig);
	    	$view->navigation($navigation);
    	}
    	
	    // configuration du ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
	}

    /* (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::dispatchLoopStartup()
     */
    public function dispatchLoopStartup(Zend_Controller_Request_Abstract $request) {
		// définition du module en fonction de mode d'accès à PHP (web ou ligne de commande)
    	if ($this->isCli($request)) {
    		self::$_module = 'cli';
    	} else {
    		self::$_module = $request->getModuleName();
    	}
    	$request->setParam('module', self::$_module);
    	$request->setModuleName(self::$_module);
		
    	// configuration du répertoire des aides de vues en fonction du module
    	Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view->addHelperPath(
        	APPLICATION_PATH . '/modules/' . self::$_module . '/views/helpers', 'Application_View_Helper_'
        );
    }
    
    // ------------------------------------------------------------------------
    // Public static methods.
    // ------------------------------------------------------------------------
    
    // Accesseur.
    
    /**
     * @return Zend_Config
     */
    public static function getXmlConfig() {
    	return self::$_xmlConfig;
    }
    
    // /Accesseur.
    
    /**
     * @param Zend_Controller_Request_Http $request Requete courante.
     * @return boolean True s'il s'agit d'une requete CLI (ligne de commande).
     */
    public static function isCli(Zend_Controller_Request_Abstract $request) {
    	if (null === self::$_isCli) {
    		self::$_isCli = substr(php_sapi_name(), 0, 3) === 'cli' && ! ($request instanceof Zend_Controller_Request_HttpTestCase);
    	}
    	return self::$_isCli;
    }
    
    // ------------------------------------------------------------------------
    // Protected methods
    // ------------------------------------------------------------------------
    
	/**
	 * Retourne true si la page à laquelle l'utilisateur essaye d'accéder est une page publique, false sinon.
	 * 
	 * @param string $module Nom du module.
	 * @param string $page	 Nom de la page.
	 * @return boolean
	 */
	protected function isPublicPage($module, $page) {
    	return in_array($module . '_' . $page, self::$_publicPageList);
    }
    
    // ------------------------------------------------------------------------
    // Private methods.
    // ------------------------------------------------------------------------
    
    /**
     * Ajout des droits à $resource.
     *
     * @param string $resource	 Nom de la ressource (concaténation du module et du contrôleur).
     * @param array $allowList	 Liste des droits autorisés pour la ressource et les filières.
     */
    private static function addAllowToResource($resource, array $allowList) {
    	foreach ($allowList as $allow) {
    		if ($allow === '') {
    			$allow = 'PUBLIC';
    		}
    		self::$_rightList[$resource][$allow] = true;
    	}
    }
    
    /**
     * Retourne la liste des droits associés aux filières et aux ressources.
     * @return array Liste des drois
     */
    private static function getRightList() {
    	if (is_null(self::$_rightList)) {
    		self::loadMenu(self::$_xmlConfig);
    		self::$_xmlConfig->setReadOnly();
    	}
    	 
    	return self::$_rightList;
    }
    
    
    
    /**
     * @param string $resource Nom de la ressource à laquelle l'utilisateur tente d'accéder.
     * @param array $droits    Droit de l'utilisateur.
     */
    private static function isAllowed($resource, array $droits, Zend_Controller_Request_Abstract $request) {
    	if (self::isCli($request)) {
    		return true;
    	}
    	
    	if ($resource === '__') {
    		throw new Klee_Util_UserException('$resource est vide');
    	}
    	 
    	foreach ($droits as $droit) {
    		if (isset(self::$_rightList[$resource][$droit])) {
    			return true;
    		}
    	}
    	return false;
    }
    
    /**
     * Parcours les noeuds de la navigation pour initialiser les ressources et les droits.
     *
     * @param Zend_Config $config Noeud de navigation.
     */
    private static function loadMenu(Zend_Config $config) {
    	foreach ($config as $value) {
    		// Pour chaque ressource du type MODULE_CONTROLLER, on associe un ou plusieurs droits.
    		$resource = $value->get('module') . '_' . $value->get('controller');
    		$allow = $value->get('allow'); // Chaîne listant les droits (séparés par une virgule).
    		$allowList = explode(',', $allow);
    		 
    		if (is_null($value->get('pages'))) {
    			if ($allow === '') {
    				throw new Zend_Exception('Droit manquant pour le bloc dont le label est : ' . $value->get('label') );
    			}
    			self::addAllowToResource($resource, $allowList);
    
    			if (! Application_Util_Context::hasRole($allowList)) {
    				$value->__set('visible', false);
    			}
    		} else {
    			self::addAllowToResource($resource, $allowList);
    
    			// Permet de masquer tous les enfants d'un noeud qui n'est pas visible.
    			if (! Application_Util_Context::hasRole(explode(',', $value->get('allow')))) {
    				$value->__set('visible', false);
    			}

    			self::loadMenu($value->get('pages'));
    		}
    	}
    }
    
    /**
     * Charge les éléments d'un menu.
     * 
     * @param Zend_Config $node Noeud.
     * @return Zend_Config_Xml
     */
    private static function loadMenuByModule($node) {
    	$menuList = Klee_Util_ReferenceManager::getInstance()->getObjectListByCriteria('CET_ID', array('MOD_CODE' => $node->get('mod')));
    	if (empty($menuList)) {
    		return null;
    	}
    	
    	$xml 		= 	'<?xml version="1.0" encoding="UTF-8"?>
    					 <actualite><pages>';
    	foreach ($menuList as $menu) {
    		$xml 	.= 			'<onglet' . $menu['CET_ID'] . '>
    								<label>' . $menu['CET_LIBELLE'] . '</label>
    								<module>' . $node->get('module') . '</module>
    								 <controller>' . $node->get('controller') . '</controller>
    				 				<action>index</action>
    								 <allow>' . $node->get('allow') . '</allow>
    				 				<params>
    									<idOnglet>' . $menu['CET_ID'] . '</idOnglet>
    								</params>
    							</onglet' . $menu['CET_ID'] . '>';
    	}
    	$xml 		.= 		'</pages></actualite>';
    	 
    	return new Zend_Config_Xml($xml);
    }
    
    /**
     * Pour générer le fichier xml de navigation.
     *
     * @return Zend_Config_Xml
     */
    private static function loadXmlConfig() {
    	$dom = new DOMDocument;
    	$dom->load(APPLICATION_PATH . '/configs/initNavigation.xml', LIBXML_NOENT|LIBXML_NOXMLDECL);
    
    	$strXml = $dom->saveXML();
    
    	$pattern = array('/\<!DOCTYPE (.*)/','/\<!ENTITY (.*)/','/\]\>/');
    	$replace = array('', '', '');
    
    	$strXmlPropre = preg_replace($pattern, $replace, $strXml);
    
    	return new Zend_Config_Xml($strXmlPropre, 'nav', array('allowModifications' => true));
    }
    
    /**
     * @return Zend_Auth
     */
    private function getAuth() {
    	if (is_null($this->_auth)) {
    		$this->_auth = Zend_Auth::getInstance();
    	}
    	return $this->_auth;
    }
}
