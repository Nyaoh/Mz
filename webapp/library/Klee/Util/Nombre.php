<?php

/**
 * Classe regroupant les différentes méthodes concernant les nombres.
 *
 * @author AMORIN
 */
final class Klee_Util_Nombre
{
	private static $_ko = 'Ko';
	private static $_mo = 'Mo';

	public static function printNombre($value) {
		if ($value >= 1000000) {
			return round($value / 1000000) . self::$_mo;
		}
		
		if ($value >= 1000) {
			return round($value / 1000) . self::$_ko;
		}
		
		return $value;
	}
}
