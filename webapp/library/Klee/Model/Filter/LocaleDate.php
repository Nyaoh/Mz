<?php

/**
 * Convertit une date du format de la locale courant vers
 * le format MySQL.
 * L'utilisation de Zend_Date permet d'assurer la prise en charge de
 * la locale de l'utilisateur.
 *
 * @author fconstantin
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Filter_LocaleDate implements Zend_Filter_Interface
{
    const MYSQL_DATE = 'Y-m-d';

    /**
     * (non-PHPdoc)
     * @see Zend_Filter_Interface::filter()
     */
	public function filter($value) {
		
		if (!empty($value)) {
            
			$dateFormatee = date_create_from_format('d/m/Y', $value);
			if($dateFormatee === false) {
				$dateFormatee = date_create_from_format('dmY', $value);
			}
			if($dateFormatee === false) {
				return $value;
			}
			
            return date_format($dateFormatee, self::MYSQL_DATE);
        } 
        return null;
	}
}
