<?php

/**
 * Helper pour les plugins de type élément.
 * 
 * @author AMORIN
 *
 */
class Klee_Plugin_Element_ElementHelper
{
	/**
	 * Chargement de l'interface du domaine.
	 * 
	 * On commence par chercher si le plugin element existe dans l'arborescence du projet.
	 * Si ce n'est pas le cas, on va chercher dans le socle.
	 * 
	 * @param string $domain			  Nom du domaine.
	 * @throws Zend_Exception
	 * @return Klee_Model_Domain_Abstract
	 */
	public static function loadDomainInterface($domain) {
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
