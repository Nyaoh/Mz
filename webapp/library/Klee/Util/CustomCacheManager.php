<?php 

/**
 * Classe de gestion du cache.
*
* @author rgrange
*/
final class Klee_Util_CustomCacheManager 
{
	private static $_cache = null;

	/**
	 * Charge le cache à partir de son Id. Renvoie false si l'id n'existe pas.
	 * @param string $id Identifiant de l'objet en cache.
	 * @return unknown_type Données cachées.
	 */
	public static function load($id) {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			return self::getCache()->load(APPLICATION_ENV . '_' . $id);
		} else {
			return null;
		}
	}
	
	/**
	 * Purge l'intégralité du cache.
     * @return boolean True if ok
	 */
	public static function purgeAllCache() {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			return self::getCache()->clean();
		}
		return true;
	}
	
	/**
	 * Charge le cache de la table de reference.
	 * @param string $tableName Nom de la table.
	 * @return array
	 */
	public static function loadReferenceTable($tableName) {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			return self::load(self::getReferenceTableCacheId($tableName));
		} else {
			return null;
		}
	}
	
	/**
	 * Ajoute au cache ces données de référence relative à la table spécifiée.
	 * @param unknown_type $data Données à cacher.
	 * @param string $id identifiant de la données à cacher.
	 */
	public static function save($data, $id) {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			self::getCache()->save($data, APPLICATION_ENV . '_' . $id);
		} else {
			return null;
		}
	}

	/**
	 * Sauvegarde en cache la liste de référence rattachée à la table et à la filère (eventuellement). 
	 * @param array $data Données au cacher.
	 * @param string $tableName No de la table concernée.
	 */
	public static function saveReferenceTable($data, $tableName) {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			self::save($data, self::getReferenceTableCacheId($tableName));
		} else {
			return null;
		}
	}
		
	/**
	 * Supprime du cache toutes les données relatives à la table.
	 * @param string $tableName Nom de la table.
	 */
	public static function removeTableCache($tableName) {
		if (Klee_Service_Proxy::getIsCacheEnabled()) {
			self::getCache()->remove(APPLICATION_ENV . '_' . self::getReferenceTableCacheId($tableName));
		}
	}	
		
	/**
	 * Renvoie le cache.
	 * @return Zend_Cache_Core
	 */
	private static function getCache() {
		if(is_null(self::$_cache)) {
			self::$_cache = Zend_Controller_Front::getInstance()->getParam('bootstrap')->getPluginResource('cachemanager')->getCacheManager()->getCache('database');
		}
		return self::$_cache;
	}
	
	/**
	 * Permet de générer la clé pour le cache de la table de réf et par filière.
	 * @param  string $tableListeReference nom de la table
	 * @return string nom de la variable dans le cache
	 */
	private static function getReferenceTableCacheId($tableListeReference) {
		return 'TABLE_' . $tableListeReference;
	}
}
