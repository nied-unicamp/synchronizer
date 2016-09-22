<?php

/**
 * This class represents the data used to access some database.
 * */
class  DBInfo
{
	private $serverType;
	private $dbHost;
	private $dbPort;
	private $dbName;
	private $dbLogin;
	private $dbPassword;
	
	function __construct($serverType, $dbHost, $dbPort, $dbName, $dbLogin, $dbPassword) {
		$this->serverType = $serverType;
		$this->dbHost = $dbHost;
		$this->dbPort = $dbPort;
		$this->dbName = $dbName;
		$this->dbLogin = $dbLogin;
		$this->dbPassword = $dbPassword;
	}
	
	public function getserverType() {
		return $this->serverType;
	}
	
	public function getdbHost() {
		return $this->dbHost;
	}
	
	public function getdbPort() {
		return $this->dbPort;
	}
	
	public function getdbName() {
		return $this->dbName;
	}
	
	public function getdbLogin() {
		return $this->dbLogin;
	}
	
	public function getdbPassword() {
		return $this->dbPassword;
	}
}
