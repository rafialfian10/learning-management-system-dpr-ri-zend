<?php
	class PenugasanService {
	  
		function __construct() {
			$this->penugasan = new Penugasan();
		}
		
		function getAllData() {
		
			$select = $this->penugasan->select()->where('status = 1')->order('id DESC');
			$result = $this->penugasan->fetchAll($select);
			return $result;
		}
		
		function getData($id) {
			$select = $this->penugasan->select()->where('status = 1')->where('id = ?', $id);
			$result = $this->penugasan->fetchRow($select);
			return $result;
		}


		function getDataSkorPenugasan($id_batch, $id_peserta) {	
			$select = $this->penugasan->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
			$result = $this->penugasan->fetchRow($select);
			return $result;
		}

		function getDataBatch($id) {
			$select = $this->penugasan->select()->where('status = 1')->where('id_batch = ?', $id);
			$result = $this->penugasan->fetchRow($select);
			return $result;
		}

		function getDataTugas($id, $batch) {
			$select = $this->penugasan->select()->where('status = 1')->where('id_peserta = ?', $id)->where('id_batch = ?', $batch);
			$result = $this->penugasan->fetchRow($select);
			return $result;
		}

		function getDataSertifikat($id_batch, $id_peserta)
		{
			$select = $this->penugasan->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
			$result = $this->penugasan->fetchRow($select);
			return $result;
		}

		function addData($id_batch, $id_peserta, $title, $file_title, $file_tugas, $ekstensi_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(			
				'id_batch' => $id_batch, 
				'id_peserta' => $id_peserta,
				'title' => $title,
				'file_title' => $file_title,
				'file_tugas' => $file_tugas,
				'ekstensi_tugas' => $ekstensi_tugas,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);

			$this->penugasan->insert($params);
			$lastId = $this->penugasan->getAdapter()->lastInsertId();

			return $lastId;	
		}

		function editData($id, $id_batch, $id_peserta, $title, $file_title, $file_tugas, $ekstensi_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_batch' => $id_batch, 
				'id_peserta' => $id_peserta,
				'title' => $title,
				'file_title' => $file_title,
				'file_tugas' => $file_tugas,
				'ekstensi_tugas' => $ekstensi_tugas,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);

		}

		function addTugas($id_batch, $id_peserta, $title, $file_title, $deadline_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(			
				'id_batch' => $id_batch, 
				'id_peserta' => $id_peserta,
				'title' => $title,
				'file_title' => $file_title,
				'deadline_tugas' => $deadline_tugas,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);

			$this->penugasan->insert($params);
			$lastId = $this->penugasan->getAdapter()->lastInsertId();

			return $lastId;	
		}

		function editTugas($id, $id_batch, $id_peserta, $title, $file_title, $deadline_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_batch' => $id_batch, 
				'id_peserta' => $id_peserta,
				'title' => $title,
				'file_title' => $file_title,
				'deadline_tugas' => $deadline_tugas,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);

		}

		function xeditTugas($id, $id_batch, $id_peserta, $title,  $deadline_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_batch' => $id_batch, 
				'id_peserta' => $id_peserta,
				'title' => $title,
				'deadline_tugas' => $deadline_tugas,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);

		}

		function submitTugas($id, $deskripsi_tugas, $file_tugas, $ekstensi_tugas) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			
			$params = array(
				'deskripsi_tugas' => $deskripsi_tugas, 
				'file_tugas' => $file_tugas, 
				'ekstensi_tugas' => $ekstensi_tugas, 
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);

		}

		function nilaiData($id, $skor_akhir) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			
			$params = array(
				'skor_akhir' => $skor_akhir, 
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);
		}

		public function deleteFilesTitle($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'file_title' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);
		}

		public function deleteFiles($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'file_tugas' => '',
				'ekstensi_tugas' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);
		}
		
		public function deleteData($id) {
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->delete($where);
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
 		
		$where = $this->penugasan->getAdapter()->quoteInto('id_batch = ?', $id);
		$this->penugasan->update($params, $where);
	}

		public function softDeleteData($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			
			$where = $this->penugasan->getAdapter()->quoteInto('id = ?', $id);
			$this->penugasan->update($params, $where);
		}
	}
?>