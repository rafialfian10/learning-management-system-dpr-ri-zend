<?php
class Admin_MentorController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->MentorService = new MentorService();
		$this->UserService = new UserService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->MentorService->getAllData();
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {
			$id_user = $this->getRequest()->getParam('id_user');

			$nama_mentor = $this->getRequest()->getParam('nama_mentor');
			$identitas_mentor = $this->getRequest()->getParam('identitas_mentor');
			$instansi = $this->getRequest()->getParam('instansi');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');

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
						$this->_redirect('/admin/mentor/index');
					</script>";
				return false;
			}
		
			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/mentor/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotomentor";
				$path_info = pathinfo($file_name);

				// get first three words of nama_pengajar
				$nama_mentor_kata = explode(" ", $nama_mentor);
				$nama_mentor_kata = $nama_mentor_kata[0] . " " . $nama_mentor_kata[1];

				$file_name = 'fotomentor-' . $nama_mentor_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id=$this->MentorService->addData($nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $file_name);
		
			$this->UserService->editDataMentor($id_user, $last_id);

			$this->_redirect('/admin/mentor/edit/id/'.$last_id);
		} else {
			$this->view->rows = $this->MentorService->getAllData();
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->MentorService->getData($id);
		$this->view->user = $this->UserService->getDataMentor($id);

		if ($this->getRequest()->isPost()) 
		{
			$id_user = $this->getRequest()->getParam('id_user');
			$old_user = $this->getRequest()->getParam('old_user');

			$nama_mentor = $this->getRequest()->getParam('nama_mentor');
			$identitas_mentor = $this->getRequest()->getParam('identitas_mentor');
			$instansi = $this->getRequest()->getParam('instansi');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');

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
						$this->_redirect('/admin/mentor/index');
					</script>";
				return false;
			}
		
			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/mentor/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotomentor";
				$path_info = pathinfo($file_name);

				// get first three words of nama_mentor
				$nama_mentor_kata = explode(" ", $nama_mentor);
				$nama_mentor_kata = $nama_mentor_kata[0] . " " . $nama_mentor_kata[1];

				$file_name = 'fotomentor-' . $nama_mentor_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->MentorService->editData($id, $nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $file_name);
				$this->UserService->editDataMentor($old_user, NULL);
				$this->UserService->editDataMentor($id_user, $id);
				$this->redirect('/admin/mentor/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/admin/mentor/index');
		}	
				
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MentorService->deleteFiles($id);
		$this->_redirect('/admin/mentor/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		//$this->MentorService->deleteData($id);
		 $this->MentorService->softDeleteData($id);

		$this->_redirect('/admin/mentor/index');
	}

	public function searchUserAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->UserService->getAllData();
	}
}