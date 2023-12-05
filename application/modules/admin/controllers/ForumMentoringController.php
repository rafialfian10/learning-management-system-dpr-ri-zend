<?php
class Admin_ForumDiskusiController extends Zend_Controller_Action
{

	public function init()
	{
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index', 'edit'));
	}

	public function preDispatch()
	{
		$this->ForumDiskusiService = new ForumDiskusiService();
		$this->ChatDiskusiService = new ChatDiskusiService();
		$this->BatchService = new BatchService();
		$this->PesertaService = new PesertaService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->MentorService = new MentorService();
		$this->CoachService = new CoachService();
	}

	public function indexAction()
	{
		$rows = $this->ForumDiskusiService->getAllData();
		$this->view->rows = $rows;
	}

	public function addAction()
	{
		if ($this->getRequest()->isPost()) {
			
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			
			$last_id = $this->ForumDiskusiService->addData($title,$id_batch, 1);
			

			$this->_redirect('/admin/forum-diskusi/edit/id/'.$last_id);
		} else {
			$this->view->rows = $this->ForumDiskusiService->getAllData();
		}
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {

			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			
			$last_id = $this->ForumDiskusiService->editData($id, $title, $id_batch);

			$this->_redirect('/admin/forum-diskusi/index');
		} else {
			$this->view->rows = $this->ForumDiskusiService->getData($id);
			$this->view->judul_batch = $this->BatchService->getData($this->view->rows->id_batch)->judul_batch;
		}
	}

	public function replyAction()
	{
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {

			$isi = $this->getRequest()->getParam('isi');
			$id_user = $this->getRequest()->getParam('id_user');
			$id_reply = $this->getRequest()->getParam('id_reply');
			$role_user = $this->getRequest()->getParam('role_user');
			
			$last_id = $this->ChatDiskusiService->replyMentoring($id,$id_reply,$id_user,$role_user, $isi);

			$this->_redirect('/admin/forum-diskusi/index');
		} else {
			$this->view->rows = $this->ForumDiskusiService->getData($id);
			$coach_id = $this->PesertaBatchService->getDataBatch($this->view->rows->id_batch);

			$this->view->nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
			$this->view->id_coach = $coach_id->id_coach;

			$this->view->chats = $this->ChatDiskusiService->getAllDataForumReply($id);
			$this->view->reply =$this->ChatDiskusiService->getAllDataReply($id);
			$this->view->coach = $this->CoachService->getAllData();
			$this->view->mentor = $this->MentorService->getAllData();
			$this->view->peserta = $this->PesertaService->getAllData();
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->ForumDiskusiService->softDeleteData($id);

		$this->_redirect('/admin/forum-diskusi/index');
	}

	public function searchBatchAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->BatchService->getAllData();
	}

	public function searchPesertaAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->_helper->getHelper('layout')->disableLayout();
		$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);
		$this->view->rows = $this->PesertaService->getAllDataBatch($peserta_id);
	}

	public function searchPesertaxAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->_helper->getHelper('layout')->disableLayout();
		$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);
		$this->view->rows = $this->PesertaService->getAllDataBatch($peserta_id);
	}

	public function searchMentorAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->_helper->getHelper('layout')->disableLayout();
		$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
		$this->view->rows = $this->MentorService->getAllDataBatch($mentor_id);
	}

}
