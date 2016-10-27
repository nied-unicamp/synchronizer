<?php

require_once '../Model/transaction.php';
// require_once '../Model/user.php';
// require_once '../Model/course.php';
// require_once '../Model/user.php';

/**
 * TODO Auto-generated comment.
 */
class transactionDAO{

	/**
	 * TODO Auto-generated comment.
	 */
	public function doTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getOperation()) {
		
			case 'update':
				$this->updateTransaction($dbInfo, $serverType, $transaction);
				break;
					
			case 'insert':
				$this->insertTransaction($dbInfo, $serverType, $transaction);
				break;
					
			case 'delete':
				$this->deleteTransaction($dbInfo, $serverType, $transaction);
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		
		}
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function insertTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				break;
					
			case 'course':
				/* Build sql query */
				break;
					
			case 'coursemember':
				/* Build sql query */
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
				
		/* Call DB wrapper*/
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteTransaction($dbInfo, $serverType, $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				break;
					
			case 'course':
				/* Build sql query */
				break;
					
			case 'coursemember':
				/* Build sql query */
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
				
		/* Call DB wrapper*/
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateTransaction($dbInfo, $serverType, $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				break;
					
			case 'course':
				/* Build sql query */
				break;
					
			case 'coursemember':
				/* Build sql query */
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
		
		/* Call DB wrapper*/
		
	}
}
