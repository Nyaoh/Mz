<?php 

// require APPLICATION_PATH . '/modules/default/Decorator/SimpleInput.php';
// require APPLICATION_PATH . '/modules/default/Decorator/SimpleSelect.php';

class UtilisateurController extends Zend_Controller_Action
{
	public function detailAction() {
		// @TODO: regarder dans CustomForm pour trouver comment résoudre le problème des inclusions de décorateurs.
		
		$form = new Default_Form_UtilisateurForm();
// 		$form->addPrefixPath('Mz_Decorator', APPLICATION_PATH . '/modules/default/Decorator/');
// 		$form->addPrefixPaths(array('path' => APPLICATION_PATH . '/modules/default/Decorator/'));
		
		$foo = new Bar();
		
		if ($this->getRequest()->isPost()) {
			if ($form->isValid($this->getRequest()->getParams())) {
				die('ok');
			} else {
				
// 				Zend_Debug::dump($form->getMessages());
// 				die('nok');
			}
// 			die('post');
		}
		
		$this->view->form = $form;
	}
}

interface IFoo
{
	public function init();
}

abstract class Foo implements IFoo
{
	public function __construct() {
		$this->init();
	}
	
	public function init() {
		die('foo');
	}
}

class Bar extends Foo
{
	
}