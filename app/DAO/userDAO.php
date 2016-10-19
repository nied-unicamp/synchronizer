<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class userDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getUserList($dbInfo, $serverType, $internal) {
		
		try {
			$recordsLoader = new serverContext($serverType, 'users');
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
			
		
		//$recordsLoader->serverQuery($dbInfo, $query);
		
		//Temporary for learning and testing...
		
		if($internal)
		{
			$query = 'select login, nome AS name, email from Usuario';
			return $recordsLoader->serverQuery($dbInfo, $query);
		}

		$query = 'SELECT login, name, email FROM users';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function addUser($dbInfo, $serverType, $user) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateUser($dbInfo, $serverType, $user) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteUser($dbInfo, $serverType, $user) {
	}
	
	public function serverQuery($string, $serverType){
	
	}
}
