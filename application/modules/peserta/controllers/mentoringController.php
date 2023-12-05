<?php
class Peserta_MentoringController extends Zend_Controller_Action {

	public function init() {
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
	}

	public function preDispatch() {
		$this->MentoringService = new MentoringService();
		$this->ChatMentoringService = new ChatMentoringService();
		$this->BatchService = new BatchService();
		$this->PesertaService = new PesertaService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->MentorService = new MentorService();
		$this->CoachService = new CoachService();
		$this->PelatihanService = new PelatihanService();
	}

	public function indexAction() {
		$id = $this->getRequest()->getParam('id');

		$batchs = $this->BatchService->getAllData();
		$this->view->batchs = $batchs;

		$mentorings = $this->MentoringService->getAllData();
		$this->view->mentorings = $mentorings;

		$chat_mentorings = $this->ChatMentoringService->getAllData();
		$this->view->chat_mentorings = $chat_mentorings;
		
		$coachs = $this->CoachService->getAllData();
		$this->view->coachs = $coachs;

		$pelatihans = $this->PelatihanService->getAllData();
		$this->view->pelatihans = $pelatihans;
		$this->view->batch = $this->BatchService->getData($id);

		$auth = Zend_Auth::getInstance()->getIdentity();
		$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
		$this->view->peserta_batch = $peserta_batch;
	}

	public function addAction() {
		if ($this->getRequest()->isPost()) {
			
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			
			$last_id = $this->MentoringService->addData($title,$id_batch);
			

			$this->_redirect('/peserta/mentoring/edit/id/'.$last_id);
		} else {
			$this->view->mentorings = $this->MentoringService->getAllData();
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {

			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			
			$last_id = $this->MentoringService->editData($id, $title, $id_batch);

			$this->_redirect('/peserta/mentoring/index/id/'. $last_id);
		} else {
			$this->view->mentorings = $this->MentoringService->getData($id);
			$this->view->judul_batch = $this->BatchService->getData($this->view->mentorings->id_batch)->judul_batch;
		}
	}

	public function replyMentoringAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {

			$isi = $this->getRequest()->getParam('isi');
			$id_user = $this->getRequest()->getParam('id_user');
			$id_reply_mentoring = $this->getRequest()->getParam('id_reply_mentoring');
			$role_user = $this->getRequest()->getParam('role_user');
			
			$last_id = $this->ChatMentoringService->replyMentoring($id, $id_reply_mentoring, $id_user, $role_user, $isi);
			
			$this->_redirect('/peserta/mentoring/reply-mentoring/id/'. $id);
			header("Refresh: 1"); 
		} else {
			$this->view->mentorings = $this->MentoringService->getData($id);
			$coach_id = $this->PesertaBatchService->getDataBatch($this->view->mentorings->id_batch);
			$pelatihan_id = $this->PesertaBatchService->getDataBatch($this->view->mentorings->id_batch);

			$this->view->id_coach = $coach_id->id_coach;
			$this->view->nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
			$this->view->fotocoach_uri = $this->CoachService->getData($coach_id->id_coach)->fotocoach_uri;
			$this->view->nama_pelatihan = $this->PelatihanService->getData($pelatihan_id->id_pelatihan)->nama_pelatihan;
			$this->view->id_pelatihan = $pelatihan_id->id_pelatihan;

			$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->search_peserta = $this->PesertaService->getAllDataBatch($peserta_id);

			$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->search_mentor = $this->MentorService->getAllDataBatch($mentor_id);

			$this->view->reply_mentorings =$this->ChatMentoringService->getAllDataReply($id);
			$this->view->chat_mentorings = $this->ChatMentoringService->getAllDataMentoringReply($id);
			$this->view->coach = $this->CoachService->getAllData();
			$this->view->mentor = $this->MentorService->getAllData();
			$this->view->peserta = $this->PesertaService->getAllData();

			$auth = Zend_Auth::getInstance()->getIdentity();
			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;
		}
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MentoringService->softDeleteData($id);

		$this->_redirect('/peserta/mentoring/index/id/'. $id);
	}

	public function searchBatchAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->mentorings = $this->BatchService->getAllData();
	}

	public function searchMentorAction() {
		$id = $this->getRequest()->getParam('id');
		$this->_helper->getHelper('layout')->disableLayout();
		$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
		$this->view->mentorings = $this->MentorService->getAllDataBatch($mentor_id);
	}
}
