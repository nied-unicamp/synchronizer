<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class userDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getUserList($dbInfo, $serverType) {
		
		try {
			$recordsLoader = new serverContext($serverType, 'users');
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
			
		
		//$recordsLoader->serverQuery($dbInfo, $query);
		
		//Temporary for learning and testing...
		$query = 'select login, nome AS name, email from Usuario';
		
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
