<?php
class PelatihanService {
	  
 	function __construct() {
		$this->pelatihan = new Pelatihan();
	}

	function getData($id) {
		$select = $this->pelatihan->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->pelatihan->fetchRow($select);
		return $result;
	}
	
	function getAllData() {
		$select = $this->pelatihan->select()->where('status = 1')->order('id DESC');
		$result = $this->pelatihan->fetchAll($select);
		return $result;
	}

	function getAllDataPelatihan() {
		$select = $this->pelatihan->select()->where('status = 1');
		$result = $this->pelatihan->fetchAll($select);
		return $result;
	}

	function getAllDataByPengajar($id_pengajar) {
		$sql = "SELECT y.*, GROUP_CONCAT(x.nama_pengajar SEPARATOR ',') AS nama_pengajar
					FROM pelatihan y
					INNER JOIN pengajar x ON FIND_IN_SET(x.id, y.id_pengajar) > 0
					WHERE FIND_IN_SET(?, y.id_pengajar) > 0
					AND y.status = 1
					GROUP BY y.id
					ORDER BY y.id DESC";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_pengajar));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllData2() {
		$sql = "SELECT y.*, GROUP_CONCAT(x.nama_pengajar SEPARATOR ',') AS nama_pengajar
		FROM pelatihan y
		INNER JOIN pengajar x ON FIND_IN_SET(x.id, y.id_pengajar) > 0
		WHERE y.status = 1 
		GROUP BY y.id
		ORDER BY y.id DESC";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllData3() {
		$select = $this->pelatihan->select()->where('status = 1');
		$result = $this->pelatihan->fetchAll($select);
		return $result;
	}

	function getData3($id) {
		$select = $this->pelatihan->select()->where('id = ?', $id);
		$result = $this->pelatihan->fetchRow($select);
		return $result;
	}

	function getData2($id) {
		$sql = "SELECT *
				FROM pelatihan
				WHERE status = 1 AND id = ?";

		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();	
		return $result;
	}

	function addData($nama_pelatihan, $deskripsi, $tipe_pelatihan, $sampul_pelatihan, $id_pengajar) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array (			
			'nama_pelatihan' => $nama_pelatihan, 	
			'deskripsi' => $deskripsi, 	
			'tipe_pelatihan' => $tipe_pelatihan, 
			'sampul_pelatihan' => $sampul_pelatihan, 	
			'id_pengajar' => $id_pengajar,
			'status' => 1, 	
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->pelatihan->insert($params);
		$lastId = $this->pelatihan->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $nama_pelatihan, $deskripsi, $tipe_pelatihan, $sampul_pelatihan, $id_pengajar) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		
		if($sampul_pelatihan == '') {
			$params = array(
				'nama_pelatihan' => $nama_pelatihan, 	
				'deskripsi' => $deskripsi, 	
				'tipe_pelatihan' => $tipe_pelatihan,
				'id_pengajar' => $id_pengajar, 	
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		} else {
			$params = array(
				'nama_pelatihan' => $nama_pelatihan, 	
				'deskripsi' => $deskripsi, 	
				'tipe_pelatihan' => $tipe_pelatihan, 	
				'sampul_pelatihan' => $sampul_pelatihan, 
				'id_pengajar' => $id_pengajar,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		}
		
 		$where = $this->pelatihan->getAdapter()->quoteInto('id = ?', $id);
		$this->pelatihan->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sampul_pelatihan' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->pelatihan->getAdapter()->quoteInto('id = ?', $id);
		$this->pelatihan->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->pelatihan->getAdapter()->quoteInto('id = ?', $id);
		$this->pelatihan->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->pelatihan->getAdapter()->quoteInto('id = ?', $id);
		$this->pelatihan->update($params, $where);
	}
}