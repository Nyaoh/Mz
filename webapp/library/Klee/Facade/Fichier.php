<?php

/**
 * Facade pour les services de fichier.
 * 
 * @author AMORIN
 *
 */
final class Klee_Facade_Fichier
{
	/**
	 * Masquage du constructeur privé.
	 */
	private function __construct() {
	}
	
	/**
	 * Retourne le service des fichiers (lecture et écriture).
	 * 
	 * @return Klee_Module_Commun_Service_Interface_IServiceFichier Service
	 */
	public static function getServiceFichier() {
		return Klee_Service_Manager::getService('ServiceFichier', 'Commun', TRUE);
	}
}
