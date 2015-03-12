<?php 

/**
 * @author AMORIN
 *
 */
class Default_Service_ServiceUtilisateur
{
	public function getUtilisateurList($limit = 10, $page = 1) {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		
		// Calcul de l'offset.
		$utilisateurCount = (int) $this->getUtilisateurListCount();
		$nbPage = round($utilisateurCount / $limit);
		
		if ($page < 1) {
			$page = 1;
		}
		
		if ($page > $nbPage) {
			$page = $nbPage;
		}
		
		$offset = ($page - 1) * $limit;
		
		$select = new Zend_Db_Select($dbAdapter);
		$select	->from(
						array('uti' => 'UTILISATEUR'),
						array('UTI_ID', 'UTI_NOM', 'UTI_PRENOM', 'UTI_EMAIL')
				)
				->join(
						array('civ' => 'CIVILITE'),
						'civ.CIV_CODE = uti.CIV_CODE',
						array('CIV_LIBELLE')
				)
				->limit($limit, $offset);
		
		// @TODO: créer un objet container.
		return array(
				'list'		=> $select->query()->fetchAll(),
				'count'		=> $utilisateurCount,
				'page'		=> $page
		);
	}
	
	public function getUtilisateurListCount() {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		
		$select = new Zend_Db_Select($dbAdapter);
		$select	->from(
						array('uti' => 'UTILISATEUR'),
						array(new Zend_Db_Expr('count(1) as TOTAL'))
				);
		$result = $select->query()->fetch();
		
		return $result['TOTAL'];
	}
	
	public function createUtilisateurList() {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		
		$dataToSave = array();
		for ($i = 2; $i < 100; $i++) {
			$utilisateur = array(
					'UTI_ID' 		=> $i,
					'CIV_CODE'		=> 'MR',
					'UTI_NOM' 		=> 'N_' . $i,
					'UTI_PRENOM'	=> 'Prénom_' . $i,
					'UTI_EMAIL'		=> 'mail_' . $i
			);
			
			$dbAdapter->insert('UTILISATEUR', $utilisateur);
		}
		
// 		Zend_Debug::dump($dataToSave);
// 		die;
		
		
	}
}
