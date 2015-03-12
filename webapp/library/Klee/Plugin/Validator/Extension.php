<?php

/**
 * @author amorin
 */
class Klee_Plugin_Validator_Extension extends Zend_Validate_File_Extension
{
    /**
     * @var array
     */
    protected $_messageTemplates = array(
        self::FALSE_EXTENSION => 'erreur.message.fichier.extensionInvalid',
        self::NOT_FOUND       => "File '%value%' is not readable or does not exist",
    );
}
