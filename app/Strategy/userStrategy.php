<?php

require_once 'formatStrategy.php';
require_once '../DAO/abstractDAO.php';
require_once '../DAO/userDAO.php';

/**
 * TODO Auto-generated comment.
 */
class userStrategy extends formatStrategy {

	//private $userDaoObject;
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getList($dbInfo, $serverType) {
		
		$this->daoObject = new userDAO();
		
		return $this->daoObject->getUserList($dbInfo, $serverType);
	}
}
