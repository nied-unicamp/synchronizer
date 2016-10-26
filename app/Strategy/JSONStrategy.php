<?php

require_once 'dataStrategy.php';

/**
 * This class is responsible for converting the transactions list to JSON object.
 */
class JSONStrategy extends dataStrategy {
	/**
	 * Transactions list.
	 */
	//private $transactions;

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
	
	/**
	 * Return the transactions as JSON object.
	 * 
	 * @return A JSON object representing the transactions list.
	 * */
	public function getTransactions()
	{
		return $this->transactions;
		//$visibleTransactions = $this->transactions;
		//$returno = json_encode($this->transactions);
		//echo json_last_error();
		//return $returno;
	}
}
