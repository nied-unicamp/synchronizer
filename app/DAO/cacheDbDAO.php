<?php

class cacheDbDAO{
	
	
	public function updateCacheDb($confDbCache)
	{
		
		$cacheManager = new DBWrapper();
		
		/*nome, login email*/
		if($cacheManager->operationOrder($confDbCache, "show tables like 'usersCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table usersCache');
		}
		
		/*cod_curso, nome_curso, categoria*/
		if($cacheManager->operationOrder($confDbCache, "show tables like 'coursesCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table usersCache');
		}
		
		/*cod_curso, nome, papel*/
		if($cacheManager->operationOrder($confDbCache, "show tables like 'coursememberCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table usersCache');
		}
		
		$users = $cacheManager($confDbCache, 'select login, nome, email from Usuario');
		
		/*
		 * TODO Discover sql query for getting course category.
		 * */
		$courses = $cacheManager->operationOrder($confDbCache, 'select cod_curso, nome_curso from Cursos');
		
		$coursemember = $cacheManager->operationOrder($confDbCache, "
				SELECT Usuario.login, Cursos.nome_curso, Usuario_curso.tipo_usuario 
					from Usuario_curso INNER JOIN Cursos 
						ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario 
							ON cod_usuario_global=Usuario.cod_usuario;
				");
		/*
		 * Possible algorithm:
		 * 
		 * Delete cache table;O K
		 * Read data from default table;
		 * Recreate cache table;
		 * Insert read values into cache table. 
		 */
		
		/*
		 * Update the users's cache table.
		 * */
		
		/*
		 * Update the courses's cache table.
		 * */
		
		/*
		 * Update the coursemember's cache table. 
		 * */
	}
	
}