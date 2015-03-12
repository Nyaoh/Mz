<?php

/**
 * Validateur pour les SIREN.
 * 
 * @author AMORIN
 *
 */
class Klee_Plugin_Validator_Siren extends Zend_Validate_Abstract
{
	const INVALIDE = 'sirenInvalide';
	
	protected $_messageTemplates = array(
			self::INVALIDE => 'validator.siren.invalid'
	);
	
	/* (non-PHPdoc)
	 * @see Zend_Validate_Interface::isValid()
	 */
	public function isValid($value)
	{
		$this->_setValue($value);
		
		$pattern = '/^[0-9]{9}$/';
		if (preg_match($pattern, $value)) {
			$tmp = "";
			$somme = 0;
			for ($i = 0; $i < 9; $i ++) {
				if (($i % 2) === 1) {
					$tmp = $value [$i] * 2;
					if ($tmp > 9) {
						$tmp -= 9;
					}
				} else {
					$tmp = $value [$i];
				}
				$somme += $tmp;
			}
			
			if (($somme % 10) === 0) {
				return true;
			}
		}
		
		$this->_error(self::INVALIDE);
		return false;
	}
}