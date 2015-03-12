<?php

/**
 * Classe de gestion des erreurs utilisateur.
 * 
 * @author rgrange
 *
 */
class Klee_Util_UserException extends Zend_Exception 
{
    private $_messageList = array();
    private $_firstMsg;
        
    /**
     * Contructeur.
     * @param string $codeErreur Code de l'erreur (à traduire.).
     * @param array $params Paramètre du message.
     * @param string $fieldName Nom du champ.
     */
    public function __construct($codeErreur = null, array $params = array(), $fieldName = null) {
        if (! is_null($codeErreur)) {
            $this->addMessage($codeErreur, $params, $fieldName);
            parent::__construct($this->_firstMsg);
        }
    }
    
    /**
     * @return boolean True si l'exection contient un ou plusieur messages (cas du pool d'exception).
     */
    public function hasMessages() {
        return count($this->_messageList) > 0;
    }
        
    /**
     * Lève l'excepion si elle ontient des messages.
     * @throws Application_Util_UserException Exception.
     */
    public function throwIfError() {
        if ($this->hasMessages()) {
            throw $this;
        }
    } 
    
    /**
     * Affichage de l'erreur.
     * @param String $separator Séparateur entres les différentes erreurs (par défaut <br/>).
     * @return string Erreur
     */
    public function display($separator = '<br/>') {
        if (! $this->hasMessages()){
            return '';
        }
        
        foreach ($this->_messageList as $message) {
            if (isset($display)) {
                $display .= $separator;
            } else {
                $display = '';
            }
            $display .= $message;
        }
        return $display;
    }
    
    /**
     * Ajoute un message d'erreur à l'exception.
     * @param string $codeErreur Code de l'erreur (à traduire.).
     * @param array $params Paramètre du message.
     * @param string $fieldName Nom du champ.
     * @param string $categorie Catégorie de l'erreur.
     */
    public function addMessage($codeErreur, $params = array(), $fieldName = null, $categorie = null) {
        $msg = vsprintf(Zend_Registry::get('Zend_Translate')->translate($codeErreur), $params);
        if (!$this->hasMessages()) {
        	$this->_firstMsg = $msg;
        }
        
        if(is_null($categorie)) {
	        if(is_null($fieldName)) {
	            $this->_messageList[] = $msg;
	        } else {
	            $this->_messageList[$fieldName] = $msg;
	        }
        } else {        
        	if(!array_key_exists($categorie, $this->_messageList)) {
        		$this->_messageList[$categorie] = array();
        	}
        	$this->_messageList[$categorie][] = $msg;
        }
    }
    
    /**
     * @return array Liste des messages de l'exception.
     */
    public function getMessageList() {
        return $this->_messageList;
    }
}
