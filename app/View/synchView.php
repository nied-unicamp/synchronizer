<?php

require_once '../Controller/diffController.php';

/**
 * TODO Auto-generated comment.
 */
class synchView {
	/**
	 * TODO Auto-generated comment.
	 */
	private $confDB;
	/**
	 * TODO Auto-generated comment.
	 */
	private $confDBCache;
	/**
	 * TODO Auto-generated comment.
	 */
	private $confTE;

	/**
	 * Shows synchronization page to user, loading html code on brower.
	 * If a syncronizations was requested, then calls a controller that syncronizes
	 * and load another html page.
	 */
	public function createView() {
		if(isset($_POST['targets']))
		{
			include "../Layout/synchronizingHeader.html";
			
			echo '<p>You asked for a sync.<p>';
			$this->callController($_POST['targets']);
			
			echo "</body>\n</html>";
			
		}
		else 
		{
			include "../Layout/sync.html";
		}
	}
	
	/**
	 * Calls controller in order to synchronize.
	 * 
	 * @param $targets List of types of the data that should be syncrhronized. 
	 * 				   Actually, can contain the types "users", "courses" and "coursemember".
	 * 				   Types passed are validated in the controller class.
	 * 
	 * @param $serverType Type of the source of external data.
	 * 
	 * @param $db String that identifies the data, according to $serverType.
	 * 
	 * @return 
	 * */
	public function callController($targets){
		
		$controlsDiff = new diffController();
		$externalList = $controlsDiff->configDB($_POST['db'], $targets, $_POST['serverType']);
		
		echo '<br>externalLIST:  ';
		var_dump($externalList);
	}
}

$synchronizerPage = new synchView();
$synchronizerPage->createView();

