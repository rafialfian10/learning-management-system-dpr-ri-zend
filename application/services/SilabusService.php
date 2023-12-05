<?php
class SilabusService {
	  
 	function __construct() {
		$this->silabus = new Silabus();
		$this->materi_silabus = new MateriSilabus();
	}

	function getAllDataPelatihan($id) {
		$select = $this->silabus->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->silabus->fetchAll($select);
		return $result;
	}
	function getAllDataSilabus($id) {
		$select = $this->silabus->select()->where('id = ?', $id);
		$result = $this->silabus->fetchRow($select);
		return $result;
	}

	function getDataByIdSilabus($id) {
		// var_dump($id); die();
		$select = $this->materi_silabus->select()->where('status = 1')->where('id_silabus = ?', $id);
		$result = $this->materi_silabus->fetchRow($select);
		return $result;
	}

	function getAllData() {
		$select = $this->silabus->select()->where('status = 1')->order('id DESC');
		$result = $this->silabus->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->silabus->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->silabus->fetchRow($select);
		return $result;
	}

	function getDataUrutan($id_pelatihan, $urutan) {
		$select = $this->silabus->select()->where('status = 1')->where('id_pelatihan = ?', $id_pelatihan)->where('urutan = ?', $urutan);
		$result = $this->silabus->fetchRow($select);
		return $result;
	}

	function getDataPelatihanById($id) {
		$select = $this->silabus->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->silabus->fetchRow($select);
		return $result;
	}

	function getAllDataMateri($id) {
		$select = $this->silabus_quiz->select()->where('status = 1')->where('id_materi = ?', $id);
		$result = $this->silabus_quiz->fetchAll($select);
		return $result;
	}

	// function getDataByIdKelasSilabus($id_kelas_silabus) {
	// 	$select = $this->silabus->select()->where('status = 1')->where('id_kelas_silabus = ?', $id_kelas_silabus);
	// 	$result = $this->silabus->fetchAll($select);
	// 	return $result;
	// }

	function addData($nama_silabus, $deskripsi, $id_pelatihan, $urutan) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'nama_silabus' => $nama_silabus,
			'deskripsi' => $deskripsi,
			'id_pelatihan' => $id_pelatihan,	
			'urutan' => $urutan,		
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->silabus->insert($params);
		$lastId = $this->silabus->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $nama_silabus, $deskripsi) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'nama_silabus' => $nama_silabus,
			'deskripsi' => $deskripsi,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->silabus->update($params, $where);

	}

	function editUrutan($id, $urutan) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'urutan' => $urutan,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->silabus->update($params, $where);

	}

	public function deleteData($id) {
		$where = $this->silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->silabus->delete($where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		
 		$where = $this->silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->silabus->update($params, $where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->silabus->update($params, $where);
	}

	public function softDeleteDataPelatihan($id_pelatihan) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->silabus->getAdapter()->quoteInto('id_pelatihan = ?', $id_pelatihan);
		$this->silabus->update($params, $where);
	}

}