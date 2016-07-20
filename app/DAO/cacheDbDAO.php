<?php

class cacheDbDAO{
	
	
	public function updateCacheDb($confDbCache)
	{
		
		$cacheManager = new DBWrapper();
		
		/*
		 * Deletes users cache table, if exists.
		 * */
		if($cacheManager->operationOrder($confDbCache, "show tables like 'usersCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table usersCache');
		}
		
		/*
		 * Deletes courses cache table, if exists.
		 * */
		if($cacheManager->operationOrder($confDbCache, "show tables like 'coursesCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table coursesCache');
		}
		
		/*
		 * Deletes coursemember relations cache table, if exists.
		 * */
		if($cacheManager->operationOrder($confDbCache, "show tables like 'coursememberCache';")){
			$cacheManager->operationOrder($confDbCache, 'drop table coursememberCache');
		}
		
		/*nome, login email*/
		$users = $cacheManager($confDbCache, 'select login, nome, email from Usuario');
		
		/*cod_curso, nome_curso, categoria*/
		$courses = $cacheManager->operationOrder($confDbCache, "
				SELECT Cursos.cod_curso, Cursos.nome_curso, Cursos_pastas.pasta 
				FROM Cursos 
				LEFT JOIN Cursos_pastas 
				ON Cursos.cod_pasta=Cursos_pastas.cod_pasta;");
		
		/*cod_curso, nome, papel*/
		$coursemember = $cacheManager->operationOrder($confDbCache, "
				SELECT Usuario.login, Cursos.nome_curso, Usuario_curso.tipo_usuario 
				from Usuario_curso 
				INNER JOIN Cursos
				ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario 
				ON cod_usuario_global=Usuario.cod_usuario;
				");
		
		/*Login, nome, email*/
		$cacheManager->operationOrder($confDbCache, "
				CREATE TABLE usersCache
				(
				login varchar(128),
				name varchar(128),
				email varchar(128)
				);
				");
		/*nome_curso, categoria*/
		$cacheManager->operationOrder($confDbCache, "
				CREATE TABLE coursesCache
				(
				courseName varchar(128),
				category char(127)
				);
				");
		
		/*login, curso, papel*/
		$cacheManager->operationOrder($confDbCache, "
				CREATE TABLE usersCache
				(
				login varchar(128),
				courseName varchar(128),
				role varchar(128)
				);
				");
		
		
		
		/*
		 * Possible algorithm:
		 * 
		 * Delete cache table;OK
		 * Read data from default table; OK
		 * Recreate cache table; OK
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