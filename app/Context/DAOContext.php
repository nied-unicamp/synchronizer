<?php
/**
 * TODO Auto-generated comment.
 */
class DAOContext {
	/**
	 * TODO Auto-generated comment.
	 */
	private $format;
	/**
	 * TODO Auto-generated comment.
	 */
	private $formatType;
	/**
	 * TODO Auto-generated comment.
	 */
	private $strategy;

	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($formatType) {
		$this->format = $formatType;
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function getList($db, $serverType) {
		
		return array($this->format);
	}
}
