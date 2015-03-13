<?php
/**
 *
 * @author ehangard
 * @version 
 */
require_once 'Zend/View/Interface.php';
/**
 * FormatData helper
 *
 * @uses viewHelper Zend_View_Helper
 */
class Klee_View_Helper_FormatDataAction extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface $view
     */
    public $view;
        
	
    /**
     * Met en forme les boutons actions (éditer, supprimer, dupliquer)
     * 
     * @param array $data Tableau de données.
     * @param string $field Nom du champ à afficher.
     * @param string $domain Domaine à utiliser pour l'affichage.
     * @param array $arguments Les boutons à afficher avec les href correspondant
     * @return Zend_View_Helper_FormatData
     */
    public function formatDataAction( $data, $field, $domain, array $arguments ) {
        $class = Klee_Model_Domain_DomainFactory::loadDomain($domain);
		
		return $class->formatDataAction($data, $field, $arguments);
    }
    
    /**
     * Sets the view field.
     * 
     * @param Zend_View_Interface $view Interface de vue Zend
     */
    public function setView (Zend_View_Interface $view) {
        $this->view = $view;
    }
}
