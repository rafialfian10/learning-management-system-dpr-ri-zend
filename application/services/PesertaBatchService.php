<?php
class PesertaBatchService 
{
	  
 	function __construct() 
	{
		$this->pesertaBatch = new PesertaBatch();
		$this->progress = new Progress();

	}
	
	function getAllData()
	{
		$select = $this->pesertaBatch->select()->where('status = 1');
		$result = $this->pesertaBatch->fetchAll($select);
		return $result;
	}
	
	function getDataBatch($id_batch)
	{
		$sql = "SELECT *
				FROM peserta_batch
				WHERE id_batch = ? LIMIT 1";

		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql, array($id_batch));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();	
		return $result;
	}

	function getAllDataBatch($id_batch)
	{
		$select = $this->pesertaBatch->select()->where('status = 1')->where('id_batch = ?', $id_batch);
		$result = $this->pesertaBatch->fetchAll($select);
		return $result;
	}

	function getAllDataPeserta($id_peserta)
	{
		$select = $this->pesertaBatch->select()->where('status = 1 OR status = 9')->where('id_peserta = ?', $id_peserta);
		$result = $this->pesertaBatch->fetchAll($select);
		return $result;
	}
	function getAllDataMentor($id_mentor)
	{
		$select = $this->pesertaBatch->select()->where('status = 1 OR status = 9')->where('id_mentor = ?', $id_mentor);
		$result = $this->pesertaBatch->fetchAll($select);
		return $result;
	}
	function getAllDataCoach($id_coach)
	{
		$select = $this->pesertaBatch->select()->where('status = 1 OR status = 9')->where('id_coach = ?', $id_coach);
		$result = $this->pesertaBatch->fetchAll($select);
		return $result;
	}

	function getDataPesertaBatch($id_peserta, $id_batch)
	{
		$select = $this->pesertaBatch->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch);
		$result = $this->pesertaBatch->fetchRow($select);
		return $result;
	}

	function getDataPeserta($id_peserta)
	{
		$select = $this->pesertaBatch->select()->where('status = 1')->where('id_peserta = ?', $id_peserta);
		$result = $this->pesertaBatch->fetchRow($select);
		return $result;
	}
	
	function getData($id)
	{
		$select = $this->pesertaBatch->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->pesertaBatch->fetchRow($select);
		return $result;
	}

	function getData2($id)
	{
		$sql = "SELECT *
				FROM peserta_batch
				WHERE status = 1 AND batch_id = ? LIMIT 1";

		$db = Zend_Registry::get('db');
		$stmt = $db->query($sql, array($id));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetch();	
		return $result;
	}

	function addPeserta($id_pelatihan, $id_batch, $id_peserta, $id_coach)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(				
			'id_pelatihan' => $id_pelatihan, 
			'id_coach' => $id_coach, 	
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta, 	
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		
		$this->pesertaBatch->insert($params);
		$lastId = $this->pesertaBatch->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData($id_pelatihan,$id_batch,$id_peserta,$id_mentor,$id_coach)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(				
			'id_pelatihan' => $id_pelatihan, 
			'id_coach' => $id_coach, 	
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta, 	
			'id_mentor' => $id_mentor,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		
		$this->pesertaBatch->insert($params);
		$lastId = $this->pesertaBatch->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData2($nama_batch, $deskripsi,$tipe_batch) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$sql = "INSERT INTO peserta_batch (nama_batch, user_input, tanggal_input, user_update, tanggal_update) VALUES (?, ?, ?, ?, ?);";

		$db = Zend_Registry::get('db');
		$db->query($sql, array($id, $user_log, $tanggal_log, $user_log, $tanggal_log));
	}

	function editData($id_pelatihan,$id_batch,$id_peserta,$id_mentor,$id_coach)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		
		$params = array(				
			'id_pelatihan' => $id_pelatihan, 
			'id_coach' => $id_coach, 	
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta, 	
			'id_mentor' => $id_mentor, 
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);
		
		$this->pesertaBatch->insert($params);
		$lastId = $this->pesertaBatch->getAdapter()->lastInsertId();
		return $lastId;	
	}

	public function deleteBatch($id)
	{
		$where = $this->pesertaBatch->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->pesertaBatch->delete($where);
	}
	public function softDeleteBatch($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->pesertaBatch->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->pesertaBatch->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->pesertaBatch->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->pesertaBatch->delete($where);
		$this->progress->delete($where);
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
 		
		$where = $this->pesertaBatch->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->pesertaBatch->update($params, $where);
	}
	public function softDeleteData2($id_batch, $id_peserta)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = array(
			'id_batch = ?' => $id_batch,
			'id_peserta = ?' => $id_peserta
		);

		$this->pesertaBatch->update($params, $where);
	}

}