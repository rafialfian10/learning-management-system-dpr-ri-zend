<?php
class PenggunaService
{

	function __construct()
	{
		$this->pengguna = new Pengguna();
	}

	function getAllData()
	{
		$select = $this->pengguna->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'pengguna'), array('*'))
			->where('a.status = 1');

		$result = $this->pengguna->fetchAll($select);
		return $result;
	}

	function getDataByEmail($email)
	{
		$select = $this->pengguna->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'pengguna'), array('*'))
			->where('a.email = ? AND a.status = 1', $email);
		$result = $this->pengguna->fetchRow($select);
		return $result;
	}

	function getDataByUsername($username)
	{
		$select = $this->pengguna->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'pengguna'), array('id', 'nama', 'nip', 'pengguna', 'email', 'peran', 'jabatan', 'unit_kerja as departemen', 'id_instansi', 'instansi', 'handphone', 'image_name', 'unit_kerja', 'id_satker'))
			->where('a.pengguna = ? AND a.status = 1', $username);
		$result = $this->pengguna->fetchRow($select);
		return $result;
	}

	function getData($id)
	{
		$select = $this->pengguna->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'pengguna'), array('*'))
			->where('a.id = ?', $id);
		$result = $this->pengguna->fetchRow($select);
		return $result;
	}

	function addData($jenis, $nama, $pengguna, $email, $nip, $jabatan, $unit_kerja, $id_instansi, $instansi, $jenis_kelamin, $handphone, $telepon, $peran, $sandi_random, $id_pengguna, $id_satker)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		//$sandi = hash('sha512', $sandi);
		$sandi = md5($sandi_random);

		$params = array(
			'jenis' => $jenis,
			'nama' => $nama,
			'pengguna' => $pengguna,
			'email' => $email,
			'sandi' => $sandi,
			'sandi_random' => $sandi_random,
			'id_satker' => $id_satker,
			'id_pengguna' => $id_pengguna,
			'peran' => 'guest',
			'nip' => $nip,
			'jabatan' => $jabatan,
			'unit_kerja' => $unit_kerja,
			'id_instansi' => $id_instansi,
			'instansi' => $instansi,
			'jenis_kelamin' => $jenis_kelamin,
			'handphone' => $handphone,
			'telepon' => $telepon,
			'peran' => $peran,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->pengguna->insert($params);
		$lastId = $this->pengguna->getAdapter()->lastInsertId();
		return $lastId;
	}

	function editData($id, $jenis, $nama, $pengguna, $email, $nip, $jabatan, $unit_kerja, $id_instansi, $instansi, $jenis_kelamin, $handphone, $telepon, $peran)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'jenis' => $jenis,
			'nama' => $nama,
			'pengguna' => $pengguna,
			'email' => $email,
			'peran' => 'guest',
			'nip' => $nip,
			'jabatan' => $jabatan,
			'unit_kerja' => $unit_kerja,
			'id_instansi' => $id_instansi,
			'instansi' => $instansi,
			'jenis_kelamin' => $jenis_kelamin,
			'handphone' => $handphone,
			'telepon' => $telepon,
			'peran' => $peran,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function editSandiData($id, $sandi_random)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		/*$sandi = hash('sha512', $sandi);*/
		$sandi = md5($sandi_random);

		$params = array(
			'sandi_random' => $sandi_random,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function editProfileData($id, $nama, $jabatan, $jenis_kelamin, $handphone, $nip, $instansi, $unit_kerja, $telepon)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'nama' => $nama,
			'jabatan' => $jabatan,
			'jenis_kelamin' => $jenis_kelamin,
			'handphone' => $handphone,
			'nip' => $nip,
			'instansi' => $instansi,
			'unit_kerja' => $unit_kerja,
			'telepon' => $telepon,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function editBerkas($id, $file_name, $file_type, $file_size)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'file_name' => $file_name,
			'file_type' => $file_type,
			'file_size' => $file_size,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function editFoto($id, $image_name, $image_type, $image_size)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'image_name' => $image_name,
			'image_type' => $image_type,
			'image_size' => $image_size,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
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

		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	public function softDeleteFile($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'file_name' => null,
			'file_type' => null,
			'file_size' => null,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		return $this->pengguna->update($params, $where);
	}

	public function softDeleteImage($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'image_name' => null,
			'image_type' => null,
			'image_size' => null,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		return $this->pengguna->update($params, $where);
	}

	function getDataByCred($cred)
	{
		$select = $this->pengguna->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'pengguna'), array('*'))
			->where('a.credential_access = ? AND a.credential_expire > NOW()', $cred);
		$result = $this->pengguna->fetchRow($select);
		return $result;
	}

	function setCredentialForgotPassword($id, $sandi, $email)
	{
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'credential_access' => $sandi,
			'credential_expire' => date("Y-m-d H:i:s", strtotime('+3 hours')),
			'user_update' => $email,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function setForgotPassword($id, $password, $cred, $email)
	{
		$tanggal_log = date('Y-m-d H:i:s');
		$sandi = hash('sha512', $password);

		$params = array(
			'credential_access' => null,
			'credential_expire' => null,
			'sandi' => md5($sandi),
			'sandi_random' => $password,
			'user_update' => $email,
			'tanggal_update' => $tanggal_log
		);

		$where = "id = ? AND (credential_access = ? AND credential_expire > ?)";
		$where = $this->pengguna->getAdapter()->quoteInto($where, $id, null, 1);
		$where = $this->pengguna->getAdapter()->quoteInto($where, $cred, null, 1);
		$where = $this->pengguna->getAdapter()->quoteInto($where, $tanggal_log, null, 1);
		$this->pengguna->update($params, $where);
	}

	function editFile($id, $file_name, $file_type, $file_size)
	{
		$params = array(
			'file_name' => $file_name,
			'file_type' => $file_type,
			'file_size' => $file_size
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}

	function deleteFile($id)
	{
		$params = array(
			'file_name' => null,
			'file_size' => null,
			'file_type' => null
		);
		$where = $this->pengguna->getAdapter()->quoteInto('id = ?', $id);
		$this->pengguna->update($params, $where);
	}
}
