<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseList($db, $serverType) {
		
		$recordsLoader = new serverContext($serverType, 'courses');
		
		//$recordsLoader->serverQuery($db, $query);
		
		//Temporary for learning and testing...
		$query = 'select cod_curso, nome_curso from Cursos';
		
		return $recordsLoader->serverQuery($db, $query);
		
		return null;
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function addCourse($db, $serverType, $course) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateCourse($db, $serverType, $course) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourse($db, $serverType, $course) {
	}
	
	public function serverQuery($string, $serverType){
	
	}
}
