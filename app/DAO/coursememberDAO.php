<?php

require_once dirname(__FILE__) . '/../Context/serverContext.php';
require_once dirname(__FILE__) . '/userDAO.php';
require_once dirname(__FILE__) . '/courseDAO.php';

/**
 * TODO Auto-generated comment.
 */
class coursememberDAO{

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
						ON cod_usuario_global=Usuario.cod_usuario
						WHERE not Usuario.login='admtele';
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
	
	public function getAllExternalRoles($dbInfo)
	{
		
		$query="select distinct role from coursemember";
		
		return $this->dbAccess->dataRequest($dbInfo, $query);
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
// 	public function addCourseMember($dbInfo, $serverType, $courseMember) {
// 	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateUserRole($dbInfo, $serverType, $courseMember) {
		$userDAOObj = new userDAO();
		$courseDAOObj = new courseDAO();
		
		$userCode = $userDAOObj->getUserCodeByLogin($dbInfo, $courseMember['login']);
		$courseCode = $courseDAOObj->getCourseCodByName($dbInfo, $courseMember['courseName']);
		
		$query = 'UPDATE Usuario_curso SET tipo_usuario=? WHERE cod_usuario_global=? AND cod_curso=?';
		
		$this->dbAccess->manipulateData($dbInfo, $query, true, array($courseMember['role'], $userCode, $courseCode));
	
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteCourseMember($dbInfo, $serverType, $courseMember) {
	}

}
