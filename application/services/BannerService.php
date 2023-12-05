<?php
class BannerService {
	  
 	function __construct() {
		$this->banner = new Banner();
	}
	
	function getAllData() {
		$select = $this->banner->select()->where('status = 1');
		$result = $this->banner->fetchAll($select);
		// var_dump($result); die();
		return $result;
	}

	function getData($id) {
		$select = $this->banner->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->banner->fetchRow($select);
		return $result;
	}

	function addData($gambar_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'gambar_uri' => $gambar_uri,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$this->banner->insert($params);
		$lastId = $this->banner->getAdapter()->lastInsertId();
		// var_dump($lastId); die();
		return $lastId;	
	}

	function editData($id, $gambar_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
				
			'gambar_uri' => $gambar_uri,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->banner->getAdapter()->quoteInto('id = ?', $id);
		$this->banner->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'gambar_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->banner->getAdapter()->quoteInto('id = ?', $id);
		$this->banner->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->banner->getAdapter()->quoteInto('id = ?', $id);
		$this->banner->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->banner->getAdapter()->quoteInto('id = ?', $id);
		$this->banner->update($params, $where);
	}
}