<?php
class HomeService {
	  
 	function __construct() {
		$this->home = new Home();
	}
	
	function getAllData() {
		$select = $this->home->select()->where('status = 1');
		$result = $this->home->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->home->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->home->fetchRow($select);
		return $result;
	}
}