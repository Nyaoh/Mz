<?php

/**
 * Classe regroupant les différentes méthodes concernant l'envoi de mail.
 *
 * @author ybaccala
 */
final class Klee_Util_Mail
{
	/**
	 * Masquage du constructeur public.
	 */
	private function __construct() {
	}
	
	/**
	 * Méthode d'envoi de mail.
	 * 
	 * @param string $expediteurMail Email expediteur
	 * @param string $expediteurNom  Nom expediteur.
	 * @param string $destinataire 	 Destinataire.
	 * @param string $sujet 		 Sujet.
	 * @param string $corps 		 Corps.
	 * @param array $paramsSujet 	 [OPTIONAL] Par défaut à {null}. Paramètre pour le sujet.
	 * @param array $paramsCorps 	 [OPTIONAL] Par défaut à {null}. Paramètre pour le corps.
	 */
	public static function envoiMail($expediteurMail, $expediteurNom, $destinataire, $sujet, $corps, $paramsSujet = null, $paramsCorps = null) {		
		if (! is_null($paramsCorps)) {
			$corps = vsprintf($corps, $paramsCorps);
		}
		
		if (! is_null($paramsSujet)) {
			$sujet = vsprintf($sujet, $paramsSujet);
		}
		
		$headers  = "MIME-Version: 1.0"."\n";
		$headers .= "Content-type: text/html; charset=UTF-8" . "\n";
		$headers .= "From: " . $expediteurNom . '<' . $expediteurMail . '>' . "\n";
		$headers .= "Reply-To: " . $expediteurMail . "\n";
		$headers .= "X-Mailer: PHP/" . phpversion() . "\n";
		if (! mail($destinataire, "=?UTF-8?B?" . base64_encode($sujet) . "?=", $corps, $headers)) {
			$error = error_get_last();
			throw new Exception($error['message']);
		}		
	}
}
