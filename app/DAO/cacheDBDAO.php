<?php

require_once '../Wrapper/DBWrapper.php';

class cacheDBDAO{
	
	
	public function updateCacheDB($confDBCache) {
		$cacheManager = new DBWrapper();
		
		$this->deleteCacheIfExists($cacheManager, $confDBCache);
		
		$this->createCacheTables($cacheManager, $confDBCache);
		
		$cacheData = $this->readDataForCache($cacheManager, $confDBCache);
		
		$this->insertDataIntoCache( $cacheManager, $confDBCache, 
									$cacheData['users'], $cacheData['courses'], $cacheData['coursemember']);
		
		unset($cacheManager);
		unset($cacheData);
		
		/*
		 * Possible algorithm:
		 * 
		 * Delete cache table;OK
		 * Read data from default tables; OK
		 * Recreate cache table; OK
		 * Insert read values into cache table. 
		 */
	}
	
	private function deleteCacheIfExists($cacheManager, $confDBCache) {
		/*
		 * Deletes users cache table, if exists.
		 * */
		if(!$cacheManager->operationOrder($confDBCache, "show tables like 'usersCache';")){
			$cacheManager->operationOrder($confDBCache, 'drop table usersCache');
		}
		
		/*
		 * Deletes courses cache table, if exists.
		 * */
		if(!$cacheManager->operationOrder($confDBCache, "show tables like 'coursesCache';")){
			$cacheManager->operationOrder($confDBCache, 'drop table coursesCache');
		}
		
		/*
		 * Deletes coursemember relations cache table, if exists.
		 * */
		if(!$cacheManager->operationOrder($confDBCache, "show tables like 'coursememberCache';")){
			$cacheManager->operationOrder($confDBCache, 'drop table coursememberCache');
		}
	}
	
	private function createCacheTables($cacheManager, $confDBCache) {
		
		/*Login, nome, email*/
		$cacheManager->operationOrder($confDBCache, "
				CREATE TABLE usersCache
				(
				login varchar(128),
				name varchar(128),
				email varchar(128)
				);
				");
		/*nome_curso, categoria*/
		$cacheManager->operationOrder($confDBCache, "
				CREATE TABLE coursesCache
				(
				courseName varchar(128),
				category char(127)
				);
				");
		
		/*login, curso, papel*/
		$cacheManager->operationOrder($confDBCache, "
				CREATE TABLE coursememberCache
				(
				login varchar(128),
				courseName varchar(128),
				role varchar(128)
				);
				");
	}
	
	private function readDataForCache($cacheManager, $confDBCache) {
		
		/*nome, login email*/
		$cacheData['users'] = $cacheManager->dataRequest($confDBCache, 'select login, nome AS name, email from Usuario');
		
		/*cod_curso, nome_curso, categoria*/
		$cacheData['courses'] = $cacheManager->dataRequest($confDBCache, "
				SELECT Cursos.nome_curso AS courseName, Cursos_pastas.pasta AS category
				FROM Cursos
				LEFT JOIN Cursos_pastas
				ON Cursos.cod_pasta=Cursos_pastas.cod_pasta;");
		
		/*cod_curso, nome, papel*/
		$cacheData['coursemember'] = $cacheManager->dataRequest($confDBCache, "
				SELECT Usuario.login, Cursos.nome_curso AS courseName, Usuario_curso.tipo_usuario AS role
				from Usuario_curso
				INNER JOIN Cursos
				ON Cursos.cod_curso=Usuario_curso.cod_curso LEFT OUTER JOIN Usuario
				ON cod_usuario_global=Usuario.cod_usuario;
				");
		
		return $cacheData;
	}
	
	private function insertDataIntoCache($cacheManager, $confDBCache, $users, $courses, $coursemember) {
		
		foreach ($users as $key => $value) {
			
			// This commented code just prints the values. Used for tests.
// 			echo $value['login'] . ' ' . $value['name'] . ' ' . $value['email'];
// 			echo '<br>
// 					';
			
			$cacheManager->operationOrder(  $confDBCache, 
											"INSERT INTO usersCache (login, name, email) VALUES (?,?,?)", 
											true, 
											array($value['login'], $value['name'], $value['email']));
		}
		
		foreach ($courses as $key => $value) {
			
			/* This commented code just prints the values. Used for tests.
			echo $value['courseName'] . '  __  ' . $value['category'];
			echo '<br>
					';
			*/
			$cacheManager->operationOrder(  $confDBCache,
											"INSERT INTO coursesCache (courseName, category) VALUES (?,?)",
											true,
											array($value['courseName'], $value['category']));
		}
		
		//var_dump($coursemember);
		foreach ($coursemember as $key => $value) {
			
			/* This commented code just prints the values. Used for tests.
			echo $value['login'] . '  __  ' . $value['courseName'] . '  __  ' . $value['role'];
			echo '<br>
					';
			*/
			
			$cacheManager->operationOrder(  $confDBCache,
											"INSERT INTO coursesCache (login, courseName, role) VALUES (?,?,?)",
											true,
											array($value['login'], $value['courseName'], $value['role']));
		}
	}
}