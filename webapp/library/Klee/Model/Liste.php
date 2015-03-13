<?php

/**
 * L'endroit où on stocke en dur les paramètres pour les listes de résultats.
 * 
 * @author AMORIN
 */
class Klee_Model_Liste
{
    private static $_limit = NULL;
    
    /**
     * Retourne le nombre maximale d'élément à retourner pour une liste.
     * 
     * @return int
     */
    public static function getLimit() {
        if (NULL === self::$_limit) {
            self::$_limit = Zend_Registry::get('nbElementMax');
        }
    }
}
