<?php 

class Example_ActualiteController extends Zend_Controller_Action
{
	public function detailAction() {
		$form = new Example_Form_ActualiteForm();
		
		Zend_Debug::dump($form);
		
		$this->view->form = $form;
	}
}