<?php

/**
 * L'interface qui définit les domaines utilisés par l'application.
 *
 * @author ehangard
 */
interface Klee_Model_Domain_Interface
{
	/**
	 * Met en forme une données pour l'affichage.
	 *
	 * @param array $data 		Données brutes.
	 * @param string $field 	Nom du champ à afficher.
	 * @param Zend_View $view	[OPTIONAL] Par défaut à {NULL}. Vue.
	 * @return Données formatées.
	 */
	public function formatData($data, $field, $view = null);
	
	/**
	 * Met en forme les boutons actions (éditer, supprimer, dupliquer)
	 *
	 * @param array $data Données brutes.
	 * @param string $field Nom du champ à afficher.
	 * @param array $arguments Les boutons à afficher avec les href correspondant
	 * @return Données formatées.
	 */
	public function formatDataAction($data, $field, array $arguments);
	
	/**
	 * Met en forme une donnée pour un affichage fixé en taille.
	 *
	 * @param array $data 		Données brutes.
	 * @param string $field 	Nom du champ à afficher.
	 * @param Zend_View $view	Vue
	 * @param Zend_View $size	Taille maximum à afficher
	 * @return Données formatées.
	 */
	public function formatDataReduced($data, $field, $view, $size);
	
	/**
	 * Met en forme une donnée pour l'affichage avec un lien qui entoure la donnée.
	 *
	 * @param array $data Données brutes.
	 * @param string $field Nom du champ à afficher.
	 * @param string $link La valeur a mettre dans le href
	 * @return Données formatées.
	 */
	public function formatDataWithLink($data, $field, $link);
	
	/**
	 * Les tris disponibles pour un champ.
	 *
	 * @param string $field [OPTIONAL] Par défaut à {NULL}. Nom du champ pour pouvoir modifier le tri plus précisément.
	 */
	//public function formatSort($field = null);
	public function formatSort($field);
	
    /**
     * Initialise un élément (validators, filters, decorators).
     * 
     * @param Zend_Form_Element $element Elément à adapter.
     */
    public function initElement($element);
    
	/**
	 * Initialise les validators pour l'élément du formulaire.
	 *
	 * @param Zend_Form_Element $element Elément sur lequel on veut appliquer le validateur
	 */
	public function initValidators($element);

	/**
	 * Initialise les filtres pour l'élément du formulaire.
	 *
	 * @param Zend_Form_Element $element Elément sur lequel on veut appliquer le filtre
	 */
	public function initFilters($element);

	/**
	 * Initialise les decorators pour l'élément du formulaire.
	 *
	 * @param Zend_Form_Element $element Elément sur lequel on veut appliquer le décorateur
	 */
	public function initDecorators($element);
}
