<?php 

class Default_Form_Register extends Zend_Form
{
	public function init() {
		$this->addElement('text', 'FTP_LIBELLE_FR', array('label' => 'default.form.register.FTP_LIBELLE_FR'));
	}
}