<?php
/**
 * TODO Auto-generated comment.
 */
class transactionIterator/* implements abstractIterator */{
	
	/**
	 * Array of transactions.
	 */
	private $allTransactions;

	/**
	 * Number of transactions in the array.
	 * */
	private $numOfTrans;
	
	/**
	 * Actual position of the iteration process.
	 * */
	private $position = 0;
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($transactions) {
		$this->position = 0;
		$this->allTransactions = $transactions;
		$this->numOfTrans = count($this->allTransactions);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function current() {
		return $this->allTransactions[$this->position];
	}
	
	public function key()
	{
		return $this->position;
	}
	
	public function next()
	{
		$this->position = $this->position + 1;
	}
	
	public function rewind()
	{
		$this->position = 0;
	}
	
	public function valid()
	{
		return isset($this->allTransactions[$this->position]);
	}

	public function hasNext() {
		
		if($this->position < $this->numOfTrans)
		{
			return true;
		}
		return false;
	}
}
