<?php
/**
 * Class that represents a relation between a user and a course:
 * if a coursemember object containing login as course exists,
 * then the user identified by that login participates in that course
 * and his role is identified by the role attribute of this class.
 */
class coursemember {
	
	private login;
	private course;
	private role;
	
	public function __construct($login, $course, $role)
	{
		$this->login = $login;
		$this->course = $course;
		$this->role = $role;	
	}
	
	public function getLogin()
	{
		return $this->login;	
	}
	
	public function getCourse()
	{
		return $this->course;	
	}
	
	public function getRole()
	{
		return $this->role;	
	}
}
