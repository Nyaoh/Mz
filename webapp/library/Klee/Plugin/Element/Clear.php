<?php

/**
 * Element Hidden principal.
 *
 * @author ehangard
 */
class Klee_Plugin_Element_Clear extends Zend_Form_Element_Hidden
{
	/**
	 * (non-PHPdoc)
	 * @see Zend_Form_Element::loadDefaultDecorators()
	 */
	public function loadDefaultDecorators() {
		parent::loadDefaultDecorators();

		foreach ($this->getDecorators() as $decorator) {
			$decorator->setOption('class', 'clear');
		}
		
		return $this;
	}

	/**
     * Set element label
     *
     * @param  string $label Le label
     * @return Zend_Form_Element
     */
    public function setLabel($label) {
        $this->setDisableTranslator(true);
    	$this->_label = (string) $label;
    	$this->setDisableTranslator(false);
        return $this;
    }
}
