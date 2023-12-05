<?php
class Admin_PesertaNonAsnController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->PesertaNonAsnService = new PesertaNonAsnService();
		$this->PesertaService = new PesertaService();
		$this->UserService = new UserService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->PesertaService->getAllDataSelainASN();
	}

	public function addAction() {	
		if ( $this->getRequest()->isPost() ) {
			$username = $this->getRequest()->getParam('username');
			$password = $this->getRequest()->getParam('password');
			$nama = $this->getRequest()->getParam('nama');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');
			$identitas = $this->getRequest()->getParam('identitas');
			$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
			$tanggal_lahir = $this->_helper->CDate($this->getRequest()->getParam('tanggal_lahir'));
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');

			// upload file
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];
			$file_error = $_FILES['file_name']['error'];

			// cek apakah user sudah upload image
			if($file_error === 4) { 
				echo "<script>
						alert('Upload photo terlebih dahulu!');
						$this->_redirect('/admin/pesertanonasn/index');
					</script>";
				return false;
			}
		
			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/pesertanonasn/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/fotopesertanonasn";
					$path_info = pathinfo($file_name);

					// get first three words of nama_pesertanonasn
					$nama_kata = explode(" ", $nama);
					$nama_kata = $nama_kata[0] . " " . $nama_kata[1];

					$file_name = 'fotopesertanonasn-' . $nama_kata . uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_name);		
				}

			// $last_id = $this->PesertaService->addSelainAsnData($username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan,  $file_name);
			
			// $id = $this->UserService->addSelainAsnData($username, $password, $nama, $email, $no_telp);


			$last_id= $this->PesertaService->registerNonAsn($nama, $email, $identitas, $tempat_lahir, $tanggal_lahir, $jenis_kelamin, $pekerjaan, $kewarganegaraan, $no_telp,  $file_name);

			$id = 1000000+$last_id;

			$this->UserService->register($id, $username,  $password,  $nama, $email, $no_telp, $last_id);


			// var_dump($file_name);
		
			$this->_redirect('/admin/pesertanonasn/edit/id/'.$last_id);
		}
	}

	public function editAction() 
	{	
		$id  = $this->getRequest()->getParam('id');
		$row = $this->PesertaService->getDataSelainASN($id);
		$this->view->row = $row;
		// print_r($row);die();
		if ( $this->getRequest()->isPost() )
		{
			$username = $this->getRequest()->getParam('username');
			$password = $this->getRequest()->getParam('password');
			$nama = $this->getRequest()->getParam('nama');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');
			$identitas = $this->getRequest()->getParam('identitas');
			$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
			$tanggal_lahir = $this->_helper->CDate($this->getRequest()->getParam('tanggal_lahir'));
			$pekerjaan = $this->getRequest()->getParam('pekerjaan');
			$kewarganegaraan = $this->getRequest()->getParam('kewarganegaraan');


			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
				$file_error = $_FILES['file_name']['error'];
			}
			// cek apakah user sudah upload image
			if($file_error === 4) { 
				echo "<script>
						alert('Upload photo terlebih dahulu!');
						$this->_redirect('/admin/pesertanonasn/index');
					</script>";
				return false;
			}

			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/pesertanonasn/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotopesertanonasn";
				$path_info = pathinfo($file_name);

				// get first three words of nama_coach
				$nama_kata = explode(" ", $nama);
				$nama_kata = $nama_kata[0] . " " . $nama_kata[1];

				$file_name = 'pesertanonasn-' . $nama_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}
			
			try{
				// var_dump($id,$username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan,  $file_name);die();

				$this->PesertaService->editSelainAsnData($id,$username, $password, $nama, $email, $no_telp, $identitas, $tempat_lahir, $tanggal_lahir, $pekerjaan, $kewarganegaraan,  $file_name);	

				$this->UserService->editSelainAsnData($id,$username, $password, $nama, $email, $no_telp);
				// var_dump($id,$username, $password, $nama, $email, $no_telp);die();
				
				$this->_redirect('/admin/pesertanonasn/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
			$this->view->row = $this->PesertaService->getDataSelainASN($id);
		}
	}
	
	public function hapusfotoAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PesertaService->hapusfoto($id);
		$this->_redirect('/admin/pesertanonasn/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		//$this->PesertaNonAsnService->deleteData($id);
		$this->PesertaNonAsnService->softDeleteData($id);

		$this->_redirect('/admin/pesertanonasn/index');
	}

}