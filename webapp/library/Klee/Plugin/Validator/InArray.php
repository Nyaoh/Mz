<?php

/**
 * Validateur {InArray}.
 * Est-ce que {$value} est comprise dans {$haystack}.
 * 
 * @author amorin
 * @version 1.0
 */
class Klee_Plugin_Validator_InArray extends Zend_Validate_InArray
{
	/**
	 * @var array
	 */
	protected $_messageTemplates = array(
        self::NOT_IN_ARRAY => 'validator.inArray.notInArray',
    );
}
