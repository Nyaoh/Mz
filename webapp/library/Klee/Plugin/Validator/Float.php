<?php

/**
 * Validateur pour les décimaux.
 * Modification des templates des messages d'erreur.
 * 
 * @author amorin
 * @version 1.0
 */
class Klee_Plugin_Validator_Float extends Zend_Validate_Float
{
	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
			self::INVALID   => 'validator.float.invalid',
			self::NOT_FLOAT => 'validator.float.notFloat'
	);
}
