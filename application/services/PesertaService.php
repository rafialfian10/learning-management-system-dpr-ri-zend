<?php
	class PesertaService {
		
		function __construct() {
			$this->peserta = new Peserta();
			$this->user = new User();
		}
		
		function getAllData() {
			$select = $this->peserta->select()->where('status = 1');
			$result = $this->peserta->fetchAll($select);
			return $result;
		}

		function getAllDataASN() {
			$select = $this->peserta->select()
			->where('jenis_peserta = "ASN"')
			->where('status = 1');
			$result = $this->peserta->fetchAll($select);
			return $result;
		}

		function getAllDataSelainASN() {
			$select = $this->peserta->select()
			->where('jenis_peserta = "NonASN"')
			->where('status = 1');
			$result = $this->peserta->fetchAll($select);
			return $result;
		}

		function checkIfExists($nama) {
			// Lakukan pemeriksaan apakah nama sudah ada dalam data peserta
			$select = $this->peserta->select()->where('nama = ?', $nama);
			$result = $this->peserta->fetchRow($select);
			// Jika hasil query tidak kosong, berarti nama sudah ada dalam data peserta
			return ($result !== null);
		}

		function getAllDataByMentor($id_mentor) { 
			$sql = "SELECT b.*
					FROM peserta_batch a 
					INNER JOIN peserta b
					ON a.id_mentor = ?";

			$db = Zend_Registry::get('db');		
			$stmt = $db->query($sql, array($id_mentor));
			$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $stmt->fetchAll();
			return $result;
		}
		
		function getAllDataBatch($peserta_id) {
			$status = '';

			foreach($peserta_id as $key=>$peserta){
				if ($key == 0){
					$status.= " AND id = ".$peserta->id_peserta;
				} else {
					$status.= " OR id = ".$peserta->id_peserta;
				}
			}

			$sql = "SELECT * from peserta 
			WHERE status = 1" .$status;

			$db = Zend_Registry::get('db');		
			$stmt = $db->query($sql);
			$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $stmt->fetchAll();
			return $result;
		}

		function getAllDataPesertaBatch($id_batch)
		{
			$sql = "SELECT peserta.nama
			FROM peserta_batch
			JOIN peserta ON peserta_batch.id_peserta = peserta.id
			WHERE peserta_batch.id_batch = ?";

			$db = Zend_Registry::get('db');		
			$stmt = $db->query($sql, array($id_batch));
			$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
			$result = $stmt->fetchAll();
			return $result;
		}

		function getData($id) {
			$select = $this->peserta->select()->where('status = 1')->where('id = ?', $id);
			$result = $this->peserta->fetchRow($select);
			return $result;
		}

		function getDataSelainASN($id) {
			$select = $this->peserta->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'peserta'), array('*'))
			->joinLeft(array('b' => 'users'), 'a.id = b.id_peserta', array('username','password'))
			->where('a.id = ?', $id)
			->where('a.status = 1');
			$result = $this->peserta->fetchRow($select);
			return $result;
		}

		function registerNonAsn($nama, $email, $identitas, $tempatlahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $telepon, $file_name) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(			
				'nama' => $nama,
				'jenis_peserta' => "NonASN",
				'email' => $email,
				'identitas' => $identitas,
				'tempat_lahir' => $tempatlahir,
				'tanggal_lahir' => $tanggal_lahir,
				'jenis_kelamin' => $jenis_kelamin,
				'pekerjaan' => $pekerjaan,
				'kewarganegaraan' => $kewarganegaraan,
				'no_telp' => $telepon,			
				'fotopesertanonasn_uri' => $file_name,			
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$this->peserta->insert($params);
			$lastId = $this->peserta->getAdapter()->lastInsertId();
			return $lastId;	
		}

		function addSelainAsnData($username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan, $fotopesertanonasn_uri) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params_peserta = array(			
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
			$this->pesertanonasn->insert($params_peserta);
			$lastId = $this->pesertanonasn->getAdapter()->lastInsertId();
			return $lastId;	
		}

		function addData($nama) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
		
			$params = array(			
				'nama' => $nama, 
				'jenis_peserta' => "ASN", 
				'status' => 1, 
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);
			$this->peserta->insert($params);
			$lastId = $this->peserta->getAdapter()->lastInsertId();
			return $lastId;	
		}

		function editData($id, $nama, $jenis_peserta, $email, $identitas, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $no_telp, $fotopesertanonasn_uri) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			if($fotopesertanonasn_uri == '') {
				$params = array(
					'nama' => $nama,
					'jenis_peserta' => $jenis_peserta,
					'email' => $email,
					'identitas' => $identitas,
					'tempat_lahir' => $tempat_lahir,
					'tanggal_lahir' => $tanggal_lahir,
					'jenis_kelamin' => $jenis_kelamin,
					'pekerjaan' => $pekerjaan,
					'kewarganegaraan' => $kewarganegaraan,
					'no_telp' => $no_telp,
					'user_update' => $user_log,
					'tanggal_update' => $tanggal_log
				);
			} else {
				$params = array(
					'nama' => $nama,
					'jenis_peserta' => $jenis_peserta,
					'email' => $email,
					'identitas' => $identitas,
					'tempat_lahir' => $tempat_lahir,
					'tanggal_lahir' => $tanggal_lahir,
					'jenis_kelamin' => $jenis_kelamin,
					'pekerjaan' => $pekerjaan,
					'kewarganegaraan' => $kewarganegaraan,
					'no_telp' => $no_telp,
					'fotopesertanonasn_uri' => $fotopesertanonasn_uri,
					'user_update' => $user_log,
					'tanggal_update' => $tanggal_log
				);
			}

			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->update($params, $where);
		}

		function editSelainAsnData($id, $username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan, $fotopesertanonasn_uri) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			$params_peserta = array(			
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

			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->update($params_peserta, $where);
		}

		public function hapusfoto($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'fotopesertanonasn_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->update($params, $where);
		}


		public function deleteData($id) {
			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->delete($where);
		}

		public function deleteFiles($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'fotopesertanonasn_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->update($params, $where);
		}

		public function softDeleteData($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);

			$where = $this->peserta->getAdapter()->quoteInto('id = ?', $id);
			$this->peserta->update($params, $where);
		}

	}
?>