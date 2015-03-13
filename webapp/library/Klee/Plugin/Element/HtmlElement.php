<?php

/**
 * Element HTML dans un Zend Form
 * 
 * A l'aide d'un array attribs, on peut passer des attributs qui vont nous permettre de définir le type d'élémnet html, la clé du texte pour le translate ainsi que les valeurs des champs qu'on veut remplacer.
 * 
 * 
 *  
 *  
 *  Cas : ouverture de div, on peut rajouter une id et une classe à la balise

 	$form->addElement('htmlElement', "divDebut", array('attribs' => array( 'balise' => '<div>',
	                                                                       'openOnly' => true,
	                                                                       'idBalise' => 'test',
	                                                                       'class' => 'toto' )));
	
 *	Cas : paragraph (remplace l’élément paragraph qui devient obsolète), c’est le cas par défaut en terme de balise 
	            
	$form->addElement('htmlElement', "paragraph", array('attribs' => array('text' => 'adhesion.paragraph',
	                                                                       'value' => array('1' => $raisonSocialeAdherent, '2' => $raisonSocialeOrganisme))
	            ));
	
 *	Cas : fermeture de div
	
	$form->addElement('htmlElement', "divFin", array('attribs' => array(   'balise' => '<div>',
	                                                                       'closeOnly' => true)));
	
	
	
	Dans ce cas, j’ouvre une div dont l’id est test et la classe toto. Puis je mets mon paragraph et je referme ma div.
 
 *   
 *   
 * 
 * @author ehangard
 *
 */
class Klee_Plugin_Element_HtmlElement extends Zend_Form_Element
{
    public $helper = 'htmlElement';
    
    /**
     * Set element label
     *
     * @param  string $label Le label
     * @return Zend_Form_Element
     */
    public function setLabel($label)
    {
        $this->setDisableTranslator(true);
    	$this->_label = (string) $label;
    	$this->setDisableTranslator(false);
        return $this;
    }

    /* (non-PHPdoc)
     * @see Zend_Form_Element::render()
     */
    public function render(Zend_View_Interface $view = null) {
   		if (! is_null($view)) {
            $this->setView($view);
        }

        $content = '';
        $decorator = new Zend_Form_Decorator_ViewHelper();
        $decorator->setElement($this);
		$content = $decorator->render($content);
        
        return $content;
    }
}
