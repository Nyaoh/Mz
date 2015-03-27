<?php 

class Example_Form_ActualiteForm extends Klee_Form
{
	public function init() {
		$aceTitreFr = new Klee_Form_Element_Text('ACE_TITRE_FR', array('domain' => 'LibelleLong', 'label' => 'actualite.champ.ACE_TITRE_FR', 'required' => true));
		Zend_Debug::dump($aceTitreFr);
		$this->add($aceTitreFr);
	}
}