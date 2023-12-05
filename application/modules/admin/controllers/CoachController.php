<?php
class Admin_CoachController extends Zend_Controller_Action
{
	
	public function init()
	{ 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch()
	{
		$this->CoachService = new CoachService();
		$this->UserService = new UserService();
	}
	
	public function indexAction() 
	{	
		$this->view->rows = $this->CoachService->getAllData();
	}

	public function addAction() 
	{	
		if ( $this->getRequest()->isPost() )
		{
			//mendapatkan id_user
			$id_user = $this->getRequest()->getParam('id_user');

			$nama_coach = $this->getRequest()->getParam('nama_coach');
			$identitas_coach = $this->getRequest()->getParam('identitas_coach');
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

			if ($file_tmp) 
				{
					$path = "//172.16.30.157/www/mooc/fotocoach";
					$path_info = pathinfo($file_name);

					// get first three words of nama_coach
					$nama_coach_kata = explode(" ", $nama_coach);
					$nama_coach_kata = $nama_coach_kata[0] . " " . $nama_coach_kata[1];

					$file_name = 'fotocoach-' . $nama_coach_kata . uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_name);		
				}

			// $id=$this->CoachService->addData($nama_coach, $instansi, $email, $no_telp, $identitas_coach);
			$last_id = $this->CoachService->addData($nama_coach, $instansi, $email, $no_telp, $identitas_coach, $file_name);

			// var_dump($file_name);
			$this->UserService->editDataCoach($id_user, $last_id);
		
			$this->_redirect('/admin/coach/edit/id/'.$last_id);
		} else {
			$this->view->rows = $this->CoachService->getAllData();
		}
	}

	public function editAction() 
	{	
		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->CoachService->getData($id);
		$this->view->user = $this->UserService->getDataCoach($id);

		if ( $this->getRequest()->isPost() )
		{
			$id_user = $this->getRequest()->getParam('id_user');
			$old_user = $this->getRequest()->getParam('old_user');

			$nama_coach = $this->getRequest()->getParam('nama_coach');
			$identitas_coach = $this->getRequest()->getParam('identitas_coach');
			$instansi = $this->getRequest()->getParam('instansi');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');
			$file_error = $_FILES['file_name']['error'];

			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
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
				$path = "//172.16.30.157/www/mooc/fotocoach";
				$path_info = pathinfo($file_name);

				// get first three words of nama_coach
				$nama_coach_kata = explode(" ", $nama_coach);
				$nama_coach_kata = $nama_coach_kata[0] . " " . $nama_coach_kata[1];

				$file_name = 'fotocoach-' . $nama_coach_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}
			
			try{
				$this->CoachService->editData($id, $nama_coach, $identitas_coach, $instansi, $email, $no_telp, $file_name);	

				$this->UserService->editDataCoach($old_user, NULL);
				$this->UserService->editDataCoach($id_user, $id);
				
				$this->_redirect('/admin/coach/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
			$this->view->row = $this->CoachService->getData($id);
		}
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->CoachService->deleteFiles($id);
		$this->_redirect('/admin/coach/edit/id/' . $id);
	}
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		// $this->CoachService->deleteData($id);
		$this->CoachService->softDeleteData($id);

		$this->_redirect('/admin/coach/index');
	}

	public function searchUserAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->UserService->getAllData();
	}

}