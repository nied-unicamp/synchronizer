<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';
require_once dirname(__FILE__) . '/../Wrapper/DBWrapper.php';

/**
 * Class used when getting user data from database.
 */
class userDAO implements abstractDAO {

	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getUserList($dbInfo, $serverType, $internal) {
		
		try {
			$recordsLoader = new serverContext($serverType, 'users');
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
		
		if($internal)
		{
			$query = 'select login, nome AS name, email from Usuario';
			return $recordsLoader->serverQuery($dbInfo, $query);
		}

		$query = 'SELECT login, name, email FROM users';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	/**
	 * Only for sql database. 
	 * */
	public function getUserByLogin($dbInfo, $internal, $login)
	{
		if($internal)
		{
			return $this->dbAccess->manipulateData($dbInfo, 'SELECT login, name, email FROM Usuario WHERE login=?', true, array($login));
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 'SELECT * FROM users WHERE login=?', true, array($login));
	}
	
	/**
	 * Adds a user to the database.
	 * @param $user An user object.
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
