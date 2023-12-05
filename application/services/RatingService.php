<?php
	class RatingService {
	  
 	function __construct() {
		$this->rating = new Rating();
	}
	
	function getAllData() {
	
		$select = $this->rating->select()->where('status = 1');
		$result = $this->rating->fetchAll($select);
		return $result;
	}
	
	
	function getData($id) {
		$select = $this->rating->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->rating->fetchRow($select);
		return $result;
	}

	function getRatingByPelatihan($id_pelatihan) {
		$select = $this->rating->select()->where('id_pelatihan = ?', $id_pelatihan);
		$result = $this->rating->fetchAll($select);
		return $result;
	}

	function getRatingByBatch($id_batch) {
		$select = $this->rating->select()->where('id_batch = ?', $id_batch);
		$result = $this->rating->fetchAll($select);
		return $result;
	}

	function getRatingPeserta($id_peserta, $id_batch) {
		$select = $this->rating->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch);
		$result = $this->rating->fetchRow($select);
		return $result;
	}

	function addData($id_peserta, $id_batch, $id_pelatihan, $star) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(	
			'id_peserta' => $id_peserta, 		
			'id_batch' => $id_batch, 
			'id_pelatihan' => $id_pelatihan,
			'star' => $star,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$this->rating->insert($params);
		$lastId = $this->rating->getAdapter()->lastInsertId();

		return $lastId;	
	}

	function editData($id, $id_peserta, $id_batch, $id_pelatihan, $star) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'$id' => $id,
			'id_peserta' => $id_peserta, 		
			'id_batch' => $id_batch, 
			'id_pelatihan' => $id_pelatihan,
			'star' => $star,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->rating->getAdapter()->quoteInto('id = ?', $id);
		$this->rating->update($params, $where);

	}
	
	public function deleteData($id) {
		$where = $this->rating->getAdapter()->quoteInto('id = ?', $id);
		$this->rating->delete($where);
	}
}