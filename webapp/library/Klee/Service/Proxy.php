<?php
/**
 * Classe de gestion des services : doit être instanciée en lieu et place de la classe métier cible.
 * Chaque service doit avoir une interface et une implémentation,
 * le manager permet de faire le lien entre l'interface et l'implémentation.
 * L'appel des méthodes de l'interface déclenche l'éxécution de greffons de code pour ajouter des fonctionnalités transverses
 *
 * @author jbourdin
 * @package serviceLayer
 */
final class Klee_Service_Proxy
{    
    /**
     * Activation du cache pour les services.
     *
     * @var boolean
     */
    private static $_isCacheEnabled;
    
    /**
     * Activation du profiler de requetes.
     * 
     * @var boolean
     */
    private static $_isDbProfilerEnabled;
    
    /**
     * Dépendance injectée : l'objet du service.
     *
     * @var object
     */
    private $_object;
    
    /**
     * Nom du service appelé.
     *
     * @var string
     */
    private $_serviceName;

    /**
     * Constructeur : instancie le conteneur ainsi que l'objet service contenu.
     *
     * @param string $serviceClassName 	nom de la classe dépendante
     * @param string $serviceName 		nom du service
     */
    public function __construct($serviceClassName, $serviceName) {
        $this->setServiceObj(new $serviceClassName());
        $this->_serviceName = $serviceName;
        $options = Klee_Service_Manager::getOptions();
        if (isset($options[$serviceName])) {
            $this->_currentOptions = $options[$serviceName];
        } else {
            $this->_currentOptions = array();
        }
    }

    /**
     * @return the $_isCacheEnabled
     */
    public static function getIsCacheEnabled() {
        return self::$_isCacheEnabled;
    }

    /**
     * @param boolean $_isCacheEnabled Cache actif.
     */
    public static function setIsCacheEnabled($_isCacheEnabled) {
        self::$_isCacheEnabled = $_isCacheEnabled;
    }
    
    /**
     * 
     * @param boolean $_isDbProfilerEnabled Vaeur du paramètre.
     */
    public static function setIsDbProfilerEnabled($_isDbProfilerEnabled) {
    	self::$_isDbProfilerEnabled = $_isDbProfilerEnabled;
    }

    /**
     * @return boolean $_isDbProfilerEnabled
     */
    public static function isDbProfilerEnabled() {
    	return self::$_isDbProfilerEnabled;
    }

    /**
     * Récupére les options du service ou l'option précisées en paramètre.
     *
     * @param string $name Nom de l'option.
     * @throws Zend_Application_Exception
     * @return mixed|NULL
     */
    public function getCurrentOptions($name = null) {
        if (gettype($name) !== 'string' && ! is_null($name)) {
            throw new Zend_Application_Exception('Application_Service_Manager->getCurrentOptions n\'accepte que les chaines de caracteres');
        }
        
        if (is_null($name)) {
            return $this->_currentOptions;
        }
        
        if (isset($this->_currentOptions[$name])) {
            return $this->_currentOptions[$name];
        }
        
        return null;
    }

    /**
     * Retourne le nom du service.
     *
     * @return string
     */
    public function getServiceName() {
        return $this->_serviceName;
    }

    /**
     * Setteur de la dépendance.
     *
     * @param object $obj Dépendance.
     */
    private function setServiceObj($obj) {
        $this->_object = $obj;
        $this->_object->_service = $this;
    }

    /**
     * Call : permet d'intercepter les appels aux methodes du service.
     *
     * @param string $name Nom de la méthode à exécuter.
     * @param array $arguments Arguments de la méthode à exécuter.
     * @throws Zend_Exception
     */
    public function __call($name, $arguments) {
        if (! method_exists($this->_object, $name)) {
            throw new Zend_Exception('La m&eacute;thode ' . $name . ' n\'existe pas');
        }
        
        // liste des injecteurs pouvant s'exécuter
        $injecteurArray = array(
                'Klee_Service_Injector_DbAdapter', 
                'Klee_Service_Injector_Transaction', 
                'Klee_Service_Injector_DbConstraint');
        
        // on dépile le premier injecteur puis on instancie le pipeline
        $injecteurName = array_shift($injecteurArray);
        $injecteur = new $injecteurName($injecteurArray, $this->_currentOptions);
        $retour = $injecteur->Process($this->_object, $name, $arguments);
        
        // on retourne le résultat obtenu en bout de pipeline
        return $retour;
    }
}
