<?php
class Coach_PelatihanController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->PelatihanService = new PelatihanService();
		$this->PengajarService = new PengajarService();
		$this->SilabusService = new SilabusService();
		$this->PreTestService = new PreTestService();
		$this->SilabusQuizService = new SilabusQuizService();
		$this->MateriSilabusService = new MateriSilabusService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->PelatihanService->getAllData2();
		$this->view->rows2 = $this->PengajarService->getAllData();
		$this->view->rows3 = $this->SilabusService->getAllData();
	}

	public function addAction() {	
		if ( $this->getRequest()->isPost()) {
			$nama_pelatihan = $this->getRequest()->getParam('nama_pelatihan');
			$deskripsi = $this->getRequest()->getParam('deskripsi');
			$tipe_pelatihan = $this->getRequest()->getParam('tipe_pelatihan');
			$id_pengajar = $this->getRequest()->getParam('id_pengajar');
			// $max_id = $this->PelatihanService->getLast();
			
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/thumbkelas";
				$path_info = pathinfo($file_name);
				$nama_pelatihan_kata = explode(" ", $nama_pelatihan);
				$nama_pelatihan_kata = $nama_pelatihan_kata[0] . " " . $nama_pelatihan_kata[1];
				$file_name = 'pelatihan-' . $nama_pelatihan_kata . uniqid() . '.' . $path_info['extension'];
				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

				$last_id = $this->PelatihanService->addData($nama_pelatihan, $deskripsi, $tipe_pelatihan, $file_name, implode(",",$id_pengajar));
				
				$this->_redirect('/coach/pelatihan/edit/id/'.$last_id);
				} else {
				$this->view->rows = $this->PelatihanService->getAllData();
			}
	}

	public function editAction() {	
		$id = $this->getRequest()->getParam('id');

		if ( $this->getRequest()->isPost()) {
			$nama_pelatihan = $this->getRequest()->getParam('nama_pelatihan');
			$deskripsi = $this->getRequest()->getParam('deskripsi');
			$tipe_pelatihan = $this->getRequest()->getParam('tipe_pelatihan');
			$id_pengajar = $this->getRequest()->getParam('id_pengajar');
			
			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = isset($_FILES['file_name']['name']) ? $_FILES['file_name']['name'] : '';
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/thumbkelas";
				$path_info = pathinfo($file_name);

				$nama_pelatihan_kata = explode(" ", $nama_pelatihan);
				$nama_pelatihan_kata = $nama_pelatihan_kata[0] . " " . $nama_pelatihan_kata[1];

				$file_name = 'pelatihan-' . $nama_pelatihan_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}
			
			try{
				$this->PelatihanService->editData($id, $nama_pelatihan, $deskripsi, $tipe_pelatihan, $file_name, implode(",",$id_pengajar));	
				$this->_redirect('/coach/pelatihan/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
		}

		$row = $this->PelatihanService->getData($id);
		$this->view->row = $row;
		$this->view->pre_test = $this->PreTestService->getAllDataPelatihan($id);
		$this->view->silabus = $this->SilabusService->getAllDataPelatihan($id);
		$this->view->quiz = $this->SilabusQuizService->getAllDataPelatihan($id);
		$this->view->materi = $this->MateriSilabusService->getAllData();
		$this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		//$this->PelatihanService->deleteData($id);
		$this->PelatihanService->softDeleteData($id);

		$this->_redirect('/coach/pelatihan/index');
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PelatihanService->deleteFiles($id);
		$this->_redirect('/coach/pelatihan/edit/id/' . $id);
	}

	public function searchPengajarAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PengajarService->getAllData();
		$this->view->id = $this->getRequest()->getParam('id');
	}
}