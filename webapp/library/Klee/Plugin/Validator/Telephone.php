<?php

/**
 * Classe permettant de valider un numéro de téléphone
 * 
 * @author ybaccala
 *
 */
class Klee_Plugin_Validator_Telephone extends Zend_Validate_Abstract
{
	const INVALIDE = 'telephoneInvalide';
	
	protected $_messageTemplates = array(
			self::INVALIDE => 'telephone.validator.invalid'
	);
	
	/* (non-PHPdoc)
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value)
	{
		$this->_setValue($value);
		
		// numéros étrangers
		$pattern = '#^\+[0-9 \-\.]{1,31}$#';
		if (preg_match($pattern, $value)) {
			return true;
		}
		
		// ex: 0147200001
		$pattern = '#^[0-9]{10}$#';
		if (preg_match($pattern, $value)) {
			return true;
		}
		
		//ex 0 800 23 56 89
		$str = str_replace(' ', '', $value);
		if (preg_match($pattern, $str)) {
			return true;
		}
		
		//ex 0.800.23.56.89
		$str = str_replace('.', '', $value);
		if (preg_match($pattern, $str)) {
			return true;
		}
		
		// ex: 01 47 20 00 01 ou 01.47.20.00.01
		$pattern = '#^0[1-9]([. ]?[0-9]{2}){4}$#';
		if (preg_match($pattern, $value)) {
			return true;
		}
		
		// ex: +33 1 47 20 00 01 ou +33.1.47.20.00.01
		$pattern = '#^[+]33[. ]?[0-9][. ]?([0-9]{2}[. ]?){4}$#';
		if (preg_match($pattern, $value)) {
			return true;
		}
		
		// ex: 0147 200 001 ou 0147.200.001
		$pattern = '#^[0-9]{4}[. ]?([0-9]{3}[. ]?){2}$#';
		if (preg_match($pattern, $value)) {
			return true;
		}
		
		$this->_error(self::INVALIDE);
		return false;
	}
}
