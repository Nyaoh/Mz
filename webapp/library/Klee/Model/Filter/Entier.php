<?php

/**
 * Filtre sur le domaine {Entier}.
 * 
 * @author amorin
 * @version 1.0
 */
class Klee_Model_Filter_Entier implements Zend_Filter_Interface
{
	/* (non-PHPdoc)
	 * @see Zend_Filter_Interface::filter()
	 */
	public function filter($value) {
	    if (is_null($value) || $value === '') {
	        return null;
	    }
	    
	    return $value;
	}
}
