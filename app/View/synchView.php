<?php

require_once '../Controller/diffController.php';
require_once '../Controller/synchController.php';
require_once '../Model/Request.php';
require_once '../Model/TeleducInc.php';

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
			include "../Layout/synchronizingHeader.html";
			
			echo '<p>You asked for a sync.<p>';
			
			/*
			 * Calls controller for synchronizing process.
			 * */
			$this->callController($request);
			
			echo "</body>\n</html>";
			return;
		}
		
		/*
		 * Loads default page for synchronizer configuration.
		 * */
		include "../Layout/sync.html";
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
		
		/*
		 * Builds an array with all data known about a possible database choosen for source of external data.
		 * TODO Put it inside a if according to the imput method for external data?
		 * */
		$this->confDB = array(
								$request->post['serverType'], $request->post['dbHost'], 
								$request->post['dbPort'], $request->post['dbName'], 
								$request->post['dbLogin'], $request->post['dbPassword']			
							);
		/*
		 * TODO HERE: Generate confDbCache value with teleduc.inc path in a save way!
		 * */
		$teleducInc = new TeleducInc();
		$this->confDBCache = $teleducInc->buildConfDBCache();
		
//These lines are the future calls. They dont work yet.		
		$transactions = $controlsDiff->createDiff(
													$this->confDB, $this->confDBCache, 
													$request->post['targets'], $request->post['serverType']
												);
		
		// TODO Review parameters of the following line.
		$controlsSync->synchronize($confTE, $serverType, $transactions);
//		
		
		
		//These lines are being used for testing.		
// 		$externalList = $controlsDiff->configDB($this->confDB, $request->post['targets'], $request->post['serverType']);
		
// 		echo '<br>externalLIST:  ';
// 		var_dump($externalList['courses']);
		

	
	}

}

$synchronizerPage = new synchView();
$synchronizerPage->createView();

