<?php

require_once '../Model/transaction.php';
require_once '../Wrapper/DBWrapper.php';


/**
 * TODO Auto-generated comment.
 */
abstract class dataStrategy {

	/**
	 * TODO Auto-generated comment.
	 */
	private $transactions;
	
	/**
	 * Common procedure for creation of $this->transactions;
	 */
	public function diff($externalList, $cacheList, $formatType, $confDB) {
		
		$this->transactions = array();
		
		if(isset($externalList['users']))
		{
			
			// $user is an array with keys login, name and email.
			foreach ($externalList['users'] as $key => $user) 
			{
				$transaction = $this->checkUser($user, $cacheList, $confDB);
				if ($transaction != null)
				{
					array_push($this->transactions, $transaction);
				}
			}
		/*
	 *  	Search for each line from externalList on internal DB (with DB query)
	 * 			If find or don't, do something (create transaction for create/update)
	 * 		Search for each line from internallist on external DB (with DB query)
	 * 			If don't find, do something (create transaction for delete on internal list)
		*/
		}
		
// 		if(isset($externalList['courses']))
// 		{
	
// 			foreach ($externalList['courses'] as $key => $course)
// 			{		
// 				$transaction = $this->checkCourse($course, $cacheList);
// 				if ($transaction != null)
// 				{
// 					array_push($this->transactions, $transaction);
// 				}
// 			}
			
			
// 					/*
// 	 *  	Search for each line from externalList on internal DB (with DB query)
// 	 * 			If find or don't, do something (create transaction for create/update)
// 	 * 		Search for each line from internallist on external DB (with DB query)
// 	 * 			If don't find, do something (create transaction for delete on internal list)
// 		*/
// 		}
		
// 		if(isset($externalList['coursemember']))
// 		{	
// 					/*
// 	 *  	Search for each line from externalList on internal DB (with DB query)
// 	 * 			If find or don't, do something (create transaction for create/update)
// 	 * 		Search for each line from internallist on external DB (with DB query)
// 	 * 			If don't find, do something (create transaction for delete on internal list)
// 		*/
// 		}
		
		
		
// 		/* 
// 		 * specific strategies will just return $this->transactions in the right format.
// 		 *
// 		 * Possible procedure:
// 		 *
// 		 *  For each target
// 		 *  	Search for each line from external DB on internal DB (with DB query)
// 		 * 			If find or don't, do something (create transaction for create/update)
// 		 * 		Search for each line from internal DB on external DB (with DB query)
// 		 * 			If don't find, do something (create transaction for delete)
// 		 *
// 		 *	Save $this->transactions in array format.
// 		 */
		
		//var_dump($this->transactions);

		return $this->transactions;
	}
	
	/**
	 * Check if $user is inside $list.
	 * If the user is inside the list, and is the same as in the list, return false.
	 *  
	 * @param $user list An array with keys login, name and email.
	 * @param $list list A list of users.
	 *  
	 * @return boolean true if $user is in list and is exactly the same;
	 * false if ;
	 * 
	 * */
	private function checkUser($user, $list, $confDB)
	{

		/*Search for $user in db and put data in TEuser*/
		
		$dbAccess = new DBWrapper();
		/*TEMPORARY! Dont put in production with this!!!!!!*/
		$TEuser = $dbAccess->dataRequest($confDB, "select * from usersCache where login='". $user['login'] . "';");
		
// 		var_dump($TEuser[0]);
// 		echo "<br>";
// 		var_dump($user);
// 		echo "<br>";
			
		if($TEuser[0]['login'] == $user['login'])
		{
			if($TEuser[0] == $user)
			{
				return new transaction('do nothing!', 'users', $user);
				//return null;
			}
			return new transaction('update', 'users', $user);
		}
		
		return new transaction('insert', 'user', $user);
	}
	
	
	
	
	private function checkCourse($course, $list, $confDB)
	{
		
		/*Search for $user in db and put data in TEuser*/
		
		$dbAccess = new DBWrapper();
		/*TEMPORARY! Dont put in production with this!!!!!!*/
		$TEuser = $dbAccess->dataRequest($confDB, "select * from coursesCache where courseName='". $course['courseName'] . "';");
		
		if($TEuser[0]['courseName'] == $user['courseName'])
		{
			if($TEcourse[0] == $course)
			{
				return null;
			}
			return new transaction('update', 'course', $course);
		}
		
		return new transaction('insert', 'course', $course);
	}
	
	//login, courseName, role
	private function checkCourseMember($coursemember, $list)
	{
		foreach ($list['courses'] as $key => $TEcoursemember)
		{
			if($TEcoursemember['courseName'] == $user['courseName'] && $TEcoursemember['login'] == $user['login'])
			{
				if($TEcoursemember == $coursemember)
				{
					return null;
				}
				return new transaction('update', 'coursemember', $course);
			}
		}
		return new transaction('insert', 'coursemember', $course);
	}
	
	

}
