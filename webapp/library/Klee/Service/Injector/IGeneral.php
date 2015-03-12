<?php

/**
 * Interface pour les greffons de code sur les services.
 * Propose l'enchainement des greffons avec appel de la m�thode en bout de chaine.
 *
 * @author jbourdin
 * @package serviceLayer
 */
interface Klee_Service_Injector_IGeneral 
{
	/**
	 * Constructeur récupérant les injecteurs suivants et les options du service.
	 *
	 * @param array $injecteurArray tableau contenant les injecteurs suivants
	 * @param array $options options du service en fin de chaîne
	 */
	public function __construct($injecteurArray, $options);

	/**
	 * Méthode de traitement appelée par l'injecteur.
	 *
	 * @param string $object Service appelé
	 * @param string $name Méthode appelée
	 * @param array $arguments Argument d'appel
	 */
	public function process($object, $name, $arguments);

	/**
	 * Méthode appelant l'injecteur suivant ou le code réel en fin de chaîne.
	 *
	 * @return mixed
	 */
	public function getNext($object, $name, $arguments);

	/**
	 * Méthode indiquant si l'injecteur va s'exécuter.
	 *
	 * @return boolean
	 */
	public function willExecute($object, $name, $arguments);
}
