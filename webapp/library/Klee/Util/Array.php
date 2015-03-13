<?php 

/**
 * Classe utilitaire de getion des array. 
 * @author ehangard
 *
 */
class Klee_Util_Array
{
	
	/**
	 * Tri un tableau par ses clés récursivement
	 * 
	 * @param array &$arr Référence du tableau à trier
	 */
	public static function deepKsort(&$arr) { 
	    ksort($arr); 
	    foreach ($arr as &$a) { 
	        if (is_array($a) && !empty($a)) { 
	            self::deepKsort($a); 
	        } 
	    } 
	}
}
