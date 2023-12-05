<?php
class Admin_AdminController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->AdminService = new AdminService();
		$this->UserService = new UserService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->AdminService->getAllData();
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {

			//mendapatkan id_user
			$id_user = $this->getRequest()->getParam('id_user');

			$nama_admin = $this->getRequest()->getParam('nama_admin');
			$identitas_admin = $this->getRequest()->getParam('identitas_admin');
			$instansi = $this->getRequest()->getParam('instansi');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');

			// upload file
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotocoach";
				$path_info = pathinfo($file_name);

				// get first three words of nama_pengajar
				$nama_admin_kata = explode(" ", $nama_admin);
				$nama_admin_kata = $nama_admin_kata[0] . " " . $nama_admin_kata[1];

				$file_name = 'fotoadmin-' . $nama_admin_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id=$this->AdminService->addData($nama_admin, $identitas_admin, $instansi, $email, $no_telp,  $file_name);

			$this->UserService->editDataAdmin($id_user, $last_id);
		
			$this->_redirect('/admin/admin/edit/id/'.$last_id);
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->AdminService->getData($id);
		$this->view->user = $this->UserService->getDataAdmin($id);

		if ($this->getRequest()->isPost()) 
		{
			$id_user = $this->getRequest()->getParam('id_user');
			$old_user = $this->getRequest()->getParam('old_user');

			$nama_admin = $this->getRequest()->getParam('nama_admin');
			$identitas_admin = $this->getRequest()->getParam('identitas_admin');
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
						$this->_redirect('/admin/admin/index');
					</script>";
				return false;
			}
		
			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/admin/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotocoach";
				$path_info = pathinfo($file_name);

				// get first three words of nama_admin
				$nama_admin_kata = explode(" ", $nama_admin);
				$nama_admin_kata = $nama_admin_kata[0] . " " . $nama_admin_kata[1];

				$file_name = 'fotoadmin-' . $nama_admin_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->AdminService->editData($id, $nama_admin, $identitas_admin, $instansi, $email, $no_telp, $file_name);
				
				$this->UserService->editDataAdmin($old_user, NULL);
				$this->UserService->editDataAdmin($id_user, $id);

				$this->redirect('/admin/admin/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/admin/admin/index');
		}

	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->AdminService->deleteFiles($id);
		$this->_redirect('/admin/admin/edit/id/' . $id);
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		//$this->AdminService->deleteData($id);
		 $this->AdminService->softDeleteData($id);

		$this->_redirect('/admin/admin/index');
	}

	public function searchUserAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->UserService->getAllData();
	}

}