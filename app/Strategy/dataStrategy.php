<?php

require_once '../Model/transaction.php';
require_once '../Wrapper/DBWrapper.php';


/**
 * This class contains the algorithm that determines the necessary operations
 * for the stablishment of a synchronized stated with the external data.
 * Child classes will determine if the opreations needed will be represented
 * with an Json, xml or csv archive.
 * Operations needed are represented by an array of transactions in this class
 * father.
 */
abstract class dataStrategy {

	/**
	 * An array with all the necessary transactions for the execution of
	 * the sync process.
	 */
	private $transactions;

	/**
	 * Common procedure for creation of $this->transactions;
	 * @param $externalList List List with external data for sync.
	 * @param $cacheList List List with the internal data.
	 * @param $confDB An array with information for teleduc's database access.
	 *
	 * @return List with transactions needed to be perform in order to obtain
	 * a synchronized state of the system with the external data.
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

		if(isset($externalList['courses']))
		{

			foreach ($externalList['courses'] as $key => $course)
			{
				$transaction = $this->checkCourse($course, $cacheList, $confDB);
				if ($transaction != null)
				{
					array_push($this->transactions, $transaction);
				}
			}


// 					/*
// 	 *  	Search for each line from externalList on internal DB (with DB query)
// 	 * 			If find or don't, do something (create transaction for create/update)
// 	 * 		Search for each line from internallist on external DB (with DB query)
// 	 * 			If don't find, do something (create transaction for delete on internal list)
// 		*/
// 		}

		if(isset($externalList['coursemember']))
		{
			foreach ($externalList['coursemember'] as $key => $coursemember)
			{
				$transaction = $this->checkCourseMember($coursemember, $cacheList, $confDB);
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
	 * Check if $user is inside $list and determines if a transaction is needed.
	 *
	 * @param $user list An array with keys login, name and email.
	 * @param $list list A list of users.
	 * @param $confDB An array with information for teleduc's database access.
	 *
	 * @return A transaction if necessary, or null otherwise.
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



	/**
	 * Check if $course is inside $list and determines if a transaction is needed.
	 *
	 * @param $course list An array with courseName, and category.
	 * @param $list list A list of courses.
	 * @param $confDB An array with information for teleduc's database access.
	 *
	 * @return A transaction if necessary, or null otherwise.
	 *
	 * */
	private function checkCourse($course, $list, $confDB)
	{

		/*Search for $course in db and put data in TEusercourse*/

		$dbAccess = new DBWrapper();
		/*TEMPORARY! Dont put in production with this!!!!!!*/
		$TEcourse = $dbAccess->dataRequest($confDB, "select * from coursesCache where courseName='". $course['courseName'] . "';");

		if($TEcourse[0]['courseName'] == $course['courseName'])
		{
			if($TEcourse[0] == $course)
			{
				return new transaction('do nothing!', 'course', $course);
				//return null;
			}
			return new transaction('update', 'course', $course);
		}

		return new transaction('insert', 'course', $course);
	}

	/**
	 * Check if $coursemember is inside $list and determines if a transaction is needed.
	 *
	 * @param $coursemember list An array with coursename relations.
	 * @param $list list A list of coursemember relations.
	 * @param $confDB An array with information for teleduc's database access.
	 *
	 * @return A transaction if necessary, or null otherwise.
	 *
	 * */
	private function checkCourseMember($coursemember, $list)
	{

		/*Search for $coursemember in db and put data in TEcoursemember*/

		$dbAccess = new DBWrapper();
		/*TEMPORARY! Dont put in production with this!!!!!!*/
		$TEcoursemember = $dbAccess->dataRequest($confDB, "select * from coursememberCache where courseName='". $coursemember['courseName'] . "' AND login='" . $coursemember['login'] . "';'");

		if($TEcoursemember[0]['courseName'] == $coursemember['courseName'] && $TEcoursemember[0]['login'] == $coursemember['login'])
		{
			if($TEcoursemember[0] == $coursemember)
			{
				return new transaction('do nothing!', 'coursemember', $course);
				//return null;
			}
			return new transaction('update', 'coursemember', $course);
		}

		return new transaction('insert', 'coursemember', $course);
	}



}
