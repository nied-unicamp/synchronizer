<?php

require_once 'formatStrategy.php';
require_once '../DAO/abstractDAO.php';
require_once '../DAO/coursememberDAO.php';

/**
 * TODO Auto-generated comment.
 */
class coursememberStrategy extends formatStrategy {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getList($dbInfo, $serverType) {
		
		$this->daoObject = new coursememberDAO();
		
		return $this->daoObject->getCourseMemberList($dbInfo, $serverType);
	}
}
