<?php
class ChatDiskusiService {
	  
 	function __construct() {
		$this->chatdiskusi = new ChatDiskusi();
	}
	
	function getAllData() {
		$select = $this->chatdiskusi->select()->where('status = 1');
		$result = $this->chatdiskusi->fetchAll($select);
		return $result;
	}

	function getAllDataForum($id) {
		$select = $this->chatdiskusi->select()->where('status = 1')->where('id_forum = ?', $id);
		$result = $this->chatdiskusi->fetchAll($select);
		return $result;
	}

	function getAllPesertaForum($id_forum) { 
		$sql = "SELECT x.id_user, x.role_user, y.nama as nama, COUNT(*) AS jumlah
		FROM chat_diskusi x
		INNER JOIN peserta y ON x.id_user = y.id
		WHERE x.role_user = 'Peserta' 
		AND x.id_forum = ?
		GROUP BY x.id_user, y.nama";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_forum));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllDataForumReply($id) {
		$select = $this->chatdiskusi->select()->where('status = 1')->where('id_forum = ?', $id)->where('id_reply = 0');
		$result = $this->chatdiskusi->fetchAll($select);
		return $result;
	}

	function getAllDataReplyNotNull($id) {
		$select = $this->chatdiskusi->select()->where('status = 1')->where('id_forum = ?', $id)->where('id_reply != 0');
		$result = $this->chatdiskusi->fetchAll($select);
		return $result;
	}

	function getAllDataReply($id) {
		$select = $this->chatdiskusi->select()->where('status = 1')->where('id_reply = ?', $id);
		$result = $this->chatdiskusi->fetchAll($select);
		return $result;
	}
	// function getAllDataReply($id) {
	// 	$select = $this->chatdiskusi->select()->where('status = 1')->where('id_reply_mentoring = ?', $id);
	// 	$result = $this->chatdiskusi->fetchAll($select);
	// 	return $result;
	// }
	

	function getData($id) {
		$select = $this->chatdiskusi->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->chatdiskusi->fetchRow($select);
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
		$this->chatdiskusi->insert($params);
		$lastId = $this->chatdiskusi->getAdapter()->lastInsertId();
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
		$this->chatdiskusi->insert($params);
		$lastId = $this->chatdiskusi->getAdapter()->lastInsertId();
		return $lastId;
	}

	
	function replyData($id_forum,$id_reply,$id_user,$role_user, $isi, $file_forum) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_forum' => $id_forum,
			'id_reply' => $id_reply,  
			'id_user' => $id_user,
			'role_user' => $role_user,
			'isi' => $isi,
			'file_forum' => $file_forum,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->chatdiskusi->insert($params);
		$lastId = $this->chatdiskusi->getAdapter()->lastInsertId();
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
 		$where = $this->chatdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->chatdiskusi->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->chatdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->chatdiskusi->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->chatdiskusi->getAdapter()->quoteInto('id = ?', $id);
		$this->chatdiskusi->update($params, $where);
	}
}