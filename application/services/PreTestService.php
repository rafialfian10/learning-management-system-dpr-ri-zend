<?php
class PreTestService {
	  
 	function __construct() {
		$this->pretest = new PreTest();
	}
	
	function getAllData() {
		$select = $this->pretest->select()->where('status = 1');
		$result = $this->pretest->fetchAll($select);
		return $result;
	}

	function getAllDataPelatihan($id) {
		$select = $this->pretest->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->pretest->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->pretest->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->pretest->fetchRow($select);
		return $result;
	}

	function addData($id_pelatihan, $soal, $batas_waktu, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban, $file_nontes) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_pelatihan' => $id_pelatihan,
			'soal' => $soal,  
			'batas_waktu' => $batas_waktu,
			'jawaban_1' => $jawaban_1,
			'jawaban_2' => $jawaban_2,
			'jawaban_3' => $jawaban_3,
			'jawaban_4' => $jawaban_4,
			'jawaban_5' => $jawaban_5,
			'kunci_jawaban' => $kunci_jawaban,
			'nontes' => $file_nontes,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->pretest->insert($params);
		$lastId = $this->pretest->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id_pelatihan, $soal, $batas_waktu, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban, $file_nontes) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');


		$params = array(			
			'id_pelatihan' => $id_pelatihan,
			'soal' => $soal,  
			'batas_waktu' => $batas_waktu,
			'jawaban_1' => $jawaban_1,
			'jawaban_2' => $jawaban_2,
			'jawaban_3' => $jawaban_3,
			'jawaban_4' => $jawaban_4,
			'jawaban_5' => $jawaban_5,
			'kunci_jawaban' => $kunci_jawaban,
			'nontes' => $file_nontes,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);
		$this->pretest->insert($params);
		$lastId = $this->pretest->getAdapter()->lastInsertId();
		return $lastId;

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotomentor_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->pretest->getAdapter()->quoteInto('id = ?', $id);
		$this->pretest->update($params, $where);
	}

	public function deleteFilesNontes($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'nontes' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->pretest->getAdapter()->quoteInto('id_pelatihan = ?', $id);
		$this->pretest->update($params, $where);
	}

	public function deletePelatihan($id_pelatihan) {
		$where = $this->pretest->getAdapter()->quoteInto('id_pelatihan = ?', $id_pelatihan);
		$this->pretest->delete($where);
	}

	public function deleteData($id) {
		$where = $this->pretest->getAdapter()->quoteInto('id = ?', $id);
		$this->pretest->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->pretest->getAdapter()->quoteInto('id = ?', $id);
		$this->pretest->update($params, $where);
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