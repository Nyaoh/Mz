<?php

/**
 * Définit le domaine des adresses utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Adresse extends Klee_Model_Domain_Abstract
{
    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Abstract::formatData()
     */
    public function formatData($data, $field, $view = null) {
    	if (is_array($data[$field])) {
    		// Il s'agit d'un tableau avec les champs d'adresse
    		$adresse = $data[$field];
    		$html = Klee_Model_Domain_Helper_DomainHelper::escapeData($adresse['ADR_ADRESSE_1'], $view);
    		if (isset($adresse['ADR_ADRESSE_2']) && $adresse['ADR_ADRESSE_2'] != '') {
    			$html .= '<br />' .  Klee_Model_Domain_Helper_DomainHelper::escapeData($adresse['ADR_ADRESSE_2'], $view);
    		}
    		if (isset($adresse['ADR_CODE_POSTAL']) && $adresse['ADR_CODE_POSTAL'] != '') {
    			$html .= '<br />' .  Klee_Model_Domain_Helper_DomainHelper::escapeData($adresse['ADR_CODE_POSTAL'], $view) . ' - ' . Klee_Model_Domain_Helper_DomainHelper::escapeData($adresse['ADR_LOCALITE'], $view);
    		} else {
    			// On a forcément une localité
    			$html .= '<br />' .  Klee_Model_Domain_Helper_DomainHelper::escapeData($adresse['ADR_LOCALITE'], $view);
    		}
    		return $html;
    	} else {
    		parent::formatData($data, $field);
    	}
    
    }
    
	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) {
		$element->addValidator('StringLength',true,array('max' => 50));
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}

	/**
	 * Met en forme une données pour le csv.
	 * 
	 * @param array $data Données brutes.
     * @param string $field Nom du champ à afficher.
     * @return Données formatées.
	 */
	public function formatDataCsv($data, $field) {
		if (is_array($data[$field])) {
			// Il s'agit d'un tableau avec les champs d'adresse
			$adresse = $data[$field];
			$html = $adresse['ADR_ADRESSE_1'];
			if (isset($adresse['ADR_ADRESSE_2']) && $adresse['ADR_ADRESSE_2'] != '') {
				$html .= ', ' . $adresse['ADR_ADRESSE_2'];
			}
			if (isset($adresse['ADR_ADRESSE_3']) && $adresse['ADR_ADRESSE_3'] != '') {
				$html .= ', ' . $adresse['ADR_ADRESSE_3'];
			}
			if (isset($adresse['ADR_CODE_POSTAL']) && $adresse['ADR_CODE_POSTAL'] != '') {
				$html .= ', ' . $adresse['ADR_CODE_POSTAL'] . ' - ' . $adresse['ADR_LOCALITE'];
			} else {
				// On a forcément une localité
				$html .= ', ' . $adresse['ADR_LOCALITE'];
			}
			return $html;
		} else {
			parent::formatData($data, $field);
		}
		
	}
}
