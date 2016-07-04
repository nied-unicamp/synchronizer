<?php

require_once '../Strategy/userStrategy.php';
require_once '../Strategy/courseStrategy.php';
require_once '../Strategy/coursememberStrategy.php';


/**
 * This class chooses an algorithm according to the data type that will be synchronized.
 */
class DAOContext {
	/**
	 * Target for synchronization. Can be users, courses or coursemember.
	 */
	private $formatType;
	/**
	 * Strategy object created according to the value of $formatType.
	 */
	private $formatStrategy;

	/**
	 * Gives a value to $formatType and instantiates an strategy object according to this value.
	 * @param $formatType string 	Target for synchronization. Can be users, courses or coursemember.
	 */
	public function __construct($formatType) {

		$this->formatType = $formatType;
		
		switch ($formatType) {
		
			case 'users':
				$this->formatStrategy = new userStrategy();
				break;
					
			case 'courses':
				$this->formatStrategy = new courseStrategy();
				break;
					
			case 'coursemember':
				$this->formatStrategy = new coursememberStrategy();
				break;
					
			default:
				throw new Exception('Unable to create a Strategy of the type data:'.$this->formatType);
		
		}
	}

	/**
	 * Uses the strategy object for getting a list of the data, according to $this->serverType.
	 * @param $dbInfo array 		An array containing all known information about the source of the data.
	 * @param $serverType string 	Defines the type of the source of data.
	 */
	public function getList($dbInfo, $serverType) {
		
		return $this->formatStrategy->getList($dbInfo, $serverType);
	}
}
