<?php
	class Peserta_PelatihanController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index', 'edit'));
		}
		
		public function preDispatch() {
			$this->BatchService = new BatchService();
			$this->PelatihanService = new PelatihanService();
			$this->PengajarService = new PengajarService();
			$this->SilabusService = new SilabusService();
			$this->PesertaBatchService = new PesertaBatchService();
			$this->ProgressService = new ProgressService();

		}


		public function indexAction() {
			$auth = Zend_Auth::getInstance()->getIdentity();

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