<?php
class Penilai_PenugasanController extends Zend_Controller_Action
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
		$this->view->Batch = $this->BatchService->getAllData();
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
            
			$this->_redirect('/penilai/penugasan/index');
		} else {
			$this->view->row = $this->PenugasanService->getData($id);

			// var_dump($this->view->row);

			$this->view->nama_peserta = $this->PesertaService->getData($this->view->row->id_peserta)->nama;
		}
	}
}