<?php

require_once 'formatStrategy.php';
require_once '../DAO/abstractDAO.php';
require_once '../DAO/userDAO.php';

/**
 * TODO Auto-generated comment.
 */
class userStrategy extends formatStrategy {

	private $userDaoObject;
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getList($db, $serverType) {
		
		$this->userDaoObject = new userDAO();
		
		return $this->userDaoObject->getUserList($db, $serverType);
		
		//return null;
	}
}
