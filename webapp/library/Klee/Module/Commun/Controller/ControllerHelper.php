<?php 

/**
 * @author AMORIN
 *
 */
class Klee_Module_Commun_Controller_ControllerHelper
{
	/**
	 * Pour refaire la liste des décorateurs en ajoutant les nouveaux
	 * @param Zend_Form_Element $element 	L'élément sur lequel on veut ajouter des décorateurs
	 * @param array $newDecorators 			Les décorateurs que l'on veut ajouter
	 */
	public static function changeDecorators(Zend_Form_Element $element, $newDecorators = array()) {
		// On sauvegarde dans un tableau les décorateurs
		$decorators = $element->getDecorators();
	
		// On supprime tous les décorateurs
		$element->clearDecorators();
	
		$listeDecorateurs= array();
		foreach ($decorators as $key => $decorator) {
				
			// On explose la clé en morceau pour enlever les préfixes
			$tableauKey = explode('_', $key);
				
			// On récupère le dernier terme pour recréer le nom des décorateurs
			$nomDecorateur = array_pop($tableauKey);
				
			//On récupère les options du décorateur
			$options = $decorator->getOptions();
				
			if (array_key_exists($nomDecorateur, $newDecorators)) {
				// cas particulier pour les classes : on veut qu'elles s'additionnent et non prendre la dernière
				if (isset($options['class']) && isset($newDecorators[$nomDecorateur]['class'])) {
					$newDecorators[$nomDecorateur]['class'] = $newDecorators[$nomDecorateur]['class'].' '.$options['class'];
				}
	
				// On remplace le décorateur par celui que l'on veut
				if (isset($newDecorators[$nomDecorateur]['decorator'])) {
					$decorateur = $newDecorators[$nomDecorateur]['decorator'];
					unset($newDecorators[$nomDecorateur]['decorator']);
						
					$listeDecorateurs[] = array( 'decorator' => $decorateur, 'options' => array_merge($options,$newDecorators[$nomDecorateur]));
				} else {
					// Sinon on rajoute juste les options que l'on souhaite
					$listeDecorateurs[] = array( 'decorator' => $nomDecorateur, 'options' => array_merge($options,$newDecorators[$nomDecorateur]));
				}
			} else {
				// On remet tel quel les autres décorateurs
				// Si il n'y a pas d'options, on met juste le nom
				if (empty($options)) {
					$listeDecorateurs[] = $nomDecorateur;
				} else {
					// sinon on met dans un tableau le nom et le tableau des options
					$listeDecorateurs[] = array( 'decorator' => $nomDecorateur, 'options' => $options);
				}
			}
		}
	
		// On remet les décorateurs
		$element->addDecorators($listeDecorateurs);
	}
}
