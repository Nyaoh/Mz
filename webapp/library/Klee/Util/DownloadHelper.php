<?php

/**
 * Helper pour le téléchargement des fichier.
 * 
 * @author AMORIN
 *
 */
class Klee_Util_DownloadHelper 
{
	/**
	 * @param string $content 	  Contenu à télécharger.
	 * @param string $name 	  	  Nom du fichier
	 * @param int $size 	  	  [OPTIONAL] Par défaut : NULL. Taille du fichier.
	 * @param string $description [OPTIONAL] Par défaut : NULL. Description du fichier.
	 * @param string $type 		  [OPTIONAL] Par défaut : File Transfer. Type du transfert.
	 */
	public static function executeDownload($content, $name, $size = null, $description = null, $type = 'File Transfer') {
		header('Content-Type: ' . $type);
		header('Content-Length: ' . $size);
		header('Content-Disposition: attachment; filename="' . utf8_decode($name) . '"');
		header('Content-Description: '. $description);
		
		header('Content-Transfer-Encoding: binary');
		header('Expires: 0');
		header('Cache-control: private, must-revalidate');
		header('Pragma: public');
		
		$fp = fopen("php://output", "w");
		if (is_resource($fp)) {
			fwrite($fp, $content);
			$strContent = ob_get_clean();
			echo $strContent;
			exit(0);
		}
		
		ob_end_clean();
		exit(1);
	}
}
