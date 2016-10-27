<?php

require_once '../Iterator/transactionIterator.php';
require_once '../DAO/transactionDAO.php';

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
	public function synchronize($confTE, $serverType, $transactions) {
		
		$transIterator = new transactionIterator($transactions);
		$transRealizer = new transactionDAO();
		//echo "Numero de transacoes dentro do iterador: " . $transIterator->numOfTrans . "<br>";
		
		while($transIterator->hasNext())
		{
			
			//var_dump($transIterator->current());
			//echo "<br>";
			
			$transRealizer->doTransaction($confTE, $serverType, $transIterator->current());
			$transIterator->next();
			
		}
		
		return true;
	}
}
