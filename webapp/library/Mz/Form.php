<?php 

require_once 'Zend/Validate/Interface.php';

class Mz_Form implements Zend_Validate_Interface
{
	const ENCTYPE_URLENCODED = 'application/x-www-form-urlencoded';
    const ENCTYPE_MULTIPART  = 'multipart/form-data';
    
    public function __construct(array $options = null) {
    	if ($options !== null) {
    		$this->setOptions($options);
    	}
    	
    	$this->init();
    }
	
	/* (non-PHPdoc)
	 * @see Zend_Validate_Interface::getMessages()
	 */
	public function getMessages() {
		
	}
	
	/**
	 * Initialisation du formulaire (utilisé par les classes filles).
	 */
	public function init() {
		
	}
	
	/* (non-PHPdoc)
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value) {
		
	}
	
	/**
	 * Fixe les options à l'objet.
	 * 
	 * @param array $options
	 */
	public function setOptions(array $options) {
		// @TODO: not yet implemented.
	}
}