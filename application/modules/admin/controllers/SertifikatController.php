<?php
class Admin_SertifikatController extends Zend_Controller_Action
{
	public function init()
	{
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index', 'edit'));
	}

	public function preDispatch() {
		$this->BatchService = new BatchService();
		$this->PesertaService = new PesertaService();
		$this->SertifikatService = new SertifikatService();
	}

	public function indexAction() {
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		$rows=$this->SertifikatService->getAllData();
		$this->view->rows = $rows;
		$this->view->batch = $this->BatchService->getAllData();
		$this->view->peserta = $this->PesertaService->getAllData();
	}

	public function fileAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->id = $this->getRequest()->getParam('id');
		$this->view->id_batch = $this->getRequest()->getParam('batch');
		$this->view->id_peserta = $this->getRequest()->getParam('peserta');
	}
	
	public function addAction() {
		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$title = $this->getRequest()->getParam('title');
			$skor_materi = $this->getRequest()->getParam('skor_materi');
			$skor_mentoring = $this->getRequest()->getParam('skor_mentoring');
			$skor_penugasan = $this->getRequest()->getParam('skor_penugasan');
			$skor_akhir = $this->getRequest()->getParam('skor_akhir');

			$last_id=$this->SertifikatService->addData($id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);

			$this->_redirect('/admin/sertifikat/edit/id/'.$last_id);
		} 
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$title = $this->getRequest()->getParam('title');
			$skor_materi = $this->getRequest()->getParam('skor_materi');
			$skor_mentoring = $this->getRequest()->getParam('skor_mentoring');
			$skor_penugasan = $this->getRequest()->getParam('skor_penugasan');
			$skor_akhir = $this->getRequest()->getParam('skor_akhir');

			$last_id=$this->SertifikatService->editData($id, $id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);
			$this->redirect('/admin/sertifikat/index');

		} else {
			$this->view->row = $this->SertifikatService->getData($id);
			$this->view->nama_batch = $this->BatchService->getData($this->view->row->id_batch)->judul_batch;
			$this->view->nama_peserta = $this->PesertaService->getData($this->view->row->id_peserta)->nama;
		}	
				
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PengajarService->deleteFiles($id);
		$this->_redirect('/admin/pengajar/edit/id/' . $id);
	}

	public function deleteAction(){
		$id = $this->getRequest()->getParam('id');
		// $this->PengajarService->deleteData($id);
		$this->PengajarService->softDeleteData($id);

		$this->_redirect('/admin/pengajar/index');
	}

	public function searchBatchAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->BatchService->getAllData();
	}

	public function searchPesertaAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PesertaService->getAllData();
	}

	public function sertifikatAction() {
		$this->_helper->getHelper('layout')->disableLayout();
	}

	public function suratAction() {
		$this->_helper->getHelper('layout')->disableLayout();
	}
}
