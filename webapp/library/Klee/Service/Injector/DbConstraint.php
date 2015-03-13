<?php

/**
 * Traite les erreurs qui remontent de la base.
 *
 * @author mdesquilbet
 * @package serviceLayer
 */
class Klee_Service_Injector_DbConstraint extends Klee_Service_Injector_Abstract 
{
	/* (non-PHPdoc)
	 * @see Application_Service_Injectors_Abstract::onCatch()
	 */
	protected function onCatch($object, $name, $arguments, Exception $exception) {
		unset($object);
		unset($arguments);
		unset($name);
		$previous = $exception->getPrevious();
		/**
		 * Informations sur associées à l'erreur lors de la dernière opération sur la base de données
		 * (viens de la previous exception qui est du type PDOException)
		 * 
		 * @var Array	
		 * 		0 => Code d'erreur SQLSTATE (un identifiant alphanumérique de cinq caractères défini dans le standard ANSI SQL)
		 * 		1 => Code d'erreur spécifique au driver (ici MySQL)
		 * 		2 => Message d'erreur spécifique au driver (ici MySQL) (il contient le nom de la contrainte)
		 * 
		 * @link http://developer.mimer.com/documentation/html_92/Mimer_SQL_Mobile_DocSet/App_Return_Codes2.html
		 * @link http://dev.mysql.com/doc/refman/5.5/en/error-messages-server.html
		 */
		$errorInfo = (get_class($previous) === 'PDOException') ? $previous->errorInfo : array(null);
		
		if ($errorInfo[0] == 23000) {
			// Integrity constraint violation (souvent lié à un UNIQUE INDEX)
			switch ($errorInfo[1]) {
				case 1062:
					// Dupplication d'une entrée
					$errorElements = explode("'", $errorInfo[2]);
					$constraintName = $errorElements[3];
					$errorCode = 'erreur.base.duplication';
					break;
				case 1451:
					// Suppression d'un élément référencé ailleurs
					$errorElements = explode("CONSTRAINT", $errorInfo[2]); // Le message est du type %CONSTRAINT `nom_de_la_contrainte`%
					$errorElements = explode("`", $errorElements[1]);
					$constraintName = $errorElements[1];
					
					$errorCode = 'elementUtilise.suppressionImpossible';
					break;
				default:
					// On renvoie l'exception initiale
					throw $exception;
			}
			$message = $constraintName . '.' . $errorCode;
			throw new Zend_Exception($message, 23000, $exception);
		} elseif ($errorInfo[0] == 22001 && $errorInfo[1] == 1406) {
			// Data exception - string data, right truncation. La donnée est trop longue pour la colonne
			$errorElements = explode("'", $errorInfo[2]);
			Klee_Util_CustomLog::error("erreur.base.donneeTropLongue\n" . print_r($errorInfo, 1));
			throw new Klee_Util_UserException('erreur.base.donneeTropLongue', array(), $errorElements[1]);
			
		}
		// Si rien ne s'est exécuté, on renvoie l'exception initiale
		throw $exception;
	}
}
