<?php

require_once dirname(__FILE__) . '/../View/errorView.php';
require_once dirname(__FILE__) . '/../DAO/userDAO.php';
require_once dirname(__FILE__) . '/../DAO/courseDAO.php';

/**
 * This class contains the errors thar must be showed to the user.
 * 
 * Possible errors: 
 * 	1 - One or more courses don't have a cordinator. (Show courses without cordinator.)
 *  2 - There are two or more courses with the same name. (Show repeated courseName and number of appearences.)
 *  3 - There are two or more users with the same login. (Show repeated logins and number of appearences.)
 *  4 - There are two or more users with the same email. (Show repeated emails and number of appearenced.)
 *  5 - There is a undescribed course. (Show name of the undescribed courses found in coursemember relations.)
 *  6 - There is a undescribed user. (Show name of the undescribed users found in coursemember relations.)
 */
class errorController {
	
	private $errorsFound;
	private $externalList;
	private $confDB;
	
	private $coursesWithoutCord;
	private $duplicateNameOfCourses;
	private $duplicateLogins;
	private $duplicateEmails;
	private $noDescribedCourse;
	private $noDescribedUser;

	function __construct($externalList, $confDB)
	{
		$this->errorsFound = false;
		$this->externalList = $externalList;
		$this->confDB = $confDB;
		
		$this->coursesWithoutCord = array();
		$this->duplicateNameOfCourses = array();
		$this->duplicateLogins = array();
		$this->duplicateEmails = array();
		$this->noDescribedCourse = array();
		$this->noDescribedUser = array();
	}
	
	public function searchErrors()
	{
		var_dump($this->externalList);
		
		// Iterates in externalList['courses'], for each course, searches for a cordinatorin the external db with coursemembers
															 //searches in DB and verify if returns only 1 course
		if(isset($this->externalList['courses']))
		{			
			foreach ($this->externalList['courses'] as $type => $data)
			{
				// searches for a cordinator for $data['course']
				
				
				// seraches for $data['course'] in external courses db and certificates that only 1 result is returned.
				
				
			}
		}
		
		// Iterates in externalList['users'], for each user, searches in DB and verify if returns only 1 user
		if(isset($this->externalList['users']))
		{
			foreach ($this->externalList['users'] as $type => $data)
			{
				// seraches for $data['login'] in external users db and certificates that only 1 result is returned.
				
				// seraches for $data['email'] in external users db and certificates that only 1 result is returned.
				
			}
		}
		
		// Iterar em externalList['coursemember'], para cada coursemember ver se o user esta na tabela externa de users, 
																	//   e ver se o curso esta na tabela externa de cursos.
		if(isset($this->externalList['coursemember']))
		{
			foreach ($this->externalList['coursemember'] as $type => $data)
			{
				//buscar um adm
				
			}
		}
		
		return false;
	}
	
	private function showErrors()
	{
		$errorPresenter = new errorView(
								array(
										'coursesWithoutCord'=> $this->coursesWithoutCord,
										'duplicateNameOfCourses'=> $this->duplicateNameOfCourses,
										'duplicateLogins'=> $this->duplicateLogins,
										'duplicateEmails'=> $this->duplicateEmails,
										'noDescribedCourse'=> $this->noDescribedCourse,
										'noDescribedUser'=> $this->noDescribedUser
									)
							);
		exit();
	}
	
	function foundMissingCord($couseName)
	{
		array_push($this->coursesWithoutCord, $couseName);
	} 
	
	function foundDuplicateCourse($couseName, $appearences)
	{
		array_push($this->duplicateNameOfCourses, array('Course name'=>$couseName, 'appearences'=>$appearences));
	}
	
	function foundDuplicatedLogin($login, $appearences)
	{
		array_push($this->duplicateLogins, array('login'=>$login,'appearences'=>$appearences));
	}
	
	function foundDuplicateEmail($email, $appearences)
	{
		array_push($this->duplicateEmails, array('email'=>$email, 'appearences'=>$appearences));
	}
	
	function foundNoDescribedCourse($courseName)
	{
		array_push($this->noDescribedCourse, $courseName);
	}
	
	function foundNoDescribedUser($login)
	{
		array_push($this->noDescribedUser, $login);
	}
}