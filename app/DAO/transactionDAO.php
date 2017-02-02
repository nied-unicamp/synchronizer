<?php

require_once dirname(__FILE__) . '/../Model/transaction.php';
require_once dirname(__FILE__) . '/../Model/user.php';
require_once dirname(__FILE__) . '/../DAO/userDAO.php';
require_once dirname(__FILE__) . '/../DAO/categoryDAO.php';
require_once dirname(__FILE__) . '/../Model/user.php';
require_once dirname(__FILE__) . '/../Model/course.php';
require_once dirname(__FILE__) . '/../Model/user.php';

/**
 * TODO Auto-generated comment.
 */
class transactionDAO{

	private $userDAOObject;
	private $courseDAOObject;
	private $courmemberDAOObj;
	private $categoryDAOObj;
	private $confExtData;
	
	public function __construct($confExtData)
	{
		$this->userDAOObject = new userDAO();
		$this->courseDAOObject = new courseDAO();
		$this->courmemberDAOObj = new coursememberDAO();
		$this->categoryDAOObj = new categoryDAO();
		
		$this->confExtData = $confExtData;
	}
	
	/**
	 * TODO Auto-generated comment.
	 */
	public function doTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getOperation()) {
		
			case 'update':
				$this->updateTransaction($dbInfo, $serverType, $transaction);
				break;
					
			case 'insert':
				$this->insertTransaction($dbInfo, $serverType, $transaction);
				break;
					
			case 'delete':
				$this->deleteTransaction($dbInfo, $serverType, $transaction);
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		
		}
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function insertTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				$userData = $transaction->getOperand();
				$user = new User($userData['login'], $userData['name'], $userData['email']);
				
				//$userInserter = new userDAO();
				$this->userDAOObject->addUser($user);
				
				break;
					
			case 'course':

				$courseData = $transaction->getOperand();
				$coordList = $this->courseDAOObject->getCourseCordList($this->confExtData, false, $courseData['courseName']);
				
				$coord = $coordList[0]['login'];
				
				$AllcoordData = $this->userDAOObject->getUserByLogin($this->confExtData, false, $coord);
				$coordData = $AllcoordData[0];
								
				$coordCode = $this->userDAOObject->getUserCodeByLogin($dbInfo, $coord);
				

				echo "<h1>";
				
				
				var_dump($coord);
				
				echo "<br><br><br>";
				
				echo "<strong>";
				var_dump($coordCode);
				echo "</strong>";
				echo "</h1>";
				
				$categoryCode = $this->categoryDAOObj->getCategoryCode($dbInfo, $courseData['category']);
				
				$this->courseDAOObject->addCourse($courseData['courseName'], 300, $categoryCode, $coordData['name'], $coordData['email'], $coordData['login'], NULL, $coordCode);

				break;
					
			case 'coursemember':
				
				break;
				
			case 'category':
				//$categoryInserter = new categoryDAO();
				$this->categoryDAOObj->insertCategory($dbInfo, $transaction->getOperand());
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
				
		/* Call DB wrapper*/
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function deleteTransaction($dbInfo, $serverType, $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				break;
					
			case 'course':
				/* Build sql query */
				break;
					
			case 'coursemember':
				/* Build sql query */
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
				
		/* Call DB wrapper*/
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateTransaction($dbInfo, $serverType, $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				break;
					
			case 'course':
				/* Build sql query */
				break;
					
			case 'coursemember':
				/* Build sql query */
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
		
		/* Call DB wrapper*/
		
	}
}
