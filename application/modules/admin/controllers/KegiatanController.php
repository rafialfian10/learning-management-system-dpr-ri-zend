<?php
class Admin_KegiatanController extends Zend_Controller_Action
{
	
	public function init()
	{ 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch()
	{
		$this->KegiatanService = new KegiatanService();
	}
	
	public function indexAction() 
	{	
		$this->view->rows = $this->KegiatanService->getAllData();
	}

	public function addAction() 
	{	
		if ( $this->getRequest()->isPost() )
		{
			$title = $this->getRequest()->getParam('title');
			$tipe_kegiatan = $this->getRequest()->getParam('tipe_kegiatan');
			$tgl_awal = $this->_helper->CDate($this->getRequest()->getParam('tgl_awal'));
			$tgl_akhir = $this->_helper->CDate($this->getRequest()->getParam('tgl_akhir'));
			$deskripsi = $this->getRequest()->getParam('deskripsi');
			$kuota_peserta = $this->getRequest()->getParam('kuota_peserta');
			$penyelenggara = $this->getRequest()->getParam('penyelenggara');
			$konten = $this->getRequest()->getParam('konten');

			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];
			$file_error = $_FILES['file_name']['error'];

			if($file_error === 4) { 
				echo "<script>
						alert('Upload photo terlebih dahulu!');
						$this->_redirect('/admin/kegiatan/index');
					</script>";
				return false;
			}

			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/kegiatan/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/sampulkegiatan";
				$path_info = pathinfo($file_name);

				$title_kata = explode(" ", $title);
				$title_kata = $title_kata[0] . " " . $title_kata[1];

				$file_name = 'sampulKegiatan-' . $title_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id = $this->KegiatanService->addData($title, $tipe_kegiatan, $tgl_awal, $tgl_akhir, $deskripsi, $kuota_peserta, $penyelenggara, $konten, $file_name);
		
			$this->_redirect('/admin/kegiatan/edit/id/'.$last_id);
		} else {
			$this->view->rows = $this->KegiatanService->getAllData();
		}
	}

	public function editAction() 
	{	
		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->KegiatanService->getData($id);

		if ( $this->getRequest()->isPost() )
		{
			$title = $this->getRequest()->getParam('title');
			$tipe_kegiatan = $this->getRequest()->getParam('tipe_kegiatan');
			$tgl_awal = $this->_helper->CDate($this->getRequest()->getParam('tgl_awal'));
			$tgl_akhir = $this->_helper->CDate($this->getRequest()->getParam('tgl_akhir'));
			$deskripsi = $this->getRequest()->getParam('deskripsi');
			$kuota_peserta = $this->getRequest()->getParam('kuota_peserta');
			$penyelenggara = $this->getRequest()->getParam('penyelenggara');
			$konten = $this->getRequest()->getParam('konten');
			$file_error = $_FILES['file_name']['error'];

			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
			}

			if($file_error === 4) { 
				echo "<script>
						alert('Upload photo terlebih dahulu!');
						$this->_redirect('/admin/kegiatan/index');
					</script>";
				return false;
			}

			if($file_size > 2000000){ 
				echo "<script>
						alert('Ukuran file photo terlalu besar!');
						$this->_redirect('/admin/kegiatan/index');
					</script>";
				return false; 
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/sampulkegiatan";
				$path_info = pathinfo($file_name);

				$title_kata = explode(" ", $title);
				$title_kata = $title_kata[0] . " " . $title_kata[1];

				$file_name = 'sampulKegiatan-' . $title_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}
			
			try{
				$this->KegiatanService->editData($id, $title, $tipe_kegiatan, $tgl_awal, $tgl_akhir, $deskripsi, $kuota_peserta, $penyelenggara, $konten,$file_name);	
				$this->_redirect('/admin/kegiatan/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
		}
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->KegiatanService->deleteFiles($id);
		$this->_redirect('/admin/kegiatan/edit/id/' . $id);
	}
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->KegiatanService->softDeleteData($id);

		$this->_redirect('/admin/kegiatan/index');
	}

}