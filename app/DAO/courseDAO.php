<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseList($dbInfo, $serverType, $internal) {

		$recordsLoader = new serverContext($serverType, 'courses');

		if($internal)
		{
			$query = "
					SELECT Cursos.nome_curso AS courseName, Cursos_pastas.pasta AS category
					FROM Cursos
					LEFT JOIN Cursos_pastas
					ON Cursos.cod_pasta=Cursos_pastas.cod_pasta;";
			return $recordsLoader->serverQuery($dbInfo, $query);
		}		

		$query = 'SELECT courseName, category FROM courses';
		
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
