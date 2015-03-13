<?php

/**
 * @author rgrange
 */
class Klee_Plugin_Validator_UrlRegex  extends Zend_Validate_Regex
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_Regex::INVALID      => 'url.regex.validator.invalid',
        Zend_Validate_Regex::NOT_MATCH    => 'url.regex.validator.notmatch',
        Zend_Validate_Regex::ERROROUS => 'url.regex.validator.errorous',
    );
}
