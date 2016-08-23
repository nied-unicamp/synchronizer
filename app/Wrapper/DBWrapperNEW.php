<?php

require_once '../Strategy/serverStrategy.php';

/**
 * TODO Try to encapsulate trycatchs blocks; test bindValue.
 */
class DBWrapper extends serverStrategy {

	private static $conn = NULL;
	/*
	 * TODO Create a method to use $conn->exec from PDO.
	 * */
	
// 	public function __construct($confBD){
// 		$this->conn = $this->createConnection($confDB);
// 	}
	
	public function operationOrder($confDB, $query, $prepare=false, $values=NULL) {
		
		try {
			
			/*
			 * Use the value of $conf[0] (which contains serverType) to discover the database implementation and be able to connect.
			 * TODO Implement this with strategy if someday the system allow the use of non mysql implementations.
			 * */
			
			//if($this->conn == NULL)
			//{
				$this->conn = $this->createConnection($confDB);
				echo 'Criei agora a conexao.';
				var_dump($this->conn);
				echo '<br><br>';
			//}
			
			if($prepare){
				
				//$conn = $this->createConnection($confDB);
				
				$stmt = $this->conn->prepare($query);
				
				/* Needs the & because bindParam expects a variable!
				 * TODO Test with bindValue.*/
	 			foreach ($values as $key => &$value) {
					
	 				$stmt->bindParam($key+1, $value);
	 			}
				var_dump($stmt);
				return $stmt->execute();
				
			}

			return $this->conn->exec($query);
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
			/*
			 * Use the value of $conf[0] (which contains serverType) to discover the database implementation and be able to connect.
			 * TODO Implement this with strategy if someday the system allow the use of non mysql implementations.
			 * */

			//if($this->conn == NULL)
			//{
				$this->conn = $this->createConnection($confDB);
			//	echo 'Criei agora a conexao.';
			//	var_dump($this->conn);
			//	echo '<br><br>';
			//}
			
			$result = $this->conn->query($query);
			echo 'Quero executar: ';
			var_dump($query);
			echo '<br><br>';
			
			echo 'var_dump de $this->conn: ';
			var_dump($this->conn);
			
			echo '<br><br>';
			echo 'var_dump de $this->conn->query($query): ';
			var_dump($result);
			echo '<br><br>';
			
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
	
	private function createConnection($confDB){
		switch ($confDB[0]) {
			case "SERVER_TYPE_MYSQL":
				$dbInfo = 'mysql:host=' . $confDB[1] . ';port=' . $confDB[2] . ';dbname=' . $confDB[3];
				
				return new PDO($dbInfo, $confDB[4], $confDB[5], array(
    PDO::ATTR_PERSISTENT => true
));
		
			default:
				throw new PDOException(
				"<h2>ERROR: Couldn't connect to database. Please check the information given about the external database.</h2>"
						);
						trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
						break;
		}
	}
}

