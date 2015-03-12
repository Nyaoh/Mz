<?php 

/**
 * Plugin ajoutant les traitements de langues.
 * 
 * @author AMORIN
 *
 */
class Klee_Plugin_Language extends Zend_Controller_Plugin_Abstract
{
    /**
     * Liste des langages disponibles.
     * 
     * @var array
     */
    protected static $_languageList = array(
    		'00' => 'fr_FR',
    		'fr' => 'fr_FR',
    		'en' => 'en_GB',
    		'es' => 'es_ES');
    
    /**
     * Retourne la liste des langages disponibles.
     * 
     * @return array
     */
    public static function getLanguageList() {
        return self::$_languageList;
    }
    
    /* (non-PHPdoc)
     * @see Zend_Controller_Plugin_Abstract::routeShutdown()
     */
    public function routeShutdown(Zend_Controller_Request_Abstract $request) {
        $lang = $request->getParam('sys-language', 'fr');
        $initialLang = $lang;
        
        Zend_Registry::set('Url_Locale', $lang);

        $lang = self::$_languageList[$lang];
        
        Zend_Registry::set('Zend_Locale', $lang);
        
        $translate= Zend_Registry::get('Zend_Translate');
        $options = $translate->getOptions();
        $translate->addTranslation($options["content"] . '/' . $lang . '/', $lang);
        
        $route = Zend_Controller_Front::getInstance()->getRouter();
        $route->setGlobalParam('sys-language', $initialLang);
    }
}