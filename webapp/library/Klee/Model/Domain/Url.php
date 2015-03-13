<?php

/**
 * Définit le domaine des urls utilisé par l'application.
 *
 * @author ehangard
 */
class Klee_Model_Domain_Url extends Klee_Model_Domain_Abstract
{

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initValidators()
	 */
	public function initValidators($element) 
	{
		$options['pattern'] = '((https?:\/\/)?(www.)?(([a-zA-Z0-9-]){2,}\.){1,4}([a-zA-Z]){2,6}(\/([a-zA-Z-_/.0-9#:+?%=&;,]*)?)?)';
		$element->addValidator('UrlRegex', true, $options);
	}

	/* (non-PHPdoc)
	 * @see Application_Model_Domains_Abstract::initFilters()
	 */
	public function initFilters($element) {

	}

}
