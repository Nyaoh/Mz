<?php 

/**
 * Classe utilitaire de getion des string. 
 * @author rgrange
 *
 */
class Klee_Util_String
{
	
	/**
	 * Teste si la chaine de caractères contient l'autre.
	 * @param string $stringToSeek Valeur à rechercher.
	 * @param string $stringToTest Chaine dans laquelle on recherche $stringToSeek.
	 * @return boolean
	 */
	public static function contains($stringToSeek, $stringToTest) {
		return stripos($stringToTest, $stringToSeek) !== false;
	}
	
	/**
	 * Supprimer les accents
	 *
	 * @param string $string chaîne de caractères avec caractères accentués
	 * @param string $encoding encodage du texte (exemple : utf-8, ISO-8859-1 ...)
	 */
	public static function removeAccents($string, $encoding='utf-8')
	{
		// transformer les caractères accentués en entités HTML
		$string = htmlentities($string, ENT_NOQUOTES, $encoding);
	
		// remplacer les entités HTML pour avoir juste le premier caractères non accentués
		// Exemple : "&ecute;" => "e", "&Ecute;" => "E", "Ã " => "a" ...
		$string = preg_replace('#&([A-za-z])(?:acute|grave|cedil|circ|orn|ring|slash|th|tilde|uml);#', '\1', $string);
	
		// Remplacer les ligatures tel que : Œ, Æ ...
		// Exemple "Å“" => "oe"
		$string = preg_replace('#&([A-za-z]{2})(?:lig);#', '\1', $string);
		// Supprimer tout le reste
		$string = preg_replace('#&[^;]+;#', '', $string);
	
		return $string;
	}
	
	/**
	 * Teste la terminaison de la chaine de caractères.
	 * @param String $haystack Chaine de caractères à tester..
	 * @param String $needle La chaine de caractère à rechercher.
	 * @return boolean True si $haystack se termine par $needle.
	 */
	public static function endsWith($haystack, $needle) {
	    return substr($haystack, -strlen($needle)) == $needle;
	}
	
	/**
	 * Teste le debut de la chaine de caractère.
	 * @param String $haystack Chaine de caractères à tester..
	 * @param String $needle La chaine de caractère à rechercher.
	 * @return boolean True si  $haystack commence par $needle.
	 */
	public static function startWith($haystack, $needle) {
		return substr($haystack, 0, strlen($needle)) == $needle;
	}
	
	/**
	 * @param string $string Chaîne à vérifier.
	 * @return boolean
	 */
	public static function isNullOrEmpty($string) {
		return (is_null($string) || empty($string)) && $string!=='0';
	}
	
	/**
	 * @param unknown_type $text
	 * @param unknown_type $length
	 * @return mixed
	 */
	public static function truncate($text, $length) {
		$length = abs((int)$length);
		if(strlen($text) > $length) {
			$text = preg_replace("/^(.{1,$length})(\s.*|$)/s", '\\1...', $text);
		}
		return($text);
	}
}
