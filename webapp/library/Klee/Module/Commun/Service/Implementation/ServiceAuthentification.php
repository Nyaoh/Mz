<?php 

/**
 * @author AMORIN
 *
 */
abstract class Klee_Module_Commun_Service_Implementation_ServiceAuthentification
		implements Klee_Module_Commun_Service_Interface_IServiceAuthentification
{
    /**
     * Paramètres pour l'authentification.
     * 
     * @var array
     */
    protected $_authenticateParamList = array();
    
	/* (non-PHPdoc)
	 * @see Klee_Module_Commun_Service_Interface_IServiceAuthentification::getAuth()
	 */
	public function getAuth() {
		return Zend_Auth::getInstance();
	}

	/* (non-PHPdoc)
	 * @see Klee_Module_Commun_Service_Interface_IServiceAuthentification::hasIdentity()
	 */
	public function hasIdentity() {
		return $this->getAuth()->hasIdentity();
	}

	/* (non-PHPdoc)
	 * @see Klee_Module_Commun_Service_Interface_IServiceAuthentification::getAuthAdapter()
	 */
	public function getAuthAdapter() {
	    $this->_authenticateParamList = Zend_Registry::get('authenticateParamList');
	    
		$authAdapter = new Zend_Auth_Adapter_DbTable($this->_dbAdapter);
		$authAdapter->setTableName($this->_authenticateParamList['tableName'])
					->setIdentityColumn($this->_authenticateParamList['identityColumn'])
					->setCredentialColumn($this->_authenticateParamList['credentialColumn']);
		return $authAdapter;
	}

	/* (non-PHPdoc)
	 * @see Klee_Module_Commun_Service_Interface_IServiceAuthentification::login()
	 */
	public function login($login, $password) {
		if ($this->hasIdentity()) {
			return true;
		}

		$authAdapter = $this->getAuthAdapter();
		$authAdapter->setIdentity($login)
					->setCredential(sha1($password));
		$result = $this->getAuth()->authenticate($authAdapter);
		$code = $result->getCode();

		switch ($code) {
			case -3:
				$fieldName = $this->_authenticateParamList['credentialColumn'];
				$msg = "authentification.erreur.message.motDePasseIncorrect";
				break;
			case -1:
				$fieldName = $this->_authenticateParamList['identityColumn'];
				$msg = "authentification.erreur.message.nomUtilisateurIncorrect";
				break;
			case 1:
				// Possible vérification sur la validité du compte.
				if ($this->isValid($login)) {
					// Récupération des données sur l'utilisateur (données stockées dans la base)
					$user = $authAdapter->getResultRowObject();
					
					$this->saveUserInSession($user);
					return $this->saveUserInSession($user);
				} else {
					$this->getAuth()->clearIdentity();
					$fieldName = $this->_authenticateParamList['identityColumn'];
					$msg = "authentification.erreur.message.compteInvalide";
					break;
				}
			default:
				// Regroupe les cas suivants :
				// FAILURE / FAILURE_IDENTITY_AMBIGUOUS / FAILURE_UNCATEGORIZED
				$fieldName = $this->_authenticateParamList['identityColumn'];
				$msg = "authentification.erreur.message.nomUtilisateurIncorrect";
				break;
		}
		throw new Klee_Util_UserException($msg, array(), $fieldName);
	}

	/* (non-PHPdoc)
	 * @see Klee_Module_Commun_Service_Interface_IServiceAuthentification::logout()
	 */
	public function logout() {
		$this->getAuth()->clearIdentity();
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------

	/**
	 * Retourne la liste des droits pour un utilisateur donné.
	 *
	 * @param int $id Identifiant de l'utilisateur.
	 * @return array
	 */
	abstract protected function getDroitByUtiId($id);
	
	/**
	 * Retourne {TRUE} si le compte est valide, {FALSE} sinon.
	 * 
	 * @param null|string $login	[OPTIONAL] Par défaut : NULL. Identifiant de l'utilisateur.
	 * @return boolean
	 */
	protected function isValid($login = null) {
		return true;
	}
	
	/**
	 * Sauvegarde en session les données sur l'utilisateur
	 *
	 * @param null|object $user	[OPTIONAL] Par défaut : NULL. Données sur l'utilisateur.
	 * @return boolean
	 */
	abstract protected function saveUserInSession($user = null);
}
