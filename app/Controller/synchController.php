<?php

require_once dirname(__FILE__) . '/../Iterator/transactionIterator.php';
require_once dirname(__FILE__) . '/../DAO/transactionDAO.php';

/**
 * TODO Auto-generated comment.
 */
class synchController {
	/**
	 * TODO Auto-generated comment.
	 */
	//private $transactions;
	/**
	 * TODO Auto-generated comment.
	 */
	//private $singleTransaction;
	/**
	 * TODO Auto-generated comment.
	 */
	//private $iterator;

	/**
	 * TODO Auto-generated comment.
	 */
	public function synchronize($confTE, $confExtData, $serverType, $transactions) {
		
		$transIterator = new transactionIterator($transactions);
		$transRealizer = new transactionDAO($confExtData);
		//echo "Numero de transacoes dentro do iterador: " . $transIterator->numOfTrans . "<br>";
		
		while($transIterator->hasNext())
		{
			
			$transRealizer->doTransaction($confTE, $serverType, $transIterator->current());
			$transIterator->next();
			
		}
		
		return true;
	}
}
