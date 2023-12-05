<?php
class BatchService 
{
	  
 	function __construct() 
	{
		$this->batch = new Batch();
	}
	
	function getAllData()
	{
		$select = $this->batch->select()->where('status = 1')->order('id DESC');
		$result = $this->batch->fetchAll($select);
		return $result;
	}

	function getAllDataByCoach($id_coach)
	{
		$select = $this->batch->select()->where('status = 1')->where('id_coach = ?', $id_coach)->order('id DESC');
		$result = $this->batch->fetchAll($select);
		return $result;
	}

	function getDataByPelatihan($id)
	{ 
		$sql = "SELECT b.*, a.judul_batch as judul_batch, a.tgl_awal as tgl_awal, a.tgl_akhir as tgl_akhir, a.kuota_peserta as kuota_peserta, a.id as id_batch, a.id_coach as id_coach
				FROM batch a 
				INNER JOIN pelatihan b ON a.id_pelatihan = b.id
				WHERE a.status = 1
				AND a.id = ?";

		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();
		return $result;
	}

	function getAllDataByPelatihan()
	{ 
		$sql = "SELECT b.*, a.judul_batch as judul_batch, a.tgl_awal as tgl_awal, a.tgl_akhir as tgl_akhir, a.kuota_peserta as kuota_peserta, a.id as id_batch
				FROM batch a 
				INNER JOIN pelatihan b ON a.id_pelatihan = b.id
				WHERE a.status = 1
				ORDER BY a.id DESC";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql);
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}

	function getAllDataByPeserta($id_peserta)
	{ 
		$sql = "SELECT b.*, a.id_pelatihan as id_kelas, c.nama_pelatihan as nama_pelatihan, c.sampul_pelatihan as sampul_pelatihan, c.tipe_pelatihan as tipe_pelatihan, d.nama_mentor as nama_mentor
				FROM peserta_batch a 
				INNER JOIN batch b ON a.id_batch = b.id
				INNER JOIN pelatihan c ON a.id_pelatihan = c.id
				LEFT JOIN mentor d ON a.id_mentor = d.id
				WHERE a.id_peserta = ?
				AND b.status = 1 AND a.status = 1
				ORDER BY a.id DESC";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_peserta));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}
	function getAllDataByPesertaTidakAktif($id_peserta)
	{ 
		$sql = "SELECT b.*, a.id_pelatihan as id_kelas, c.nama_pelatihan as nama_pelatihan, c.sampul_pelatihan as sampul_pelatihan, d.nama_mentor as nama_mentor
				FROM peserta_batch a 
				INNER JOIN batch b ON a.id_batch = b.id
				INNER JOIN pelatihan c ON a.id_pelatihan = c.id
				LEFT JOIN mentor d ON a.id_mentor = d.id
				WHERE a.id_peserta = ?
				AND (b.status = 1 OR b.status = 9)  AND a.status = 9
				ORDER BY a.id DESC";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_peserta));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}
	
	function getData($id)
	{
		$select = $this->batch->select()->where('id = ?', $id)->where('status = 1');
		$result = $this->batch->fetchRow($select);
		return $result;

	}

	function getDataForSertifikat($id)
	{
		$select = $this->batch->select()->where('id = ?', $id);
		$result = $this->batch->fetchRow($select);
		return $result;

	}

	function getData2($id)
	{
		$sql = "SELECT *
				FROM batch
				WHERE status = 1 AND id = ?";

		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();	
		return $result;
	}

	function addData($judul_batch, $id_pelatihan, $id_coach, $kuota_peserta,$tgl_awal,$tgl_akhir)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'judul_batch' => $judul_batch,
			'id_pelatihan' => $id_pelatihan,
			'id_coach' => $id_coach,
			'kuota_peserta' => $kuota_peserta, 	
			'tgl_awal' => $tgl_awal, 	
			'tgl_akhir' => $tgl_akhir, 
			'status' => 1, 	
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->batch->insert($params);
		$lastId = $this->batch->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData2($nama_batch, $deskripsi,$tipe_batch) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = "INSERT INTO batch (nama_batch, user_input, tanggal_input, user_update, tanggal_update) VALUES (?, ?, ?, ?, ?);";

		$db = Zend_Registry::get('db');
		$db->query($sql, array($id, $user_log, $tanggal_log, $user_log, $tanggal_log));
	}

	function editData($id, $id_pelatihan, $id_coach, $judul_batch,$kuota_peserta,$tgl_awal,$tgl_akhir)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		
		$params = array(			
			'judul_batch' => $judul_batch,
			'id_pelatihan' => $id_pelatihan,
			'id_coach' => $id_coach,
			'kuota_peserta' => $kuota_peserta, 	
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		
 		$where = $this->batch->getAdapter()->quoteInto('id = ?', $id);
		$this->batch->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sampul_batch' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->batch->getAdapter()->quoteInto('id = ?', $id);
		$this->batch->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->batch->getAdapter()->quoteInto('id = ?', $id);
		$this->batch->delete($where);
	}

	public function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->batch->getAdapter()->quoteInto('id = ?', $id);
		$this->batch->update($params, $where);
	}

}