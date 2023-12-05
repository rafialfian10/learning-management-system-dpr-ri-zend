<?php
class SilabusQuizService {
	  
	function __construct() {
		$this->quiz = new SilabusQuiz();
	}
	
	function getAllData() {
		$select = $this->quiz->select()->where('status = 1');
		$result = $this->quiz->fetchAll($select);
		return $result;
	}

	function getAllDataPelatihan($id) {
		$select = $this->quiz->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->quiz->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->quiz->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->quiz->fetchRow($select);
		return $result;
	}

	function addData($id_pelatihan, $soal, $batas_waktu, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban) {
		
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
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->quiz->insert($params);
		$lastId = $this->quiz->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id_pelatihan, $soal, $batas_waktu, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban) {

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
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);
		$this->quiz->insert($params);
		$lastId = $this->quiz->getAdapter()->lastInsertId();
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
 		$where = $this->quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->quiz->update($params, $where);
	}

	public function deletePelatihan($id_pelatihan) {
		$where = $this->quiz->getAdapter()->quoteInto('id_pelatihan = ?', $id_pelatihan);
		$this->quiz->delete($where);
	}

	public function deleteData($id) {
		$where = $this->quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->quiz->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->quiz->update($params, $where);
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