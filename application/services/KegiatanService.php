<?php
class KegiatanService 
{
	  
 	function __construct() 
	{
		$this->kegiatan = new Kegiatan();
	}
	
	function getAllData()
	{
	
		$select = $this->kegiatan->select()->where('status = 1');
		$result = $this->kegiatan->fetchAll($select);
		return $result;
	}
	
	
	function getData($id)
	{
		$select = $this->kegiatan->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->kegiatan->fetchRow($select);
		return $result;
	}

	function addData($title, $tipe_kegiatan, $tgl_awal, $tgl_akhir, $deskripsi, $kuota_peserta, $penyelenggara, $konten, $sampul)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'title' => $title, 
			'tipe_kegiatan' => $tipe_kegiatan,
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
			'deskripsi' => $deskripsi,
			'kuota_peserta' => $kuota_peserta,
			'penyelenggara' => $penyelenggara,
			'konten' => $konten,
			'sampul' => $sampul,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		
		$this->kegiatan->insert($params);
		$lastId = $this->kegiatan->getAdapter()->lastInsertId();
		return $lastId;	
	}


	function editData($id, $title, $tipe_kegiatan, $tgl_awal, $tgl_akhir, $deskripsi, $kuota_peserta, $penyelenggara, $konten, $sampul)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'title' => $title, 
			'tipe_kegiatan' => $tipe_kegiatan,
			'tgl_awal' => $tgl_awal,
			'tgl_akhir' => $tgl_akhir,
			'deskripsi' => $deskripsi,
			'kuota_peserta' => $kuota_peserta,
			'penyelenggara' => $penyelenggara,
			'konten' => $konten,
			'sampul' => $sampul,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->kegiatan->getAdapter()->quoteInto('id = ?', $id);
		$this->kegiatan->update($params, $where);
	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'sampul' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->kegiatan->getAdapter()->quoteInto('id = ?', $id);
		$this->kegiatan->update($params, $where);
	}
	
	public function deleteData($id)
	{
		$where = $this->kegiatan->getAdapter()->quoteInto('id = ?', $id);
		$this->kegiatan->delete($where);
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
 		
		$where = $this->kegiatan->getAdapter()->quoteInto('id = ?', $id);
		$this->kegiatan->update($params, $where);
	}

}