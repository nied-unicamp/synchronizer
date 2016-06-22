<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class userDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getUserList($db, $serverType) {
		
		try {
			$recordsLoader = new serverContext($serverType, 'users');
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
			
		
		//$recordsLoader->serverQuery($db, $query);
		
		//Temporary for learning and testing...
		$query = 'select login, nome, email from Usuario';
		
		return $recordsLoader->serverQuery($db, $query);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function addUser($db, $serverType, $user) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateUser($db, $serverType, $user) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteUser($db, $serverType, $user) {
	}
	
	public function serverQuery($string, $serverType){
	
	}
}
