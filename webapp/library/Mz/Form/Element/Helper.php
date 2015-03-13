<?php

/**
 * @author MZ
 */
class Mz_Form_Element_Helper
{
	/**
	 * Chargement de l'interface du domaine.
	 * 
	 * On commence par chercher si le plugin element existe dans l'arborescence du projet.
	 * Si ce n'est pas le cas, on va chercher dans le socle.
	 * 
	 * @param string $name	  Nom du domaine.
	 * @throws Zend_Exception
	 * @return Klee_Model_Domain_Abstract
	 */
	public static function getDomain($name) {
	    $class = 'Application_Model_Domains_' . $domain;

		if (! class_exists($class)) {
		    $class = 'Klee_Model_Domain_' . $domain;

		    if (! class_exists($class)) {
				throw new Zend_Exception("$class n'existe pas.");
		    }
		}
		
		return new $class();
	}
}
