<?php

class Request {
	
	public $post;
	public $get;
	
	public function __construct()
	{
		$this->get = $_GET;
		$this->post = $_POST;
	}
}