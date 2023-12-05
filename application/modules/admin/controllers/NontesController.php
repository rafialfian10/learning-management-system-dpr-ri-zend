<?php
class Admin_NontesController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->NontesService = new NontesService();
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->PesertaService = new PesertaService();
		$this->PesertaBatchService = new PesertaBatchService();
        $this->ProgressService = new ProgressService();
        $this->SertifikatService = new SertifikatService();
		$this->SkorBelajarService = new SkorBelajarService();
		$this->SkorNontestService = new SkorNontestService();
        $this->SkorMentoringService = new SkorMentoringService();
	}
	
	public function indexAction() {	
		$this->view->nontest = $this->NontesService->getAllData();
		$this->view->pelatihans = $this->PelatihanService->getAllData();
		$this->view->peserta = $this->PesertaService->getAllData();
		$this->view->skor_nontest = $this->SkorNontestService->getAllData();
	}

	public function addAction() {	
		$id = $this->getRequest()->getParam('id');

		if ( $this->getRequest()->isPost() ) {
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			$title = $this->getRequest()->getParam('title');

			// upload file tugas nontes
			$file_nontes = $_FILES['file_nontes']['name'];
			$file_type = $_FILES['file_nontes']['type'];
			$file_size = $_FILES['file_nontes']['size'];
			$file_tmp = $_FILES['file_nontes']['tmp_name'];
			$file_error = $_FILES['file_nontes']['error'];

			// cek apakah sudah upload file
			if($file_error == 4) {
				echo "<script>
						alert('Upload file nontes');
					</script>";
				return false;
			}

			//Cek ukuran file nontes (maks 128mb)
			if($file_size > 128000000){
				echo "<script>
						alert('Ukuran file terlalu besar (maks 128 MB)')
					</script>";
				return false;
			}

			if ($file_tmp) {
				$path_nontes = "//172.16.30.157/www/mooc/soalpretest";
				$path_info = pathinfo($file_nontes);
	
				$nama_materi_kata = explode(" ",$id_pelatihan);
				$nama_materi_kata = $nama_materi_kata[0] . "" . $nama_materi_kata[1];
				$file_nontes = 'soal-nontes-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];
	
				move_uploaded_file($file_tmp, $path_nontes . "/" . $file_nontes);
			}

			$last_id = $this->NontesService->addTugas($id_pelatihan, $title, $file_nontes);
			$this->_redirect('/admin/pelatihan/edit/id/'. $id_pelatihan);
		} else {
			$this->view->rows = $this->NontesService->getAllData();

			$pelatihan = $this->PelatihanService->getData($id);
			$this->view->pelatihan = $pelatihan;
		}
	}

	public function editAction() {	
		$id= $this->getRequest()->getParam('id');
		if ($this->getRequest()->isPost()) {
			$id_nontes = $this->getRequest()->getParam('id_nontes');
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			$title = $this->getRequest()->getParam('title');

			// upload file tugas nontes
			$file_nontes = $_FILES['file_nontes']['name'];
			$file_type = $_FILES['file_nontes']['type'];
			$file_size = $_FILES['file_nontes']['size'];
			$file_tmp = $_FILES['file_nontes']['tmp_name'];
			$file_error = $_FILES['file_nontes']['error'];

			// cek apakah sudah upload file
			if($file_error == 4) {
				echo "<script>
						alert('File masih kosong');
					</script>";
				return false;
			}

			//Cek ukuran file nontes (maks 128mb)
			if($file_size > 128000000){
				echo "<script>
						alert('Ukuran file terlalu besar (maks 128 MB)')
					</script>";
				return false;
			}

			if ($file_tmp) {
				$path_nontes = "//172.16.30.157/www/mooc/soalpretest";
				$path_info = pathinfo($file_nontes);
	
				$nama_materi_kata = explode(" ",$id_pelatihan);
				$nama_materi_kata = $nama_materi_kata[0] . "" . $nama_materi_kata[1];
				$file_nontes = 'soal-nontes-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];
	
				move_uploaded_file($file_tmp, $path_nontes . "/" . $file_nontes);
			}
// var_dump($id_nontes, $id_pelatihan, $title, $file_nontes);die();
			$this->NontesService->editTugas($id_nontes, $id_pelatihan, $title, $file_nontes);
			$this->_redirect('/admin/pelatihan/edit/id/'. $id);
		} else {
			$pelatihan = $this->PelatihanService->getData($id);
			$this->view->pelatihan = $pelatihan;

			$this->view->row = $this->NontesService->getDataPelatihan($id);
			$this->view->nama_pelatihan = $this->PelatihanService->getData($this->view->row->id_pelatihan)->nama_pelatihan;
			// $this->view->nama_peserta = $this->PesertaService->getData($this->view->row->id_peserta)->nama;
		}
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
		$this->NontesService->deleteFiles($id);
		$this->_redirect('/admin/nontes/edit/id/' . $id_pelatihan);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->NontesService->softDeleteData($id);

		$this->_redirect('/admin/nontes/index');
	}

	public function searchPelatihanAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PelatihanService->getAllData();
	}

	public function searchPesertaAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PesertaService->getAllData();
	}

}