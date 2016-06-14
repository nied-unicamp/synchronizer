<?php
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
	 * TODO Auto-generated comment.
	 
	private $transactions;
	/**
	 * TODO Auto-generated comment.
	 */
	private $dataStrategy = Null;

	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($dataType) {

		switch($dataType){
			
			case 'DATA_TYPE_JSON':
			     $this->$dataStrategy = new JSONStrategy();
			     break;
			
			case 'DATA_TYPE_XML':
			
			case 'DATA_TYPE_CSV':
			
			default:
				throw new Exception('Unable to create a Strategy of the type '.$dataType);

	       }
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function diff($externalList, $cacheList, $formatType) {
		return null;
	}
}
