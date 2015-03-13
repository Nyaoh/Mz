<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initDoctype()
    {
        $this->bootstrap('view');
        $view = $this->getResource('view');
        $view->doctype('XHTML1_STRICT');
    }
    
    /**
     * Initialisation de la connexion à la BDD.
     */
    protected function _initDbAdapter () {
    	$options = $this->getOptions();
    	$dbConfig = new Zend_Config($options['database']);
    	$dbName = $options['database']['params']['dbname'];
    	$db = Zend_Db::factory($dbConfig);
    	 
    	if (getenv('APPLICATION_ENV') === 'testing') {
    		$db->dbName = $dbName . '_' . gethostname();
    	} else {
    		$db->dbName = $dbName;
    	}
    	Zend_Registry::set('dbAdapter', $db);
    }
}

