<?php

/**
 * Injecte le Zend_Db_Adapter en fonction du type de service invoqué en premier.
 *
 * @author fconstantin
 * @package serviceLayer
 */
class Klee_Service_Injector_DbAdapter extends Klee_Service_Injector_Abstract 
{
	/**
	 * DbAdapter.
	 *
	 * @var Zend_Db_Adapter_Abstract
	 */
	private static $_dbAdapter;
	
	/**
	 * Profiler base de données.
	 * 
	 * @var Zend_Db_Profiler
	 */
	private static $_dbProfiler;
	
	/**
	 * Indique si le Zend_DbAdapter a été ouvert par l'instance courante.
	 *
	 * @var boolean
	 */
	private $_openDbAdapter;
	
	/**
	 * Retourne le profiler de base de données.
	 * 
	 * @return Zend_Db_Profiler Profiler BDD.
	 */
	public static function getDbProfiler() {
	    return self::$_dbProfiler;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::preProcess()
	 */
	protected function preProcess($object, $name, $arguments) {
	    unset($name);
		unset($arguments);

	    if (! isset(self::$_dbAdapter)) {
			$this->_openDbAdapter = true;

			if (substr(get_class($object), -4) !== "Read") {
				self::$_dbAdapter = Zend_Registry::get('dbWriteAdapter');
			} else {
				self::$_dbAdapter = Zend_Registry::get('dbReadAdapter');
			}

            if (! self::$_dbAdapter instanceof Zend_Db_Adapter_Abstract) {
                throw new Zend_Db_Adapter_Exception('Impossible de créer le Zend_Db_Adapter');
            }
	    }
	    
	    if (Klee_Service_Proxy::isDbProfilerEnabled() && ! isset(self::$_dbProfiler)) {
	        self::$_dbProfiler = new Zend_Db_Profiler();
	        self::$_dbProfiler->setEnabled(true);
	    }
	    self::$_dbAdapter->setProfiler(self::$_dbProfiler);
	    
	    $object->_dbAdapter = self::$_dbAdapter;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::postProcess()
	 */
	protected function postProcess($object, $name, $arguments, $return = null) {
	    unset($name);
	    unset($arguments);

		unset($object->_dbAdapter);

	    if ($this->_openDbAdapter) {
			self::$_dbAdapter = null;
	    }

		return $return;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::onCatch()
	 */
	protected function onCatch($object, $name, $arguments, Exception $exception) {
	    unset($name);
	    unset($arguments);

	    if ($this->_openDbAdapter) {
			unset($object->_dbAdapter);
		}
		self::$_dbAdapter = null;

		throw $exception;
	}
}
