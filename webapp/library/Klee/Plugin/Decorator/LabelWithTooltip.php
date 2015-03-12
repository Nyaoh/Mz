<?php
	
require_once 'Zend/Form/Decorator/Abstract.php';

/**
 * Classe pour rajouter un tooltip au label
 * Actuellement l'image du tooltip est fixé en dur...
 *  
 * @author ehangard
 *
 */
class Klee_Plugin_Decorate_LabelWithTooltip extends Zend_Form_Decorator_Label 
{
	/* (non-PHPdoc)
	 * @see Zend_Form_Decorator_Label::getLabel()
	 */
	public function getLabel()
    {
        $element = $this->getElement();
    	$translator = $element->getTranslator();
    	$title = $translator->translate(parent::getOption('tooltip'));
    	
        $baseUrl = Zend_Controller_Front::getInstance()->getBaseUrl();
        
        return parent::getLabel() . '&nbsp;<img style="vertical-align: middle;" src="' . $baseUrl . '/static/images/i-info.gif" title="' . $title . '" />';
    }
}
