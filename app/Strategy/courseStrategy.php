<?php

require_once 'formatStrategy.php';
require_once '../DAO/abstractDAO.php';
require_once '../DAO/courseDAO.php';

/**
 * Specific strategy for getting the course list from data source.
 */
class courseStrategy extends formatStrategy {
	
	//private $courseDaoObject;
	
	/**
	 * Gets a list of all the data from the type defined in the construction 
	 * of the object.
	 *  @param $db Info Array/string	Contains an array with the database information,
	 *  								or an string with a path to a file with data.
	 *  @param $serverType string		Defines the type of the data source.
	 */
	public function getList($dbInfo, $serverType, $internal=0) {
		
		/*
		 * This DAO object, atribute from a parent class, is created according to
		 * the strategy.
		 * */
		$this->daoObject = new courseDAO();
		
		return $this->daoObject->getCourseList($dbInfo, $serverType, $internal);

	}
}
