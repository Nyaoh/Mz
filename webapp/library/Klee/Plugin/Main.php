<?php

class Klee_Plugin_Main extends Zend_Controller_Plugin_ErrorHandler
{
	
	/**
	 * Const - User exception
	 */
	const USER_EXCEPTION = 'USER_EXCEPTION';
	
	/**
	 * Nom du module courant.
	 * 
	 * @var string
	 */
	private static $_module = null;
	
	private static $_isCli = null;
	
	/**
	 * Constructeur.
	 */
	public function __construct(array $options = array()) {
		parent::__construct($options);
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
		//     	$request->setParam('module', self::$_module);
		$request->setModuleName(self::$_module);
	
		// configuration du répertoire des aides de vues en fonction du module
		//     	Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->view->addHelperPath(
		//         	APPLICATION_PATH . '/modules/' . self::$_module . '/views/helpers', 'Application_View_Helper_'
		//         );
	}
	
	/* (non-PHPdoc)
	 * @see Zend_Controller_Plugin_ErrorHandler::preDispatch()
	 */
	public function preDispatch(Zend_Controller_Request_Abstract $request) {
		$front = Zend_Controller_Front::getInstance();
		
	    $layout = $front->getParam('bootstrap')->getResource('layout');
	    $view = $layout->getView();
	    	
	    $sysLanguage = $request->getParam('sys-language');
	    
	    if (! self::isCli($request)) {
	    	Zend_Navigation_Page::setDefaultPageType('mvc');
    	}
    	
	    // configuration du ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see Zend_Controller_Plugin_ErrorHandler::_handleError()
	 */
	protected function _handleError(Zend_Controller_Request_Abstract $request) {
		$frontController = Zend_Controller_Front::getInstance();
		if ($frontController->getParam('noErrorHandler')) {
			return;
		}
	
		$response = $this->getResponse();
	
		if ($this->_isInsideErrorHandlerLoop) {
			$exceptions = $response->getException();
			if (count($exceptions) > $this->_exceptionCountAtFirstEncounter) {
				// Exception thrown by error handler; tell the front controller to throw it
				$frontController->throwExceptions(true);
				throw array_pop($exceptions);
			}
		}
	
		// check for an exception AND allow the error handler controller the option to forward
		if (($response->isException()) && (!$this->_isInsideErrorHandlerLoop)) {
			$this->_isInsideErrorHandlerLoop = true;
	
			// Get exception information
			$error            = new ArrayObject(array(), ArrayObject::ARRAY_AS_PROPS);
			$exceptions       = $response->getException();
			$exception        = $exceptions[0];
			$exceptionType    = get_class($exception);
			$error->exception = $exception;
			switch ($exceptionType) {
				case 'Zend_Controller_Router_Exception':
					if (404 == $exception->getCode()) {
						$error->type = self::EXCEPTION_NO_ROUTE;
					} else {
						$error->type = self::EXCEPTION_OTHER;
					}
					break;
				case 'Zend_Controller_Dispatcher_Exception':
					$error->type = self::EXCEPTION_NO_CONTROLLER;
					break;
				case 'Zend_Controller_Action_Exception':
					if (404 == $exception->getCode()) {
						$error->type = self::EXCEPTION_NO_ACTION;
					} else {
						$error->type = self::EXCEPTION_OTHER;
					}
					break;
				case 'Klee_Util_UserException':
					$error->type = self::USER_EXCEPTION;
					break;
				default:
					$error->type = self::EXCEPTION_OTHER;
					break;
			}
	
			// Keep a copy of the original request
			$error->request = clone $request;
	
			// get a count of the number of exceptions encountered
			$this->_exceptionCountAtFirstEncounter = count($exceptions);
	
			// Forward to the error handler
			$request->setParam('error_handler', $error)
			->setModuleName($this->getErrorHandlerModule())
			->setControllerName($this->getErrorHandlerController())
			->setActionName($this->getErrorHandlerAction())
			->setDispatched(false);
		}
	}

    // ------------------------------------------------------------------------
    // Public static methods.
    // ------------------------------------------------------------------------
    
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
}
