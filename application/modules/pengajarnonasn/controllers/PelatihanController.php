<?php
class Pengajarnonasn_PelatihanController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow();
	}
	
	public function preDispatch() {
		$session = new Zend_Session_Namespace('loggedInUser');
        if (!isset($session->user)) {
            $this->_redirect('/loginselainasn');
        }
		$this->PelatihanService = new PelatihanService();
		$this->PengajarService = new PengajarService();
		$this->SilabusService = new SilabusService();
		$this->NontesService = new NontesService();
		$this->PreTestService = new PreTestService();
		$this->SilabusQuizService = new SilabusQuizService();
		$this->MateriSilabusService = new MateriSilabusService();
	}
	
	public function indexAction() {
		
		$session = new Zend_Session_Namespace('loggedInUser');
        $user= $session->user;
		
		$this->view->rows = $this->PelatihanService->getAllDataByPengajar($user->id_pengajar);
	}

	public function editAction() {	
		$id = $this->getRequest()->getParam('id');

		$row = $this->PelatihanService->getData($id);
		$this->view->row = $row;
		$this->view->non_test = $this->NontesService->getAllDataPelatihan($id);
		$this->view->pre_test = $this->PreTestService->getAllDataPelatihan($id);
		$this->view->silabus = $this->SilabusService->getAllDataPelatihan($id);
		$this->view->quiz = $this->SilabusQuizService->getAllDataPelatihan($id);
		$this->view->materi = $this->MateriSilabusService->getAllData();
		$this->view->nama_pengajar = $this->PengajarService->getData($row->id_pengajar)->nama_pengajar;

	
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PelatihanService->deleteFiles($id);
		$this->_redirect('/pengajarnonasn/pelatihan/edit/id/' . $id);
	}
}