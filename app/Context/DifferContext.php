<?php

require_once dirname(__FILE__) . '/../Strategy/JSONStrategy.php';
require_once dirname(__FILE__) . '/../Strategy/dataStrategy.php';

/**
 * TODO Auto-generated comment.
 */
class DifferContext {

	/**
	 * Desired data type when returning the transactions list.
	 */
	private $dataType;

	/**
	 * Strategy generated on construction according to the desired data type when 
	 * returning the transactions list.
	 */
	private $dataStrategy;

	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($dataType) {

		$this->dataType = $dataType;
		
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
	public function diff($externalList, $cacheList, $formatType, $confDB, $confExternalDB) {

		//return $this->dataStrategy->diff($externalList, $cacheList, $formatType, $confDB, $confExternalDB);
		$this->dataStrategy->diff($externalList, $cacheList, $formatType, $confDB, $confExternalDB);
		return $this->dataStrategy->getTransactions();
		/*Shoul return getTransactions from JSON or XML or CSV strategy.*/
	}
}
