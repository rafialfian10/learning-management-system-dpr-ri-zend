<?php
class Admin_ProgressController extends Zend_Controller_Action
{
	
	public function init()
	{ 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch()
	{
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->PesertaService = new PesertaService();
		$this->ProgressService = new ProgressService();
		$this->SkorPretestService = new SkorPretestService();
		$this->SkorBelajarService = new SkorBelajarService();
		$this->SkorMentoringService = new SkorMentoringService();
		$this->PenugasanService = new PenugasanService();

	}
	
	public function indexAction() 
	{	
		$rows =$this->ProgressService->getAllData();
		$batch = $this->BatchService->getAllData();
		$pelatihan = $this->PelatihanService->getAllData();
		$peserta_batch = $this->PesertaBatchService->getAllData();
		$peserta= $this->PesertaService->getAllData();
		$this->view->rows = $rows;
		$this->view->batch = $batch;
		$this->view->pelatihan = $pelatihan;
		$this->view->peserta_batch = $peserta_batch;
		$this->view->peserta = $peserta;
	}

	public function viewAction() 
	{	
		$id = $this->getRequest()->getParam('id');
		$id_batch = $this->getRequest()->getParam('batch');
		$id_peserta = $this->getRequest()->getParam('peserta');
		$this->view->row = $this->ProgressService->getData($id,$id_batch, $id_peserta);


    	$skor_pretest = $this->SkorPretestService->getDataScorePretest($id_batch, $id_peserta);
    	$this->view->skor_pretest = $skor_pretest;

    	$skor_belajar = $this->SkorBelajarService->getDataScoreBelajar($id_batch, $id_peserta);
    	$this->view->skor_belajar = $skor_belajar;
		
    	$skor_mentoring = $this->SkorMentoringService->getDataScoreMentoring($id_batch, $id_peserta);
    	$this->view->skor_mentoring = $skor_mentoring;

    	$skor_penugasan = $this->PenugasanService->getDataSkorPenugasan($id_batch, $id_peserta);
    	$this->view->skor_penugasan = $skor_penugasan;
	}

}