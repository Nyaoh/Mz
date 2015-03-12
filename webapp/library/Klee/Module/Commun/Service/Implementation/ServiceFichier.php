<?php 

/**
 * @author AMORIN
 *
 */
class Klee_Module_Commun_Service_Implementation_ServiceFichier
		implements Klee_Module_Commun_Service_Interface_IServiceFichier
{
    /* (non-PHPdoc)
     * @see Klee_Module_Commun_Service_Interface_IServiceFichier::getFichier()
     */
    public function getFichier($ficId) {
    	return Klee_Service_Broker_BrokerManager::getBroker($this, 'FICHIER')->get($ficId);
    }
    
    /* (non-PHPdoc)
     * @see Klee_Module_Commun_Service_Interface_IServiceFichier::getFichierImage()
     */
    public function getFichierImage($fiiId) {
    	return Klee_Service_Broker_BrokerManager::getBroker($this, 'FICHIER_IMAGE')->get($fiiId);
    }
}
