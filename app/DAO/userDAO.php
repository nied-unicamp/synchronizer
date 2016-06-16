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
		
		$recordsLoader = new serverContext($serverType, 'users');
		
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
