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
	private $transactions;
	/**
	 * TODO Auto-generated comment.
	 */
	private $singleTransaction;
	/**
	 * TODO Auto-generated comment.
	 */
	private $iterator;

	/**
	 * TODO Auto-generated comment.
	 */
	public function synchronize($confTE, $serverType, $transactions) {
		
		$doIteration = new transactionIterator($transactions);
		$doTransInDB = new transactionDAO();
		//echo "Numero de transacoes dentro do iterador: " . $doIteration->numOfTrans . "<br>";
		
		while($doIteration->hasNext())
		{
			var_dump($doIteration->current());
			echo "<br>";
			$doIteration->next();
		}
		
		return false;
	}
}
