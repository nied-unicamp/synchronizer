<?php
/**
 * TODO Auto-generated comment.
 */
class diffController {
	/**
	 * TODO Auto-generated comment.
	 */
	private $externalList;
	/**
	 * TODO Auto-generated comment.
	 */
	private $cacheList;
	/**
	 * TODO Auto-generated comment.
	 */
	private $transactions;

	/**
	 * Returns a list with all courses, relations between course and membres, and users
	 * from a database or similar.
	 * 
	 * @param $db A database or archive string identifier.
	 * @param $dataType Specifies if the returned list shoul contain members, users or 
	 * course member relations.
	 * @param $serverType Specifies if the source of data is a database, a archive or
	 * some other thing.
	 * 
	 * @return 
	 */
	public function configDB($db, $dataType, $serverType) {
		
		$strategyManager = new DAOContext($dataType);
		
		return $strategyManager->getList($db, $serverType);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function createDiff($externalList, $cacheList, $formatType) {
		return null;
	}
}
