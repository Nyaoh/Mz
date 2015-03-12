<?php

/**
 * Domaine pour les entiers positifs.
 *
 * @author amorin
 * @version 1.0
 */
class Klee_Model_Domain_EntierPositif extends Klee_Model_Domain_Entier 
{
	protected $_minValue = 0;
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Entier::initOtherDecorators()
	 */
	protected function initOtherDecorators($element) {
		parent::initOtherDecorators($element);
		$element->setAttrib('maxlength', 10);
	}
}
