<?php

/**
 * Helper pour les préfixes.
 * Préfixe utilisé pour les traductions.
 * <nom_module>.<nom_contrôleur>.{list;detail}
 * 
 * @author AMORIN
 *
 */
class Klee_Module_Commun_Controller_PrefixeTranslationHelper
{
	/**
	 * Retourne le préfixe à utiliser pour les traductions.
	 * Calculé automatiquement à partir du nom du module et du nom du contrôleur.
	 *
	 * @return string
	 */
	public static function getPrefixForTranslation($request) {
		$moduleName = strtolower($request->getModuleName());
	
		// Le nom du contrôleur se décompose de la manière suivante:
		// <nom_fonctionnel>-list
		// le préfixe pour les traductions, sera de la forme : {nomFonctionnel.list}
		$arrayControllerName = explode('-', $request->getControllerName());
		
		$pageType = '.' . $arrayControllerName[count($arrayControllerName) - 1] . '.';
	
		return $moduleName . '.' . $arrayControllerName[0] . implode('', array_map('ucfirst', array_splice($arrayControllerName, 1, -1))) . $pageType;
	}
}
