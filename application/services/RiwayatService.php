<?php
class RiwayatService {
	  
 	function __construct() {
		$this->riwayat = new Riwayat();
	}
	
	function getAllData() {
		$select = $this->riwayat->select()->where('status = 1');
		$result = $this->riwayat->fetchAll($select);
	
		return $result;
	}

	function getData($id) {
		$select = $this->riwayat->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->riwayat->fetchRow($select);
		return $result;
	}

	function getAllRiwayat($id_peserta) {
		$select = $this->riwayat->select()->where('status = 1')->where('id_peserta = ?', $id_peserta);
		$result = $this->riwayat->fetchAll($select);
		return $result;
	}


	function getRiwayat($id_peserta, $id_batch) {

		$select = $this->riwayat->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch);
		$result = $this->riwayat->fetchRow($select);
		return $result;
	}

	function addData($id, $id_peserta, $id_batch, $status_progress, $status_riwayat) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id' => $id, 
			'id_peserta' => $id_peserta, 
			'id_batch' => $id_batch,
			'status_progress' => $status_progress,
			'status_riwayat' => $status_riwayat,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);

		$this->riwayat->insert($params);
		
		return $id;	
	}



}