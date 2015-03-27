<?php 

abstract class Example_View_Helper_AbstractHtml extends Zend_View_Helper_Abstract
{
	/**
	 * Retourne la liste des attributs sous forme de chaîne de caractères.
	 * 
	 * @param array $attributeList Liste des attributs de l'élément.
	 * @return string
	 */
	public function getAttributeListAsString(array $attributeList = array()) {
		$result = array();
		
		foreach ($attributeList as $key => $value) {
			$result[] = sprintf('%s="%s"', $key, $value);
		}
		
		return explode(' ', $result);
	}
}