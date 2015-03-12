<?php 

interface Default_Service_IServiceFamilleTypePost
{
	/**
	 * 
	 * 
	 * @return Default_Model_IFamilleTypePoste[]
	 */
	public function findAllFamilleTypePoste();
	
	/**
	 * 
	 * 
	 * @param int $id Identifiant de la famille de type de poste.
	 * @return Default_Model_IFamilleTypePoste
	 */
	public function findFamilleTypePoste($id);
}