<?php

/**
 * Validateur non vide : modification de l'internationalisation.
 * 
 * @author fconstantin
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Plugin_Validator_Nom extends Zend_Validate_Alpha
{
	/**
     * @var array
     */
    protected $_messageTemplates = array(
        Zend_Validate_Alpha::INVALID      => 'alpha.validator.invalid',
        Zend_Validate_Alpha::NOT_ALPHA    => 'alpha.validator.noalpha',
        Zend_Validate_Alpha::STRING_EMPTY => 'alpha.validator.fieldRequired',
    );
        
    /**
     * Constructeur.
     * @param boolean $allowWhiteSpace True si les espaes sont autorisés.
     */
    public function __construct($allowWhiteSpace = false) {
    	parent::__construct($allowWhiteSpace);
    	self::$_filter = new Klee_Model_Filter_Nom($allowWhiteSpace);
    }
}
