<?php
class UserService {
	  
 	function __construct() {
		$this->user = new User();
		$this->peserta = new Peserta();
	}

	// public function authenticate($username, $password) {
       
    //     $select = $this->user->select()->where('username = ?', $username);
    //     $user = $this->user->fetchRow($select);
		
    //     if ($user !== null && $user->password === $password) {
        
	// 		return $user;
    //     } else {
    //         return false;
    //     }
    // }


	
	public function authenticate($username, $password) {
       
        $select = $this->user->select()
				->setIntegrityCheck(false)
				->from(array('a' => 'users'), array('*'))
				->joinLeft(array('b' => 'peserta'), 'a.id_peserta = b.id', array('jenis_peserta'))
				->where('username = ?', $username);
        $user = $this->user->fetchRow($select);
		
        if ($user !== null && $user->password === $password && $user->jenis_peserta == "NonASN" ) {

			return $user;
        } else {
            return false;
        }
    }



	public function X($username, $password)
	{
	
		$select = $this->user->select()
			->where('username = ?', $username)
			->from($this->user, ['password','name','email','kontak','id', 'id_peserta', 'id_mentor', 'id_coach', 'id_pengajar', 'id_penilai', 'id_admin']);

		$userRow = $this->user->fetchRow($select);
		
		if ($userRow !== null && $userRow->password === $password) {

			$user = new stdClass();
			$user->name = $userRow->email;
			$user->kontak = $userRow->kontak;
			$user->id = $userRow->id;
			$user->id_peserta = $userRow->id_peserta;
			$user->id_mentor = $userRow->id_mentor;
			$user->id_coach = $userRow->id_coach;
			$user->id_pengajar = $userRow->id_pengajar;
			$user->id_penilai = $userRow->id_penilai;
			$user->id_admin = $userRow->id_admin;
			
			return $user;
		} else {
			return false;
		}
	}


	function getAllData() {
		$select = $this->user->select()->where('status = 1');
		$result = $this->user->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function getDataAdmin($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_admin = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function getDataCoach($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_coach = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function getDataMentor($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_mentor = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function getDataPengajar($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_pengajar = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function getDataPenilai($id) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_penilai = ?', $id);
		$result = $this->user->fetchRow($select);
		return $result;
	}
	

	function getDataUser2($id, $id_user) {
		$select = $this->user->select()
		->where('status = 1')
		->where('id_admin = ?', $id)
		->where('id = ?', $id_user);
		$result = $this->user->fetchRow($select);
		return $result;
	}

	function addData($username, $password, $id, $name, $id_peserta) {
		
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'username' => $username,
			'password' => $password, 	
			'id' => $id, 	
			'name' => $name,
			'id_peserta' => $id_peserta, 	
			'user_input' => $username,
			'tanggal_input' => $tanggal_log,
		);
		$this->user->insert($params);
		return $id;	
	}

	function register($id, $username, $password, $nama, $email, $telepon, $id_peserta) {
		
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id' => $id,
			'username' => $username,
			'password' => $password, 		
			'name' => $nama,	
			'email' => $email,	
			'kontak' => $telepon,
			'id_peserta' => $id_peserta,
			//'verification_token' => $verification_token,
			'user_input' => $username,
			'tanggal_input' => $tanggal_log,
		);
		$this->user->insert($params);
		return $id;	
	}



	function UbahPasswordData($id, $new_password) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
	
		$params = array(
			'password' => $new_password,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);
	}
	






	function registerWithToken($id, $username, $password, $nama, $email, $telepon, $id_peserta,$verification_token) {
		
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id' => $id,
			'username' => $username,
			'password' => $password, 		
			'name' => $nama,	
			'email' => $email,	
			'kontak' => $telepon,
			'id_peserta' => $id_peserta,
			'verification_token' => $verification_token,
			'user_input' => $username,
			'tanggal_input' => $tanggal_log,
		);
		$this->user->insert($params);
		return $id;	
	}


	function editData($id, $id_pelatihan, $nama_user) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_pelatihan' => $id_pelatihan, 	
			'nama_user' => $nama_user,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	function editSelainAsnData($id, $username, $password, $nama, $email, $no_telp) {

		// var_dump($id, $username, $password, $nama, $email, $no_telp); die;
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params_users = array(			
			'username' => $username, 
			'password' => $password,
			'name' => $nama,
			'email' => $email,
			'kontak' => $no_telp,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		// var_dump($params_users);die();

		$where = $this->user->getAdapter()->quoteInto('id = ?', 1000000+$id);
		$this->user->update($params_users, $where);
	}


	function editDataAdmin($id, $id_admin) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_admin' => $id_admin, 	
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	function editDataPenilai($id, $id_penilai) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_penilai' => $id_penilai, 	
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	function editDataMentor($id, $id_mentor) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_mentor' => $id_mentor, 	
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	function editDataPengajar($id, $id_pengajar) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_pengajar' => $id_pengajar, 	
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	function editDataCoach($id, $id_coach) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'id_coach' => $id_coach, 	
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);

	}

	public function deleteData($id) {
		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->delete($where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		
 		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->user->getAdapter()->quoteInto('id = ?', $id);
		$this->user->update($params, $where);
	}

}