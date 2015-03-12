<?php

/**
 * Radio avec des labels variables
 *
 * @author ehangard
 */
class Klee_Plugin_Element_RadioWithArray extends Klee_Plugin_Element_Radio
{
	/* (non-PHPdoc)
	 * @see Zend_Form_Element_Multi::addMultiOption()
	 */
	public function addMultiOption($option, $value = '') {
        $option  = (string) $option;
        
        $this->_getMultiOptions();
        
        // On récupère le translator
        $translator = $this->getTranslator();
        
        // On traduit le texte
    	$textTraduit = $translator->translate($value['attribs']['label']);
    	
    	// On boucle pour remplacer chaque %val% par sa value
	    foreach ($value['attribs']['value'] as $keyVal => $val) {
	    	$textTraduit = str_replace('%val'.$keyVal.'%', $val, $textTraduit);
	    }	
        
	    // On met dans le tableau des options, le texte traduit
        $this->options[$option] = $textTraduit;
		return $this;
    }
    
	/* (non-PHPdoc)
	 * @see Zend_Form_Element_Multi::addMultiOptions()
	 */
	public function addMultiOptions(array $options) {
        // On boucle sur les options. Le système est simplifié par rapport à celui par défaut. On n'a pas l'utilité du reste...
    	foreach ($options as $option => $value) {
			$this->addMultiOption($option, $value);
        }
        return $this;
    }
	
}
