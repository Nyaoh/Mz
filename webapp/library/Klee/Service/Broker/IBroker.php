<?php

/**
 * Interface définissant les différentes méthodes utilisables par les broker
 *
 * @author AMORIN
 *
 */
interface Klee_Service_Broker_IBroker
{
	
	/**
	 * Déinit des paramètres relarif au broker.
	 * @param array $params paramètres.
	 */
	public function setParams(array $params);
	
	/**
	 * Initialisation du dbAdapter et de l'identifiant de l'utilisateur
	 *
	 * @param Zend_Db_Adapter_Abstract $dbAdapter	Adaptateur pour se connecter à la base de données
	 */
	public function init($dbAdapter);

	/**
	 * Retourne si l'objet est utilisé
	 *
	 * @param int $primaryKey	Clé primaire de l'objet à tester
	 * @param array $ignoreList	Liste des tables à ignorer
	 * @return boolean			True si l'objet est utilisé, false sinon
	 */
	public function isUsed($primaryKey, $ignoreList = null);

	/**
	 * Chargement des données d'une table à partir de sa clé primaire
	 *
	 * @param int $primaryKey	Clé primaire de la donnée à charger
	 * @return array			Objet contenant les données métiers
	 * @throws Zend_Exception	S'il y a plus d'un résultat dans la recherche
	 */
	public function get($primaryKey);

	/**
	 * Chargement des données d'une table (Récupération de toutes les données, dépend de {$maxRows}
	 *
	 * @param string $sort		Tri effectué sur les données
	 * @param int $maxRows 		(optional) Nombre de ligne maximum à charger, par défaut charge toutes les données
	 * @return array			Tableau d'objets
	 */
	public function getAll($sort, $maxRows = null);

	/**
	 * Chargement des données d'une table à partir d'une liste de critères
	 *
	 * Les critères Zend_Db_Select::LIMIT_COUNT et Zend_Db_Select::LIMIT_OFFSET peuvent
	 * être utilisée pour définir l'intervale de données à récupérer.
	 *
	 * @param array $criteria	Tableau des critères de recherche
	 * @param string $sort		Tri effectué sur les données
	 * @return array			Tableau d'objets
	 */
	public function getAllByCriteria(array $criteria, $sort);
	
	/**
	 * Chargement des données d'une table à partir de critères
	 *
	 * @param array $criteria	Le tableau de critères
	 * @return array 			Objet contenant les données métiers
	 * @throws Zend_Exception	S'il y a plus d'un résultat dans la recherche
	 */
	public function getByCriteria($criteria);

	/**
	 * Sauvegarde un objet
	 *
	 * @param array $object			Objet à sauvegarder
	 * @param array $naturalKeys 	Liste des champs composant une clef naturelle; ces champs sont insérés et participent à la clause Where pour les updates.
	 * @return mixed				La clef primaire correspondant à l'objet
	 */
	public function save($object, array $naturalKeys = array());

	/**
	 * Sauvegarde un tableau d'objets
	 *
	 * @param array $objects		Tableau d'objets
	 * @param array $naturalKeys 	Liste des champs composant une clef naturelle; ces champs sont insérés et participent à la clause Where pour les updates.
	 * @return array				Liste des identifiants sauvegardés en base.
	 */
	public function saveAll(array $objects, array $naturalKeys = array());
	
	/**
	 * Insertion ensembliste de données.
	 * 
	 * @param array $objects Liste des données à sauvegarder
	 */
	public function insertAll(array $objects, $bufferSize = 500);
	
	/**
	 * Met à jour un objet
	 * @param array $object	Données métiers
	 * @param array $criteria	Tableau de conditions
	 */
	public function update($object, array $criteria);
	
	/**
	 * Met à jour une liste d'objets
	 * 
	 * @param array $object	Tableau de données métiers
	 * @param array $criteria	Tableau de conditions
	 */
	public function updateAll(array $object, array $criteria);
	
	/**
	 * Supprime l'objet
	 *
	 * @param int $primaryKey	Clé primaire de la donnée à supprimer
	 */
	public function delete($primaryKey);

	/**
	 * Supprime l'objet
	 *
	 * @param array $criteria	Le tableau de critères
	 */
	public function deleteByCriteria($criteria);

	/**
	 * Supprime un tableau d'objets à partir de critères
	 *
	 * @param array $criteria	Tableau des critères de suppression
	 */
	public function deleteAllByCriteria(array $criteria);
	
	/**
	 * Chargement de certaines colonnes d'une table à partir d'une liste de critères
	 *
	 * Les critères Zend_Db_Select::LIMIT_COUNT et Zend_Db_Select::LIMIT_OFFSET peuvent
	 * être utilisée pour définir l'intervale de données à récupérer.
	 *
	 * @param array $criteria	Tableau des critères de recherche
	 * @param string $sort		Tri effectué sur les données
	 * @param array $columns	Les colonnes qu'on veut remonter
	 * @return array			Tableau d'objets
	 */
	public function getColumnsByCriteria(array $criteria, $sort, array $columns);
}
