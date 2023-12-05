<?php
	class Peserta_IndexController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index', 'edit'));
		}
		
		public function preDispatch() {
			$this->BatchService = new BatchService();
			$this->PelatihanService = new PelatihanService();
			$this->PesertaBatchService = new PesertaBatchService();
			$this->ProgressService = new ProgressService();
			$this->SertifikatService = new SertifikatService();
		}
		
		public function indexAction() {
			$auth = Zend_Auth::getInstance()->getIdentity();
			
			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;

			$batch = $this->BatchService->getAllDataByPeserta($auth->id_peserta);
			$this->view->batch = $batch;

			$sertifikat = $this->SertifikatService->getDataAllSertifikat($auth->id_peserta);
			$this->view->sertifikat = $sertifikat;

			
			$this->view->page = 'Dashboard - ';
			
			$id_batch = 0;
			foreach($batch as $val){
				$id_batch = $val->id;
			}

			$progress = $this->ProgressService->getProgress($auth->id_peserta, $id_batch);

			$this->view->progress = $progress;
			
		}	
	}
?>