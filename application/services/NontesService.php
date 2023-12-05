<?php
	class NontesService {
	  
		function __construct() {
			$this->nontes = new Nontes();
		}
		
		function getAllData() {
			$select = $this->nontes->select()->where('status = 1');
			$result = $this->nontes->fetchAll($select);
			return $result;
		}
		
		function getAllDataPelatihan($id) {
			$select = $this->nontes->select()->where('status = 1')->where('id_pelatihan = ?', $id);
			$result = $this->nontes->fetchAll($select);
			return $result;
		}

		function getDataPelatihan($id_pelatihan) {
			$select = $this->nontes->select()->where('status = 1')->where('id_pelatihan = ?', $id_pelatihan);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function getDataPelatihanByPeserta($id_peserta, $id_pelatihan) {
			$select = $this->nontes->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_pelatihan = ?', $id_pelatihan);
			$result = $this->nontes->fetchAll($select);
			return $result;
		}
		
		function getData($id) {
			$select = $this->nontes->select()->where('status = 1')->where('id = ?', $id);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function getDataSkorPenugasan($id_batch, $id_peserta) {	
			$select = $this->nontes->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function getDataBatch($id) {
			$select = $this->nontes->select()->where('status = 1')->where('id_batch = ?', $id);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function getDataNontest($id_pelatihan) {
			$select = $this->nontes->select()->where('status = 1')->where('id_pelatihan = ?', $id_pelatihan);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function getDataSertifikat($id_batch, $id_peserta) {
			$select = $this->nontes->select()->where('status = 1')->where('id_batch = ?', $id_batch)->where('id_peserta = ?', $id_peserta);
			$result = $this->nontes->fetchRow($select);
			return $result;
		}

		function addData($id_pelatihan, $title, $file_nontes) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(			
				'id_pelatihan' => $id_pelatihan, 
				'title' => $title,
				'file_nontes' => $file_nontes,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);

			$this->nontes->insert($params);
			$lastId = $this->nontes->getAdapter()->lastInsertId();

			return $lastId;	
		}

		function editData($id, $id_pelatihan, $title, $file_nontes) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_pelatihan' => $id_pelatihan, 
				'title' => $title,
				'file_nontes' => $file_nontes,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
			$this->nontes->update($params, $where);

		}

		function addTugas($id_pelatihan, $title, $file_nontes) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			
			$params = array(			
				'id_pelatihan' => $id_pelatihan,
				'title' => $title,
				'file_nontes' => $file_nontes,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);

			$this->nontes->insert($params);
			$lastId = $this->nontes->getAdapter()->lastInsertId();

			return $lastId;	
		}

		function editTugas($id, $id_pelatihan, $title, $file_nontes) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$select = $this->nontes->select()->where('id = ?', $id);
			$row = $this->nontes->fetchRow($select);
			
			if($file_nontes == '' ){
				$file_nontes = $row['file_nontes'];
			}
			
			$params = array(
				'id_pelatihan' => $id_pelatihan,
				'title' => $title,
				'file_nontes' => $file_nontes,
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);
			$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
			$this->nontes->update($params, $where);

		}

		// function submitTugas($id, $jawaban_nontes, $ekstensi_file) {
		// 	$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		// 	$tanggal_log = date('Y-m-d H:i:s');
			
		// 	$params = array(
		// 		'jawaban_nontes' => $jawaban_nontes, 
		// 		'ekstensi_file' => $ekstensi_file, 
		// 		'user_update' => $user_log,
		// 		'tanggal_update' => $tanggal_log
		// 	);
		// 	$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
		// 	$this->nontes->update($params, $where);

		// }

		// function nilaiData($id, $skor_akhir) {
		// 	$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		// 	$tanggal_log = date('Y-m-d H:i:s');
			
		// 	$params = array(
		// 		'skor_akhir' => $skor_akhir, 
		// 		'user_update' => $user_log,
		// 		'tanggal_update' => $tanggal_log
		// 	);
		// 	$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
		// 	$this->nontes->update($params, $where);

		// }

		public function deleteFiles($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'file_nontes' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
			$this->nontes->update($params, $where);
		}

		// public function deleteFilesNontes($id) {
		// 	$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		// 	$tanggal_log = date('Y-m-d H:i:s');

		// 	$params = array(
		// 		'jawaban_nontes' => '',
		// 		'ekstensi_file' => '',
		// 		'user_update' => $user_log,
		// 		'tanggal_update' => $tanggal_log
		// 	);
		// 	$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
		// 	$this->nontes->update($params, $where);
		// }
		
		public function deleteData($id) {
			$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
			$this->nontes->delete($where);
		}

		public function softDeleteData($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			
			$where = $this->nontes->getAdapter()->quoteInto('id = ?', $id);
			$this->nontes->update($params, $where);
		}

		public function softDeleteDataPelatihan($id_pelatihan) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
	
			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			 
			$where = $this->silabus->getAdapter()->quoteInto('id_pelatihan = ?', $id_pelatihan);
			$this->silabus->update($params, $where);
		}
	}
?>