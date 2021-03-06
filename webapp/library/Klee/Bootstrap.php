<?php

class Klee_Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    /**
     * Initialisation de la vue.
     * 
     * @return string|Zend_View
     */
    protected function _initView() {
        $view = new Zend_View();
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

		Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->setView($view);

		// Ajout d'un chemin vers le dossier d'helper.
        $view->addHelperPath(APPLICATION_PATH . '/../library/Klee/Module/Commun/View/Helper', 'Klee_View_Helper_');
//         $view->addHelperPath(APPLICATION_PATH . '/modules/Commun/views/helpers', 'Application_View_Helper_');
        
        // Fixe la méthode permettant d'échapper les données.
        $view->setEscape(
	        function ($s) {
	            static $isXml = null;
				return htmlspecialchars(Klee_Util_MbString::convertEncoding($s),
	            ($isXml === true ? ENT_COMPAT : ENT_QUOTES), Klee_Util_MbString::ENCODING);
	        }
        );
  
        return $view;
    }
    
//     /**
//      * Initialisation du resourceLoader.
//      */
//     protected function _initDefaultResourceTypes() {
//     	$resourceLoader = $this->getResourceLoader();
// //     	$resourceLoader->addResourceType('util', 'utils/', 'Util');
// //     	$resourceLoader->addResourceType('module', 'modules/', 'Module');
// //     	$resourceLoader->addResourceType('facade', 'facades/', 'Facade');
//     	$resourceLoader->addResourceType('model', 'models/', 'Model');
//     }
    
   	/**
     * Initialisation du logger.
     */
    protected function _initLogger() {
    	$options = $this->getOption('logger');
    	$writer = new Zend_Log_Writer_Stream($options['logPath']);
    	$logger = new Zend_Log($writer);
    	Zend_Registry::set('logger', $logger);
    }
    
    /**
     * Initialisation du plugin Main.
     */
    protected function _initPlugin() {
    	$front = Zend_Controller_Front::getInstance();
    	$front->registerPlugin(new Klee_Plugin_Main());
    	$front->setParam('noErrorHandler', true);
    }
    
    /**
     * Initialisation des routes.
     */
    protected function _initRoute() {
    	$front = Zend_Controller_Front::getInstance();
    	$router = $front->getRouter();
    	
    	// Chargement du fichier de configuration des routes.
    	$config = new Zend_Config_Ini(APPLICATION_PATH . DIRECTORY_SEPARATOR . 'configs' . DIRECTORY_SEPARATOR . 'routes.ini', 'production');
    	
    	// Ajout des routes au routeur.
    	$router->addConfig($config, 'routes');
    }
    
//     protected function _initController() {
//     	$front = Zend_Controller_Front::getInstance();
//     	$front->setControllerDirectory(array(
//     			'default' 	=> APPLICATION_PATH . '/modules/default/controllers',
//     			'example'	=> APPLICATION_PATH . '/modules/example/controllers'
//     	));
//     }
}

