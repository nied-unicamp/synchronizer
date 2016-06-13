<?php

require_once '../Context/DAOContext.php';

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
	 * 		  course member relations.
	 * @param $serverType Specifies if the source of data is a database, a archive or
	 * some other thing.
	 * 
	 * @return 
	 */
	public function configDB($db, $dataType, $serverType) {
		
		$databaseData = array();
		
		try {
			
			$this->validateTargets($dataType);
			
		} catch (Exception $e) {
			
			echo '<h2>Exception: ' . $e->getMessage() . '</h2><br>';
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
			return null;
		}
		
		foreach($dataType as $key => $target)
		{
			$differentiator = new DAOContext($target);
			array_push($databaseData, $differentiator->getList($db, $serverType));
			unset($differentiator);
		}
		
		return $databaseData;
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function createDiff($externalList, $cacheList, $formatType) {
		return null;
	}
	
	public function validateTargets($dataType){
		$validTargets = array('users', 'courses', 'coursemember');
		
		foreach($dataType as $key => $target)
		{
			if (in_array($target, $validTargets))
			{
				continue;
			}
			else
			{
				throw new Exception('Unkown target for syncronization: '.$target);
			}
		}
	}
}
