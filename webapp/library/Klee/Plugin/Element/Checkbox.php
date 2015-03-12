<?php

/**
 * Element Checkbox principal
 * @author ehangard
 *
 */
class Klee_Plugin_Element_Checkbox extends Zend_Form_Element_Checkbox
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

	/* (non-PHPdoc)
	 * @see Zend_Form_Element::init()
	 */
	public function init() {
		if (! is_null($this->getDomain())) {
			$this->_domainInterface = $this->loadDomainInterface();
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
	
	/**
     * Set element label
     *
     * @param  string $label Le label
     * @return Zend_Form_Element
     */
    public function setLabel($label) {
        $keepValueTranslatorIsDisabled = $this->translatorIsDisabled();
    	// On désactive la traduction
    	$this->setDisableTranslator(true);
    	$this->_label = (string) $label;
    	// On ne remet pas la traduction si jamais on a fixé sur l'élément qu'on n'en voulait pas
    	$this->setDisableTranslator($keepValueTranslatorIsDisabled);
        return $this;
    }
}
