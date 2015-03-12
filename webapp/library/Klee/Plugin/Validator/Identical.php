<?php

/**
 * 
 * @author ttran
 */
class Klee_Plugin_Validator_Identical extends Zend_Validate_Identical
{
    protected $_token = 'UTI_MOT_DE_PASSE';
    
    /**
     * Sets validator options
     *
     * @param  mixed $token
     * @return void
     */
    public function __construct($token)
    {
		$validatorIdentical = new Zend_Validate_Identical($token);
    }
    /**
     * Error messages
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_Identical::NOT_SAME      => "motdePasse.validator.notSame",
    	Zend_Validate_Identical::MISSING_TOKEN => "motdePasse.validator.missing",
    );
}
