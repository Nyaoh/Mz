<?php

/**
 * Element Fichier.
 *
 * @author fconstantin
 */
class Klee_Plugin_Element_File extends Zend_Form_Element_File
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
	 * Get transfer adapter
	 *
	 * Lazy loads CustomTransferHttpAdapter transfer adapter when no adapter registered.
	 *
	 * @return Application_Util_CustomTransferHttpAdapter
	 */
	public function getTransferAdapter() {
		if (is_null($this->_adapter)) {
			$this->setTransferAdapter(new Klee_Util_CustomTransferHttpAdapter());
		}
		return $this->_adapter;
	}
}
