<?php 

/**
 * Classe utilitaire de gestion de fichier xml
 * @author rgrange
 *
 */
class Klee_Util_Xml 
{	
	const NEW_LINE = "\r\n";
	const TAB = "\t";
	
	/**
	 * Ecrit le noeud XML dans le flux.
	 * @param &handle resource &fs.file.pointer
	 * @param string $nodeName Nom du noeud.
	 * @param string $nodeValue Valeur du noeud.
	 * @param int $indention Niveau d'indention.
	 */
	public static function writeNode(&$handle, $nodeName, $nodeValue, $indention) {
		fwrite($handle, self::getOpenBalise($nodeName, $indention));
		if (! Klee_Util_String::isNullOrEmpty($nodeValue)) {
			fwrite($handle, $nodeValue);
		}
		fwrite($handle, self::getCloseBalise($nodeName));
				
	}
	
	/**
	 * Revnoie la valeur XML d'un noeud.
	 * @param string $nodeName Nom du noeud.
	 * @param string $nodeValue Valeur du noeud.
	 * @param int $indention Niveau d'indention.
	 * @return string Noeud xml
	 */
	public static function getNode($nodeName, $nodeValue, $indention) {
		$node = self::getOpenBalise($nodeName, $indention);
		if (! Klee_Util_String::isNullOrEmpty($nodeValue)) {
			$node .= $nodeValue;
		}
		$node .= self::getCloseBalise($nodeName);
		return $node;
	}
	
	/**
	 * renvoie la balise d'ouverture d'un noeud.
	 * @param string $nodeName Nom du noeud XML.
	 * @param int $indention Niveau d'indention du noeud.
	 * @return string Noeud xml.
	 */
	public static function getOpenBalise($nodeName, $indention) {
		$node = '';
		for($i =0 ; $i < $indention; $i++) {
			$node .= self::TAB;
		}
		return $node . '<' . $nodeName . '>';
	}
	
	/**
	 * renvoie la balise de fermeture d'un noeud.
	 * @param string $nodeName Nom du noeud XML.
	 * @return string Noeud xml.
	 */
	public static function getCloseBalise($nodeName) {
		return '</' . $nodeName . '>'. self::NEW_LINE;
	}
}
