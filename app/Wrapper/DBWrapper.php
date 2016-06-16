<?php

require_once '../Strategy/serverStrategy.php';

/**
 * TODO Auto-generated comment.
 */
class DBWrapper extends serverStrategy {

	/**
	 * TODO Auto-generated comment.
	 */
	public function dataRequest($confDB, $query) {
		
		$data = array();
		
		try {
			
			$conn = new PDO($confDB, 'root', 'Sxp01zN');
			$result = $conn->query($query);
			
			if($result){
				
				while($row = $result->fetch(PDO::FETCH_ASSOC)){
					//temporary, for learning and testing...
					//var_dump($row);
					array_push($data, $row);
					
					//echo $row['nome'] . <br>;
				}			
			}
		} catch (PDOException $e) {
			echo "Couldn't connect to database: " . $e->getMessage();
		}
		
//var_dump($data);

		return $data;
		
	}
}
