<?php 

class IndexController extends Zend_Controller_Action
{
	public function indexAction() {
		$foo = new Default_Model_Foo(42);
	}
}