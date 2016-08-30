<?php

require_once '../Strategy/JSONStrategy.php';
require_once '../Strategy/dataStrategy.php';

/**
 * TODO Auto-generated comment.
 */
class DifferContext {
	
	/**
	 * TODO Auto-generated comment.
	 */
	private $data;
	
	/**
	 * TODO Auto-generated comment.
	 */
	private $dataType;
	
	/**
	 * Atomic operations that need to be done in teleduc's database
	 * in order to obtain a synchronized stated.
	 */
	private $transactions;
	
	/**
	 * TODO Auto-generated comment.
	 */
	private $dataStrategy;

	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($dataType) {

		switch($dataType){
			
			case 'DATA_TYPE_JSON':
			     $this->dataStrategy = new JSONStrategy();
			     break;
			
			case 'DATA_TYPE_XML':
			
			case 'DATA_TYPE_CSV':
			
			default:
				throw new Exception('Unable to create a Strategy of the type '.$dataType);

	       }
	}

	/**
	 * TODO Auto-generated comment.
	 * TODO Redundant information in parameter $formatType? This information is
	 * given in the constructor of the object.
	 */
	public function diff($externalList, $cacheList, $formatType, $confDB) {

		return $this->dataStrategy->diff($externalList, $cacheList, $formatType, $confDB);
		
	}
}
