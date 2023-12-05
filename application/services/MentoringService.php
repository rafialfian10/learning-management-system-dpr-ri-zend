<?php
class MentoringService {
	  
 	function __construct() {
		$this->mentoring = new Mentoring();
	}
	
	function getAllData() {
		$select = $this->mentoring->select()->where('status = 1')->order('id DESC');
		$result = $this->mentoring->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->mentoring->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->mentoring->fetchRow($select);
		return $result;
	}

	function getDataBatch($id_batch) {
		$select = $this->mentoring->select()->where('status = 1')->where('id_batch = ?', $id_batch);
		$result = $this->mentoring->fetchRow($select);
		return $result;
	}

	function getAllDataBatch($id_batch) {
		$select = $this->mentoring->select()->where('status = 1')->where('id_batch = ?', $id_batch);
		$result = $this->mentoring->fetchAll($select);
		return $result;
	}

	function addData($title, $file_mentoring, $id_batch) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'title' => $title,		
			'file_mentoring' => $file_mentoring,
			'id_batch' => $id_batch,  
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->mentoring->insert($params);
		$lastId = $this->mentoring->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $title, $file_mentoring, $id_batch) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		
		$select = $this->mentoring->select()->where('id = ?', $id);
		$row = $this->mentoring->fetchRow($select);
		
		if($file_mentoring == '' ){
			$file_mentoring = $row['file_mentoring'];
		}

		$params = array(
			'title' => $title,
			'file_mentoring' => $file_mentoring,
			'id_batch' => $id_batch,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->mentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->mentoring->update($params, $where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'file_mentoring' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->mentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->mentoring->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->mentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->mentoring->delete($where);
	}


	public function softDeleteBatch($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->mentoring->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->mentoring->update($params, $where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->mentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->mentoring->update($params, $where);
	}
}