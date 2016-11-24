<?php
/**
 * Class thar represents an user.
 */
class user {
	private $login;
	private $name;
	private $email;
	
	public function __construct($login, $name, $email)
	{
		$this->login = $login;
		$this->name = $name;
		$this->email = $email;
	}
	
	public function getLogin()
	{
		return $this->login;	
	}
	
	public function getName()
	{
		return $this->name;	
	}
	
	public function getEmail()
	{
		return $this->email;
	}
}
