<?php

require_once 'Zend/View/Interface.php';

/**
 * Helper pour la vue détail.
 * 
 * @TODO: extraire les différents styles pour les mettre dans des classes.
 * 
 * @uses viewHelper Zend_View_Helper
 * 
 * @author AMORIN
 *
 */
class Klee_View_Helper_ListDetailView extends Zend_View_Helper_Abstract
{
    /**
     * Génére l'affichage pour la datatable.
     * 
     * @param string $prefixe prefixe.
     * @param boolean $isDelete
     * @return string
     */
    public function showDataTable() {
        $xhtml = '';
        
        $xhtml .= 	'<div style="width: 1125px;">'
        		.		'<table cellpadding="0" cellspacing="0" border="0" class="display gray-table" id="dashboard-table"></table>'
        		. 	'</div>';
        
        return $xhtml;
    }
    
    /**
     * Génére l'affichage d'un bouton d'ajout d'élément.
     *
     * @param array $url   Tableau contenant les informations sur l'url vers laquelle rediriger.
     * @param string $prefixe prefixe.
     * @return string
     */
    public function buttonCreate(array $url, $prefixe) {
        $xhtml = '';
        $xhtml .=  		'<div class="nouveau">'
        		.			'<div class="button">'
        		.				'<a class="buttonLink" href="' . $this->view->url($url, null, true) . '">'
        		.					$this->view->translate($prefixe . 'bouton.nouveau')
        		.				'</a>'
        		.			'</div>'
        		.		'</div>';
        return $xhtml;
    }
    
    /**
     * Génére l'affichage d'un bouton d'ajout d'élément.
     *
     * @param array $url   Tableau contenant les informations sur l'url vers laquelle rediriger.
     * @param string $prefixe prefixe.
     * @return string
     */
    public function deleteDialog($prefixe) {
    	$xhtml = '';
    	
    		$xhtml .=	'<div id="dialog-confirm" title="' . $this->view->translate($prefixe . 'dialog.title') . '" style="display: none;">'
    				.		'<p id="dialog-confirm-loading" style="display: none;">'
    				.			'<span class="ui-icon-loading" style="float:left; margin:0 7px 20px 0;"></span>'
    				.			$this->view->translate($prefixe . 'dialog.verification')
    				.		'</p>'
    	
    				.		'<p id="dialog-confirm-objectIsUsed" style="display: none;">'
    				.			$this->view->translate($prefixe . 'dialog.message.objectIsUsed')
    				.		'</p>'
    				.		'<p id="dialog-confirm-message">'
    				.			'<span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>'
    				.			$this->view->translate($prefixe . 'dialog.confirmation.suppression')
    				.		'</p>'
    				.	'</div>';
    	
    	return $xhtml;
    }
    
    /**
     * Génére l'affichage pour la datatable sans creation d'élement.
     *
     * @param array $url   Tableau contenant les informations sur l'url vers laquelle rediriger.
     * @param string $link Texte dans le lien.
     * @return string
     */
    public function showDataTableInForm() {
    	$xhtml = '';
    
    	$xhtml .= 	'<div style="width: 1125px;margin-left: -213px;">'
    			.		'<table cellpadding="0" cellspacing="0" border="0" class="display gray-table" id="dashboard-table"></table>'
    			.  	'</div>';
    
    	return $xhtml;
    }
}
