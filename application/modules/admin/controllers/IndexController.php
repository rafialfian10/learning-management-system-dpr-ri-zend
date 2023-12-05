<?php
class Admin_IndexController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->PesertaService = new PesertaService();
		$this->AdminService = new AdminService();
		$this->PengajarService = new PengajarService();
		$this->CoachService = new CoachService();
		$this->MentorService = new MentorService();
		$this->PenilaiService = new PenilaiService();

		$this->MateriSilabusService = new MateriSilabusService();
		$this->SilabusService = new SilabusService();
		$this->PelatihanService = new PelatihanService();
		$this->BatchService = new BatchService();

		$this->PenugasanService = new PenugasanService();
		$this->SertifikatService = new SertifikatService();
		$this->ProgressService = new ProgressService();
		$this->RiwayatService = new RiwayatService();
		$this->NontesService = new NontesService();


	}
	
	public function indexAction()
	{
		$this->view->peserta = $this->PesertaService->getAllData();
		$this->view->admin = $this->AdminService->getAllData();
		$this->view->pengajar = $this->PengajarService->getAllData();
		$this->view->mentor = $this->MentorService->getAllData();
		$this->view->coach = $this->CoachService->getAllData();
		$this->view->penguji = $this->PenilaiService->getAllData();

		$this->view->materisilabus = $this->MateriSilabusService->getAllData();
		$this->view->silabus = $this->SilabusService->getAllData();
		$this->view->pelatihan = $this->PelatihanService->getAllData();
		$this->view->batch = $this->BatchService->getAllData();
		
		$this->view->nontes = $this->NontesService->getAllData();
		$this->view->penugasan = $this->PenugasanService->getAllData();
		$this->view->sertifikat = $this->SertifikatService->getAllData();
		$this->view->progress = $this->ProgressService->getAllData();
		$this->view->riwayat = $this->RiwayatService->getAllData();

		
	}	
}