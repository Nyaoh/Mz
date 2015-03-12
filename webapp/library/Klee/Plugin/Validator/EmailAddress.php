<?php

/**
 * Validateur non vide : modification de l'internationalisation.
 * 
 * @author fconstantin
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Plugin_Validator_EmailAddress extends Zend_Validate_EmailAddress
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_EmailAddress::INVALID           	=> 'emailAdress.validator.invalid',
        Zend_Validate_EmailAddress::INVALID_FORMAT    	=> "emailAdress.validator.invalidFormat",
        Zend_Validate_EmailAddress::INVALID_HOSTNAME  	=> "emailAdress.validator.invalidHostName",
        Zend_Validate_EmailAddress::INVALID_MX_RECORD 	=> "emailAdress.validator.invalidMxRecord",
        Zend_Validate_EmailAddress::INVALID_SEGMENT   	=> "emailAdress.validator.invalidSegment",
        Zend_Validate_EmailAddress::DOT_ATOM          	=> "emailAdress.validator.dotAtom",
        Zend_Validate_EmailAddress::QUOTED_STRING     	=> "emailAdress.validator.quotedString",
        Zend_Validate_EmailAddress::INVALID_LOCAL_PART	=> "emailAdress.validator.invalidLocalPart",
        Zend_Validate_EmailAddress::LENGTH_EXCEEDED      => "emailAdress.validator.lengthExceeded",
    );
    
    /*
     * (non-PHPdoc)
     * @see Zend_Validate_EmailAddress::isValid()
     */
    public function isValid($value)
    {
		$isValid = parent::isValid($value);
		
		if(in_array(self::DOT_ATOM, $this->_errors)) {
			unset($this->_messages[self::DOT_ATOM]);
			unset($this->_messages[self::QUOTED_STRING]);
			$this->_error(self::INVALID_LOCAL_PART);
		}
		return $isValid;
    }
}
