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
				
				echo "<p>Achei os seguintes coordenadores para o curso " . $courseData['courseName'] . ":</p>";
				var_dump($coordList);
				
				$coord = $coordList[0]['login'];
				
				$AllcoordData = $this->userDAOObject->getUserByLogin($this->confExtData, false, $coord);
				$coordData = $AllcoordData[0];
								
				$coordCode = $this->userDAOObject->getUserCodeByLogin($dbInfo, $coord);
				
				$categoryCode = $this->categoryDAOObj->getCategoryCode($dbInfo, $courseData['category']);
				
				$this->courseDAOObject->addCourse($courseData['courseName'], 300, $categoryCode, $coordData['name'], $coordData['email'], $coordData['login'], NULL, $coordCode);

				break;
					
			case 'coursemember':
				$cmData = $transaction->getOperand();
				

				// If it's the insertion of a teacher...
				if($cmData['role'] == 'F' || $cmData['role'] == 'f')
				{
					//...check if the course has a valid coordinator...
					if( ! $this->courseDAOObject->hasValidCoordinator($dbInfo, $cmData['courseName']))
					{
						//...if this course's coordinator isn't valid, this teacher has to become a cordinator.
						$this->courseDAOObject->setCourseCoordinator($dbInfo, $cmData['login'], $cmData['courseName']);	
					}
				}
				
				$this->courseDAOObject->insertUserInCourse($dbInfo, $cmData['login'], $cmData['courseName'], $cmData['role']);
				
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
	public function deleteTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				/* Build sql query */
				// get cod_usuario by login
				// usando o cod_usuario:
					//drop usuario da tabela Cursos, 
					// drop de todas as entradas da tabela Usuario_curso, 
					// drop de todas entradas em Usuario_config, 
					// e pesquisar sobre desativacao de portfolio				
				
				break;
					
			case 'course':
				/* Build sql query */
				// get cod_curso by courseName
				// drop todas entradas com esse cod_curso em Usuario_curso
				// drop de todas as entradas com esse cod_curso em Usuario_config
				// apagar as pastas de arquivos deste curso
				// apagar as pastas de diretorio deste curso
				// apagar a entrada deste curso na tabela Cursos
				// drop o banco deste curso
				
				break;
					
			case 'coursemember':
				/* Build sql query */
				// get cod_usuario by login
				// get cod_curso by login
				// remover a linha desta relacao na tabela Usuario_curso
				// remover a linha desta relacao na tabela Usuario_config
				
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
				
		/* Call DB wrapper*/
	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function updateTransaction($dbInfo, $serverType, transaction $transaction) {
		
		switch ($transaction->getdataType()) {
		
			case 'user':
				$userData = $transaction->getOperand();
				
				$this->userDAOObject->updateUser($dbInfo, $serverType, $userData);
				
				break;
					
			case 'course':
				$courseData = $transaction->getOperand();
				$this->courseDAOObject->updateCourseCategory($dbInfo, $serverType, $courseData);
				
				break;
					
			case 'coursemember':
				$courMemData = $transaction->getOperand();
				
				$actualCoursememb = $this->courmemberDAOObj->getCourseMemberByPair($dbInfo, true, $courMemData['courseName'], $courMemData['login']);
				
// 				echo "<p>Vardump de actualCourseMembr</p>";
// 				echo "<p>";
// 				var_dump($actualCoursememb);
// 				echo "</p>";
				
				// If the target user was a teacher...
				if($actualCoursememb['role'] = 'f' || $actualCoursememb = 'F')
				{
					$coord = $this->courseDAOObject->getCourseCoordData($dbInfo, $courMemData['courseName']);
		
					
					
					
					// and if he is this course's coordinator...
					if ($coord['login'] == $courMemData['login'])
					{
						
						
// 						echo "<h1>OLHA EU AQUI...</h1>";
// 						echo "<p>Vardump de coord:</p>";
// 						echo "<p>";
// 						var_dump($coord);
// 						echo "</p>";
// 						echo "<p>Vardump de courMemData:</p>";
// 						echo "<p>";
// 						var_dump($courMemData);
// 						echo "</p>";
// 						echo "<p></p>";
						
						
						
						
						// search for another teacher already in the course, to be the new coordinator.
						$coordList = $this->courseDAOObject->getCourseCordList($dbInfo, true, $courMemData['courseName']);
						
						// remove from the teachers list the teacher that won't be the coordinator anymore.
						unset($coordList[array_search($coord['login'], $coordList)]);
						


						
						// If there isn't another teacher...
						if(empty($coordList))
						{
							
							echo "<p>Eu vou colocar um codigo invalido para o coordenado!!</p>";
							
							//entao colocar um cod_coordenador invalido reconhecivel: um novo formador sera inserido
							// posteriormente pois erroController garantiu que existe um coordenador, ou ja inserido, ou novo. nesse caso sera novo
							// pois nenhum foi encontrado ja inserido.
							$this->courseDAOObject->setInvalidCoord($dbInfo, $courMemData['courseName']);
							
							//break;
						}
						
						//Otherwise, if there is some other teacher...
							//then this other teacher will become the coordinator.
						else
						{
							$this->courseDAOObject->setCourseCoordinator($dbInfo, $coordList[0]['login'], $courMemData['courseName']);
						}
						
					}					
				}
				
 				if($courMemData['role'] == 'F' || $courMemData['role'] == 'f') 
 				{
 					
 					echo "<h1>Esse update transforma alguem em formador...</h1>";
 					
 					if( ! $this->courseDAOObject->hasValidCoordinator($dbInfo, $courMemData['courseName']))
 					{
 						echo "<h1>Achei um coord invalido... esse formador vai se tornar coordenador!</h1>";
 						
 						$this->courseDAOObject->setCourseCoordinator($dbInfo, $courMemData['login'], $courMemData['courseName']);
 					}
 				}
				
 				
 				echo "<h1>EU TENHO QUE APARECER 3 vezes</h1>";
				$this->courmemberDAOObj->updateUserRole($dbInfo, $serverType, $courMemData);
		
				break;
					
			default:
				throw new Exception('Unable to recognize transaction!');
		}
		
		/* Call DB wrapper*/
		
	}
}
