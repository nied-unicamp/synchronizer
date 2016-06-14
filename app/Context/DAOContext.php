<?php

require_once '../Strategy/userStrategy.php';
require_once '../Strategy/courseStrategy.php';
require_once '../Strategy/coursememberStrategy.php';


/**
 * TODO Auto-generated comment.
 */
class DAOContext {
	/**
	 * Source data type.
	 */
	private $formatType;
	/**
	 * TODO Auto-generated comment.
	 */
	private $formatStrategy;

	/**
	 * TODO Auto-generated comment.
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
	 * TODO Auto-generated comment.
	 */
	public function getList($db, $serverType) {
		
		$this->formatStrategy->getList($db, $serverType);
		
		return array($this->formatType);
	}
}
