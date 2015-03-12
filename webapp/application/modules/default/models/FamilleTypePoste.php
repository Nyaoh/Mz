<?php 

/**
 * @author AMORIN
 *
 */
class Default_Model_FamilleTypePoste implements Default_Model_IFamilleTypePoste
{
	/**
	 * @var int
	 * @domain={Identifiant}
	 */
	protected $_id;
	
	/**
	 * @var string
	 */
	protected $_code;
	
	/**
	 * @var int
	 */
	protected $_ordreAffichage;
	
	/**
	 * @var string
	 */
	protected $_libelleFr;
	
	/**
	 * @var string
	 */
	protected $_libelleEn;
	
	/**
	 * @var string
	 */
	protected $_libelleEs;
	
	// ------------------------------------------------------------------------
	// get ; set
	// ------------------------------------------------------------------------
	
	/* (non-PHPdoc)
	 * @see Default_Model_IFamilleTypePoste::getId()
	 */
	public function getId() {
		return $this->_id;
	}
	
	/**
	 * @param int $id
	 */
	public function setId($id) {
		$rc = new ReflectionClass($this);
		
		$comment = $rc->getProperty('_id')->getDocComment();
		
		preg_match('#(@domain={)([^}]*)#', $comment, $matches);
		$domain = new $matches[2]();
		
		
		Zend_Debug::dump($domain->filter($id));
		die;
		
		$domain = new Identifiant();
		$this->_id = $domain->filter($id);
	}
	
	/* (non-PHPdoc)
	 * @see Default_Model_IFamilleTypePoste::getCode()
	 */
	public function getCode() {
		return $this->_code;
	}
	
	/**
	 * @param string $code
	 */
	public function setCode($code) {
		$this->_code = $code;
	}
	
	/* (non-PHPdoc)
	 * @see Default_Model_IFamilleTypePoste::getOrdreAffichage()
	 */
	public function getOrdreAffichage() {
		return $this->_ordreAffichage;
	}
	
	/**
	 * @param int $ordreAffichage
	 */
	public function setOrdreAffichage($ordreAffichage) {
		$this->_ordreAffichage = $ordreAffichage;
	}
	
	/* (non-PHPdoc)
	 * @see Default_Model_IFamilleTypePoste::getLibelle()
	 */
	public function getLibelle() {
		// @TODO: dépend de la langue.
	}
	
	/**
	 * @param string $libelle
	 */
	public function setLibelle($libelle) {
		// @TODO: dépend de la langue.
	}
}

class Identifiant
{
	public function filter($value) {
		$intFilter = new Zend_Filter_Int();
		return $intFilter->filter($value);
	}
}