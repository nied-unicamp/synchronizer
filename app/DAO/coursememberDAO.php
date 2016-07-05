<?php

require_once '../Context/serverContext.php';

/**
 * TODO Auto-generated comment.
 */
class coursememberDAO implements abstractDAO {

	/**
	 * TODO Auto-generated comment.
	 */
	public function getCourseMemberList($dbInfo, $serverType) {
		
		$recordsLoader = new serverContext($serverType, 'coursemember');
		
		//Temporary for learning and testing...
		$query = "
				SELECT Usuario.login, Cursos.nome_curso, Usuario_curso.tipo_usuario 
					from Usuario_curso INNER JOIN Cursos 
						ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario 
							ON cod_usuario_global=Usuario.cod_usuario;
				";
		
		return $recordsLoader->serverQuery($dbInfo, $query);
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
