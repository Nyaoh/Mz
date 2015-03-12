<?php

/**
 * Aide de vue pour l'affichage des dates au format dépendant de la langue.
 *
 * @author fconstantin
 */
class Klee_View_Helper_FormDate extends Zend_View_Helper_FormText
{
    const MYSQL_DATE = 'yyyy-MM-dd';

    /**
     * Convertit une date au format MySQL dans le format d'affichage.
     *
     * @param unknown_type $name Nom du contrôle.
     * @param unknown_type $value Valeur.
     * @param unknown_type $attribs Attributs.
     */
    public function formDate($name, $value = '', $attribs = null) {
        if (! isset($attribs)) {
        	$attribs = array();
        }

        if (isset($attribs['class'])) {
        	$attribs['class'] .= ' date';
        } else {
        	$attribs['class'] = 'date';
        }
        if (! isset($attribs['maxlength'])) {
        	$attribs['maxlength'] = 10;
        }
        if (! isset($attribs['size'])) {
        	$attribs['size'] = 10;
        }

        if (Zend_Date::isDate($value, self::MYSQL_DATE)) {
        	$value = Klee_Util_Date::printDate($value);
        } elseif ($value == '0000-00-00') {
            $value = '';
        }
        return parent::formText($name, $value, $attribs);
    }
}
