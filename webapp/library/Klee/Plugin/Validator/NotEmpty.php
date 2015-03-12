<?php

/**
 * Validateur non vide.
 * 
 * @author fconstantin
 * @version 1.0
 */
class Klee_Plugin_Validator_NotEmpty extends Zend_Validate_NotEmpty
{
    protected $_messageTemplates = array(
        Zend_Validate_NotEmpty::IS_EMPTY => 'validator.notEmpty.isEmpty',
        Zend_Validate_NotEmpty::INVALID  => 'validator.notEmpty.invalid',
    );
}
