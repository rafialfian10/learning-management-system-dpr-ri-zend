<?php
class ForumDiskusiService {
	  
 	function __construct() {
		$this->forumdiskusi = new ForumDiskusi();
	}
	
	function getAllData() {
		$select = $this->forumdiskusi->select()->where('status = 1')->order('id DESC');
		$result = $this->forumdiskusi->fetchAll($select);
		return $result;
	}
	function getDataBatch($id_batch) {
		$select = $this->forumdiskusi->select()->where('status = 1')->where('id_batch = ?', $id_batch);
		$result = $this->forumdiskusi->fetchRow($select);
		return $result;
	}

	function getAllDataByCoach($id_coach) {
		$select = $this->forumdiskusi->select()->where('status = 1')->where('id_coach = ?', $id_coach);
		$result = $this->forumdiskusi->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->forumdiskusi->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->forumdiskusi->fetchRow($select);
		return $result;
	}

	function addData($title, $file_forum, $id_batch, $id_coach) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'title' => $title,
			'file_forum' => $file_forum,
			'id_batch' => $id_batch,
			'id_coach' => $id_coach,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->forumdiskusi->insert($params);
		$lastId = $this->forumdiskusi->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $title, $file_forum, $id_batch) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$select = $this->forumdiskusi->select()->where('id = ?', $id);
		$row = $this->forumdiskusi->fetchRow($select);
		
		if($file_forum == '' ){
			$file_forum = $row['file_forum'];
		}

		$params = array(
			'title' => $title,
			'file_forum' => $file_forum,
			'id_batch' => $id_batch,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->forumdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->forumdiskusi->update($params, $where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'file_forum' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->forumdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->forumdiskusi->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->forumdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->forumdiskusi->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->forumdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->forumdiskusi->update($params, $where);
	}
}