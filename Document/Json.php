<?php
abstract class Document_Json {
	protected $data;

	public function __construct() {
		$this->data = array();
		if(method_exists($this, 'init'))
			$this->init();
	}

	public function __set($name, $value) {
		$this->data[$name] = $value;
	}

	public function __get($name) {
		if(isset($this->data[$name]))
			return $this->data[$name];
		else
			return null;
	}

	public function __toString() {
		return json_encode($this->data);
	}
}
