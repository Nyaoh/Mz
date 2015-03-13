<?php

/**
 * Ajoute les débuts et fin de transaction sur les services elligibles.
 *
 * @author jbourdin
 * @package serviceLayer
 */
class Klee_Service_Injector_Transaction extends Klee_Service_Injector_Abstract 
{
	/**
	 * Constante de classe : sémaphore indiquant une transaction en cours.
	 *
	 * @var boolean
	 */
	private static $_transactionProcessing;

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::preProcess()
	 */
	protected function preProcess($object, $name, $arguments) {
	    unset($name);
	    unset($arguments);
		$object->_dbAdapter->beginTransaction();
		self::$_transactionProcessing = true;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::postProcess()
	 */
	protected function postProcess($object, $name, $arguments, $return = null) {
	    unset($name);
	    unset($arguments);
		$object->_dbAdapter->commit();
		self::$_transactionProcessing = false;
		return $return;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::onCatch()
	 */
	protected function onCatch($object, $name, $arguments, Exception $exception) {
	    unset($name);
	    unset($arguments);
		$object->_dbAdapter->rollback();
		self::$_transactionProcessing = false;
		throw $exception;
	}

	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::willExecute()
	 */
	public function willExecute($object, $name, $arguments) {
	    unset($arguments);
	    unset($name);
		if (self::$_transactionProcessing === true) {
			return false;
		} else if (substr(get_class($object), -4) !== 'Read') {
		    return true;
		} else {
			return false;
		}
	}
}
