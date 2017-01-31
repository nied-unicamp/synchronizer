<?php

require_once dirname(__FILE__) . '/../View/errorView.php';
require_once dirname(__FILE__) . '/../DAO/userDAO.php';
require_once dirname(__FILE__) . '/../DAO/courseDAO.php';
require_once dirname(__FILE__) . '/../DAO/userDAO.php';
require_once dirname(__FILE__) . '/../DAO/coursememberDAO.php';

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
	private $invalidRoles;

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
		
		$this->invalidRoles = array();
	}
	
	public function searchErrors()
	{
		echo "VARDUMP DE EXTERNALLIST<br>";
		var_dump($this->externalList);
		echo "<br><br>";
		
		$courseDAOObject = new courseDAO();
		$userDAOObject = new userDAO();
		
		// Iterates in externalList['courses'], for each course, searches for a cordinator in the external db with coursemembers
															 //searches in DB and verify if returns only 1 course
		if(isset($this->externalList['courses']))
		{			

			foreach ($this->externalList['courses'] as $key => $course)
			{
				// Search for coordinator
				
				$cordinators = $courseDAOObject->getCourseCordList($this->confDB, false, $course['courseName']);
				
				if(empty($cordinators))
				{
					$this->errorsFound = true;
					array_push($this->coursesWithoutCord, $course['courseName']);
				}
				
				// Verify no repeated course
				$coursesWithThisName = $courseDAOObject->getCourseByName($this->confDB, false, $course['courseName']);
				
				$this->checkDuplicateData($coursesWithThisName, $this->duplicateNameOfCourses, $course, 'courseName');
			}
		}
		
		// Iterates in externalList['users'], for each user, searches in DB and verify if returns only 1 user
		if(isset($this->externalList['users']))
		{
			
			foreach ($this->externalList['users'] as $userData)
			{
				// searches for $userData['login'] in external users db and certificates that only 1 result is returned.
				$usersWithThisLogin = $userDAOObject->getUserByLogin($this->confDB, false, $userData['login']);
				$this->checkDuplicateData($usersWithThisLogin, $this->duplicateLogins, $userData, 'login');
				
				
				// searches for $userData['email'] in external users db and certificates that only 1 result is returned.
				$usersWithThisEmail = $userDAOObject->getUserByEmail($this->confDB, false, $userData['email']);
				$this->checkDuplicateData($usersWithThisEmail, $this->duplicateEmails, $userData, 'email');
			}
		}
		
		// Iterar em externalList['coursemember'], para cada coursemember ver se o user esta na tabela externa de users, 
																	//   e ver se o curso esta na tabela externa de cursos.
		if(isset($this->externalList['coursemember']))
		{
			foreach ($this->externalList['coursemember'] as $type => $coursemember)
			{
				// search user
				$userInCourse = $userDAOObject->getUserByLogin($this->confDB, false, $coursemember['login']);
				
				if(empty($userInCourse))
				{
					$this->errorsFound = true;
					
					array_push($this->noDescribedUser, array('login' => $coursemember['login'], 'course' => $coursemember['courseName']));
				}
				
				// search course
				$coursesWithThisName = $courseDAOObject->getCourseByName($this->confDB, false, $coursemember['courseName']);
				
				if(empty($userInCourse))
				{
					$this->errorsFound = true;
					
					array_push($this->noDescribedCourse, $coursemember['courseName']);
				}
			}
		}
		
		// verify that all roles are valid.
		$rolesGetter = new coursememberDAO();
		
		$AllRoles = $rolesGetter->getAllExternalRoles($this->confDB);
		
		$this->validadeRoles($AllRoles);
		
		return false;
	}
	
	public function showErrors()
	{
		
		$errorData = array();

		if(!empty($this->coursesWithoutCord))
		{
			$errorData['coursesWithoutCord'] = $this->coursesWithoutCord;
		}
		if(!empty($this->duplicateNameOfCourses))
		{
			$errorData['duplicateNameOfCourses'] = $this->duplicateNameOfCourses;
		}
		if(!empty($this->duplicateLogins))
		{
			$errorData['duplicateLogins'] = $this->duplicateLogins;
		}
		if(!empty($this->duplicateEmails))
		{
			$errorData['duplicateEmails'] = $this->duplicateEmails;
		}
		if(!empty($this->noDescribedCourse))
		{
			$errorData['noDescribedCourse'] = $this->noDescribedCourse;
		}
		if(!empty($this->noDescribedUser))
		{
			$errorData['noDescribedUser'] = $this->noDescribedUser;
		}
		if(!empty($this->invalidRoles))
		{
			$errorData['invalidRoles'] = $this->invalidRoles;
		}
		
		$errorPresenter = new errorView($errorData);
		
		exit();
	}
	
	public function errorFound(){
		return $this->errorsFound;
	}
	

	
	/**
	 * Check if a courseName, a login or an email is used more than once in the array returned from a query in the external database.
	 * 
	 * @param $data Can be course or userData, array with data.
	 * @param $dataKey Type of data that is being tested; can be course, name or login.
	 * @param &$duplicatesRegister One of the this classes attributes that stores duplicated info. 
	 * @param $appearencesOfThisData Returned array with each appearence of the data identified by $data[$dataKey]
	 * */
	private function checkDuplicateData($appearencesOfThisData, &$duplicatesRegister, $data, $dataKey)
	{
		
		if(count($appearencesOfThisData) != 1)
		{
			$this->errorsFound = true;
				
			if(in_array(array($dataKey => $data[$dataKey], 'Appearences' => count($appearencesOfThisData)), $duplicatesRegister))
			{
				return;
			}
			
			array_push($duplicatesRegister, array($dataKey => $data[$dataKey], 'Appearences' => count($appearencesOfThisData)));
		}
	}
	
	private function validadeRoles($AllRoles)
	{
		foreach ($AllRoles as $role)
		{
			if ($role != 'V' && $role != 'Z' && $role != 'A' && $role != 'F')
			{
				if ($role != 'v' && $role != 'z' && $role != 'a' && $role != 'f')
				{
					$this->errorsFound = true;
					
					array_push($this->invalidRoles, $role['role']);
				}
			}
		}
	}
}