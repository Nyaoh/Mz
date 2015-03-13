<?php 

interface Default_Mapper_IMapperFamilleTypePoste
{
	/**
	 * @param int|string $id
	 * @return Default_Model_IFamilleTypePoste
	 * @throws Exception
	 */
	public function find($id);
	
	/**
	 * @return array|Default_Model_IFamilleTypePoste[]
	 */
	public function findAll();
}