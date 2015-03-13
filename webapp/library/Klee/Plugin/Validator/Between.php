<?php

/**
 * Validateur pour les bornes.
 * Modification des templates des messages d'erreur.
 * 
 * @author amorin
 * @version 1.0
 */
class Klee_Plugin_Validator_Between extends Zend_Validate_Between
{
    protected $_messageTemplates = array(
        self::NOT_BETWEEN        => 'validator.between.notBetween',
        self::NOT_BETWEEN_STRICT => 'validator.between.notBetweenStrict'
    );
    
    public function __construct($options) {
        $this->setTranslator(Zend_Registry::get('Zend_Translate'));
        
        parent::__construct($options);
    }
}
