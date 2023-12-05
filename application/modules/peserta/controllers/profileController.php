<?php
	class Peserta_ProfileController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index', 'edit'));
		}
		
		public function preDispatch() {
			$this->PesertaService = new PesertaService();
		}
		
		public function indexAction() {
			$id = $this->getRequest()->getParam('id');
			$auth = Zend_Auth::getInstance()->getIdentity();

			$peserta = $this->PesertaService->getData($id);
			$this->view->peserta = $peserta;

			if ($this->getRequest()->isPost()) {
				$nama = $this->getRequest()->getParam('nama');
				$jenis_peserta = $this->getRequest()->getParam('jenis_peserta');
				$email = $this->getRequest()->getParam('email');
				$identitas = $this->getRequest()->getParam('identitas');
				$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
				$tanggal_lahir = $this->getRequest()->getParam('tanggal_lahir');
				$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
				$pekerjaan = $this->getRequest()->getParam('pekerjaan');
				$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');
				$no_telp = $this->getRequest()->getParam('no_telp');
		
				$file_image = isset($_FILES['file_image']['name']) ? $_FILES['file_image']['name'] : '';
				$file_type = $_FILES['file_image']['type'];
				$file_size = $_FILES['file_image']['size'];
				$file_tmp = $_FILES['file_image']['tmp_name'];
				$file_error = $_FILES['file_image']['error'];
		
				// cek ukuran gambar
				if ($file_size > 128000000) {
					echo "<script>
							Swal.fire({
								icon: 'warning',
								title: 'Warning',
								html: 'Ukuran file terlalu besar (maks 128 MB)',
								confirmButtonColor: '#4CAF50',
								customClass: {
								popup: 'my-custom-popup-class',
								title: 'my-custom-title-class',
								content: 'my-custom-content-class',
								confirmButton: 'confirm-button-class'
								}
							});
						</script>";
					return false;
				}
		
				// upload file image
				if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/fotopesertanonasn";
					$path_info = pathinfo($file_image);
		
					$nama_materi_kata = explode(" ", $nama);
					$nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
					$file_image = 'foto-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];
		
					move_uploaded_file($file_tmp, $path . "/" . $file_image);
				}
			
				$result = $this->PesertaService->editData($id, $nama, $jenis_peserta, $email, $identitas, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $no_telp, $file_image);
				
				$this->_redirect('/peserta/profile/index/id/'. $id);
			}

		}

		public function deleteFilesAction() {
			$id = $this->getRequest()->getParam('id');
			$this->PesertaService->deleteFiles($id);
			$this->_redirect('/peserta/profile/index/id/' . $id);
		}
	}
?>