<?php 

/**
 * Contexte.
 * 
 * @author AMORIN
 *
 */
class Klee_Util_Context
{
	/**  
	 * @var string Profil de l'utilisateur.
	 */
	private static $_profil = null;
	
	/**
	 * @var string Locale courante.
	 */
	private static $_locale = null;

	/**
	 * Retourne la liste des droitprofils de l'utilisateur.
	 * 
	 * @return array
	 */
	public static function getProfil() {
		if (is_null(self::$_profil)) {
			self::setProfil();
		}
		return self::$_profil;
	}
	
	/**
	 * @return string Locale courante.
	 */
	public static function getLocale() {
	    if (is_null(self::$_locale)) {
	    	self::setLocale();
	    }
		return strtoupper(self::$_locale);
	}
	
	/**
	 * Retourne {true} si l'utilisateur est connecté, {false} sinon.
	 * 
	 * @return boolean
	 */
	public static function hasIdentity() {
	    return Zend_Auth::getInstance()->hasIdentity();
	}
	
	/**
	 * Retourne TRUE si l'utilisateur a un des rôles $role.
	 * 
	 * @param array $roleList Liste des rôles à vérifier.
	 * @return boolean
	 */
	public static function hasRole(array $roleList) {
		foreach ($roleList as $role) {
			if (in_array($role, array(self::getProfil()))) {
				return true;
			}
		}
		return false;
	}
	
	/**
	 * @param string $locale [OPTIONAL] Par défaut à {NULL}. Locale courante.
	 */
	public static function setLocale($locale = null) {
		if ($locale === '00' || is_null($locale)) {
			self::$_locale = 'fr';
		} else {
			self::$_locale = $locale;
		}
	}	
	
	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------
	
	/**
	 * Fixe le profil de l'utilisateur.
	 */
	private static function setProfil() {
		if (Zend_Auth::getInstance()->hasIdentity()) {
			$identity = Zend_Auth::getInstance()->getIdentity();
			self::$_profil = $identity['PRO_CODE'];
		} else {
			self::$_profil = array('PUBLIC');
		}
	}
}
