<?php

/**
 * Classe permettant d'instancier un GenericBroker pour une table donnée
 * 
 * @author AMORIN
 *
 */
final class Klee_Service_Broker_BrokerManager
{
	/**
	 * Retourne le broker pour une table donnée
	 * 
	 * @param Application_Service_Injectors_DbAdapter $service Service appelant
	 * @param string $tableName	Nom de la table dont on souhaite retourner le broker
	 * @return Klee_Service_Broker_IBroker
	 * @throws Zend_Exception	Si {$tableName} n'est pas trouvée
	 */
	public static function getBroker($service, $tableName)
	{
		// Création du broker (avec possibilité future de cache)
		$broker = new Klee_Service_Broker_GenericBroker($tableName);
		$broker->init($service->_dbAdapter);
		
		return $broker;
	}
}
