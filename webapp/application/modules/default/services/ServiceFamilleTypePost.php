<?php 

/**
 * @author AMORIN
 *
 */
class Default_Service_ServiceFamilleTypePost implements Default_Service_IServiceFamilleTypePost
{
	protected $_data = array(
		array(
				'id'				=> 1,
				'code'				=> 'FTP_1',
				'ordreAffichage' 	=> 1
		),
		array(
				'id'				=> 2,
				'code'				=> 'FTP_2',
				'ordreAffichage' 	=> 2
		),
		array(
				'id'				=> 3,
				'code'				=> 'FTP_3',
				'ordreAffichage' 	=> 3
		)
	);
	
	/**
	 * @var Default_Mapper_IMapperFamilleTypePoste
	 */
	protected $_mapper;
	
	public function __construct(/*Default_Mapper_IMapperFamilleTypePoste $ftpMapper*/) {
		//$this->_mapper = $ftpMapper;
	}
	
	private function hydrateName($property, $data = NULL) {
		// $property = 'FTP_ID';
		
		//$namingStrategy;
		
		$name = strtolower(substr($property, 4));
		$nameAsArray = explode('_', $name);
		return implode('', array_map('ucfirst', explode('_', $name)));
	}
	
	/* (non-PHPdoc)
	 * @see Default_Service_IFamilleTypePost::findAllFamilleTypePoste()
	 */
	public function findAllFamilleTypePoste() {
		$dbAdapter = Zend_Registry::get('dbAdapter');
		
		$select = new Zend_Db_Select($dbAdapter);
		$select	->from(
					array('ftp' => 'FAMILLE_TYPE_POSTE'),
					array('ftp.*')
				);
		$data = $select->query()->fetch();
		
		$object = new Default_Model_FamilleTypePoste();
		
		Zend_Debug::dump($object);
// 		die;

		// Identifiant::filter($value);
		
		foreach ($data as $property => $value) {
			$name = $this->hydrateName($property);
			$method = 'set' . ucfirst($this->hydrateName($property));
			Zend_Debug::dump($method);
			
			if (true === is_callable(array($object, $method))) {
				$object->$method($value);
			}
		}
		
		Zend_Debug::dump($object);
		
		Zend_Debug::dump($object->getId());
		
		
		
		
		
		
		
		
		
		
		
		
		
		Zend_Debug::dump($data);
		die;
		
		return $this->_mapper->findAll();
		
		/*$ftpList = array();
		
		foreach ($this->_data as $index => $ftp) {
			$ftpList[] = $this->findFamilleTypePoste($index);
		}
		
		return $ftpList;*/
	}
	
	/* (non-PHPdoc)
	 * @see Default_Service_IFamilleTypePost::findFamilleTypePoste()
	 */
	public function findFamilleTypePoste($id) {
		return $this->_mapper->find($id);
		
		/*$ftpData = $this->_data[$id];
		
		$model = new Default_Model_FamilleTypePoste();
		$model->setId($ftpData['id']);
		$model->setCode($ftpData['code']);
		$model->setOrdreAffichage($ftpData['ordreAffichage']);
		
		return $model;*/
	}
	
	
	/*public function getList() {
		$select = new Zend_Db_Select(Zend_Registry::get('dbAdapter'));
		$select	->from(
					array('ftp' => 'FAMILLE_TYPE_POSTE'),
					array('*')
				)
				->order('ftp.FTP_ORDRE_AFFICHAGE asc');
		return $select->query()->fetchAll();
	}*/
}