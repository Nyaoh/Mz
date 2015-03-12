<?php 

class Mz_Util_String
{
	public static function isNullOrEmpty($value) {
		return $value === null || $value === '';
	}
	
	public static function toCamelCase($string) {
		assert(self::isNullOrEmpty($string), '$string ne doit pas être null ou vide');
		
		$length = strlen($string);
		for ($i = 0; $i < $length; $i++) {
			
		}
	}
}