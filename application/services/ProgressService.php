<?php
class ProgressService {
	  
 	function __construct() {
		$this->progress = new Progress();
	}
	
	function getAllData() {
		$select = $this->progress->select()->where('status = 1');
		$result = $this->progress->fetchAll($select);
	
		return $result;
	}

	function getData($id) {
		$select = $this->progress->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->progress->fetchRow($select);
		return $result;
	}

	function getAllProgress($id_peserta) {
		$select = $this->progress->select()->where('status = 1')->where('id_peserta = ?', $id_peserta);
		$result = $this->progress->fetchAll($select);
	
		return $result;
	}

	function getProgress($id_peserta, $id_batch) {

		$select = $this->progress->select()->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch);
		$result = $this->progress->fetchRow($select);
		return $result;
	}

	function addData($id_peserta, $id_batch, $status_progress) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_peserta' => $id_peserta, 
			'id_batch' => $id_batch,
			'status_progress' => $status_progress,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);

		$this->progress->insert($params);
		$lastId = $this->progress->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $status_progress) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'status_progress' => $status_progress,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

 		$where = $this->progress->getAdapter()->quoteInto('id = ?', $id);
		$this->progress->update($params, $where);

	}

	public function deleteBatch($id)
	{
		$where = $this->progress->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->progress->delete($where);
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
 		
		$where = $this->progress->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->progress->update($params, $where);
	}


	public function deleteData($id) {
		$where = $this->progress->getAdapter()->quoteInto('id = ?', $id);
		$this->progress->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->progress->getAdapter()->quoteInto('id = ?', $id);
		$this->progress->update($params, $where);
	}


}