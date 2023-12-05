<?php
	class Admin_SkorNontestController extends Zend_Controller_Action {
	
		public function init() { 
			$this->_helper->_acl->allow('admin');
			$this->_helper->_acl->allow('super');
			$this->_helper->_acl->allow('user', array('index'));
		}
		
		public function preDispatch() {
			$this->SkorNontestService = new SkorNontestService();
			$this->PelatihanService = new PelatihanService();
			$this->PesertaService = new PesertaService();
		}
		
		public function indexAction() {	
			$id = $this->getRequest()->getParam('id');
			$this->view->skor_nontest = $this->SkorNontestService->getAllData();
			$this->view->pelatihan = $this->PelatihanService->getData($id);
			$this->view->pesertas = $this->PesertaService->getAllData();
		}

		public function nilaiAction() {	
			$id = $this->getRequest()->getParam('id');
	
			if ( $this->getRequest()->isPost()) {
				$this->view->skor_nontes = $this->SkorNontestService->getData($id);

				$skor_akhir = $this->getRequest()->getParam('skor_akhir');
				$penugasan = $this->SkorNontestService->getData($id);
	
				$last_id = $this->SkorNontestService->nilaiData($id, $skor_akhir);
				
				$this->_redirect('/admin/skor-nontest/index/id/'.$this->view->skor_nontes->id_pelatihan);
			} else {
				$this->view->skor_nontes = $this->SkorNontestService->getData($id);
				$this->view->peserta = $this->PesertaService->getData($this->view->skor_nontes->id_peserta);
			}
		}

		public function deleteAction() {
			$id = $this->getRequest()->getParam('id');
			$this->SkorMentoringService->softDeleteData($id);

			$this->_redirect('/admin/skor-nontest/index');
		}
	}

?>