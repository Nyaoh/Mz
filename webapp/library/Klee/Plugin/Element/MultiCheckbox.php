<?php

/**
 * Element Button principal.
 *
 * @author fconstantin
 */
class Klee_Plugin_Element_MultiCheckbox extends Zend_Form_Element_MultiCheckbox
{
    /**
     * Le domaine de l'élément
     * @var string
     */
    private $_domain;
    
    /**
     * L'interface du domaine.
     *
     * @var Application_Model_Domain_Abstract
     */
    private $_domainInterface;
    
    /* (non-PHPdoc)
     * @see Zend_Form_Element_Multi::addMultiOption()
     */
    public function addMultiOption($option, $value = '') {
        $this->setDisableTranslator(true);
        $ret = parent::addMultiOption($option, $value);
        $this->setDisableTranslator(false);
        return $ret;
    }
    
    /**
     * Retourne le domaine.
     *
     * @return string
     */
    public function getDomain() {
    	return $this->_domain;
    }
    
    /* (non-PHPdoc)
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
    		$this->_domainInterface = $this->loadDomainInterface();
    		$this->_domainInterface->initElement($this);
    	}
    }
    
    /* (non-PHPdoc)
     * @see Zend_Form_Element_MultiCheckbox::loadDefaultDecorators()
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
     * Fixe le domaine.
     *
     * @param string $domain Le domaine
     * @return Sfrr_Element_Text
     */
    public function setDomain($domain) {
    	$this->_domain = $domain;
    	return $this;
    }
}
