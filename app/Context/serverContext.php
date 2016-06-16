<?php

require_once '../Wrapper/DBWrapper.php';
require_once '../Wrapper/restfulWrapper.php';

/**
 * TODO Auto-generated comment.
 */
class serverContext {
	/**
	 * TODO Auto-generated comment.
	 */
	private $data;
	/**
	 * TODO Auto-generated comment.
	 */
	private $server;

	/**
	 * TODO Auto-generated comment.
	 */
	public function __construct($serverType, $data) {
        switch($serverType){
        case "SERVER_TYPE_MYSQL":
            $this->server = new DBWrapper($data);
            
            break;

        case "SERVER_TYPE_REST":
            $this->server = new restfulWrapper($data);
            break;

        default:
            throw new Exception('Unable to create Strategy for Server Type '.$serverType);

        }


	}

	/**
	 * TODO Auto-generated comment.
	 */
	public function serverQuery($db, $query) {
		
		$this->data = $this->server->dataRequest($db, $query);
		
		return $this->data;
	}
}
