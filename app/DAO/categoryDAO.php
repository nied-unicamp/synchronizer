<?php

require_once dirname(__FILE__) . '/../Wrapper/DBWrapper.php'; 

class categoryDAO
{
	
	private $dbAccess;
	
	public function __construct()
	{
		$this->dbAccess = new DBWrapper();
	}
	
	public function getExtCategories($dbInfo)
	{
		$query = "select distinct category from courses";
		
		return $this->dbAccess->dataRequest($dbInfo, $query);
		
	}
	
	public function isNewCategory($dbInfo, $category)
	{
		$query="select cod_pasta from Cursos_pastas where pasta like ?";
		
		if(empty($this->dbAccess->dataRequest($dbInfo, $query, array($category))))
		{
			return true;
		}
		return false;
	}
	
	public function insertCategory($dbInfo, $category)
	{
		$query="insert into Cursos_pastas (pasta) values (?)";
		
		return $this->dbAccess->manipulateData($dbInfo, $query, true, array($category));
	}
	
	public function getCategoryCode($dbInfo, $category)
	{
		return $this->dbAccess->dataRequest($dbInfo, "select cod_pasta from Cursos_pastas where pasta like ?", array($category));
	}
}