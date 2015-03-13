<?php

/**
 * Contrôleur abstrait permettant de gérer le détail d'un élément.
 * 
 * @author AMORIN
 *
 */
class Klee_Module_Commun_Controller_HelperException
{
	/**
	 * Exception à afficher au niveau du formulaire.
	 *
	 * @param Zend_Form $form Formulaire.
	 * @param string $key clef du champs
	 * @param string $message Message d'erreur
	 */
	public static function printExceptionFormError(Zend_Form $form, $key, $message) {
		self::printFormError($form, $key, $message, true);
	}
	
	/**
	 * Message d'erreur (champs invalide) à afficher au niveau du formulaire.
	 *
	 * @param Zend_Form $form Formulaire.
	 * @param string $key clef du champs
	 * @param string $message Message d'erreur
	 */
	public static function printInvalidFormError(Zend_Form $form, $key, $message) {
		self::printFormError($form, $key, $message, false);
	}
	
	// ------------------------------------------------------------------------
	// Private methods
	// ------------------------------------------------------------------------
	
	/**
	 * Ajout d'une class d'erreur
	 * @param class $initialClass lass initiale
	 * @return string
	 */
	private static function addErrorClass($initialClass) {
		if (is_null($initialClass)) {
			return 'error';
		}
		return $initialClass . ' error';
	}
	
	/**
	 * @param Zend_Form $form 	   Formulaire.
	 * @param string $key 		   Clef du champ.
	 * @param string $message 	   Message d'erreur.
	 * @param boolean $isException Est-ce qu'il s'agit du message d'une exception à afficher.
	 */
	private static function printFormError(Zend_Form $form, $key, $message, $isException = false) {
		if (ctype_digit($key) || is_int($key) || is_null($form->getElement($key))) {
		    $form->markAsError();
			$form->addErrorMessage($message);
		} else {
			$element = $form->getElement($key);
			$element->removeDecorator('Errors');
			$element->setAttrib('class', self::addErrorClass($element->getAttrib('class')));

			if ($isException) {
				$form->markAsError();
				if (Klee_Util_String::isNullOrEmpty($element->getLabel())) {
					$form->addErrorMessages(array($message));
				} else {
				    $element->addError($message);
				}
			}

			if (($decorator = $element->getDecorator('Label')) || ($decorator = $element->getDecorator('LabelWithToolTip'))) {
				$decorator->setOption('class', self::addErrorClass($decorator->getOption('class')));
			}
		}
	}
}
