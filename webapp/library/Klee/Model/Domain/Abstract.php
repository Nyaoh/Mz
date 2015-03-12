<?php

/**
 * Définit les domaines utilisés par l'application de manière générique.
 *
 * @author ehangard
 * @version 1.0.0
 */
abstract class Klee_Model_Domain_Abstract implements Klee_Model_Domain_Interface 
{
	
	const MANDATORY_SUFFIX = ' *';

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::formatData()
	 */
	public function formatData($data, $field, $view = null) {
		if (! array_key_exists($field, $data)){
			throw new Zend_Exception("$field n'existe pas.");
		}
		return $data[$field];
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::formatDataAction()
	 */
	public function formatDataAction($data, $field, array $arguments) {
		$viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('viewRenderer');
	
		if (! array_key_exists($field, $data)) {
			throw new Zend_Exception("$field n'existe pas.");
		}

		$action = '';
		
		foreach ($arguments as $argument) {
			$action .= '
				<a href="' . $argument['url'] . '/idElement/' . $data[$field] . '" class="' . $argument['class'] . '" element="' . $data[$field] . '">
					<img src="' . $argument['picto'] . '" title="' . $argument['translate'] . '" alt="' . $argument['translate'] . '" />
				</a>
			';
		}
	
		$actionWithSpan = '<span>' . $action . '</span>';

		return $actionWithSpan;
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::formatDataReduced()
	 */
	public function formatDataReduced($data, $field, $view, $size) {
		$dataToReduce = $this->formatData($data, $field, $view);
	
		return $this->reduceWordsFromString($dataToReduce, $size);
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::formatDataWithLink()
	 */
	public function formatDataWithLink($data, $field, $link) {
		if (! array_key_exists($field, $data)) {
			throw new Zend_Exception("$field n'existe pas.");
		}
		if (! $link) {
			throw new Zend_Exception("Vous devriez utiliser la fonction formatData.");
		}
	
		if (array_key_exists($field, $data)) {
			return '<a href="' . $link . '" class="lienTable">' . $data[$field] . '</a>';
		} else {
			return '<a href="' . $link . '" class="lienTable">' . $data[$field] . '</a>';
		}
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::formatSort()
	 */
	public function formatSort($field) {
		unset($field);
		return array(
				"asSorting" => array(
						"desc", "asc", "asc"),
				"sClass" => array("left"));
	}

    /* (non-PHPdoc)
     * @see Klee_Model_Domain_Interface::initElement()
     */
    public final function initElement($element) {
        $element->addPrefixPath('Klee_Plugin_Validator', APPLICATION_PATH . '/../library/Klee/Plugin/Validator/', 'validate');
        $element->addPrefixPath('Klee_Plugin_Decorate', APPLICATION_PATH . '/../library/Klee/Plugin/Decorator/', 'decorator');
        
		// Ajout des validators
		$this->initValidators($element);

		// Ajout des filters
		$this->initFilters($element);
    }

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::initValidators()
	 */
	public function initValidators($element) {
		
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::initFilters()
	 */
	public function initFilters($element) {
		
	}

	/* (non-PHPdoc)
	 * @see Klee_Model_Domain_Interface::initDecorators()
	 */
	public final function initDecorators($element) {
		$element->setAttrib('requiredSuffix', utf8_encode(str_replace(' ', chr(160), self::MANDATORY_SUFFIX)));
		$this->initOtherDecorators($element);
	}
	

	/**
	 * Pour ajouter des décorateurs supplémentaires
	 *
	 * @param Zend_Form_Element $element Elément sur lequel on veut appliquer le décorateur
	 */
	protected function initOtherDecorators($element) {
		if ($element->getAttrib('tooltip')) {
		    
			Klee_Module_Commun_Controller_ControllerHelper::changeDecorators($element,
			array('Label' => array('decorator' => 'LabelWithTooltip', 'escape' => false, 'tooltip' => $element->getAttrib('tooltip'))));
		}
	}
	
	

	// ------------------------------------------------------------------------
	// Private methods
	// ------------------------------------------------------------------------
	
	/**
	 * Découpe les mots trop long
	 *
	 * @param string $string La chaine
	 * @param int $limit La limite
	 * @return string
	 */
	private function reduceWordsFromString($string, $limit) {
		$words = explode(" ", $string);
		foreach ($words as $word) {
			$wordLength = mb_strlen($word,'UTF-8');
			if ($wordLength >= $limit) {
				$nombreMots = ceil($wordLength / $limit);
				for ($i = 0; $i < ($nombreMots-1); $i++) {
					$offset = $i * $limit;
					$wordList[] = mb_substr($word, $offset, $limit, 'UTF-8');
				}
				$wordList[] = mb_substr($word, $offset, $limit, 'UTF-8');
			} else {
				$wordList[] = $word;
			}
		}
	
		return join(' ' ,$wordList);
	}
}
