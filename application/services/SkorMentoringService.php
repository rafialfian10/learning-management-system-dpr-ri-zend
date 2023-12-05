<?php
class SkorMentoringService 
{
	  
 	function __construct() 
	{
		$this->skorMentoring = new SkorMentoring();
	}
	
	function getAllData()
	{
		$select = $this->skorMentoring->select()->where('status = 1')->order('id DESC');
		$result = $this->skorMentoring->fetchAll($select);
		return $result;
	}

	function getAllDataByMentor($id_mentor)
	{
		$select = $this->skorMentoring->select()->where('status = 1')->where('id_mentor = ?', $id_mentor)->order('id DESC');
		$result = $this->skorMentoring->fetchAll($select);
		return $result;
	}

	function getDataScoreMentoring($id_batch, $id_peserta)
	{	
		$select = $this->skorMentoring->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
		$result = $this->skorMentoring->fetchRow($select);
		return $result;
	}

	function getDataSertifikat($id_batch, $id_peserta)
	{
		$select = $this->skorMentoring->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
		$result = $this->skorMentoring->fetchRow($select);
		return $result;
	}
	
	function getData($id)
	{
		$select = $this->skorMentoring->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->skorMentoring->fetchRow($select);
		return $result;
	}

	function addSkor($id_batch, $id_peserta, $id_mentor) {
		$existingData = $this->skorMentoring->fetchRow(
			$this->skorMentoring->select()
				->where('id_batch = ?', $id_batch)
				->where('id_peserta = ?', $id_peserta)
				->where('id_mentor = ?', $id_mentor)
		);
	
		// Jika data sudah ada, Anda dapat mengembalikan ID atau memberikan pesan kesalahan
		if ($existingData) {
			return $existingData['id']; // ID data yang sudah ada
		}
	
		// Jika data belum ada, Anda dapat menyisipkan data baru
		$params = array(
			'id_batch' => $id_batch,
			'id_peserta' => $id_peserta,
			'id_mentor' => $id_mentor,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
	
		$this->skorMentoring->insert($params);
		$lastId = $this->skorMentoring->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData($id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'skor_keaktifan' => $skor_keaktifan, 
			'skor_pemahaman' => $skor_pemahaman,
			'skor_tugas' => $skor_tugas, 
			'skor_forum' => $skor_forum,
			'skor_akhir' => $skor_akhir,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);

		$this->skorMentoring->insert($params);
		$lastId = $this->skorMentoring->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'skor_keaktifan' => $skor_keaktifan, 
			'skor_pemahaman' => $skor_pemahaman,
			'skor_tugas' => $skor_tugas, 
			'skor_forum' => $skor_forum,
			'skor_akhir' => $skor_akhir,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log,
		);

 		$where = $this->skorMentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->skorMentoring->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotoskorMentoring_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->skorMentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->skorMentoring->update($params, $where);
	}

	public function deleteBatch($id)
	{
		$where = $this->skorMentoring->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->skorMentoring->delete($where);
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
 		
		$where = $this->skorMentoring->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->skorMentoring->update($params, $where);
	}

	
	public function deleteData($id)
	{
		$where = $this->skorMentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->skorMentoring->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->skorMentoring->getAdapter()->quoteInto('id = ?', $id);
		$this->skorMentoring->update($params, $where);
	}

}