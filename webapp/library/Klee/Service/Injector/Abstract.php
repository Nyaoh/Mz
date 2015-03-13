<?php

/**
 * Classe abstraite décrivant le squelette de greffon de code pour les services.
 *
 * @author jbourdin
 * @package serviceLayer
 * @SuppressWarnings(PHPMD.UnusedFormalParameter)
 */
abstract class Klee_Service_Injector_Abstract implements Klee_Service_Injector_IGeneral
{
    /**
     * Liste des injecteurs restant à traiter
	 *
     * @var array
     */
    protected $_injecteurArray;

    /**
     * Flag indiquant si une valeur doit être remontée vers le haut de la chaîne en ignorant le bas de celle-ci.
     *
     * @var boolean
     */
    protected $_immediateReturn = false;

    /* (non-PHPdoc)
	 * @see Application_Service_Injectors_General::__construct()
	 */
    public function __construct($injecteurArray, $options) {
        if (! is_array($injecteurArray)) {
            throw new Zend_Application_Resource_Exception('Un injecteur prend un tableau en argument de construction');
        }
        $this->_injecteurArray = $injecteurArray;
        $this->_options = $options;
    }

    /* (non-PHPdoc)
	 * @see Application_Service_Injectors_General::process()
	 */
    public final function process($object, $name, $arguments) {
        if ($this->willExecute($object, $name, $arguments)) {
            $retour = $this->preProcess($object, $name, $arguments);
            if ($this->_immediateReturn) {
                if (isset($object->_log)) {
                    $level = Zend_Log::INFO;
                    $msg = 'Reponse immediate de ' . get_class($this) . ' au nom de ' . get_class($object) . '->' . $name . ' avec les arguments ' . "\n" . print_r($arguments, true);
                    $object->_log->customLog($msg, $level);
                }
                return $retour;
            }
            try {
                $retour = $this->getNext($object, $name, $arguments);
            } catch (Exception $exception) {
                $this->onCatch($object, $name, $arguments, $exception);
            }
            $retour = $this->postProcess($object, $name, $arguments, $retour);
        } else {
            $retour = $this->getNext($object, $name, $arguments);
        }
        return $retour;
    }

    /* (non-PHPdoc)
	 * @see Application_Service_Injectors_General::getNext()
	 */
    public final function getNext($object, $name, $arguments) {
        if (count($this->_injecteurArray) === 0) {
            $retour = call_user_func_array(array($object, $name), $arguments);
        } else {
            $injecteurName = array_shift($this->_injecteurArray);
            $injecteur = new $injecteurName($this->_injecteurArray, $this->_options);
            $retour = $injecteur->Process($object, $name, $arguments);
        }
        return $retour;
    }

    /* (non-PHPdoc)
	 * @see Application_Service_Injectors_General::willExecute()
	 */
    public function willExecute($object, $name, $arguments) {
        return true;
    }

    /**
     * Méthode de traitement appelée par l'injecteur avant le traitement de la méthode du service.
     *
     * @param string $object Service appelé
     * @param string $name Méthode appelée
     * @param array $arguments Argument d'appel
     */
    protected function preProcess($object, $name, $arguments) {
    }

    /**
     * Méthode de traitement appelée par l'injecteur après le traitement de la méthode du service.
     *
     * @param string $object Service appelé
     * @param string $name Méthode appelée
     * @param array $arguments Argument d'appel
     * @param mixed $return Valeur de retour
     * @return mixed
     */
    protected function postProcess($object, $name, $arguments, $return = null) {
        return $return;
    }

    /**
     * Méthode appelée en cas d'exception levée dans la suite de la chaîne.
     *
     * @param object $object Service appelé
     * @param string $name Méthode appelée
     * @param array $arguments Argument d'appel
     * @param Exception $exception Exception
     * @throws Exception
     */
    protected function onCatch($object, $name, $arguments, Exception $exception) {
        throw $exception;
    }
}
