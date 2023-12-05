<?php
class SkorPretestService 
{
	  
 	function __construct() 
	{
		$this->skorPretest = new SkorPretest();
	}
	
	function getAllData()
	{
	
		$select = $this->skorPretest->select()->where('status = 1');
		$result = $this->skorPretest->fetchAll($select);
		return $result;
	}

	function getAllNilai()
	{ 
		$sql = "SELECT a.*, b.skor_keaktifan, b.skor_pemahaman, b.skor_tugas, b.skor_forum, b.skor_akhir AS skor_mentoring
				FROM skor_pretest a
				JOIN skor_mentoring b ON (a.id_peserta = b.id_peserta AND a.id_batch = b.id_batch)";

		$db = Zend_Registry::get('db');		
		$stmt = $db->query($sql, array($id_subtipe_artikel));
		$stmt->setFetchMode(Zend_Db::FETCH_OBJ); 
		$result = $stmt->fetchAll();
		return $result;
	}
	
	function getData($id)
	{
		$select = $this->skorPretest->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}

	function getJawabanNontesPeserta($id_batch)
	{
		$select = $this->skorPretest->select()->where('status = 1')->where('id_batch = ?', $id_batch);
		$result = $this->skorPretest->fetchAll($select);
		return $result;
	}
	
	function getDataScorePretest($id_batch, $id_peserta)
	{	
		$select = $this->skorPretest->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}

	function getDataSkor($id, $id_batch)
	{
		$select = $this->skorPretest->select()->where('status = 1')->where('id_peserta = ?', $id)->where('id_batch = ?', $id_batch);;
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}

	function getDataPelatihan($id)
	{
		$select = $this->skorPretest->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}


	
	function getDataSertifikat($id_batch, $id_peserta)
	{
		$select = $this->skorPretest->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}

	function cekNilai($id_batch, $id_peserta, $id_pelatihan) 
	{	
		$select = $this->skorPretest->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_batch = ?', $id_batch)->where('id_pelatihan = ?', $id_pelatihan);
		$result = $this->skorPretest->fetchRow($select);
		return $result;
	}

	function addNilai($id_batch, $id_peserta, $id_pelatihan, $jawaban_peserta, $kunci_jawaban, $skor_akhir) {
		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_batch' => $id_batch,
			'id_peserta' => $id_peserta,  
			'id_pelatihan' => $id_pelatihan,
			'jawaban_peserta' => $jawaban_peserta,
			'kunci_jawaban' => $kunci_jawaban,
			'skor_akhir' => $skor_akhir,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->skorPretest->insert($params);
		$lastId = $this->skorPretest->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function addData($id_batch, $id_peserta, $id_pelatihan, $skor_akhir) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'id_pelatihan' => $id_pelatihan,
			'skor_akhir' => $skor_akhir,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->skorPretest->insert($params);
		$lastId = $this->skorPretest->getAdapter()->lastInsertId();
		return $lastId;	
	}


	function editData($id, $id_batch, $id_peserta, $id_pelatihan, $skor_akhir)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		
		$params = array(
			'id_batch' => $id_batch, 
			'id_peserta' => $id_peserta,
			'id_pelatihan' => $id_pelatihan,
			'skor_akhir' => $skor_akhir,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->skorPretest->getAdapter()->quoteInto('id = ?', $id);
		$this->skorPretest->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotoskorPretest_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->skorPretest->getAdapter()->quoteInto('id = ?', $id);
		$this->skorPretest->update($params, $where);
	}
	
	public function deleteData($id)
	{
		$where = $this->skorPretest->getAdapter()->quoteInto('id = ?', $id);
		$this->skorPretest->delete($where);
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
 		
		$where = $this->skorPretest->getAdapter()->quoteInto('id = ?', $id);
		$this->skorPretest->update($params, $where);
	}

}