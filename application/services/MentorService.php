<?php
class MentorService {
	  
 	function __construct() {
		$this->mentor = new Mentor();
	}
	
	function getAllData() {
		$select = $this->mentor->select()->where('status = 1');
		$result = $this->mentor->fetchAll($select);
		return $result;
	}

	function getAllDataBatch($mentor_id)
	{

		$status = '';

		foreach($mentor_id as $key=>$mentor){
			if ($key == 0){
				$status.= " AND id = ".$mentor->id_mentor;
			} else {
				$status.= " OR id = ".$mentor->id_mentor;
			}
		}

		$sql = "SELECT * from mentor 
		WHERE status = 1" .$status;

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getData($id) {
		$select = $this->mentor->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->mentor->fetchRow($select);
		return $result;
	}

	function addData($nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $fotomentor_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'nama_mentor' => $nama_mentor, 
			'identitas_mentor' => $identitas_mentor,
			'instansi' => $instansi,
			'email' => $email,
			'no_telp' => $no_telp,
			'fotomentor_uri' => $fotomentor_uri,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->mentor->insert($params);
		$lastId = $this->mentor->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $fotomentor_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'nama_mentor' => $nama_mentor,
			'identitas_mentor' => $identitas_mentor, 	 		
			'instansi' => $instansi, 	
			'email' => $email, 	
			'no_telp' => $no_telp, 	
			'fotomentor_uri' => $fotomentor_uri,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->mentor->getAdapter()->quoteInto('id = ?', $id);
		$this->mentor->update($params, $where);

	}

	function editBiodata($id, $nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $fotomentor_uri) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'nama_mentor' => $nama_mentor,
			'identitas_mentor' => $identitas_mentor, 	 		
			'instansi' => $instansi, 	
			'email' => $email, 	
			'no_telp' => $no_telp, 	
			'fotomentor_uri' => $fotomentor_uri,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->mentor->getAdapter()->quoteInto('id = ?', $id);
		$this->mentor->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotomentor_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->mentor->getAdapter()->quoteInto('id = ?', $id);
		$this->mentor->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->mentor->getAdapter()->quoteInto('id = ?', $id);
		$this->mentor->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->mentor->getAdapter()->quoteInto('id = ?', $id);
		$this->mentor->update($params, $where);
	}
}