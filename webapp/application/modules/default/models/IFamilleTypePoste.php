<?php 

interface Default_Model_IFamilleTypePoste
{
	/**
	 * Retourne l'identifiant {FTP_ID} de la famille de type de poste.
	 * 
	 * @return int
	 */
	public function getId();
	
	/**
	 * Retourne le code {FTP_CODE} de la famille de type de poste.
	 * 
	 * @return string
	 */
	public function getCode();
	
	/**
	 * Retourne l'odre d'affichage {FTP_ORDRE_AFFICHAGE} de la famille de type de poste.
	 * 
	 * @return int
	 */
	public function getOrdreAffichage();
	
	/**
	 * Retourne le libellé {FTP_LIBELLE_?} de la famille de type de poste.
	 * 
	 * @return string
	 */
	public function getLibelle();
}