<?php 

/**
 * Classe permettant la gestion des formulaires de l'application.
 * 
 * @author AMORIN
 *
 */
abstract class Klee_Util_CustomForm extends Zend_Form
{
	/**
	 * Paramètre du formulaire.
	 * 
	 * @var array
	 */
	protected $_params;
	
	/**
	 * @var Zend_Translate
	 */
	protected $_translator;
	
	/**
	 * @param mixed $options Options lié à zend.
	 * @param array $params  Paramètres.
	 */
	public function __construct($options = null, array $params = array()) {
		$this->_params = $params;
		$this->_translator = Zend_Registry::get('Zend_Translate');
		parent::__construct($options);
	}
	
	/* (non-PHPdoc)
	 * @see Zend_Form::init()
	 */
	public function init() {
		// Ajout du lien vers les éléments de formulaire custom.
		$this->addPrefixPath('Klee_Plugin_Element', APPLICATION_PATH . '/../library/Klee/Plugin/Element/', 'element');

		$this->initForm();
	}

	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------

	/**
	 * Ajout des éléments au formulaire.
	 */
	abstract protected function initForm();
}
