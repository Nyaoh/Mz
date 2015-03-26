<?php

/**
 * Contrôleur d'erreur.
 * Doit être crée au niveau du module par défaut.
 * 
 * @author AMORIN
 *
 */
class ErrorController extends Zend_Controller_Action
{
    /**
     * action appelée quand quelque chose ne fonctionne pas
     */
    public function errorAction()
    {
        $errors = $this->_getParam('error_handler');
 
        if(APPLICATION_ENV == 'testing'){
            throw $errors->exception;
        }
        
        if (!$errors || !$errors instanceof ArrayObject) {
            $this->view->message = 'You have reached the error page';
            return;
        }

        switch ($errors->type) {
            case Klee_Plugin_Main::EXCEPTION_NO_ROUTE:
            case Klee_Plugin_Main::EXCEPTION_NO_CONTROLLER:
            case Klee_Plugin_Main::EXCEPTION_NO_ACTION:
                // 404 error -- controller or action not found
                $this->getResponse()->setHttpResponseCode(404);
                $priority = Zend_Log::NOTICE;
                $this->view->message = 'Page not found';
                break;
                // @TODO: créer une classe dans la librairie qui hérite de la classe Zend_Controller_Plugin_ErrorHandler.
            case Klee_Plugin_Main::USER_EXCEPTION:
            	$this->view->message = 'User exception';
            	$priority = Zend_Log::ERR;
            	break;
            default:
                // application error
                $this->getResponse()->setHttpResponseCode(500);
                $priority = Zend_Log::CRIT;
                $this->view->message = 'Application error';
                break;
        }

        // Log exception, if logger available
        $log = $this->getLog();
        if (isset($log) && $log != null) {
			$params = $errors->request->getParams();
			
			$id = 'ERR' . md5(uniqid());
			$message = "\n\n" . $id .  "\n\n" . $this->view->message . "\n" . $errors->exception->getMessage() . "\nParamètres : " . print_r($params, true);
            $log->log($message, $priority);
            
            if ($errors->type == Klee_Plugin_Main::USER_EXCEPTION) {
            	// Envoi d'un mail
            	$mailMessage = Klee_Util_Date::getCurrentDatetime() . $message;
            	Klee_Util_Mail::envoiMail('socle@kleegroup.com', 'Socle', 'alexis.morin@kleegroup.com', '[Socle]', "<pre>" . $mailMessage . "</pre>");
            }  
        }

        // conditionally display exceptions
        if ($this->getInvokeArg('displayExceptions') == true) {
            $this->view->exception = $errors->exception;
        }

        $this->view->id = $id;
        $this->view->request = $errors->request;
    }

    /**
     * Vérifie si un logger est déclaré dans la conf de l'application et le retourne en cas de besoin
     * @return boolean|Zend_Log
     */
    public function getLog() {
        return Zend_Registry::get('logger');
    }
}
