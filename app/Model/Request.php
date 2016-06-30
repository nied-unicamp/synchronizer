<?php

class Request {
	
	public $post;
	public $get;
	
	public function __construct($post, $get)
	{
		$this->get = $get;
		$this->post = $post;
	}
}