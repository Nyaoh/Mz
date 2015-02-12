<?php 

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{
    protected function _initView() {
        $view = new Zend_View();
        $view->headMeta()->appendHttpEquiv('Content-Type', 'text/html; charset=UTF-8');

		Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer')->setView($view);

		/*
		// Ajout d'un chemin vers le dossier d'helper.
        $view->addHelperPath(APPLICATION_PATH . '/../library/Klee/Module/Commun/View/Helper', 'Klee_View_Helper_');
        $view->addHelperPath(APPLICATION_PATH . '/modules/Commun/views/helpers', 'Application_View_Helper_');
        
        // Fixe la méthode permettant d'échapper les données.
        $view->setEscape(
        function ($s) {
            static $isXml = null;
			return htmlspecialchars(Klee_Util_MbString::convertEncoding($s),
            ($isXml === true ? ENT_COMPAT : ENT_QUOTES), Klee_Util_MbString::ENCODING);
        });*/
  
        return $view;
    }
}