<?php
class Admin_BannerController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->BannerService = new BannerService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->BannerService->getAllData();
		// var_dump($this->view->rows);
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {

			// upload file
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];


			// var_dump($file_name);die();
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/banner";
				$path_info = pathinfo($file_name);

				// get first three words of nama_pengajar
				
				$file_name = 'gambarbanner-'. uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id=$this->BannerService->addData($file_name);
	
			$this->_redirect('/admin/banner/edit/id/'.$last_id);
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$row=$this->BannerService->getData($id);
		$this->view->row = $row;

		if ($this->getRequest()->isPost()) {
			
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
						$this->_redirect('/admin/banner/index');
					</script>";
				return false;
			}
		
			//Cek ukuran file image (maks 2mb)
			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/banner/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/banner";
				$path_info = pathinfo($file_name);

				// get first three words of nama_banner
				$nama_banner_kata = explode(" ", "banner");
				$nama_banner_kata = $nama_banner_kata[0] . " " . $nama_banner_kata[1];

				$file_name = 'fotobanner-' . $nama_banner_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->BannerService->editData($id, $file_name);
				$this->redirect('/admin/banner/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/admin/banner/index');
		}	
				
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->BannerService->deleteFiles($id);
		$this->_redirect('/admin/banner/edit/id/' . $id);
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		//$this->BannerService->deleteData($id);
		 $this->BannerService->softDeleteData($id);

		$this->_redirect('/admin/banner/index');
	}

}