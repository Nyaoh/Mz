<?php

/**
 * Domain factory.
 * Intégration des domaines provenant de l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_DomainFactory
{
	private static $_tableauDomaines = array();
	
	/**
	 * Pour charger la classe du domaine une seule fois
	 * @param string $domain Le nom du domaine
	 * @return Application_Model_Domains_Abstract
	 */
	public static function loadDomain($domain) {
		if (! array_key_exists($domain, self::$_tableauDomaines)) {
			$class = 'Application_Model_Domains_' . $domain;

			if (! class_exists($class)) {
			    $class = 'Klee_Model_Domain_' . $domain;
	
			    if (! class_exists($class)) {
					throw new Zend_Exception("$class n'existe pas.");
			    }
			}

			self::$_tableauDomaines[$domain] = new $class;
		}
		
		return self::$_tableauDomaines[$domain];
	}
}
