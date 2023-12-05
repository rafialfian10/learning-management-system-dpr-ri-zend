<?php
class ChatMentoringService {
	  
 	function __construct() {
		$this->chatmentoring = new ChatMentoring();
	}
	
	function getAllData() {
		$select = $this->chatmentoring->select()->where('status = 1');
		$result = $this->chatmentoring->fetchAll($select);
		return $result;
	}

	function getAllDataMentoring($id) {
		$select = $this->chatmentoring->select()->where('status = 1')->where('id_mentoring = ?', $id);
		$result = $this->chatmentoring->fetchAll($select);
		return $result;
	}

	function getAllPesertaMentoring($id_mentoring)
	{ 
		$sql = "SELECT x.id_user, x.role_user, y.nama as nama, COUNT(*) AS jumlah
		FROM chat_mentoring x
		INNER JOIN peserta y ON x.id_user = y.id
		WHERE x.role_user = 'Peserta' 
		AND x.id_mentoring = ?
		GROUP BY x.id_user, y.nama";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_mentoring));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	// function getAllDataMentoringReply($id) {
	// 	$select = $this->chatmentoring->select()->where('status = 1')->where('id_mentoring = ?', $id);
	// 	$result = $this->chatmentoring->fetchAll($select);
	// 	return $result;
	// }
	function getAllDataMentoringReply($id) {
		$select = $this->chatmentoring->select()->where('status = 1')->where('id_mentoring = ?', $id)->where('id_reply_mentoring = 0');
		$result = $this->chatmentoring->fetchAll($select);
		return $result;
	}

	function getAllDataReply($id) {
		$select = $this->chatmentoring->select()->where('status = 1')->where('id_reply_mentoring = ?', $id);
		$result = $this->chatmentoring->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->chatmentoring->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->chatmentoring->fetchRow($select);
		return $result;
	}

	function addData($id_pelatihan, $soal, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_pelatihan' => $id_pelatihan,
			'soal' => $soal,  
			'jawaban_1' => $jawaban_1,
			'jawaban_2' => $jawaban_2,
			'jawaban_3' => $jawaban_3,
			'jawaban_4' => $jawaban_4,
			'jawaban_5' => $jawaban_5,
			'kunci_jawaban' => $kunci_jawaban,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->chatmentoring->insert($params);
		$lastId = $this->chatmentoring->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id_pelatihan, $soal, $jawaban_1, $jawaban_2, $jawaban_3, $jawaban_4, $jawaban_5, $kunci_jawaban) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_pelatihan' => $id_pelatihan,
			'soal' => $soal,  
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
		$this->chatmentoring->insert($params);
		$lastId = $this->chatmentoring->getAdapter()->lastInsertId();
		return $lastId;
	}

	
	function replyMentoring($id_mentoring, $id_reply_mentoring, $id_user, $role_user, $isi, $file_mentoring) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_mentoring' => $id_mentoring,
			'id_reply_mentoring' => $id_reply_mentoring,  
			'id_user' => $id_user,
			'role_user' => $role_user,
			'isi' => $isi,
			'file_mentoring' => $file_mentoring,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->chatmentoring->insert($params);
		$lastId = $this->chatmentoring->getAdapter()->lastInsertId();
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
 		$where = $this->chatmentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->chatmentoring->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->chatmentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->chatmentoring->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->chatmentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->chatmentoring->update($params, $where);
	}
}