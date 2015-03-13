<?php

/**
 * Définit le domaine des mots de passe utilisé par l'application.
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Domain_MotDePasse extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$element->addValidator('regex', false, array("/^.*(?=.{8,})((?=.*\d)|(?=.*[\`\~\!\@\#\$\%\^\&\*\(\)\_\-\+\=\{\}\[\]\\\|\:\;\"\'\<\>\,\.\?\/]))(?=.*[a-z])(?=.*[A-Z]).*$/"));
		$element->addValidator('StringLength', true, array('min' => 8));
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}
}
