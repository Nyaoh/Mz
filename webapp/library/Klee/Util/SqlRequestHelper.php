<?php 

/**
 * Helper pour la gestion des requêtes SQL.
 * @author rgrange
 *
 */
class Klee_Util_SqlRequestHelper
{
	/**
	 * Methode permettant de retourner un et un seul objet (relève un exception sinon)
	 *
	 * @param array $result   Tableau de données
	 * @throws Zend_Exception 
	 * @return mixed
	 */
	public static function retourResultatUnique(array $result) {
		switch (count($result)) {
			case 0 :
				return null;
			case 1 :
				return array_pop($result);
			default :
				Klee_Util_CustomLog::error("retourResultatUnique()<br />" . print_r($result));
				throw new Klee_Util_UserException('resultatUnique');
		}
	}
}
