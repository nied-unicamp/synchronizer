<?php

require_once '../Strategy/serverStrategy.php';

/**
 * TODO Try to encapsulate trycatchs blocks; test bindValue.
 */



class DBWrapper extends serverStrategy {

	private $executionReturnValue;
	
	public function operationOrder($confDB, $query, $prepare=false, $values=NULL) {
		
		try {

			if($prepare){
				
				$conn = $this->createConnection($confDB);
				$stmt = $conn->prepare($query);
				
				
				/* Needs the & because bindParam expects a variable!
				 * TODO Test with bindValue.
				 * TODO Try catch here.*/
	 			foreach ($values as $key => &$value) {
					
	 				$stmt->bindParam($key+1, $value);
	 			}
	 			
	 			$this->executionReturnValue = $stmt->execute();
	 			
	 			if($this->executionReturnValue)
	 			{
	 				$data = array();
	 				while($row = $stmt->fetch(PDO::FETCH_ASSOC))
	 				{
	 					array_push($data, $row);
	 				}	
	 				return $data;
	 			}
	 			
				throw new PDOException("<h2>ERROR: Couldn't execute query.</h2>");
				//trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
				
			}
		
			$conn = $this->createConnection($confDB);
			return $conn->exec($query);
		}
		catch (PDOException $e) {
				
			echo "<h2>ERROR: Couldn't connect to database. Please check the information given about the external database.</h2>";
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
				
		}
	}
	
	/**
	 * Sends a query to the database.
	 * 
	 * @param $confDB A array containing the parameters that must be used for connection with PDO.
	 * 			$confDB can be described as: 
	 * 			$confDB = array(serverType, dbHost, dbPort, dbName, dbLogin, dbPassword).
	 * @param $query The query that will be sent to the database.
	 * 
	 * TODO Find a way to know if its necessary to use conn->query or conn->exec. 
	 */
	public function dataRequest($confDB, $query) {
		
		$data = array();
		
		try {

			$conn = $this->createConnection($confDB);
			$result = $conn->query($query);
			
			if($result){
				
				/*
				 * Build an array with a row in each element. Each row is also a array with the data
				 * of the line from the table.
				 * */
				while($row = $result->fetch(PDO::FETCH_ASSOC)){
					array_push($data, $row);
				}			
			}
			
		} 
		catch (PDOException $e) {
			
			echo "<h2>ERROR: Couldn't connect to database. Please check the information given about the external database.</h2>";
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
			
		}

		
		return $data;
		
	}
	
	public function tableExists($confDB, $tableName)
	{
		//TODO Try catch here.
		try {
			$pdo = $this->createConnection($confDB);
		} catch (Exception $e) {
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
		}
		
		$query = "SHOW TABLES LIKE :tablename";
		$safeQuerier = $pdo->prepare($query);
		$safeQuerier->bindParam(":tablename", $tableName,  PDO::PARAM_STR);
		
		$sqlResult = $safeQuerier->execute();
		
		if($sqlResult)
		{
			$row = $safeQuerier->fetch(PDO::FETCH_NUM);
			if ($row[0]) {
				//table was found
				return true;
			} 
			//table was not found
			return false;
		}
		
		//some PDO error occurred
		echo("Could not check if table exists, Error: ".var_export($pdo->errorInfo(), true));
		return false;
	}
	
	private function createConnection($confDB){
			
		/*
		 * Use the value of $conf->getServerType to discover the database implementation and be able to connect.
		 * TODO Implement this with strategy if someday the system allow the use of non mysql implementations.
		 * */
		switch ($confDB->getserverType()) {
			case "SERVER_TYPE_MYSQL":
// 				$dbInfo = 'mysql:host=' . $confDB[1] . ';port=' . $confDB[2] . ';dbname=' . $confDB[3];
				$dbInfo = 'mysql:host=' . $confDB->getdbHost() . ';port=' . $confDB-> getdbPort() . ';dbname=' . $confDB->getdbName();
				return new PDO($dbInfo, $confDB->getdbLogin(), $confDB->getdbPassword());
		
			default:
				throw new PDOException(
				"<h2>ERROR: Couldn't connect to database. Please check the information given about the external database.</h2>"
						);
						break;
		}
	}
}
