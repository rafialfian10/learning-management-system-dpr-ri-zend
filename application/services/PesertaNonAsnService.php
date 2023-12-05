<?php
class PesertaNonAsnService {
	  
 	function __construct() {
		$this->pesertanonasn = new PesertaNonAsn();
	}
	
	function getAllData() {
		$select = $this->pesertanonasn->select()->where('status = 1');
		$result = $this->pesertanonasn->fetchAll($select);
		return $result;
	}
	
	
	function getData($id) {
		$select = $this->pesertanonasn->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->pesertanonasn->fetchRow($select);
		return $result;
	}

	function addData($username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan, $fotopesertanonasn_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'username' => $username, 
			'email' => $email,
			'password' => $password,
			'nama' => $nama,
			'identitas' => $identitas,
			'no_telp' => $no_telp,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'pekerjaan' => $pekerjaan,
			'kewarganegaraan' => $kewarganegaraan,
			'fotopesertanonasn_uri' => $fotopesertanonasn_uri,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->pesertanonasn->insert($params);
		$lastId = $this->pesertanonasn->getAdapter()->lastInsertId();
		return $lastId;	
	}

	
	function editData($id, $username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan, $fotopesertanonasn_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(			
			'username' => $username, 
			'password' => $password,
			'nama' => $nama,
			'email' => $email,
			'no_telp' => $no_telp,
			'identitas' => $identitas,
			'tempat_lahir' => $tempat_lahir,
			'tanggal_lahir' => $tanggal_lahir,
			'pekerjaan' => $pekerjaan,
			'kewarganegaraan' => $kewarganegaraan,
			'fotopesertanonasn_uri' => $fotopesertanonasn_uri,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->pesertanonasn->getAdapter()->quoteInto('id = ?', $id);
		$this->pesertanonasn->update($params, $where);
	}

	function register($username, $email, $password, $nama, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'username' => $username, 
			'email' => $email,
			'password' => $password,
			'nama' => $nama,
			'identitas' => $identitas,
			'tempat_lahir' => $tempatlahir,
			'tanggal_lahir' => $tanggal_lahir,
			'jenis_kelamin' => $jenis_kelamin,
			'pekerjaan' => $pekerjaan,
			'kewarganegaraan' => $kewarganegaraan,
			'no_telp' => $telepon,			
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->pesertanonasn->insert($params);
		$lastId = $this->pesertanonasn->getAdapter()->lastInsertId();
		return $lastId;	
	}

	public function hapusfoto($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotopesertanonasn_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->pesertanonasn->getAdapter()->quoteInto('id = ?', $id);
		$this->pesertanonasn->update($params, $where);
	}
	
	public function deleteData($id) {
		$where = $this->pesertanonasn->getAdapter()->quoteInto('id = ?', $id);
		$this->pesertanonasn->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->pesertanonasn->getAdapter()->quoteInto('id = ?', $id);
		$this->pesertanonasn->update($params, $where);
	}
}