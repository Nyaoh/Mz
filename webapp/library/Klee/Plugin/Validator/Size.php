<?php

/**
 * @author amorin
 */
class Klee_Plugin_Validator_Size extends Zend_Validate_File_Size
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
    		self::TOO_BIG   => 'size.validator.tooBig',
    		self::TOO_SMALL => 'size.validator.tooSmall',
    		self::NOT_FOUND => 'size.validator.notFound',
    );
}
