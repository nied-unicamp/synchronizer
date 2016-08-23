<?php
/**
 * TODO Auto-generated comment.
 */
abstract class dataStrategy {

	/**
	 * TODO Auto-generated comment.
	 */
	private $transactions;
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function diff($externalList, $cacheList, $formatType) {
		return null;
	}
	
	/* TODO Create common procedure for creation of $this->transactions; 
	 * specific strategies will just return $this->transactions in the right format. 
	 * 
	 * Possible procedure:
	 *
	 *  For each target
	 *  	Search for each line from external DB on internal DB (with DB query)
	 * 			If find or don't, do something (create transaction for create/update)
	 * 		Search for each line from internal DB on external DB (with DB query)
	 * 			If don't find, do something (create transaction for delete)
	 *
	 *	Return transactions in array format.
	 */
}
