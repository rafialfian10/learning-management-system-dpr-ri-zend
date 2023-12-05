<?php
class Mentor_PenugasanController extends Zend_Controller_Action
{
	
	public function init()
	{ 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch()
	{
		$this->PenugasanService = new PenugasanService();
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->PesertaService = new PesertaService();
		$this->PesertaBatchService = new PesertaBatchService();
        $this->ProgressService = new ProgressService();
        $this->SertifikatService = new SertifikatService();
		$this->SkorBelajarService = new SkorBelajarService();
        $this->SkorMentoringService = new SkorMentoringService();
	}
	
	public function indexAction() 
	{	
		$this->view->rows = $this->PenugasanService->getAllData();
		$this->view->peserta = $this->PesertaService->getAllData();
		$this->view->batch = $this->BatchService->getAllData();
        $this->view->pelatihan = $this->PelatihanService->getAllData();
	}

	public function addAction() 
	{	
		if ( $this->getRequest()->isPost() )
		{
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$deadline_tanggal = $this->_helper->CDate($this->getRequest()->getParam('deadline_tanggal'));
			$deadline_waktu = $this->getRequest()->getParam('deadline_waktu');

			$deadline_tugas = $deadline_tanggal . ' ' . $deadline_waktu;
			$deadline_tugas = date('Y-m-d H:i:s', strtotime($deadline_tugas));

			$file_name = NULL;
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/penugasan";
				$path_info = pathinfo($file_name);
				$file_name = 'contoh_tugas'.'-'. uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id = $this->PenugasanService->addTugas($id_batch, $id_peserta, $title, $file_name, $deadline_tugas);
		
			// $this->_redirect('/mentor/penugasan/nilai/id/'.$last_id);
			$this->_redirect('/mentor/penugasan/index');
		} else {
			$this->view->rows = $this->PenugasanService->getAllData();
		}
	}

	public function tambahAction() 
	{	
		$id = $this->getRequest()->getParam('id');
		$peserta = $this->getRequest()->getParam('peserta');
		
		$this->view->batch = $this->BatchService->getData($id);
		$this->view->peserta = $this->PesertaService->getData($peserta);

		if ( $this->getRequest()->isPost() )
		{
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$deadline_tanggal = $this->_helper->CDate($this->getRequest()->getParam('deadline_tanggal'));
			$deadline_waktu = $this->getRequest()->getParam('deadline_waktu');

			$deadline_tugas = $deadline_tanggal . ' ' . $deadline_waktu;
			$deadline_tugas = date('Y-m-d H:i:s', strtotime($deadline_tugas));

			$file_name = NULL;
			$file_name = $_FILES['file_name']['name'];
			$file_type = $_FILES['file_name']['type'];
			$file_size = $_FILES['file_name']['size'];
			$file_tmp = $_FILES['file_name']['tmp_name'];

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/penugasan";
				$path_info = pathinfo($file_name);
				$file_name = 'contoh_tugas'.'-'. uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			$last_id = $this->PenugasanService->addTugas($id_batch, $id_peserta, $title, $file_name, $deadline_tugas);
			// $this->_redirect('/mentor/penugasan/nilai/id/'.$last_id);
			$this->_redirect('/mentor/nilai-mentoring/index');
		} else {
			$this->view->rows = $this->PenugasanService->getAllData();
		}
	}

	public function editAction() 
	{	
		$id = $this->getRequest()->getParam('id');

		if ( $this->getRequest()->isPost() )
		{
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$cek_file = $this->getRequest()->getParam('cek_file');
			$deadline_tanggal = $this->_helper->CDate($this->getRequest()->getParam('deadline_tanggal'));
			$deadline_waktu = $this->getRequest()->getParam('deadline_waktu');
			$file_name = NULL;

			$deadline_tugas = $deadline_tanggal . ' ' . $deadline_waktu;
			$deadline_tugas = date('Y-m-d H:i:s', strtotime($deadline_tugas));

			if($cek_file == "2"){
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];

				if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/penugasan";
					$path_info = pathinfo($file_name);
					$file_name = 'contoh_tugas'.'-'. uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_name);		
				}

				$last_id = $this->PenugasanService->editTugas($id, $id_batch, $id_peserta, $title, $file_name, $deadline_tugas);
			} else if($cek_file == "1"){
				$last_id = $this->PenugasanService->xeditTugas($id, $id_batch, $id_peserta, $title, $deadline_tugas);
			}
		
			$this->_redirect('/mentor/penugasan/index');
		} else {
			$this->view->row = $this->PenugasanService->getData($id);
			$this->view->judul_batch = $this->BatchService->getData($this->view->row->id_batch)->judul_batch;
			$this->view->nama_peserta = $this->PesertaService->getData($this->view->row->id_peserta)->nama;
		}
	}

	public function nilaiAction() 
	{	
		$id = $this->getRequest()->getParam('id');

		if ( $this->getRequest()->isPost() )
		{
			$skor_akhir = $this->getRequest()->getParam('skor_akhir');
			$penugasan = $this->PenugasanService->getData($id);

			$progress = $this->ProgressService->getProgress($penugasan->id_peserta, $penugasan->id_batch);
            $this->ProgressService->editData($progress->id, 'Sertifikat');
			$last_id = $this->PenugasanService->nilaiData($id, $skor_akhir);
			
			$batch = $this->BatchService->getData($penugasan->id_batch);
			$skor_materi = (int)$this->SkorBelajarService->getDataSertifikat($penugasan->id_batch, $penugasan->id_peserta)->skor_akhir;
			$skor_mentoring = (int)$this->SkorMentoringService->getDataSertifikat($penugasan->id_batch, $penugasan->id_peserta)->skor_akhir;
			$skor_penugasan = (int)$this->PenugasanService->getData($id)->skor_akhir;

			$skor_akhir = ($skor_materi + ($skor_mentoring * 2) + ($skor_penugasan * 7)) /10;

			$sertifikat = $this->SertifikatService->getSertifikat($penugasan->id_peserta, $penugasan->id_batch);

			if($sertifikat){
				$this->SertifikatService->editData($sertifikat->id, $penugasan->id_batch, $penugasan->id_peserta, $batch->judul_batch, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);
			} else {
				$this->SertifikatService->addData($penugasan->id_batch, $penugasan->id_peserta, $batch->judul_batch, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);
			}
            
			$this->_redirect('/mentor/penugasan/index');
		} else {
			$this->view->row = $this->PenugasanService->getData($id);
			$this->view->nama_peserta = $this->PesertaService->getData($this->view->row->id_peserta)->nama;
		}
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PenugasanService->deleteFilesTitle($id);
		$this->_redirect('/mentor/penugasan/edit/id/' . $id);
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->PenugasanService->softDeleteData($id);

		$this->_redirect('/mentor/penugasan/index');
	}

	public function searchBatchAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->BatchService->getAllData();
		$this->view->pelatihan = $this->PelatihanService->getAllData();
	}

	public function searchPesertaAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PesertaService->getAllData();
	}

}