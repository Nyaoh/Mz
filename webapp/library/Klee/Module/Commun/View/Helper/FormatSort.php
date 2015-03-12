<?php
/**
 *
 * @author ehangard
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * FormatSort helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Klee_View_Helper_FormatSort extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface $view
     */
    public $view;
        
    /**
     * Met en forme les données pour l'affichage.
     * 
     * @param string $field Nom du champ à afficher.
     * @param string $domain Domaine à utiliser pour l'affichage.
     * @return Zend_View_Helper_FormatData
     */
    public function formatSort($field, $domain) {
        $class = 'Klee_Model_Domain_' . $domain;
		if (!class_exists($class)) {
			throw new Zend_Exception("$class n'existe pas.");
		}
		$amdClass = new $class();
		
		return $amdClass->formatSort($field);
    }
	
    /**
     * Sets the view field.
     * 
     * @param Zend_View_Interface $view Interface de vue Zend
     */
    public function setView (Zend_View_Interface $view)
    {
        $this->view = $view;
    }
}
