<?php

/**
 * Filtre pour les noms : alpha + tiret + apostrophe
 *
 * @author ehangard
 * 
 * @TODO: à mettre à jour.
 */
class Klee_Model_Filter_Nom extends Zend_Filter_Alpha
{
	/* (non-PHPdoc)
	 * @see Zend_Filter_Interface::filter()
	 */
	public function filter($value)
	{
		$whiteSpace = $this->allowWhiteSpace ? '\s' : '';
		if (!self::$_unicodeEnabled) {
			// POSIX named classes are not supported, use alternative a-zA-Z match
			$pattern = '/[^a-zA-Z' . $whiteSpace . '\-]/';
		} else if (self::$_meansEnglishAlphabet) {
			//The Alphabet means english alphabet.
			$pattern = '/[^a-zA-Z'  . $whiteSpace . '\-]/u';
		} else {
			//The Alphabet means each language's alphabet.
			$pattern = '/[^\p{L}' . $whiteSpace . '\-]/u';
		}
		$value = str_replace('&#039;','-', $value);
		$value = str_replace('&quot;','', $value);
		return preg_replace($pattern, '', (string) $value);
	}
}
