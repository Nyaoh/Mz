<?php

/**
 * Classe regroupant les différents outils pour la conversion vers l'UTF8.
 *
 * @author jbourdin
 */
final class Klee_Util_MbString
{
	const ENCODING = 'utf-8';

	/**
	 * Masquage du constructeur public.
	 */
	private function __construct()
	{
	}

	/**
	 * EXTDEP: mbstring.
	 *
	 * @throws Zend_Exception
	 */
	public static function init()
	{
		if (!function_exists('mb_internal_encoding')) {
			throw new Zend_Exception("L'extension mbstring doit être chargée.");
		}
		mb_internal_encoding(self::ENCODING);
	}

	/**
	 * Normalise une chaine de caractères en supprimant espaces multiples
	 * et tout les caractères non aplha-numériques.
	 *
	 * @access public
	 * @static
	 * @param string $string La chaîne à normaliser
	 * @param string $replace Le caractère à utiliser
	 * @param bool $removeAccents true si on veut utiliser le removeAccents, false sinon (true par defaut)
	 * @return string
	 */
	public static function normalize($string, $replace = ' ', $removeAccents = true)
	{
		$string = self::convertEncoding($string);
		if($removeAccents) {
			$string = self::removeAccents($string);        	
        }
		$string = self::removeLigatures($string);
		$string = strtoupper($string);
		$string = trim(preg_replace('/[^A-Z0-9\']+/', $replace, $string));
		$string = preg_replace('/\s+/', $replace, $string);

		return $string;
	}

	/**
     * Supprime les ligatetures de la chaîne (oe et ae).
     *
     * @param string $string La chaîne dont on veut enlever les ligatures
     * @return string
     */
    public static function removeLigatures($string)
    {
        static $ligatures = null;

        if (!is_array($ligatures)) {
            $tmp = array(
                'ae' => "\xC3\xA6",
                'oe' => "\xC5\x93",
            );

            $ligatures = array(
                'from' => array_values($tmp),
                'to' => array_keys($tmp),
            );
        }

        if (!self::isUtf8($string)) {
            return $string;
        }

        return str_replace(
            $ligatures['from'],
            $ligatures['to'],
            $string
        );
    }


	/**
	 * Test si une chaîne est en UTF-8.
	 *
	 * @param string $string La chaine à tester
	 * @return boolean
	 */
	private static function isUtf8($string)
	{
	    return mb_check_encoding($string, 'UTF-8');
	}

	/**
	 * Converti en UTF-8 les chaînes qui ne le sont pas.
	 *
	 * @param string $string La chaîne à convertir
	 * @return string
	 */
	public static function convertEncoding($string)
	{
		if (!self::isUtf8($string)) {
			return utf8_encode($string);
		}

		return $string;
	}
}
