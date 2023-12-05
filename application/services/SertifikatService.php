<?php
class SertifikatService {
	  
 	function __construct() {
		$this->sertifikat = new Sertifikat();
	}
	
	function getAllData() {
		$select = $this->sertifikat->select()->where('status = 1')->order('id DESC');
		$result = $this->sertifikat->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->sertifikat->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->sertifikat->fetchRow($select);
		return $result;
	}

	function getSertifikat($id_peserta, $id_batch) {
		$select = $this->sertifikat->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch);
		$result = $this->sertifikat->fetchRow($select);
		return $result;
	}

	function getDataAllSertifikat($id) {
		$select = $this->sertifikat->select()->where('status = 1')->where('id_peserta = ?', $id);
		$result = $this->sertifikat->fetchAll($select);
		return $result;
	}

	function addSertifikat($id_batch, $id_peserta, $title, $skor_akhir) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'title' => $title,
			'skor_akhir' => $skor_akhir,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);

		$this->sertifikat->insert($params);
		$lastId = $this->sertifikat->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData($id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'title' => $title,
			'skor_materi' => $skor_materi,
			'skor_mentoring' => $skor_mentoring,
			'skor_penugasan' => $skor_penugasan,
			'skor_akhir' => $skor_akhir,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);

		$this->sertifikat->insert($params);
		$lastId = $this->sertifikat->getAdapter()->lastInsertId();
		return $lastId;	
	}


	function editdata ($id, $id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'title' => $title,
			'skor_materi' => $skor_materi,
			'skor_mentoring' => $skor_mentoring,
			'skor_penugasan' => $skor_penugasan,
			'skor_akhir' => $skor_akhir,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->sertifikat->getAdapter()->quoteInto('id = ?', $id);
		$this->sertifikat->update($params, $where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotosertifikat_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->sertifikat->getAdapter()->quoteInto('id = ?', $id);
		$this->sertifikat->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->sertifikat->getAdapter()->quoteInto('id = ?', $id);
		$this->sertifikat->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->sertifikat->getAdapter()->quoteInto('id = ?', $id);
		$this->sertifikat->update($params, $where);
	}

}