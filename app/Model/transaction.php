<?php
/**
 * This class represents a need operation in the internal database
 * in order to obtain a synchronized state.
 */
class transaction {
	/**
	 * Represents what need to be done. Can be an update, an insertion or a deletion.000
	 */
	private $operation;
	
	/**
	 * Generically represents what kind of data has to be manipulated. Can be a course,
	 * an user or a course-member relation.
	 */
	private $operator;
	
	/**
	 * Specifically represents the data to be manipulates. Expected to be an array with
	 * data of a course, data of a user or data of a course-member relation.
	 */
	private $operand;

	public function __construct($operation, $operator, $operand) {
		
		$this->setOperation($operation);
		$this->setOperator($operator);
		$this->setOperand($operand);
		
	}	

	public function getOperation() {
		return $this->operation;
	}

	public function getOperator() {
		return $this->operator;
	}

	public function getOperand() {
		return $this->operand;
	}

	public function setOperation($operation) {
		$this->operation = $operation;
	}

	public function setOperator($operator) {
		$this->operator = $operator;
	}

	public function setOperand($operand) {
		$this->operand = $operand;
	}
}
