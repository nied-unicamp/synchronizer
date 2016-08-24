<?php

require_once '../Model/transaction.php';

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
	public function diff($externalList, $cacheList, $formatType) {
		
		$this->transactions = array();
		
		if(isset($externalList['users']))
		{
			
			// $user is an array with keys login, name and email.
			foreach ($externalList['users'] as $key => $user) 
			{
				
				$transaction = $this->checkUser($user, $cacheList);
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
		
		if(isset($externalList['courses']))
		{
			
			foreach ($externalList['courses'] as $key => $course)
			{
			
				$transaction = $this->checkCourse($course, $cacheList);
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
		
		if(isset($externalList['coursemember']))
		{
					/*
	 *  	Search for each line from externalList on internal DB (with DB query)
	 * 			If find or don't, do something (create transaction for create/update)
	 * 		Search for each line from internallist on external DB (with DB query)
	 * 			If don't find, do something (create transaction for delete on internal list)
		*/
		}
		
		
		
		/* 
		 * specific strategies will just return $this->transactions in the right format.
		 *
		 * Possible procedure:
		 *
		 *  For each target
		 *  	Search for each line from external DB on internal DB (with DB query)
		 * 			If find or don't, do something (create transaction for create/update)
		 * 		Search for each line from internal DB on external DB (with DB query)
		 * 			If don't find, do something (create transaction for delete)
		 *
		 *	Save $this->transactions in array format.
		 */
		
		return null;
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
	private function checkUser($user, $list)
	{
		foreach ($list['users'] as $key => $TEuser)
		{
			if($TEuser[login] == $user['login'])
			{
				if($TEuser == $user)
				{
					return null;
				}
				return new transaction('update', 'users', $user);
			}
		}
		return new transaction('insert', 'user', $operand);
	}
	
	private function checkCourse($course, $list)
	{
		foreach ($list['courses'] as $key => $TEcourse)
		{
			if($TEuser['courseName'] == $user['courseName'])
			{
				if($TEcourse == $course)
				{
					return null;
				}
				return new transaction('update', 'course', $course);
			}
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
	
	
	
	
	
	
	public function getTransactions()
	{
		return $this->transactions;
	}
	
	

}
