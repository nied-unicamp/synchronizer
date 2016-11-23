<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class coursememberDAO implements abstractDAO {

	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseMemberList($dbInfo, $serverType, $internal) {

		$recordsLoader = new serverContext($serverType, 'coursemember');

		if($internal)
		{
			$query = "
						SELECT Usuario.login, Cursos.nome_curso AS courseName, Usuario_curso.tipo_usuario AS role
						from Usuario_curso
						INNER JOIN Cursos
						ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario
						ON cod_usuario_global=Usuario.cod_usuario;
					";
			return $recordsLoader->serverQuery($dbInfo, $query);
		}

		$query = 'SELECT login, courseName, role FROM coursemember';
		
		return $recordsLoader->serverQuery($dbInfo, $query);
	}

	public function getCourseMemberByPair($dbInfo, $internal, $courseName, $login)
	{
		if($internal)
		{
			$query = "
						SELECT Usuario.login, Cursos.nome_curso AS courseName, Usuario_curso.tipo_usuario AS role
						from Usuario_curso
						INNER JOIN Cursos
						ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario
						ON cod_usuario_global=Usuario.cod_usuario
						WHERE Cursos.nome_curso=?
						AND Usuario.login=?";
								
			return $this->dbAccess->manipulateData($dbInfo, $query, true, array($courseName, $login));
		}
		
		return $this->dbAccess->manipulateData($dbInfo, 
								   		       "select * from coursemember where courseName=? AND login=?", 
											   true, 
											   array($courseName, $login));
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function addCourseMember($dbInfo, $serverType, $courseMember) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateCourseMember($dbInfo, $serverType, $courseMember) {
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourseMember($dbInfo, $serverType, $courseMember) {
	}

	public function serverQuery($string, $serverType){

	}
}
