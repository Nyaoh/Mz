<?php

/**
 * Element Button principal.
 *
 * @author fconstantin
 */
class Klee_Plugin_Element_MultiCheckboxWithTooltip extends Klee_Plugin_Element_MultiCheckbox
{
	public $helper = 'formMultiCheckboxWithTooltip';
	
	/* (non-PHPdoc)
	 * @see Zend_Form_Element_Multi::isValid()
	 */
	public function isValid($value, $context = null) {
		if ($this->registerInArrayValidator()) {
			if (!$this->getValidator('InArray')) {
				$multiOptions = $this->getMultiOptions();
				$options      = array();
	
		    	// @codingStandardsIgnoreStart
				foreach ($multiOptions as $opt_value => $opt_tab) {
					// optgroup instead of option label
					if (is_array($opt_tab['label'])) {
						$options = array_merge($options, array_keys($opt_tab['label']));
					} else {
						$options[] = $opt_value;
					}
				}
				// @codingStandardsIgnoreEnd
				
				$this->addValidator(
						'InArray',
						true,
						array($options)
				);
			}
		}
		return parent::isValid($value, $context);
	}
}
