<?php

/**
 * Element Select principal.
 *
 * @author fconstantin
 */
class Klee_Plugin_Element_Select extends Zend_Form_Element_Select
{
	/**
	 * L'interface du domaine.
	 *
	 * @var Application_Model_Domain_Abstract
	 */
	private $_domainInterface;

	/**
	 * Le domaine de l'élément
	 * @var string
	 */
	private $_domain;
	

    /*
     * (non-PHPdoc)
     * @see Zend_Form_Element_Multi::getMultiOptions()
     */
    public function getMultiOptions() {
        $this->_getMultiOptions();
        return $this->options;
    }
    
	/* (non-PHPdoc)
	 * @see Zend_Form_Element::init()
	 */
	public function init() {
		if (! is_null($this->getDomain())) {
			$this->_domainInterface = Klee_Plugin_Element_ElementHelper::loadDomainInterface($this->getDomain());
			$this->_domainInterface->initElement($this);
		}
	}

	/**
     * Load default decorators
     *
     * @return Zend_Form_Element
     */
    public function loadDefaultDecorators() {
    	parent::loadDefaultDecorators();

		if (! is_null($this->_domainInterface)) {
    		// Ajout des decorateurs
			$this->_domainInterface->initDecorators($this);
		}
		
		if (!$this->isRequired()) {
			if ($this->getDomain()) {
				$this->options = array(null => Zend_Registry::get('Zend_Translate')->translate('select.selection.facultative')) + $this->options;
			}
		} else if (Klee_Util_String::isNullOrEmpty($this->getValue())) {
			$this->options = array(null => Zend_Registry::get('Zend_Translate')->translate('select.selection.obligatoire')) + $this->options;
		}
		
		return $this;
    }

	/**
	 * Fixe le domaine.
	 *
	 * @param string $domain Le domaine
	 * @return Sfrr_Element_Text
	 */
	public function setDomain($domain) {
		$this->_domain = $domain;
		return $this;
	}


	/**
	 * Retourne le domaine.
	 *
	 * @return string
	 */
	public function getDomain() {
		return $this->_domain;
	}

	/**
	 * Chargement de l'interface du domaine.
	 *
	 * @return Application_Model_Domain_Abstract
	 */
	public function loadDomainInterface() {
		$class = 'Klee_Model_Domain_' . $this->getDomain();
		if (! class_exists($class)) {
			throw new Zend_Exception("$class n'existe pas.");
		}
		return new $class();
	}
	
    /*
     * (non-PHPdoc)
     * @see Zend_Form_Element_Multi::addMultiOption()
     */
    public function addMultiOption($option, $value = '') {
        $this->setDisableTranslator(true);
        $ret = parent::addMultiOption($option, $value);
        $this->setDisableTranslator(false);
        return $ret;
    }
    
	/**
     * Set element label
     *
     * @param  string $label Label de l'élément.
     * @return Zend_Form_Element
     */
    public function setLabel($label) {
        $this->setDisableTranslator(true);
    	$this->_label = (string) $label;
    	$this->setDisableTranslator(false);
    	return $this;
    }
}
