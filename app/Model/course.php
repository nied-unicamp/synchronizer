<?php
/**
 * Class that represents a course.
 */
class course {
	
	private $courseName;
	private $category;
	
	public function __construct($courseName, $category)
	{
		$this->courseName = $courseName;
		$this->category = $category;	
	}
	
	public function getCourseName()
	{
		return $this->courseName;	
	}
	
	public function getCategory()
	{
		return $this->category;	
	}
}
