<?php

/**
 * Validateur pour les tailles de chaines de charactères.
 * 
 * @author ttran
 * 
 */
class Klee_Plugin_Validator_StringLength extends Zend_Validate_StringLength
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_StringLength::INVALID   => "regex.validator.invalid",
        Zend_Validate_StringLength::TOO_SHORT => "regex.validator.tooShort",
        Zend_Validate_StringLength::TOO_LONG  => "regex.validator.tooLong",
    ); 
}
