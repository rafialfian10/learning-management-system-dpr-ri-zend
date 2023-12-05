<?php
class Admin_RiwayatController extends Zend_Controller_Action
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
		$this->RiwayatService = new RiwayatService();
		$this->PenugasanService = new PenugasanService();

	}
	
	public function indexAction() 
	{	
		$rows =$this->RiwayatService->getAllData();
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

}