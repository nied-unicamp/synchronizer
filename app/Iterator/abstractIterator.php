<?php
/**
 * abstractIterator
 */
interface abstractIterator {

	public function __construct();

	public function getNext();

	public function getCurrent();

	public function hasNext();
}
