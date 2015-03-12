<?php

/**
 * Definit le domaine des fichiers utilisés par l'application.
 *
 * @author ehangard
 * @version 1.0
 */
class Klee_Model_Domain_Fichier extends Klee_Model_Domain_Abstract
{
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$maxFileSize = Zend_Registry::get('maxFileSize');
		$element->addValidator('Size', false, array('max' => $maxFileSize, 'messages' => 'erreur.message.fichier.tooBig'));
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initFilters()
	 */
	public function initFilters($element) {
	}
}
