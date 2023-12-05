<?php
class MateriSilabusQuizService {
	  
 	function __construct() {
		$this->materi_silabus_quiz = new MateriSilabusQuiz();
	}

	function getAllData() {
		$select = $this->materi_silabus_quiz->select()->where('status = 1');
		$result = $this->materi_silabus_quiz->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->materi_silabus_quiz->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->materi_silabus_quiz->fetchRow($select);
		return $result;
	}

	function addData($id_materi_silabus, $pertanyaan, $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5, $kunci_jawaban, $id_pengajar) {
		// $id_kelas_silabus
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_materi_silabus' => $id_materi_silabus, 		
			'pertanyaan' => $pertanyaan,
			'jawaban1' => $jawaban1,
			'jawaban2' => $jawaban2,
			'jawaban3' => $jawaban3,
			'jawaban4' => $jawaban4,
			'jawaban5' => $jawaban5,
			'kunci_jawaban' => $kunci_jawaban,
			'id_pengajar' => $id_pengajar,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->materi_silabus_quiz->insert($params);
		$lastId = $this->materi_silabus_quiz->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $id_materi_silabus, $pertanyaan, $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5, $kunci_jawaban, $id_pengajar) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

			$params = array(		
				'id_materi_silabus' => $id_materi_silabus, 
				'pertanyaan' => $pertanyaan, 
				'jawaban1' => $jawaban1, 
				'jawaban2' => $jawaban2,
				'jawaban3' => $jawaban3,
				'jawaban4' => $jawaban4,
				'jawaban5' => $jawaban5,
				'kunci_jawaban' => $kunci_jawaban, 	
				'id_pengajar' => $id_pengajar,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);

 		$where = $this->materi_silabus_quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus_quiz->update($params, $where);

	}

	public function deleteData($id) {
		$where = $this->materi_silabus_quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus_quiz->delete($where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'gambar_materi_uri' => '',
			'document_materi_uri' => '',
			'audio_materi_uri' => '',
			'video_materi_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		
 		$where = $this->materi_silabus_quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus_quiz->update($params, $where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->materi_silabus_quiz->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus_quiz->update($params, $where);
	}

}