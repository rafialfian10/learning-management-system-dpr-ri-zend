<?php
class MateriSilabusService {
	  
 	function __construct() {
		$this->materi_silabus = new MateriSilabus();
	}

	function getAllData() {
		$select = $this->materi_silabus->select()->where('status = 1')->order('id DESC');
		$result = $this->materi_silabus->fetchAll($select);
		return $result;
	}

	function getAllDataSilabus($id) {
		$select = $this->materi_silabus->select()->where('status = 1')->where('id_silabus = ?', $id);
		$result = $this->materi_silabus->fetchAll($select);
		return $result;
	}

	function getAllDataPelatihan($id) {
		$select = $this->materi_silabus->select()->where('status = 1')->where('id_pelatihan = ?', $id);
		$result = $this->materi_silabus->fetchAll($select);
		return $result;
	}

	function getData($id) {
		$select = $this->materi_silabus->select()->where('status = 1')->where('id = ?', $id);
		$result = $this->materi_silabus->fetchRow($select);
		return $result;
	}

	function getDataUrutan($id_pelatihan, $id_silabus, $urutan) {
		$select = $this->materi_silabus->select()->where('status = 1')->where('id_pelatihan = ?', $id_pelatihan)->where('id_silabus = ?', $id_silabus)->where('urutan = ?', $urutan);
		$result = $this->materi_silabus->fetchRow($select);
		return $result;
	}
	
	function addData($id_silabus,$id_pelatihan, $nama_materi, $deskripsi_materi, $jumlah_jp, $batas_waktu, $isi_materi, $gambar_materi_uri, $document_materi_uri, $audio_materi_uri, $video_materi_uri, $urutan) {

		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(			
			'id_silabus' => $id_silabus,
			'id_pelatihan' => $id_pelatihan,
			'urutan' => $urutan,
			'nama_materi' => $nama_materi,
			'deskripsi_materi' => $deskripsi_materi,
			'jumlah_jp' => $jumlah_jp,
			'batas_waktu' => $batas_waktu,
			'isi_materi' => $isi_materi,
			'gambar_materi_uri' => $gambar_materi_uri,
			'document_materi_uri' => $document_materi_uri,
			'audio_materi_uri' => $audio_materi_uri,
			'video_materi_uri' => $video_materi_uri,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
		);
		$this->materi_silabus->insert($params);
		$lastId = $this->materi_silabus->getAdapter()->lastInsertId();
		return $lastId;	
	}

	function editData($id, $nama_materi, $deskripsi_materi, $jumlah_jp, $batas_waktu, $isi_materi, $gambar_materi_uri, $document_materi_uri, $audio_materi_uri, $video_materi_uri) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$select = $this->materi_silabus->select()->where('id = ?', $id);
		$row = $this->materi_silabus->fetchRow($select);

		
		if($gambar_materi_uri == '' ){
			$gambar_materi_uri = $row['gambar_materi_uri'];
		}
		if($document_materi_uri == '' ){
			$document_materi_uri = $row['document_materi_uri'];
		}
		if($audio_materi_uri == '' ){
			$audio_materi_uri = $row['audio_materi_uri'];
		}
		if($video_materi_uri == '' ){
			$video_materi_uri = $row['video_materi_uri'];
		}
		
			$params = array(		
				'nama_materi' => $nama_materi,
				'deskripsi_materi' => $deskripsi_materi,
				'jumlah_jp' => $jumlah_jp,
				'batas_waktu' => $batas_waktu,
				'isi_materi' => $isi_materi,
				'gambar_materi_uri' => $gambar_materi_uri,
				'document_materi_uri' => $document_materi_uri,
				'audio_materi_uri' => $audio_materi_uri,
				'video_materi_uri' => $video_materi_uri,
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
	
		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		
		return $this->materi_silabus->update($params, $where);
	}

	function editUrutan($id, $urutan) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(		
			'urutan' => $urutan,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
	
 		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);

	}

	public function deleteFiles($id, $file_image, $file_pdf, $file_audio, $file_video) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
		$params = array(
			'gambar_materi_uri' => ($file_image),
			'document_materi_uri' => ($file_pdf),
			'audio_materi_uri' => ($file_audio),
			'video_materi_uri' => ($file_video),
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
      
 		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);
	}


	public function hapusgambar($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'gambar_materi_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);
	}

	public function hapusdocument($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'document_materi_uri' => '',
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);
	}


	public function hapusfile($id, $jenis_file) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');
	
		if($jenis_file == 'gambar') {
			$params = array(
				'gambar_materi_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		} else if($jenis_file == 'document') {
			$params = array(
				'document_materi_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		}else if($jenis_file == 'audio') {
			$params = array(
				'audio_materi_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		}else if($jenis_file == 'video') {
			$params = array(
				'video_materi_uri' => '',
				'user_update' => $user_log,
				'tanggal_update' => $tanggal_log
			);
		}
	
		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);
	}

	public function deleteData($id) {
		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->delete($where);
	}

	public function softDeleteData($id) {
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->materi_silabus->getAdapter()->quoteInto('id = ?', $id);
		$this->materi_silabus->update($params, $where);
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