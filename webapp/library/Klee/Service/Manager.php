<?php

/**
 * Classe de gestion des services : doit être instanciée en lieu et place de la classe métier cible.
 * Chaque service doit avoir une interface et une implémentation,
 * le manager permet de faire le lien entre l'interface et l'implémentation.
 * L'appel des méthodes de l'interface déclenche l'éxécution de greffons de code pour ajouter des fonctionnalités transverses
 *
 * @author jbourdin
 * @package serviceLayer
 *
 */
final class Klee_Service_Manager
{
    /**
     * Ensemble de la configuration des services.
     *
     * @var array
     */
    private static $_servicesOptions;

    /**
     * Array of mock service.
     */
    protected static $_mockServicesArray = array();

    /**
     * Reset the mockObject array for isolation purpose.
     */
    public static function resetMocks() {
        self::$_mockServicesArray = array();
    }

    /**
     * Set a mock service for testing purpose.
     *
     * @param object $obj mock object substituted to the service
     * @param string $serviceName nom du service à instancier
     * @param string $module nom du module dans lequel on trouve ce service
     */
    public static function setMockService($obj, $serviceName, $module = '') {
        if ($module !== '') {
            self::$_mockServicesArray[$module][$serviceName] = $obj;
        } else {
            self::$_mockServicesArray[$serviceName] = $obj;
        }
    }

    /**
     * Setter des options, pour initialiser la conf depuis le bootstrap.
     *
     * @param array $options Les options du bootstrap
     * @throws Zend_Application_Bootstrap_Exception
     */
    public static function setOptions($options) {
        if ('array' !== gettype($options)) {
            throw new Zend_Application_Bootstrap_Exception('Les options de services ne sont pas sous forme de tableau');
        }
        self::$_servicesOptions = $options;
    }

    /**
     * Retourne les options des services.
     *
     * @return array
     */
    public static function getOptions() {
        return self::$_servicesOptions;
    }

    /**
     *
     * Methode publique pour instancier le service d'aprês son nom. Injecte la dépendance.
     *
     * @param string $serviceName nom du service à instancier
     * @param string $module nom du module dans lequel on trouve ce service
     * @return Application_Model_Services_Manager
     * @throws Zend_Exception
     */
    public static function getService($serviceName, $module = '') {
        if (gettype($serviceName) !== 'string') {
            throw new Zend_Exception('getService n\'accepte qu\'une variable string');
        }

        $pathServiceModule = '_';

        if ($module !== '') {
            $pathServiceModule = '_Module_' . $module . '_';
        }

        if ($module !== '') {
            if (isset(static::$_mockServicesArray[$module][$serviceName])) {
                return static::$_mockServicesArray[$module][$serviceName];
            }
        } else {
            if (isset(static::$_mockServicesArray[$serviceName])) {
                return static::$_mockServicesArray[$serviceName];
            }
        }

        $serviceClassName = self::resolveName($serviceName, $pathServiceModule);

        if (! in_array(
            	'Application' . $pathServiceModule . 'Services_Interfaces_I' . $serviceName,
            	class_implements($serviceClassName))) {
            throw new Zend_Exception(
                $serviceClassName . ' n\'impl&eacute;mente pas ' . 'Application' . $pathServiceModule . 'Services_Interfaces_I' . $serviceName);
    	}

    	return new Klee_Service_Proxy($serviceClassName, $serviceName);
	}

	/**
	 *
	 * Resoud l'association entre le nom du service et la classe à instancier
	 * Dépendance.
	 *
	 * @param string $serviceName nom du service
	 * @param string $pathServiceModule nom du chemin au service
	 * @return string nom de la classe à instancier
	 * @throws Zend_Exception
	 */
	private static function resolveName($serviceName, $pathServiceModule) {
	    $options = self::$_servicesOptions;

	    if (isset($options[$serviceName]['className'])) {
	        return $options[$serviceName]['className'];
	    } elseif (class_exists('Application' . $pathServiceModule . 'Services_Implementations_' . $serviceName)) {
	        return 'Application' . $pathServiceModule . 'Services_Implementations_' . $serviceName;
	    } else {
	        throw new Zend_Exception(
	            'Application' . $pathServiceModule . 'Services_Implementations_' . $serviceName . ' n\'existe pas');
	    }
	}
}
