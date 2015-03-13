<?php

/** Zend_Locale */
require_once 'Zend/Locale.php';

/** Zend_Translate_Adapter */
require_once 'Zend/Translate/Adapter.php';

/**
 * Adapter CSV
 *
 */
class Klee_Util_CustomTranslateAdapter extends Zend_Translate_Adapter_Csv 
{
	/**
	 * Translates the given string
	 * returns the translation
	 *
	 * @see Zend_Locale
	 * @param  string|array       $messageId Translation string, or Array for plural translations
	 * @param  string|Zend_Locale $locale    (optional) Locale/Language to use, identical with
	 *                                       locale identifier, @see Zend_Locale for more information
	 * @return string
	 */
	public function translate($messageId, $locale = null) {
		if (is_null($locale)) {
			$locale = $this->_options['locale'];
		}
	
		if (! Zend_Locale::isLocale($locale, true, false)) {
			if (! Zend_Locale::isLocale($locale, false, false)) {
				// language does not exist, return original string
				$this->_log($messageId, $locale);
			}
			$locale = new Zend_Locale($locale);
		}
	
		$locale = (string) $locale;
	
		// Gestion des messages d'erreur.
		if (is_array($messageId)) {
			foreach($messageId as $keyMessageArray => $unMessageArray) {
				foreach ($unMessageArray as $keyMessageValue => $unMessage) {
					$unMessage = $this->reduireLibelleErreurs($unMessage);
					if (isset($this->_translate[$locale][$unMessage])) {
						$unMessage = $this->_translate[$locale][$unMessage];
					}
					$messageId[$keyMessageArray][$keyMessageValue]=$unMessage;
				}
			}
			return $messageId;
		}
		
		if (Zend_Registry::get('Url_Locale') === '00') {
			return $messageId;
		}

        if (is_string($messageId)) {
        	if (isset($this->_translate[$locale][$messageId])) {
           		return $this->_translate[$locale][$messageId];
        	}
            return $this->reduireChaine($messageId, $locale);
        }
	}
	
	// ------------------------------------------------------------------------
	// Protected methods.
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see Zend_Translate_Adapter_Csv::_loadTranslationData()
	 */
	protected function _loadTranslationData($filename, $locale, array $options = array()) {
		$this->_data = $this->doLoadTranslationData($filename, $locale, $options);
		 
		if (Klee_Util_String::contains($locale, $filename)) {
			$tab = explode($locale, $filename);
			$customFile = Zend_Registry::get('customResourceContent') . $locale . end($tab);
			if (file_exists($customFile)) {
				foreach ($this->doLoadTranslationData($customFile, $locale, $options) as $key => $value) {
					foreach ($value as $k => $v) {
						$this->_data[$key][$k] = $v;
					}
				}
			}
		}
		 
		return $this->_data;
	}
	
	// ------------------------------------------------------------------------
	// Private methods.
	// ------------------------------------------------------------------------

	/**
	 * Chargement des traductions.
	 * 
	 * @param string $filename Nom du fichier de traduction.
	 * @param string $locale   Locale.
	 * @param array $options   Options
	 * @return array Data
	 */
	private function doLoadTranslationData($filename, $locale, array $options = array()) {
		$localData = array();
		if (Klee_Util_String::contains($locale, $filename)) {
			$options     = $options + $this->_options;
			$localFile = @fopen($filename, 'rb');
			if (! $localFile) {
				require_once 'Zend/Translate/Exception.php';
				throw new Zend_Translate_Exception('Error opening translation file \'' . $filename . '\'.');
			}

			$doublonRessource = array();
	
			while (($data = fgetcsv($localFile, $options['length'], $options['delimiter'], $options['enclosure'])) !== false) {
				if (substr($data[0], 0, 1) === '#') {
					continue;
				}
				 
				if (! isset($data[1])) {
					continue;
				}
				
				if (count($data) == 2) {
					if (isset($localData[$locale][$data[0]])) {
						$doublonRessource[] = $data[0];
					}
					$localData[$locale][$data[0]] = $data[1];
				} else {
					$singular = array_shift($data);
					if (isset($localData[$locale][$singular])) {
						$doublonRessource[] = $singular;
					}
					$localData[$locale][$singular] = $data;
				}
			}
	
			if (! empty($doublonRessource)) {
				$value = '';
				foreach ($doublonRessource as $ressource) {
					$value .= $ressource .', ';
				}
				throw new Exception('Resources en doublon : ' . $value);
			}
		}
		return $localData;
	}
	
	/**
	 * Parse la clef de resource.
	 * 
	 * @param string $key Clef de resource.
	 */
	private static function parseKey($key) {
		return strtolower($key);
	}
	
	/**
	 * Fonction pour réduire la chaine jusqu'à tomber sur une traduction existante.
	 * 
	 * @param string $messageId La cle à traduire
	 */
	private function reduireChaine($messageId, $locale) {
		//On explose la chaine de caractère en un tableau
		$explodeString = explode('.', $messageId);
		while (count($explodeString) > 1) {
			//On retire le premier élément du tableau à chaque passage dans la boucle
			array_shift($explodeString);
			//On reconstitue une chaine
			$newMessageId = implode('.', $explodeString);

			//On vérifie si celle-ci existe dans les fichiers de traduction
			if (is_string($newMessageId) && isset($this->_translate[$locale][$newMessageId])) {
				return $this->_translate[$locale][$newMessageId];
			}
		}
	
		return $messageId;
	}
}
