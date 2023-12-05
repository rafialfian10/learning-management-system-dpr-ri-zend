<?php
	class SkorNontestService {
	  
		function __construct() {
			$this->skorNontest = new SkorNontest();
		}

		function getData($id) {
			$select = $this->skorNontest->select()->where('status = 1')->where('id = ?', $id);
			$result = $this->skorNontest->fetchRow($select);
			return $result;
		}
		
		function getAllData() {
			$select = $this->skorNontest->select()->where('status = 1');
			$result = $this->skorNontest->fetchAll($select);
			return $result;
		}

		function getDataPelatihan($id_pelatihan) {
			$select = $this->skorNontest->select()->where('status = 1')->where('id_pelatihan = ?', $id_pelatihan);
			$result = $this->skorNontest->fetchRow($select);
			return $result;
		}

		function getDataSkorNontestBatch($id, $id_peserta) {
			$select = $this->skorNontest->select()->where('status = 1')->where('id = ?', $id)->where('id_peserta = ?', $id_peserta);
			$result = $this->skorNontest->fetchRow($select);
			return $result;
		}

		function getDataSkorNontest($id_peserta, $id_pelatihan) {
			$select = $this->skorNontest->select()->where('status = 1')->where('id_peserta = ?', $id_peserta)->where('id_pelatihan = ?', $id_pelatihan);
			$result = $this->skorNontest->fetchRow($select);
			return $result;
		}
	
		function submitTugas($id_peserta, $id_batch, $id_pelatihan, $title, $soal_nontes, $jawaban_nontes, $ekstensi_file) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			
			$params = array(
				'id_peserta' => $id_peserta, 
				'id_batch' => $id_batch, 
				'id_pelatihan' => $id_pelatihan, 
				'title' => $title, 
				'soal_nontes' => $soal_nontes, 
				'jawaban_nontes' => $jawaban_nontes, 
				'ekstensi_file' => $ekstensi_file, 
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
			);
		
			$this->skorNontest->insert($params);
			$lastId = $this->skorNontest->getAdapter()->lastInsertId();
			return $lastId;	
		}

		function nilaiData($id, $skor_akhir) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');
			
			$params = array(
				'skor_akhir' => $skor_akhir, 
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$where = $this->skorNontest->getAdapter()->quoteInto('id = ?', $id);
			$this->skorNontest->update($params, $where);

		}
		
		public function deleteFilesJawabanNontest($id) {
			$where = $this->skorNontest->getAdapter()->quoteInto('id = ?', $id);
			$this->skorNontest->delete($where);
		}

		public function softDeleteData($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			
			$where = $this->skorNontest->getAdapter()->quoteInto('id = ?', $id);
			$this->skorNontest->update($params, $where);
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