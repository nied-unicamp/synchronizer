<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseList($dbInfo, $serverType) {
		
		$recordsLoader = new serverContext($serverType, 'courses');
		
		//Temporary for learning and testing...
		
		$query = 'select cod_curso, nome_curso from Cursos';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function addCourse($dbInfo, $serverType, $course) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateCourse($dbInfo, $serverType, $course) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourse($dbInfo, $serverType, $course) {
	}
	
	public function serverQuery($string, $serverType){
	
	}
}
