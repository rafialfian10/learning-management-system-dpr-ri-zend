<?php
	class Peserta_SertifikatController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index', 'edit'));
		}
		
		public function preDispatch() {
			$this->BatchService = new BatchService();
			$this->PesertaBatchService = new PesertaBatchService();
			$this->PelatihanService = new PelatihanService();
			$this->PengajarService = new PengajarService();
			$this->MentorService = new MentorService();
			$this->PesertaService = new PesertaService();
			$this->SilabusService = new SilabusService();
			$this->MateriSilabusService = new MateriSilabusService();
			$this->SkorPretestService = new SkorPretestService();
			$this->SkorMentoringService = new SkorMentoringService();
			$this->SkorBelajarService = new SkorBelajarService();
			$this->PenugasanService = new PenugasanService();
			$this->SertifikatService = new SertifikatService();
			$this->PretestService = new PreTestService();
		}
		
		public function indexAction() {
			$id = $this->getRequest()->getParam('id');
			$auth = Zend_Auth::getInstance()->getIdentity();

			$this->view->rows1 = $this->SilabusService->getAllDataPelatihan($id);
			$this->view->rows2 = $this->PelatihanService->getData3($id);
			$this->view->rows3 = $this->MateriSilabusService->getAllData();

			$batchs = $this->BatchService->getAllData();
			$this->view->batchs = $batchs;

			$batch = $this->BatchService->getData($id);
			$this->view->batch = $batch;

			$pelatihans = $this->PelatihanService->getAllData();
			$this->view->pelatihans = $pelatihans;

			$pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
			$this->view->pelatihan = $pelatihan;

			$silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$this->view->silabus = $silabus;

			$materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$this->view->materi = $materi;

			$pretest = $this->SkorPretestService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->pretest = $pretest;

			$penugasan = $this->PenugasanService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->penugasan = $penugasan;

			$belajar = $this->SkorBelajarService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->belajar = $belajar;

			// $skor_mentoring = $this->SkorMentoringService->getDataSertifikat($batch->id, $auth->id_peserta);
			// $this->view->$skor_mentoring = $skor_mentoring;

			$pengajars = $this->PengajarService->getAllData();
			$this->view->pengajars = $pengajars;

			$pengajar = $this->PengajarService->getData($pelatihan->id_pengajar);
			$this->view->pengajar = $pengajar;

			$mentors = $this->MentorService->getAllData();
			$this->view->mentors = $mentors;

			$pesertas = $this->PesertaService->getAllData();
			$this->view->pesertas = $pesertas;

			$peserta = $this->PesertaService->getData($auth->id_peserta);
			$this->view->peserta = $peserta;

			$sertifikats = $this->SertifikatService->getAllData();
			$this->view->sertifikats = $sertifikats;

			$penugasan = $this->PenugasanService->getDataBatch($id);
			$this->view->penugasan = $penugasan;

			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;
		}	

		public function fileAction() {
			$this->_helper->getHelper('layout')->disableLayout();
			$this->view->id = $this->getRequest()->getParam('id');
			$this->view->id_batch = $this->getRequest()->getParam('batch');
			$this->view->id_peserta = $this->getRequest()->getParam('peserta');
		}
	}
?>