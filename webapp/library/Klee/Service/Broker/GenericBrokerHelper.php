<?php

/**
 * Helper pour le broker.
 *
 * @author AMORIN
 *
 */
class Klee_Service_Broker_GenericBrokerHelper
{
	/**
	 * Ajout de critères au select.
	 * 
	 * @param Zend_Db_Select &$select Select.
	 * @param array $criteria		 Liste des critères.
	 * @param array $metadata		 Metadata.
	 */
	public static function addCriteriaToSelect(Zend_Db_Select &$select, array $criteria, $metadata = null) {
		$count = null;
		$offset = null;
		foreach ($criteria as $key => $value) {
			$dataType = null;
			if (! is_null($metadata) && array_key_exists($key, $metadata)) {
				$dataType = $metadata[$key]['DATA_TYPE'];
			}
			
			if ((is_array($value))&&(isset($value['operator']))) {
				$value['bindedParam'] = ' ? ';
				$value = self::getExpressionFromOperator($value);
				 
				$select->where($key .' '.$value['operator']. $value['bindedParam'], $value['value'], $dataType);
			} else {
				if ($key === Zend_Db_Select::LIMIT_COUNT) {
					$count = $value;
				} elseif ($key === Zend_Db_Select::LIMIT_OFFSET) {
					$offset = $value;
				} else {
					$select->where($key . ' = ?', $value, $dataType);
				}
			}
		}
		$select->limit($count, $offset);
	}
	
	/**
	 * Retourne l'expression a inserer dans le where avec un operateur
	 * 
	 * @param array $value contient l'operateur et la valeur a comparer dans le where
	 */
	public static function getExpressionFromOperator($value) {
		switch ($value['operator']) {
			case 'startWith':
				$value['operator'] = 'LIKE';
				$value['value'] = $value['value'] . '%';
				break;
			case 'supOrEqual':
				$value['operator'] = '>=';
				break;
			case 'infOrEqual':
				$value['operator'] = '<=';
				break;
			case 'superior':
				$value['operator'] = '>';
				break;
			case 'inferior':
				$value['operator'] = '<';
				break;
			case 'different':
				$value['operator'] = '<>';
				break;
			case 'equal':
				$value['operator'] = '=';
				break;
			case 'contains':
				$value['operator'] = 'LIKE';
				$value['value'] = '%' . $value['value'] . '%';
				break;
			case 'notIn':
				$value['operator'] = 'NOT IN';
				$value['dataType'] = 'LIST';
				$value['bindedParam'] = ' (?)';
				break;
			case 'in':
				$value['operator'] = 'IN';
				$value['dataType'] = 'LIST';
				$value['bindedParam'] = ' (?)';
				break;
			default:
				throw new Zend_Exception('Opérateur inconnu.');
				break;
		}
		return $value;
	}
}
