<?php

/**
 * Validateur pour les entiers.
 * Modification des templates des messages d'erreur.
 * 
 * @author amorin
 * @version 1.0
 */
class Klee_Plugin_Validator_Int extends Zend_Validate_Int
{
	protected $_messageTemplates = array(
			self::INVALID => 'validator.int.invalid',
			self::NOT_INT => 'validator.int.notInt'
	);
}
