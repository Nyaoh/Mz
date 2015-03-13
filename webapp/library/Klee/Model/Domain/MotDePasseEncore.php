<?php

/**
 * Domaine pour confirmer un mot de passe.
 * 
 * @author AMORIN
 *
 */
class Klee_Model_Domain_MotDePasseEncore extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$validatorIdentical = new Klee_Plugin_Validator_Identical('UTI_MOT_DE_PASSE');
		$element->addValidator($validatorIdentical);
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Abstract::initFilters()
	 */
	public function initFilters($element) {
	}
}
