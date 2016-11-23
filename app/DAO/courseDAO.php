<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class courseDAO implements abstractDAO {

	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
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

	public function getCourseByName($dbInfo, $internal, $courseName)
	{
		if($internal)
		{
			$query = "SELECT Cursos.nome_curso AS courseName, Cursos_pastas.pasta AS category 
					FROM Cursos 
					LEFT JOIN Cursos_pastas 
					ON Cursos.cod_pasta=Cursos_pastas.cod_pasta 
					WHERE Cursos.nome_curso=?";
			
			//$query = 'SELECT * FROM coursesCache where courseName=?';
			
			return $this->dbAccess->manipulateData($dbInfo, $query, true, array($courseName));
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 'SELECT * FROM courses WHERE courseName=?', true, array($courseName));
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
