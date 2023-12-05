<?php
	class DenahTempatDudukService {  
		function __construct() {
			$this->denah_tempat_duduk = new DenahTempatDuduk();
		}

		function getData($id) {
			$select = $this->denah_tempat_duduk->select()
				->setIntegrityCheck(false)
				->from(array('a' => 'denah_tempat_duduk'), array('*'))
				->where('a.id = ?', $id);

			$result = $this->denah_tempat_duduk->fetchRow($select);
			return $result;
		}

		function getAllData() { 
			$select = $this->denah_tempat_duduk->select()
				->setIntegrityCheck(false)
				->from(array('a' => 'denah_tempat_duduk'), array('*'))
				->where('a.status = 1');

			$result = $this->denah_tempat_duduk->fetchAll($select);
			return $result;
		}

		function getBarisByBlok($blok) {
			$select = $this->denah_tempat_duduk->select()
				->setIntegrityCheck(false)
				->from(array('a' => 'denah_tempat_duduk'), array('baris'))
				->where('a.blok = ?', $blok)
				->group('a.baris')
				->order('a.baris CAST( a.baris AS UNSIGNED)');

			$result = $this->denah_tempat_duduk->fetchAll($select);
			return $result;	
		}

		function getKursiByBaris($baris) {
			$select = $this->denah_tempat_duduk->select()
				->setIntegrityCheck(false)
				->from(array('a' => 'denah_tempat_duduk'), array('kursi'))
				->where('a.baris = ?', $baris)
				->group('a.kursi')
				->order('a.kursi CAST( a.kursi AS UNSIGNED)');

			$result = $this->denah_tempat_duduk->fetchAll($select);
			return $result;	
		}

		function checkData($denah_tempat_duduk) { 
			$select = $this->denah_tempat_duduk->select()->where('denah_tempat_duduk = ?', $denah_tempat_duduk);
			$result = $this->denah_tempat_duduk->fetchAll($select);
			return $result;
		}

		function addData($id_sidang, $seat, $kode, $blok, $baris, $kursi, $id_anggota, $nama_anggota, $no_anggota, $fraksi_anggota, $lembaga, $undangan) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_sidang' => $id_sidang, 
				'seat' => $seat, 
				'kode' => $kode, 
				'blok' => $blok, 
				'baris' => $baris, 
				'kursi' => $kursi, 
				'id_anggota' => $id_anggota, 
				'nama_anggota' => $nama_anggota, 
				'no_anggota' => $no_anggota, 
				'fraksi_anggota' => $fraksi_anggota, 
				'lembaga' => $lembaga, 
				'undangan' => $undangan, 
				'user_input' => $user_log,
				'tanggal_input' => $tanggal_log,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			$this->denah_tempat_duduk->insert($params);	
			$lastId = $this->denah_tempat_duduk->getAdapter()->lastInsertId();
			return $lastId;
		}

		function editData($id, $id_sidang, $seat, $kode, $blok, $baris, $kursi, $id_anggota, $nama_anggota, $no_anggota, $fraksi_anggota, $lembaga, $undangan) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'id_sidang' => $id_sidang, 
				'seat' => $seat, 
				'kode' => $kode, 
				'blok' => $blok, 
				'baris' => $baris, 
				'kursi' => $kursi, 
				'id_anggota' => $id_anggota, 
				'nama_anggota' => $nama_anggota, 
				'no_anggota' => $no_anggota, 
				'fraksi_anggota' => $fraksi_anggota, 
				'lembaga' => $lembaga, 
				'undangan' => $undangan, 
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			
			$where = $this->denah_tempat_duduk->getAdapter()->quoteInto('id = ?', $id);
			$this->denah_tempat_duduk->update($params, $where);
		}

		public function deleteData($id) {
			$where = $this->denah_tempat_duduk->getAdapter()->quoteInto('id = ?', $id);
			$this->denah_tempat_duduk->delete($where);
		}

		public function softDeleteData($id) {
			$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
			$tanggal_log = date('Y-m-d H:i:s');

			$params = array(
				'status' => 9,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
			
			$where = $this->denah_tempat_duduk->getAdapter()->quoteInto('id = ?', $id);
			$this->denah_tempat_duduk->update($params, $where);
		}
	}
?>