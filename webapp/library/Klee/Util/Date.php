<?php

/**
 * Classe regroupant les différentes méthodes concernant les dates.
 *
 * @author ehangard
 */
final class Klee_Util_Date
{
	private static $_recetteSimulation;
	
	/**
	 * Masquage du constructeur public.
	 */
	private function __construct() {
	}
	
	/**
	 * Définit la date simulée.
	 * 
	 * @param string $date Date courante simulée.
	 */
	public static function setCurrentDate($date) {
		if (!isset(self::$_recetteSimulation)) {
            self::$_recetteSimulation = Zend_Registry::get('recetteSimulation');
        }
	    self::$_recetteSimulation["debug"] = true;
	    self::$_recetteSimulation["dateSimulee"] = $date;
	}

	/**
	 * Retourne la date et l'heure courante au format SQL datetime.
	 *
	 * @param string $part Le paramètre pour déterminer si on veut une date ou un datetime
	 * @return string La datetime
	 */
	public static function getCurrentDatetime($part = null) {
        $debug = false;
		if (! $debug && $part === 'date') {
			$date = date('Y-m-d');
		} else {
			$date = date('Y-m-d H:i:s');
		}
		
		return $date;
	}

	/**
	 * Retourne la date courante au format SQL.
	 *
	 * @return string
	 */
	public static function getCurrentDate() {
		return self::getCurrentDatetime('date');
	}
	
	public static function addDays($date, $nbDays, $part = null) {
		if ($part === 'date') {
			$format = 'Y-m-d';
		} else {
			$format = 'Y-m-d H:i:s';
		}
		if($nbDays >= 0) {
			$nbDays = '+'.$nbDays;
		}
		 
		return date($format, strtotime( "$date  $nbDays day" ) );
	}
	
	public static function getFirstDayOfMonth($date) {
		return date('Y-m-01', strtotime($date));
	}
	
	/**
	 * Vérifie si il s'agit d'une plage
	 * @param string $dateDebut La date de début de l'intervalle
	 * @param string $dateFin La date de fin de l'intervalle
	 * @return boolean
	 */
	public static function isIntervalValid($dateDebut, $dateFin) {
		if (!isset($dateFin)) {
			return true;
		}
		return ($dateFin >= $dateDebut);
	}
	
	/**
	 * Vérifie si il s'agit d'une plage
	 * @param string $dateDebut La date de début de l'intervalle
	 * @param string $dateFin La date de fin de l'intervalle
	 * @return boolean
	 */
	public static function isIntervalValidStrict($dateDebut, $dateFin) {
		if (!isset($dateFin)) {
			return true;
		}
		return ($dateFin > $dateDebut);
	}
	
	/**
	 * Renvoie true si la date est comprise dans l'intervalle.
	 * @param date $date Date a tester.
	 * @param date $dateDebut Date de début de l'intervalle
	 * @param date $dateFin Date de fin de l'intervalle
	 * @return boolean True/falses
	 */
	public static function isDateInsideInterval($date, $dateDebut, $dateFin) {
		if ($dateDebut > $date) {
			return false;
		}
		if (is_null($dateFin)) {
			return true;
		}
		if ($dateFin < $date) {
			return false;
		}
		return true;
	}
	
	/**
	 * Vérifie si la date actuelle est comprise entre les 2 dates fournies en paramètres
	 * @param string $dateDebut La date de début de l'intervalle
	 * @param string $dateFin La date de fin de l'intervalle
	 * @return boolean
	 */
	public static function isTodayInsideInterval($dateDebut, $dateFin = null) {
		return self::isDateInsideInterval(self::getCurrentDate(), $dateDebut, $dateFin);
	}
	
	/**
	 * Renvoie une date formatée.
	 * @param Date $date Date à formatter.
	 * @return string Date formatée.
	 */
	public static function printDate($date) {		
		if($date != null && $date != '') {
	    	$dateFormatee = date_create_from_format('Y-m-d', $date);
			if($dateFormatee === false) {
	    		$dateFormatee = date_create_from_format('Y-m-d H:i:s', $date);
	    	}
	    	if($dateFormatee === false) {
	    		return 'Invalid date : ' . $date;
	    	}
	    	return date_format($dateFormatee, 'd/m/Y');
		}
	}
	
	/**
	 * Retourne une date au format US (Y-m-d).
	 * 
	 * @param string $date Date au format FR.
	 * @throws Klee_Util_UserException
	 * @return string
	 */
	public static function printDateToDatabaseFormat($date) {
	    $dateAsArray = explode('/', $date);
	    
	    if (false === checkdate($dateAsArray[1], $dateAsArray[0], $dateAsArray[2])) {
	        throw new Klee_Util_UserException('dateFormat.incorrect');
	    }
	    
	    return $dateAsArray[2] . '-' . $dateAsArray[1] . '-' . $dateAsArray[0];
	}
	
	public static function printDateWithDayLabel($date) {
		$jour = date('l');
		switch($jour) {
			case 'Monday': $jour = 'Lundi'; break;
			case 'Tuesday': $jour = 'Mardi'; break;
			case 'Wednesday': $jour = 'Mercredi'; break;
			case 'Thursday': $jour = 'Jeudi'; break;
			case 'Friday': $jour = 'Vendredi'; break;
			case 'Saturday': $jour = 'Samedi'; break;
			case 'Sunday': $jour = 'Dimanche'; break;
			default: $jour =''; break;
		}	
		$dateFormatee = date_create_from_format('Y-m-d H:i:s', $date);
		return $jour.' '.date_format($dateFormatee, 'd/m/Y');
	}
	
	/**
	 * Renvoie l'année de la date.
	 * @param string $date Date.
	 * @return string année
	 */
	public static function getYear($date) {
		$dateTab = explode('-', $date);
		return $dateTab[0];
	}
	
	/**
	 * Renvoie le libelle du mois de la date selon la langue de l'application.
	 * @param string $date Date.
	 * @return string mois de l'année en chaine.
	 */
	public static function getMonthString($date) {
	    $dateTab = explode('-', $date);
	    
	    $monthNum = (int)$dateTab[1];
	    $monthName = strtolower(date('F', mktime(0, 0, 0, $monthNum, 10)));
	    
	    $translator = Zend_Registry::get('Zend_Translate');
	    
		return $translator->translate('monthes.'.$monthName);
	}
}
