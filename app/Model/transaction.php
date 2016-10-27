<?php
/**
 * This class represents a need operation in the internal database
 * in order to obtain a synchronized state.
 */
class transaction implements JsonSerializable {
	/**
	 * Represents what need to be done. Can be an update, an insertion or a deletion.000
	 */
	private $operation;
	
	/**
	 * Generically represents what kind of data has to be manipulated. Can be a course,
	 * an user or a course-member relation.
	 */
	private $dataType;
	
	/**
	 * Specifically represents the data to be manipulates. Expected to be an array with
	 * data of a course, data of a user or data of a course-member relation.
	 */
	private $operand;

	public function __construct($operation, $dataType, $operand) {
		
		$this->setOperation($operation);
		$this->setdataType($dataType);
		$this->setOperand($operand);
		
	}	

	public function getOperation() {
		return $this->operation;
	}

	public function getdataType() {
		return $this->dataType;
	}

	public function getOperand() {
		return $this->operand;
	}

	public function setOperation($operation) {
		$this->operation = $operation;
	}

	public function setdataType($dataType) {
		$this->dataType = $dataType;
	}

	public function setOperand($operand) {
		$this->operand = $operand;
	}
	
	public function jsonSerialize()
	{
		return [
				'transaction' => [
						'operation' => $this->operation,
						'dataType' => $this->dataType,
						'operand' => $this->operand
				]
		];
	}
}
