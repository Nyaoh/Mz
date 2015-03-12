<?php 

/**
 * Classe permettant la gestion personnalisée des logs.
 * 
 * @author AMORIN
 *
 */
abstract class Klee_Util_CustomLog
{	
	private static $_logger;
	
	/**
	 * @param multitype $message Message à logger.
	 */
	public static function log($message, $level = Zend_Log::ERR) {
		self::getInstance()->log(print_r($message, true), $level);
	}
	
	/**
	 * @param multitype $message Message à logger.
	 */
	public static function info($message) {
		self::getInstance()->log(print_r($message, true), Zend_Log::INFO);
	}
	
	/**
	 * @param multitype $message Message à logger.
	 */
	public static function error($message) {
		self::getInstance()->log(print_r($message, true), Zend_Log::ERR);
	}
	
	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------
	
	/**
	 * @return Zend_Log
	 */
	private static function getInstance() {
		if (is_null(self::$_logger)) {
			self::$_logger = Zend_Registry::get('logger');
		}
		
		return self::$_logger;
	}
}
