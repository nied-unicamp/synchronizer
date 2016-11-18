<?php

require_once dirname(__FILE__) . '/formatStrategy.php';
require_once dirname(__FILE__) . '/../DAO/abstractDAO.php';
require_once dirname(__FILE__) . '/../DAO/coursememberDAO.php';

/**
 * Specific strategy for getting the course/member relation list from data source.
 */
class coursememberStrategy extends formatStrategy {

	/**
	 * Gets a list of all the data from the type defined in the construction 
	 * of the object.
	 * 
	 *  @param $db Info Array/string	Contains an array with the database information,
	 *  								or an string with a path to a file with data.
	 *  @param $serverType string		Defines the type of the data source.
	 */
	public function getList($dbInfo, $serverType, $internal=0) {
		
		/*
		 * This DAO object, atribute from a parent class, is created according to
		 * the strategy.
		 * */
		$this->daoObject = new coursememberDAO();
		
		return $this->daoObject->getCourseMemberList($dbInfo, $serverType, $internal);
	}
}
