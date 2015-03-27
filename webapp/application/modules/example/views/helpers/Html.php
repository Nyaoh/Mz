<?php 

class Example_View_Helper_Html extends Example_View_Helper_AbstractHtml
{
	public function html() {
		return $this;
	}
	
	/**
	 * Retourne le rendu HTML du label d'un élément.
	 * 
	 * @param Klee_Form_Element $element Elément à rendre.
	 * @return string
	 */
	public function renderLabel(Klee_Form_Element $element) {
		return sprintf(
			'<label for="%s">%s</label>',
			$element->getName(),
			$element->getLabel()
		);
	}
	
	/**
	 * Retourne le rendu HTML d'un élément.
	 * 
	 * @param Klee_Form_Element $element Elément à rendre.
	 * @return string
	 */
	public function render(Klee_Form_Element $element) {
		$name = $element->getName();
		
		Zend_Debug::dump($this->getAttributeListAsString($element->getAttributeList()));
		
		return sprintf(
			'<input %s />',
			$this->getAttributeListAsString($element->getAttributeList())
		);
	}
}