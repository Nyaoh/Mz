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
class Klee_View_Helper_FormatData extends Zend_View_Helper_Abstract
{
    /**
     * @var Zend_View_Interface $view
     */
    public $view;
        
    /**
     * Met en forme les données pour l'affichage.
     * 
     * @param array $data Tableau de données.
     * @param string $field Nom du champ à afficher.
     * @param string $domain Domaine à utiliser pour l'affichage.
     * @return Zend_View_Helper_FormatData
     */
    public function formatData($data, $field, $domain) {
        $class = Klee_Model_Domain_DomainFactory::loadDomain($domain);
		
        if (substr($field, -8) === '_LIBELLE') {
            $field .= '_' . Klee_Util_Context::getLocale();
        }
        
		return $class->formatData($data, $field, $this->view);
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
