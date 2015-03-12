<?php 

class Default_Decorator_SimpleSelect extends Zend_Form_Decorator_Abstract
{
	public function render($content) {
		$element = $this->getElement();

		$id 	= htmlentities($element->getId());
		$name	= htmlentities($element->getFullyQualifiedName());
		$value	= htmlentities($element->getValue());
		$label	= htmlentities($element->getLabel());
		
// 		Zend_Debug::dump($element);

		
		$htmlLabel 	= 	'<div class="div-formlabel">'
					.		'<label for="' . $id . '" class="formlabel">' . $label . '</label>'
					.	'</div>';
		
		$htmlInfo	= 	'';
		if ($element->getAttrib('info') !== null) {
			$htmlInfo	=	'<span class="forminfo">'
						.		htmlentities($element->getAttrib('info'))
						.	'</span>'
						.	'<br />';
		}
		
		$htmlError	=	'';
		if ($element->getMessages() !== null) {
			foreach (array_values($element->getMessages()) as $index => $message) {
				if ($htmlError !== '') {
					$htmlError .= '<br />';
				}
				
				$formattedFieldName = implode('', array_map('ucfirst', explode('_', strtolower($element->getName()))));
				$htmlError	.=	'<span id="errormsg_' . $index . '_' . $formattedFieldName . '" class="formerror">'
							.		htmlentities($message)
							. 	'</span>';
			}
		}
		
		// @TODO: faire la même chose avec le tooltip.
		// @TODO: calendrier
		// @TODO: autres éléments javascript.
		
		$htmlSelect	=	'<div class="div-formselect">'
					.		'<select id="' . $id . '" name="' . $name . '" class="formselect">'
					.			'<option value="-1">---</option>';
		foreach ($element->getAttrib('options') as $eltKey => $eltValue) {
			$selectedOption = '';
			if ($eltKey == $value) {
				$selectedOption = 'selected="selected"';
			}
			
			$htmlSelect	.=	'<option value="' . htmlentities($eltKey) . '" ' . $selectedOption . '>' . htmlentities($eltValue) . '</option>';
		}
		$htmlSelect	.		'</select>'
					.		'<div class="div-formerror">'
					.			$htmlInfo
					.			$htmlError
					.		'</div>'
					.	'</div>';
		
// 		Zend_Debug::dump($element);
// 		die;	
				
// 					.		'<input id="' . $id . '" name="' . $name . '" type="text" value="' . $value . '" class="forminput" />'
// 					.		'<div class="div-formerror">'
// 					.			$htmlInfo
// 					.			$htmlError
// 					.		'</div>'
// 					.	'</div>';
		
		return 		'<div class="formrow">'
				.		$htmlLabel
				.		$htmlSelect
				.	'</div>';
	}
}
