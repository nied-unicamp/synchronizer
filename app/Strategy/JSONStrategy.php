<?php

require_once 'dataStrategy.php';

/**
 * TODO Auto-generated comment.
 */
class JSONStrategy extends dataStrategy {
	/**
	 * TODO Auto-generated comment.
	 */
	private $transactions;

	/**
	 * TODO Auto-generated comment.
	 */
// 	public function diff($externalList, $cacheList, $formatType) {
		
// 		/* 
// 		 * Use father class method for obtain $this->transactions;
// 		 * Return $this->transactions on json format.
// 		 * 
// 		 * */
		
// 		return null;
// 	}
	
	public function getTransactions()
	{
		return json_encode($this->transactions);
	}
}
