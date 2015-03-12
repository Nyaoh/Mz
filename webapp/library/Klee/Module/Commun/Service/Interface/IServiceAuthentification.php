<?php 

/**
 * @author AMORIN
 *
 */
interface Klee_Module_Commun_Service_Interface_IServiceAuthentification
{
    /**
	 * @return Zend_Auth
	 */
	public function getAuth();
	
	/**
	 * @return boolean True si l'utilisateur est connecté, false sinon
	 */
	public function hasIdentity();
	
	/**
	 * Retourne l'adaptateur utilisé pour l'authentification
	 *
	 * @return Zend_Auth_Adapter_DbTable
	 */
	public function getAuthAdapter();
	
	/**
	 * Connexion d'un utilisateur
	 *
	 * @param string $login	   Login de l'utilisateur
	 * @param string $password Mot de passe
	 * @throws Zend_Exception  Si une erreur est survenue lors de la connexion (identifiant incorrect ou autres erreurs)
	 */
	public function login($login, $password);
	
	/**
	 * Supprime les données stockées en session sur un utilisateur
	 */
	public function logout();
}