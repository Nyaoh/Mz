<?php

/**
 * 
 * @author ybaccala
 */
class Klee_Plugin_Validator_Regex extends Zend_Validate_Regex
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_Regex::INVALID    => 'regex.validator.invalid',
        Zend_Validate_Regex::NOT_MATCH  => 'regex.validator.notmatch',
        Zend_Validate_Regex::ERROROUS 	=> 'regex.validator.errorous',
    );
}
