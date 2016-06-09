<?php
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
	 * TODO Auto-generated comment.
	 */
	public function createView() {
		if(isset($_POST['targets']))
		{
			echo "You asked for a sync.<br>";
			$this->callController($_POST['targets']);
		}
		else 
		{
			include "../layout/sync.html";
		}
	}
	
	public function callController($strategies){

		$databaseData = array();
		
		$differentiator = new diffController();
		
		foreach($strategies as $key => $strategy)
		{
			array_push($databaseData, $differentiator->configDB('todo', $strategy, 'todo'));
		}
		
		var_dump($databaseData);
	}
}

$synchronizerPage = new synchView();
$synchronizerPage->createView();

