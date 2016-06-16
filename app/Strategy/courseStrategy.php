<?php

require_once 'formatStrategy.php';
require_once '../DAO/abstractDAO.php';
require_once '../DAO/courseDAO.php';

/**
 * TODO Auto-generated comment.
 */
class courseStrategy extends formatStrategy {
	
	//private $courseDaoObject;
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getList($db, $serverType) {
		
		$this->daoObject = new courseDAO();
		
		return $this->daoObject->getCourseList($db, $serverType);
		return null;
	}
}
