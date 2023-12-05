<?php
	class Pesertanonasn_PelatihanController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow();
		}
		
		public function preDispatch() {
			$session = new Zend_Session_Namespace('loggedInUser');
			if (!isset($session->user)) {
				$this->_redirect('/');
			}
			$this->BatchService = new BatchService();
			$this->PelatihanService = new PelatihanService();
			$this->PengajarService = new PengajarService();
			$this->SilabusService = new SilabusService();
			$this->PesertaBatchService = new PesertaBatchService();
			$this->ProgressService = new ProgressService();

		}


		public function indexAction() {
			$session = new Zend_Session_Namespace('loggedInUser');
        	$auth= $session->user;

			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;

			$batch = $this->BatchService->getAllDataByPeserta($auth->id_peserta);
			$this->view->batch = $batch;

			$progress = $this->ProgressService->getAllProgress($auth->id_peserta);
			$this->view->progress = $progress;

			$this->view->page = 'Pelatihan - ';
		}	
	}
?>