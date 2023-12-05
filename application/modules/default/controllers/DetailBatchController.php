<?php
class DetailBatchController extends Zend_Controller_Action
{
	
	public function init() {  
		$this->_helper->_acl->allow();
	}
	
	public function preDispatch() {
		$this->PesertaBatchService = new PesertaBatchService();
		$this->PelatihanService = new PelatihanService();
		$this->BatchService = new BatchService();
		$this->SilabusService = new SilabusService();
		$this->MateriSilabusService = new MateriSilabusService();
		$this->PengajarService = new PengajarService();
		$this->ProgressService = new ProgressService();
		$this->RatingService = new RatingService();
		$this->PesertaBatchService = new PesertaBatchService();
	}
	
	public function indexAction() {	
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			
			$user = Zend_Auth::getInstance()->getIdentity();
			$batch = $this->BatchService->getDataByPelatihan($id);
			$this->PesertaBatchService->addPeserta($batch->id, $id, $user->id_peserta, $batch->id_coach);
			$this->ProgressService->addData($user->id_peserta, $batch->id_batch, 'Terdaftar');
			$this->_redirect('/peserta/index');

		} else {
			$batch = $this->BatchService->getDataByPelatihan($id);
			$this->view->batch = $batch;

			$silabus = $this->SilabusService->getAllDataPelatihan($batch->id);
			$this->view->silabus = $silabus;

			$materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id);
			$this->view->materi = $materi;

			$pengajar = $this->PengajarService->getAllData();
			$this->view->pengajar = $pengajar;

			$user = Zend_Auth::getInstance()->getIdentity(); 

			$ratings = $this->RatingService->getRatingByBatch($batch->id_batch);
			$this->view->ratings = $ratings;
			
			$peserta_batchs = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->peserta_batchs = $peserta_batchs;

			if($user){
				$progress = $this->ProgressService->getAllProgress($user->id_peserta);
				$this->view->progress = $progress;

				$peserta_batch = $this->PesertaBatchService->getDataPesertaBatch($user->id_peserta, $id);
				$this->view->peserta_batch = $peserta_batch;
			}
		}
	}
}