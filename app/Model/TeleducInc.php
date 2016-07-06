<?php

class TeleducInc {
	
	private $dbHost;
	private $dbPort;
	private $dbName;
	private $dbLogin;
	private $dbPassword;
	private $dbNameCurso;

	public function __construct()
	{
		include 'teleduc.inc';
		
		$this->dbHost = $_SESSION['dbhost'];
		$this->dbPort = $_SESSION['dbport'];
		$this->dbName = $_SESSION['dbnamebase'];
		$this->dbLogin = $_SESSION['dbtmpuser'];
		$this->dbPassword = $_SESSION['dbtmppassword'];
		$this->dbNameCurso = $_SESSION['dbnamecurso'];
	}
	
	public function buildConfDBCache(){
		return array(
						'SERVER_TYPE_MYSQL', $this->dbHost, 
						$this->dbPort, $this->dbName, 
						$this->dbLogin, $this->dbPassword		
					);
	}
	
	//TODO Create getters!!!
	
}