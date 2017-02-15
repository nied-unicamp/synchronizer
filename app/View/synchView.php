<?php

require_once dirname(__FILE__) . '/../Controller/diffController.php';
require_once dirname(__FILE__) . '/../Controller/synchController.php';
require_once dirname(__FILE__) . '/../Model/Request.php';
require_once dirname(__FILE__) . '/../Model/TeleducInc.php';
require_once dirname(__FILE__) . '/../DAO/cacheDBDAO.php';
require_once dirname(__FILE__) . '/../Model/DBInfo.php';

/*Just for tests!!*/
require_once dirname(__FILE__) . '/../Wrapper/DBWrapper.php';
require_once dirname(__FILE__) . '/../DAO/courseDAO.php';
//ini_set('display_errors', 'On');
//ini_set('display_errors', 1);

/**
 * This class mounts a html page for the page of the synchronizer, and calls the controller
 * method reponsible for the synchonization process.
 */
class synchView {
	/**
	 * Contains an array with all data known about the external data.
	 */
	private $confDB;
	/**
	 * Contains an array with all data known about the internal TelEduc's data.
	 */
	private $confDBCache;
	/**
	 * Contains the path to teleduc.inc file, witch contains information about TelEduc's database.
	 */
	private $confTE;

	public function showTransactions($transactions)
	{
	// Tests of update transactions.
		echo "<p>Transactions:</p>";
	
		$numTrans = count($transactions);
	
		for($i=0; $i<$numTrans; $i++)
		{
			echo "<p>";
			echo $transactions[$i]->getOperation(). " " . $transactions[$i]->getDataType() . " ";
			var_dump($transactions[$i]->getOperand());
			echo "</p>";
		}
	}
	
	/**
	 * Shows synchronization page to user by loading html code on browser.
	 * If a synchronization was requested, then calls a controller that synchronizes
	 * and loads another html page.
	 */
	public function createView() {

		/*
		 * Encapsulates superglobals values.
		 * */
		$request = new Request();

		/*
		 * Checks if a syncrhonization was alredy requested to this page.
		 * */
		if(isset($request->post['targets']))
		{
			//include dirname(__FILE__) . '/../Layout/synchronizingHeader.html';
			//include '../Layout/synchronizingHeader.html';

			//echo '<p>You asked for a sync.<p>';

			/*
			 * Calls controller for synchronizing process.
			 * */
			

			
			
			
			
			$this->callController($request);
			
			// Here, put some message after sync conclusion.
			
			//echo "</body>\n</html>";
			return;
		}

		/*
		 * Loads default page for synchronizer configuration.
		 * */
		include dirname(__FILE__) . '/../Layout/sync.html';
		
		//$criadorDeCurso = new courseDAO();
		//$criadorDeCurso->addCourse("O TESTE SUPREMO DA CRIAÇÂO DE CURSO AGORAVAI", 300, NULL, 'Junior', 'aristeujnr@gmail.com','','',37);
		
		return;
	}

	/**
	 * Calls controller method in order to synchronize.
	 *
	 * @param $targets List of types of the data that should be synchronized.
	 * 				   Actually, can contain the types "users", "courses" and "coursemember".
	 * 				   Types passed are validated in the controller class.
	 *
	 * @param $serverType Type of the source of external data.
	 *
	 * @param $dbInfo String that identifies the data, according to $serverType.
	 *
	 * @return void
	 * */
	public function callController($request){

		$controlsDiff = new diffController();

		$controlsSync = new synchController();

		
		/**
		 * Builds an array with all data known about a possible database choosen for source of external data.
		 * TODO Put it inside a if according to the imput method for external data?
		 * TODO Change this in order to use an object instead of an array! Code would be a lot more readble!!!
		 * */
		$this->confDB = new DBInfo($request->post['serverType'], $request->post['dbHost'], 
									$request->post['dbPort'], $request->post['dbName'], 
									$request->post['dbLogin'], $request->post['dbPassword']);

// 		/*
// 		 * Generate confDbCache value with teleduc.inc path in a safe way!
// 		 * */
 		$teleducInc = new TeleducInc();
 		$this->confDBCache = $teleducInc->buildConfDBCache();
	
   		//$cacheManager = new DBWrapper();
//   	echo 'Retorno da consulta:<br>';
//   	var_dump($cacheManager->manipulateData($this->confDBCache, "select * from usersCache where login=?;", true, array('admtele')));
//   	echo '<br><br>';

		var_dump($request->post['targets']);
		
		
	   	if (in_array('users', $request->post['targets']))
	   	{
	   		
	   		
			$transactions = $controlsDiff->createDiff(
														$this->confDB, $this->confDBCache,
														array('users'), $request->post['serverType']
													);
			echo "<p>Transações da sinc de users:</p>";
			$this->showTransactions($transactions);
			
			$controlsSync->synchronize($this->confDBCache, $this->confDB, $request->post['serverType'], $transactions);
			
			if (in_array('courses', $request->post['targets']))
			{
				$transactions = $controlsDiff->createDiff(
						$this->confDB, $this->confDBCache,
						array('courses'), $request->post['serverType']
				);
				
				echo "<p>Transações da sinc de courses:</p>";
				$this->showTransactions($transactions);
				
				$controlsSync->synchronize($this->confDBCache, $this->confDB, $request->post['serverType'], $transactions);
			
				if (in_array('coursemember', $request->post['targets']))
				{
										
					$transactions = $controlsDiff->createDiff(
							$this->confDB, $this->confDBCache,
							array('coursemember'), $request->post['serverType']
					);
				
					echo "<p>Transações da sinc de coursemembers:</p>";
					$this->showTransactions($transactions);
					
					$controlsSync->synchronize($this->confDBCache, $this->confDB, $request->post['serverType'], $transactions);
				}
			}
	   	}

		
		//var_dump(json_decode($transactions));
		
	//	//echo "<br>";



		// TODO Review parameters of the following line.
		//OLD
		//$controlsSync->synchronize($confTE, $serverType, $transactions);
		
		
//

		//These lines are being used for testing.
// 		$externalList = $controlsDiff->configDB($this->confDB, $request->post['targets'], $request->post['serverType']);

// 		echo '<br>externalLIST:  ';
// 		var_dump($externalList['coursemember'][1]);

// TEST QUERY TIME!
//  		set_time_limit ( 0 );
//  		$testConn = new DBWrapper();
//  		for ($i=0; $i<1000000; $i++)
//  		{

//  		$testConn->dataRequest(  $this->confDBCache,
//  				"SELECT * from coursesCache where courseName='Teste123'"

//  											 );

//  		}

//  		echo '<h1>'.$i.'</h1>';
	}
}



VerificaAutenticacaoAdministracao();
$synchronizerPage = new synchView();
$synchronizerPage->createView();
