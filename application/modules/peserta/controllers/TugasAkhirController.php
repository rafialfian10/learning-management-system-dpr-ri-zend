<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">

<?php
	class Peserta_TugasAkhirController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index', 'edit'));
		}
		
		public function preDispatch() {
			$this->PenugasanService = new PenugasanService();

			$this->BatchService = new BatchService();
			$this->PelatihanService = new PelatihanService();
			$this->SilabusService = new SilabusService();
			$this->MateriSilabusService = new MateriSilabusService();

			$this->ProgressService = new ProgressService();
		}

		public function indexAction() {	
			$auth = Zend_Auth::getInstance()->getIdentity();

			$id = $this->getRequest()->getParam('id');
			$penugasan = $this->PenugasanService->getDataTugas($auth->id_peserta, $id);
			$this->view->penugasan = $penugasan;

			$this->view->batch = $this->BatchService->getData($id);
			$batch_id = $this->BatchService->getData($id);
			$this->view->pelatihan = $this->PelatihanService->getData($batch_id->id_pelatihan);
			$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch_id->id_pelatihan);
			$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch_id->id_pelatihan); 
			$this->view->progress = $this->ProgressService->getProgress($auth->id_peserta, $id);


			if ($this->getRequest()->isPost()) {
				
				$auth = Zend_Auth::getInstance()->getIdentity();
				$title = $penugasan->title;
				$ekstensi_tugas = $this->getRequest()->getParam('ekstensi_tugas');

				
				// upload file tugas
				$file_tugas = $_FILES['file_tugas']['name'];
				$file_type = $_FILES['file_tugas']['type'];
				$file_size = $_FILES['file_tugas']['size'];
				$file_tmp = $_FILES['file_tugas']['tmp_name'];
				$file_error = $_FILES['file_tugas']['error'];

				//Cek ukuran file tugas
				if($file_size > 128000){
					echo "<script>
							alert('Maximum file size is 128 MB!');
							$this->_redirect('/peserta/tugas-akhir/index/id/".$penugasan->id_batch."');
						</script>";
				}

				$file_extension = pathinfo($file_tugas, PATHINFO_EXTENSION);

				// check if file extension is valid
				$allowed_extensions = array('pdf', 'doc', 'docx', 'txt', 'jpg', 'png', 'jpeg', 'mp3', 'mp4', 'mkv');
				if(!in_array($file_extension, $allowed_extensions)){
					echo "<script>
							alert('File extension not allowed!');
							$this->_redirect('/peserta/tugas-akhir/index/id/".$penugasan->id_batch."');
						</script>";
				}

				// upload file tugas
				if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/penugasan";
					$nama_materi_kata = explode(" ", $auth->id_peserta);
					$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
					$file_tugas = 'penugasan-' . $nama_materi_kata . '-' . uniqid() . '.' . $file_extension;

					move_uploaded_file($file_tmp, $path . "/" . $file_tugas);
				}

				$isSuccess = $this->PenugasanService->addData($penugasan->id_batch, $auth->id_peserta, $title, $file_tugas, $file_extension);

				if ($isSuccess) {
					$this->view->insertSuccess = true;
				} else {
					$this->view->insertSuccess = false;
				}

				// $this->_redirect('/peserta/tugas-akhir/index/id/'.$id);
			} else {
				$this->view->rows = $this->PenugasanService->getDataTugas($auth->id_peserta, $id);
			}
		}
	}
?>

<?php if (isset($insertSuccess) && $insertSuccess) { ?>
    <script>
        $(document).ready(function() {
            $('#successModal').modal('show');
        });
    </script>
<?php } ?>
