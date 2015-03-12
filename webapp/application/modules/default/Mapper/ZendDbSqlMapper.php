<?php 

class Default_Mapper_ZendDbSqlMapper implements Default_Mapper_IMapperFamilleTypePoste
{
	/**
	 * @var Zend_Db_Adapter_Abstract
	 */
	protected $_dbAdapter;
	
	public function __construct(Zend_Db_Adapter_Abstract $dbAdapter) {
		$this->_dbAdapter = $dbAdapter;
	}
	
	/* (non-PHPdoc)
	 * @see Default_Mapper_IMapperFamilleTypePoste::find()
	 */
	public function find($id) {
		
	}
	
	/* (non-PHPdoc)
	 * @see Default_Mapper_IMapperFamilleTypePoste::findAll()
	 */
	public function findAll() {
		$select = new Zend_Db_Select($this->_dbAdapter);
		$select	->from(
					array('ftp' => 'FAMILLE_TYPE_POSTE'),
					array('*')
				);
		
		return $select->query()->fetchAll();
	}
}