<?php
/**
 * Helper pour les exports
 * 
 * @author ehangard
 *
 */
class Klee_Util_ExportHelper 
{
	/**
	 * Exporte en CSV des données.
	 * 
	 * @param array $dataList Données à exporter.
	 */
	public static function makeCsvExport($dataList, $fileName) {
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=\"" . utf8_decode($fileName) . "-export.csv\";");
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-control: private, must-revalidate');
		header("Pragma: public");

		ob_start();
		
		$fp = fopen("php://output", "w");
		if (is_resource($fp)) {
			foreach ($dataList as $data) {
				fputcsv($fp, $data, ';', '"');
			}
			$strContent = ob_get_clean();

			$intLength = mb_strlen($strContent);
				
			// length
			header('Content-Length: ' . $intLength);
			echo $strContent;
			exit(0);
		}
		ob_end_clean();
		exit(1);
	}
		
	/**
	 * Permet de préparer l'export et appelle l'export
	 * 
	 * @param array $datas					Les lignes de données du csv
	 * @param array $enteteColonnes			La ligne des entêtes de colonnes
	 * @param string $classname				Le nom de la classe qui servira de titre
	 * @param Zend_View_Interface $view 	Vue courante.
	 * @param array $critere 				Critere de recherche.
	 * @param boolean $isTranslateEntete 	True si on traduit les entêtes
	 */
	public static function prepareExport ($datas, $enteteColonnes, $classname, $view, $critere = array(), $isTranslateEntete = true) {
		$view->isExport = true;
				
		$translator = Zend_Registry::get('Zend_Translate');
		
		$datasExport = array();
		
		$nbrColonnes = count($enteteColonnes);
		$inc = 1;
		$colonneAction = false;
		
		foreach ($enteteColonnes as $keyEnteteColonne => $enteteColonne) {
			if( strpos($enteteColonne, 'action') && $inc === $nbrColonnes ) {
				$colonneAction = $keyEnteteColonne;
				continue;
			}
			$inc++;
			
			$enteteColonnesCsv[] = utf8_decode($isTranslateEntete ? $translator->translate($enteteColonne) : $enteteColonne);
		}

		
		$datasExport[] = $enteteColonnesCsv;
		
		foreach( $datas as $uneLigneData) {
			if($colonneAction) {
				unset($uneLigneData[$colonneAction]);
			}
			$dataFormatForCsv = array();
			foreach($uneLigneData as $data) {
				$dataFormatForCsv[] = html_entity_decode(utf8_decode($data),ENT_QUOTES);
			}			
			$datasExport[] = $dataFormatForCsv;
		}
		
		if (isset($classname[1])) {
			$classname = substr($classname[1], 0, -10);
		} else {
			$classname = substr($classname[0], 0, -10);
		}
		
		$critereList = array();
		foreach($critere as $line) {
			$l =array();
			foreach($line as $value) {
				 $l[] = html_entity_decode(utf8_decode($value),ENT_QUOTES);
			}
			$critereList[] = $l;
		} 
		
		self::printExport($datasExport,$translator->translate($classname . '.titre'), false, $critereList);
		 
	}
	
	// ------------------------------------------------------------------------
	// private methods.
	// ------------------------------------------------------------------------
	
	/**
	 * Génère l'export
	 * @param array $aryData	Les données à mettre dans le csv
	 * @param string $strName	Le type de fichier
	 * @param boolean $bolCols	false : la première ligne des données est composée des entêtes; true : les entêtes sont les clés des données
	 */
	private static function printExport($aryData = array(), $strName = "csv", $bolCols = true, $critere = array())
	{
		if (!is_array($aryData) || empty($aryData)) {
			exit(1);
		}

		// header
		header('Content-Description: File Transfer');
		header('Content-Type: text/csv; charset=utf-8');
		header("Content-Disposition: attachment; filename=\"" . utf8_decode($strName) . "-export.csv\";");
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-control: private, must-revalidate');
		header("Pragma: public");

		if ($bolCols) {
			$aryCols = array_keys($aryData[0]);
			array_unshift($aryData, $aryCols);
		}
		
		ob_start();

		$fp = fopen("php://output", "w");
		if (is_resource($fp)) {
			foreach($critere as $line) {
				fputcsv($fp, $line, ';', '"');
			}
			foreach ($aryData as $aryLine) {
				fputcsv($fp, $aryLine, ';', '"');
			}
			$strContent = ob_get_clean();
	
			$strContent = preg_replace('/^ID/', 'id', $strContent);
	
			$intLength = mb_strlen($strContent);
			
			// length
			header('Content-Length: ' . $intLength);
			echo $strContent;
			exit(0);
		}
		ob_end_clean();
		exit(1);
	}
}
