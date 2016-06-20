<?php

require_once '../Strategy/serverStrategy.php';

/**
 * TODO Auto-generated comment.
 */
class DBWrapper extends serverStrategy {

	/**
	 * Sends a query to the database.
	 * 
	 * @param $confDB A string prepared for the creation of a connection to the database.
	 * @param #query The query that will be sent to the database.
	 * 
	 * TODO Find a way to get here with login  and password for database access.
	 * TODO Find a way to know if its necessary to use conn->query or conn->exec. 
	 */
	public function dataRequest($confDB, $query) {
		
		$data = array();
		
		try {
			
			
			// Temporary, for tests.
			$conn = new PDO($confDB, 'root', 'Sxp01zN');
			$result = $conn->query($query);
			
			if($result){
				
				//Build an array with a row in each element. Each row is also a array with the data
				//of the line from the table.
				while($row = $result->fetch(PDO::FETCH_ASSOC)){
					array_push($data, $row);
				}			
			}
			
		} 
		catch (PDOException $e) {
			echo "Couldn't connect to database: " . $e->getMessage();
		}

		
		return $data;
		
	}
}
