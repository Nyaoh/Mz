<?php

/**
 * Définit le domaine des checkBox utilisé par l'application.
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Domain_Checkbox extends Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface
{
    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	///TODO Faire en sorte de rendre la balise name paramètrable (ici on est en dur).
    	$check = '';
    	if ($data[$field] == 1){
    		$check = 'checked';
    	}
    	return '<input type="checkbox" name="checkbox[]" value="' . $data['ELT_ID'] . '" />';
    }
    
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}
}
