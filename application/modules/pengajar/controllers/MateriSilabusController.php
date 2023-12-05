<?php
class Pengajar_MateriSilabusController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	

	public function preDispatch() {
		$this->MateriSilabusService = new MateriSilabusService();
		$this->SilabusService = new SilabusService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->MateriSilabusService->getAllData();
		$this->view->rows2 = $this->SilabusService->getAllData();
	}

	public function addAction() {	
		$id = $this->getRequest()->getParam('id');
		$pelatihan = $this->getRequest()->getParam('pelatihan');
		$this->view->row = $this->MateriSilabusService->getData($id);

		if ($this->getRequest()->isPost()) {
			$nama_materi = $this->getRequest()->getParam('nama_materi');
			$deskripsi_materi = $this->getRequest()->getParam('deskripsi_materi');
			$jumlah_jp = $this->getRequest()->getParam('jumlah_jp');
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');
			$isi_materi = $this->getRequest()->getParam('isi_materi');

			// upload file gambar
			$file_image = $_FILES['file_image']['name'];
			$file_type = $_FILES['file_image']['type'];
			$file_size = $_FILES['file_image']['size'];
			$file_tmp = $_FILES['file_image']['tmp_name'];
			$file_error = $_FILES['file_image']['error'];

			//Cek ukuran gambar
			if($file_size > 134217728){
				echo "<script>
						alert('Ukuran gambar terlalu besar!');
						$this->_redirect('/pengajar/materi-silabus/index');
					</script>";
			}

			// upload file gambar
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/gambarmateri";
				$path_info = pathinfo($file_image);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_image = 'image-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_image);
			}
			//----------------------------------------------------------

			// upload file document
			$file_pdf = $_FILES['file_pdf']['name'];
			$file_pdf_type = $_FILES['file_pdf']['type'];
			$file_pdf_size = $_FILES['file_pdf']['size'];
			$file_pdf_tmp = $_FILES['file_pdf']['tmp_name'];
			$file_pdf_error = $_FILES['file_pdf']['error'];
		
			//Cek ukuran file pdf (maks 100mb)
			if($file_pdf_size > 134217728){ 
				echo "<script>
						alert('Ukuran file document pdf terlalu besar!');
						$this->_redirect('/pengajar/materi-silabus/index');
					</script>";
				return false; 
			}

			if($file_pdf_tmp) {
				$path_pdf = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_pdf);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_pdf = 'File-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_pdf_tmp, $path_pdf . "/" . $file_pdf);		
			}
			//----------------------------------------------------------

			// upload file audio
			$file_audio = $_FILES['file_audio']['name'];
			$file_audio_type = $_FILES['file_audio']['type'];
			$file_audio_size = $_FILES['file_audio']['size'];
			$file_audio_tmp = $_FILES['file_audio']['tmp_name'];
			$file_audio_error = $_FILES['file_audio']['error'];

			//Cek ukuran file audio (maks 10mb)
			if($file_audio_size > 134217728){ 
				echo "<script>
						alert('Ukuran file audio terlalu besar!');
						$this->_redirect('/pengajar/materi-silabus/index');
					</script>";
				return false; 
			}

			if($file_audio_tmp) {
				$path_audio = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_audio);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_audio = 'audio-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_audio_tmp, $path_audio . "/" . $file_audio);			
			}
			//----------------------------------------------------------

			// upload file video
			$file_video = $_FILES['file_video']['name'];
			$file_video_type = $_FILES['file_video']['type'];
			$file_video_size = $_FILES['file_video']['size'];
			$file_video_tmp = $_FILES['file_video']['tmp_name'];
			$file_video_error = $_FILES['file_video']['error'];
		
			//Cek ukuran file video (maks 128mb)
			if($file_video_size > 134217728){ 
				echo "<script>
						alert('Ukuran file video terlalu besar!');
						$this->_redirect('/pengajar/materi-silabus/index');
					</script>";
				return false; 
			}

			if($file_video_tmp) {
				$path_video = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_video);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_video = 'video-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_video_tmp, $path_video . "/" . $file_video);		
			}
			
			$urutan = count($this->MateriSilabusService->getAllDataSilabus($id))+1;
			$last_id=$this->MateriSilabusService->addData($id, $pelatihan, $nama_materi, $deskripsi_materi, $jumlah_jp, $batas_waktu, $isi_materi, $file_image, $file_pdf, $file_audio, $file_video, $urutan);
			
			$this->_redirect('/pengajar/silabus/edit/id/'.$id);
		} else {
			$this->view->rows = $this->MateriSilabusService->getAllData();
		}
	}


	public function editAction() {
		$id = $this->getRequest()->getParam('id');	
		
		$silabus = $this->MateriSilabusService->getData($id);
		$id_silabus = $silabus['id_silabus'];

		if ($this->getRequest()->isPost()) {
			$nama_materi = $this->getRequest()->getParam('nama_materi');
			$deskripsi_materi = $this->getRequest()->getParam('deskripsi_materi');
			$jumlah_jp = $this->getRequest()->getParam('jumlah_jp');
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');
			$isi_materi = $this->getRequest()->getParam('isi_materi');
	
			$file_image = isset($_FILES['file_image']['name']) ? $_FILES['file_image']['name'] : '';
			$file_type = $_FILES['file_image']['type'];
			$file_size = $_FILES['file_image']['size'];
			$file_tmp = $_FILES['file_image']['tmp_name'];
			$file_error = $_FILES['file_image']['error'];
	
			// cek ukuran gambar
			if ($file_size > 134217728) {
				echo "<script>
						alert('Ukuran gambar terlalu besar!');
						window.location.href='/pengajar/silabus/edit/id/".$id_silabus."';
					  </script>";
				return false;
			}
	
			// upload file image
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/gambarmateri";
				$path_info = pathinfo($file_image);
	
				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_image = 'image-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];
	
				move_uploaded_file($file_tmp, $path . "/" . $file_image);
			}
			//----------------------------------------------
	
			$file_pdf = isset($_FILES['file_pdf']['name']) ? $_FILES['file_pdf']['name'] : '';
			$file_pdf_type = $_FILES['file_pdf']['type'];
			$file_pdf_size = $_FILES['file_pdf']['size'];
			$file_pdf_tmp = $_FILES['file_pdf']['tmp_name'];
			$file_pdf_error = $_FILES['file_pdf']['error'];
	
			// cek ukuran file pdf (maks 100mb)
			if ($file_pdf_size > 134217728) {
				echo "<script>
						alert('Ukuran file document pdf terlalu besar!');
						window.location.href='/pengajar/silabus/edit/id/".$id_silabus."';
					  </script>";
				return false;
			}

			// upload file pdf
			if ($file_pdf_tmp) {
				$path_pdf = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_pdf);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_pdf = 'File-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_pdf_tmp, $path_pdf . "/" . $file_pdf);
			}
		
			$file_audio = isset($_FILES['file_audio']['name']) ? $_FILES['file_audio']['name'] : '';
			$file_audio_type = $_FILES['file_audio']['type'];
			$file_audio_size = $_FILES['file_audio']['size'];
			$file_audio_tmp = $_FILES['file_audio']['tmp_name'];
			$file_audio_error = $_FILES['file_audio']['error'];

			
			//Cek ukuran file audio (maks 10mb)
			if($file_audio_size > 134217728){ 
				echo "<script>
						alert('Ukuran file audio terlalu besar!');
						$this->_redirect('/pengajar/silabus/edit/id/".$id_silabus."');
					</script>";
				return false; 
			}
			//----------------------------------------------

			// upload file audio
			if ($file_audio_tmp) {
				$path_audio = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_audio);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_audio = 'audio-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_audio_tmp, $path_audio . "/" . $file_audio);
			}

			$file_video = isset($_FILES['file_video']['name']) ? $_FILES['file_video']['name'] : '';
			$file_video_type = $_FILES['file_video']['type'];
			$file_video_size = $_FILES['file_video']['size'];
			$file_video_tmp = $_FILES['file_video']['tmp_name'];
			$file_video_error = $_FILES['file_video']['error'];

			//Cek ukuran file video (maks 100mb)
			if($file_video_size > 134217728){ 
				echo "<script>
						alert('Ukuran file video terlalu besar!');
						$this->_redirect('/pengajar/silabus/edit/id/".$id_silabus."');
					</script>";
				return false; 
			}

			// upload file video
			if ($file_video_tmp) {
				$path_video = "//172.16.30.157/www/mooc/filemateri";
				$path_info = pathinfo($file_video);

				$nama_materi_kata = explode(" ", $nama_materi);
				$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_video = 'video-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_video_tmp, $path_video . "/" . $file_video);
			}
			//-------------------------------------------------

			try {
			
				$result = $this->MateriSilabusService->editData($id, $nama_materi, $deskripsi_materi, $jumlah_jp, $batas_waktu, $isi_materi, $file_image, $file_pdf, $file_audio, $file_video);
				//var_dump($result);
				$this->_redirect('/pengajar/silabus/edit/id/'.$id_silabus);
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
			$this->_redirect('/pengajar/silabus/edit/id/'.$id_silabus);
			
			if ($result != null) {
				echo 	"<script>
							alert('Berhasil edit data');
							window.location.href='/pengajar/silabus/edit/id/".$id_silabus."';
						</script>";
					} else {
				echo 	"<script>
							alert('Gagal edit data');
							window.location.href='/pengajar/silabus/edit/id/".$id_silabus."';
						</script>";
			}
		}

		$this->view->row = $this->MateriSilabusService->getData($id);
	}

	public function deletefilesAction() {
		$id = $this->getRequest()->getParam('id');
		$file_image = $this->getRequest()->getParam('file_image');
		$file_pdf = $this->getRequest()->getParam('file_pdf');
		$file_audio = $this->getRequest()->getParam('file_audio');
		$file_video = $this->getRequest()->getParam('file_video');
		$this->MateriSilabusService->deleteFiles($id, $file_image, $file_pdf, $file_audio, $file_video);
		$this->_redirect('/pengajar/materi-silabus/edit/id/' . $id);
	}

	public function hapusgambarAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MateriSilabusService->hapusgambar($id);
		$this->_redirect('/pengajar/materi-silabus/edit/id/' . $id);
	}

	public function hapusdocumentAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MateriSilabusService->hapusdocument($id);
		$this->_redirect('/pengajar/materi-silabus/edit/id/' . $id);
	}


	public function hapusfileAction() {
		$id = $this->getRequest()->getParam('id');
		$jenis_file = $this->getRequest()->getParam('jenis_file');
		$this->MateriSilabusService->hapusfile($id, $jenis_file);
		$this->_redirect('/pengajar/materi-silabus/edit/id/' . $id);
	}
	


	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
	
		$silabus = $this->MateriSilabusService->getData($id);
		$id_silabus = $silabus['id_silabus'];

		$this->MateriSilabusService->deleteData($id);
		$silabus = $this->MateriSilabusService->getAllDataSilabus($id_silabus);

		foreach($silabus as $key=>$val){
			$this->MateriSilabusService->editUrutan($val->id, ($key+1));
		}
		
		$this->_redirect('/pengajar/silabus/edit/id/'.$id_silabus);
	}


	public function searchMateriSilabusQuizAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->MateriSilabusQuizService->getAllData();
	}
}