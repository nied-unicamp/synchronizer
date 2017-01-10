<?php

/**
 * This class mounts a html page, and is used when the external database don't have
 * the expected information for a viable sync process.
 */
class errorView {
	
	public function __construct($errorData)
	{
		echo "<h1>The sync couldn't be performed.</h1>";
		echo "<h2>The problems are descripted bellow</h2>";
		$this->printErrors($errorData);
	}
	
	private function printErrors($errorData)
	{
		foreach($erroData as $type => $errorInfo)
		{
			switch ($key) {
				case 'coursesWithoutCord':
					$this->notifyErrorOneData($errorInfo, "<h3>The following courses don't have a cordinator in the external database:</h3>");
					break;
					
				case 'duplicateNameOfCourses':
					$this->notifyErrorMulData($errorInfo, "<h3>The following course names were used more than once:</h3>");
					break;
					
				case 'duplicateLogins':
					$this->notifyErrorMulData($errorInfo, "<h3>The following logins were used more than once:</h3>");
					break;
					
				case 'duplicateEmails':
					$this->notifyErrorMulData($errorInfo, "<h3>The following email were used more than once:</h3>");
					break;
					
				case 'noDescribedCourse':
					
					break;
					
				case 'noDescribedUser':
					
					break;
					
				default:
					break;
			}
		}
	}
	
	private function notifyErrorOneData($errorInfo, $message){
		echo $message;
		foreach ($errorInfo as $data)
		{
			echo "<p>" . $data . "</p>";
		}
	}
	
	private function notifyErrorMulData($errorInfo, $message){
		echo $message;
		foreach ($errorInfo as $data)
		{	
			echo "<p>";
			foreach ($data as $key => $info)
			{
				echo $key . ": " . $info . "; ";
			}
			echo "</p>";
		}
	}
	
	
}