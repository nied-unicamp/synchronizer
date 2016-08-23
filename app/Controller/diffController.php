<?php

require_once '../Context/DAOContext.php';
require_once '../Context/DifferContext.php';
require_once '../DAO/cacheDBDAO.php';

/*TODO Create file for defining constants.*/
//define('SERVER_TYPE_MYSQL', 'SERVER_TYPE_MYSQL');

/**
 * This class validates the input given in the default page of synchronizer, call another 
 * classes's methods in order to load the databases's infos, and calls another classes's 
 * methods in order to trigger the synchronization process.
 */
class diffController {
	/**
	 * Contains an array with the external data.
	 */
	private $externalList;
	/**
	 * Contains an array with the internal TelEduc's data.
	 */
	private $cacheList;
	/**
	 * Contains an array with transactions, atomic operations that has to be done for the 
	 * internal database synchronization. 
	 */
	private $transactions;
	
	/**
	 * Returns a list with all courses, relations between course and membres, and users
	 * from a database or similar.
	 * 
	 * @param $dbInfo An array with info about a database, or an archive string identifier.
	 * @param $syncTargets Specifies if the returned list shoul contain members, users or 
	 * 		  course member relations.
	 * @param $serverType Specifies if the source of data is a database, a archive or
	 * some other thing.
	 * 
	 * @return 
	 */
	public function configDB($dbInfo, $syncTargets, $serverType) {
		
		$databaseData = array();	
		
		/*
		 * Validates the target information for sync process.
		 * */
		try {
			
			$this->validateTargets($syncTargets);
			
		} catch (Exception $e) {
			
			echo '<h2>Exception: ' . $e->getMessage() . '</h2><br>';
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
			return null;
		}
		
		/*
		 * Validates the server information for sync process.
		 * */
		try {
				
			$this->validateServer($serverType);
			if(is_array($dbInfo))
			{
				$this->validateServer($dbInfo[0]);
			}
				
		} catch (Exception $e) {
				
			echo '<h2>Exception: ' . $e->getMessage() . '</h2><br>';
			trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
			return null;
		}
		
		/*
		 * Iterates thougth the targets, getting a list of the data for each target.
		 * */
		foreach($syncTargets as $key => $target)
		{
			/*
			 * Creates a object according to the target information.
			 * */
			try {
				$differentiator = new DAOContext($target);
				
			} catch (Exception $e) {
				echo '<h2>Exception: ' . $e->getMessage() . '</h2><br>';
				trigger_error ('<h2>Exception: ' . $e->getMessage() . '</h2><br>', E_USER_ERROR);
				return null;
			}
			
			/*
			 * Uses the object created to get the data list, and puts that list in an array with all 
			 * the other data from other targets
			 * */
			$databaseData[$target] = $differentiator->getList($dbInfo, $serverType);
			
			unset($differentiator);
		}
		
		return $databaseData;
	}

	/**
	 * Uses the method configDB to get two lists containg the external and internal data,
	 * and uses these lists to create a list with transactions, that are atomic operations
	 * needed to synchronize.
	 * 
	 * @param $confDB list			An array with all known information about the external data source.
	 * @param $confDbCache list		An array with all known information about the internal data source.
	 * @param $syncTargets list		An array with the information that has to be synchronized (can contain
	 * users, courses or coursemember).
	 * @param $serverType string	Defines the type of the external source data.
	 * 
	 * TODO $serverType with redundant information? serverType is the first element of $conDB.
	 * TODO Describe the structure of $confDB.
	 * 		
	 */
	//public function createDiff($externalList, $cacheList, $formatType) {
	public function createDiff($confDB, $confDbCache, $syncTargets, $serverType) {		
		
		$differentiator = new DifferContext('DATA_TYPE_JSON');
		
		$this->externalList = $this->configDB($confDB, $syncTargets, $serverType);
		
		/*
		 * Update cache tables.
		 * */
		$cacheDaoUpdater = new cacheDBDAO();
		$cacheDaoUpdater->updateCacheDB($this->confDBCache);
		
		// TODO Here, servertype has to be the internal teleduc's database? 
		$this->cacheList = $this->configDB($confDbCache, $syncTargets, 'SERVER_TYPE_MYSQL');
		
		/*
		 * TODO redundant information in parameter $formatType? See DifferContext.php.
		 * */
		return $differentiator->diff($externalList, $cacheList, $formatType);
	}
	
	/**
	 * Throws exception if the user has selected an invalid data type.
	 * Ains to avoid malicious code send througth $_POST.
	 * 
	 * @param unknown $syncTargets Contain a data type, expected to be on the synchonization tables.
	 * @throws Exception
	 * 
	 * @return void
	 */
	public function validateTargets($syncTargets){
		
		$validTargets = array('users', 'courses', 'coursemember');
		
		foreach($syncTargets as $key => $target)
		{
			if (in_array($target, $validTargets))
			{
				continue;
			}
			
			throw new Exception('Unkown target for syncronization: '.$target);

		}
	}
	
	/**
	 * Return true if $serverType is a valid server type. Throws exception otherwise.
	 * Ains to avoid malicious code sent througth $_POST.
	 * 
	 * @param string $serverType Contains a server type choosen by the user.
	 * @throws Exception
	 * 
	 * @return void
	 */
	public function validateServer($serverType){
		
		if(!isset($serverType) || $serverType == Null)
		{
			throw new Exception('Unkown server type for syncronization.');
		}
		
		/*Array with valid server types.*/
		$validTargets = array('SERVER_TYPE_MYSQL', 'json', 'xml', 'SERVER_TYPE_REST', 'csv');

		if (in_array($serverType, $validTargets))
		{
			return;
		}
		throw new Exception('Unkown server type for syncronization: ' . $serverType);
	}
}
