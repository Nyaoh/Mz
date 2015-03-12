<?php 

class Default_Form_UtilisateurForm extends Zend_Form
{
	public function init() {
		$this->addPrefixPath('Default_', APPLICATION_PATH . '/modules/default/');
		
		$this->addElement('text', 'UTI_NOM', array('decorators' => array('SimpleInput'), 'info' => 'Message d\'information', 'label' => 'UTI_NOM', 'required' => true));
		$this->addElement('select', 'CIV_CODE', array('decorators' => array('SimpleSelect'), 'label' => 'CIV_CODE', 'required' => true));
		
		// @TODO: il faut passer par les éléments présents dans Klee_
// 		$civElement = new Zend_Form_Element_Select('CIV_CODE', array('decorators' => array('SimpleSelect'), 'label' => 'CIV_CODE', 'required' => true, 'setRegisterInArrayValidator' => false));
		$civList = array(
				array('CIV_CODE' => 'MR', 'CIV_LIBELLE' => 'Monsieur'),
				array('CIV_CODE' => 'MME', 'CIV_LIBELLE' => 'Madame')
		);
		
		$civOptionList = array();
		foreach ($civList as $civ) {
			$civOptionList[$civ['CIV_CODE']] = $civ['CIV_LIBELLE'];
		}
		$this->getElement('CIV_CODE')->setMultiOptions($civOptionList);
// 		$civElement->setMultiOptions($civOptionList);
// 		$this->addElement($civElement);
		
		$this->addElement('submit', 'buttonSave', array('label' => 'default.utilisateur.form.buttonSave'));
// 		$this->addElement('select', 'CIV_CODE', )
		
	}
}