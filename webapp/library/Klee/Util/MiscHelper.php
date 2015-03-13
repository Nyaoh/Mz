<?php

/**
 * Helper contenant diverses fonctions
 * 
 * @author smbape
 *
 */
class Klee_Util_MiscHelper {

	/**
	 * Values of $target are updated with values of $src. Arguments that are undefined are ignored..
	 *
	 * @param array $target An array that will receive the new properties
	 * @param array &$src An array containing properties to update
	 * @return array tableau contenant les propriétes mises à jour
	 */
	public static function arrayUpdate($target, &$src) {
		foreach(array_keys($target) as $k) {
			if (isset($src[$k])) {
				$target[$k] = $src[$k];
			}
		}
		
		return $target;
	}

	/**
	 * Values of $target are extended with values of $src
	 *
	 * @param array $target An array that will receive the new properties
	 * @param array &$src An array containing properties to update
	 * @return array tableau contenant les propriétes mises à jour
	 */
	public static function arrayExtend($target, &$src) {
		foreach(array_keys($src) as $k) {
			$target[$k] = $src[$k];
		}
		
		return $target;
	}
	
	/**
	 * Replace place holders
	 *
	 * @param string $str string
	 * @param array $args arguments
	 * @return string
	 */
	public static function vmprintf($str, $args = null)
	{
		if (is_null($args)) {
			return;
		}
		
		if (is_object($args)) {
			$args = get_object_vars($args);
		}

		if (!self::isAssoc($args)) {
			return vsprintf($str, $args);
		}
		
		$newStr = $str;
		
		foreach($args as $key => &$value) {
			$newStr = str_replace('%'.$key.'%', $value, $newStr);
		}
		
		return $newStr;
	}
	
	/**
	 * Retourne 'http://' si le lien ne dispose pas de scheme
	 * 
	 * @param string $url url
	 * @return string
	 */
	public static function getUrlDefaultScheme($url) {
		$parsedUrl = parse_url($url);
		if (isset($parsedUrl['scheme'])) {
			$scheme = '';
		} else {
			$scheme = 'http://';
		}
		
		return $scheme;
	}
	
	/**
	 * Translate and replace plca holders
	 * 
	 * @param Zend_View &$view vue contenant le traducteur
	 * @param string $str texte à traduire
	 * @param array $args placeholders
	 */
	public static function translate(Zend_View &$view, $str, $args = null) {
		return self::vmprintf($view->translate($str), $args);
	}
	
	/**
	 * Return true if array is an associative array
	 * 
	 * @param array &$arr array
	 * @return boolean
	 */
	private static function isAssoc(array &$arr) {
	    return array_keys($arr) !== range(0, count($arr) - 1);
	}
}
