<?php 

class Klee_Form_Element
{
	protected static $_validOptionList = array(
		'label'	=> true
	);
	
	/**
	 * Liste des attributs de l'élément.
	 * 
	 * @var array
	 */
	protected $_attributeList = array();

	protected $_errorMessageList = array();
	
	/**
	 * Label de l'élément.
	 * 
	 * @var string
	 */
	protected $_label = null;
	
	/**
	 * Nom de l'élément.
	 * 
	 * @var string
	 */
	protected $_name = null;

	protected $_value;

	/**
	 * Constructeur de la classe.
	 * 
	 * @param string $name 		Nom de l'élément.
	 * @param array $optionList Liste des options de l'élément.
	 */
	public function __construct($name, array $optionList = array()) {
		$this->_name = $name;
		
		foreach ($optionList as $option => $value) {
			if (in_array($option, static::$_validOptionList)) {
				$this->_attributeList[$option] = $value;
			}
		}
	}
	
	/**
	 * Retourne la liste des attributs de l'élément.
	 * 
	 * @return array
	 */
	public function getAttributeList() {
		return $this->_attributeList;
	}

	public function getErrorMessageList() {
		return $this->_errorMessageList;
	}
	
	/**
	 * Retourne le label d'un élément.
	 * 
	 * @return string
	 */
	public function getLabel() {
		if (isset($this->_attributeList['label'])) {
			return $this->_attributeList['label'];
		}
		
		return null;
	}
	
	/**
	 * Retourne le nom de l'élément.
	 * 
	 * @return string
	 */
	public function getName() {
		return $this->_name;
	}

	public function setErrorMessageList($errorMessageList) {
		$this->_errorMessageList = $errorMessageList;
	}
}