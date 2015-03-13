<?php 

/**
 * Helper pour la gestion des domaines.
 * @author rgrange
 *
 */
class Klee_Model_Domain_Helper_DomainHelper
{
	/**
	 * Echappe les données.
	 * 
	 * @param mixed $data 		Données à echapper.
	 * @param Zend_View $view 	Vue.
	 * 
	 * @return mixed
	 */
	public static function escapeData($data, $view) {
		if (is_array($data)) {
			return $data;
		}
		if (empty($view->isExport) || ! $view->isExport) {
			return $view->escape($data);
		}
		return $data;
	}
}
