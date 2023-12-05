<?php
class DaftarAbsenService 
{
	  
 	function __construct() 
	{
		$this->daftarabsen = new DaftarAbsen();
	}
	
	function getAllData()
	{
	
		$select = $this->daftarabsen->select()->where('status = 1');
		$result = $this->daftarabsen->fetchAll($select);
		return $result;
	}
	
	
	function getData($id)
	{
		$select = $this->daftarabsen->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->daftarabsen->fetchRow($select);
		return $result;
	}

	function addData($nama_daftarabsen, $instansi, $email, $no_telp, $identitas_daftarabsen, $fotodaftarabsen_uri) 
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'nama_daftarabsen' => $nama_daftarabsen, 
			'identitas_daftarabsen' => $identitas_daftarabsen,
			'instansi' => $instansi,
			'email' => $email,
			'no_telp' => $no_telp,
			'fotodaftarabsen_uri' => $fotodaftarabsen_uri,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->daftarabsen->insert($params);
		$lastId = $this->daftarabsen->getAdapter()->lastInsertId();
		return $lastId;	
	}


	function editData($id, $nama_daftarabsen, $identitas_daftarabsen, $instansi, $email, $no_telp, $fotodaftarabsen_uri)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'nama_daftarabsen' => $nama_daftarabsen,
			'identitas_daftarabsen' => $identitas_daftarabsen, 	 		
			'instansi' => $instansi, 	
			'email' => $email, 	
			'no_telp' => $no_telp, 	
			'fotodaftarabsen_uri' => $fotodaftarabsen_uri,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->daftarabsen->getAdapter()->quoteInto('id = ?', $id);
		$this->daftarabsen->update($params, $where);

	}

	public function deleteFiles($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'fotodaftarabsen_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->daftarabsen->getAdapter()->quoteInto('id = ?', $id);
		$this->daftarabsen->update($params, $where);
	}
	
	public function deleteData($id)
	{
		$where = $this->daftarabsen->getAdapter()->quoteInto('id = ?', $id);
		$this->daftarabsen->delete($where);
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
 		
		$where = $this->daftarabsen->getAdapter()->quoteInto('id = ?', $id);
		$this->daftarabsen->update($params, $where);
	}

}