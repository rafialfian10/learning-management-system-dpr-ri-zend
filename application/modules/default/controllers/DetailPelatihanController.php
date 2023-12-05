<?php
class DetailPelatihanController extends Zend_Controller_Action
{
	
	public function init() {  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() {
		$this->BatchService = new BatchService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->PelatihanService = new PelatihanService();
		$this->SilabusService = new SilabusService();
		$this->MateriSilabusService = new MateriSilabusService();
		$this->PengajarService = new PengajarService();
		$this->RatingService = new RatingService();
	}
	
	public function indexAction() {	
		$id = $this->getRequest()->getParam('id');

		$batchs = $this->BatchService->getAllData();
		$this->view->batchs = $batchs;

		$pelatihan = $this->PelatihanService->getData($id);
		$this->view->pelatihan = $pelatihan;

		$pelatihans = $this->PelatihanService->getAllData();
		$this->view->pelatihans = $pelatihans;

		$silabus = $this->SilabusService->getAllDataPelatihan($id);
		$this->view->silabus = $silabus;

		$materi = $this->MateriSilabusService->getAllData();
		$this->view->materi = $materi;

		$pengajar = $this->PengajarService->getAllData();
		$this->view->pengajar = $pengajar;

		$ratings = $this->RatingService->getRatingByPelatihan($id);
		$this->view->ratings = $ratings;
	}
}