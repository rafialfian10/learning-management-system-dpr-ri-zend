<?php
class Penilai_NilaiMentoringController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->PenilaiService = new PenilaiService();

		$this->BatchService = new BatchService();
		$this->PesertaService = new PesertaService();
		$this->ForumDiskusiService = new ForumDiskusiService();
		$this->MentoringService = new MentoringService();
		$this->SkorMentoringService = new SkorMentoringService();
        $this->ProgressService = new ProgressService();
	}
	
	public function indexAction() {	
		$this->view->rows = $this->PenilaiService->getAllData();
        $this->view->batch = $this->BatchService->getAllData();
		$this->view->peserta = $this->PesertaService->getAllData();
		$this->view->rows = $this->SkorMentoringService->getAllData();
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
            $skor_keaktifan = $this->getRequest()->getParam('skor_keaktifan');
			$skor_pemahaman = $this->getRequest()->getParam('skor_pemahaman');
			$skor_tugas = $this->getRequest()->getParam('skor_tugas');
			$skor_forum = $this->getRequest()->getParam('skor_forum');

            $skor_akhir = 4;

			if($skor_keaktifan == ''){
				$skor_keaktifan == NULL;
				$skor_akhir--;
			}

			if($skor_pemahaman == ''){
				$skor_pemahaman == NULL;
				$skor_akhir--;
			}

			if($skor_tugas == ''){
				$skor_tugas == NULL;
				$skor_akhir--;
			}

			if($skor_forum == ''){
				$skor_forum == NULL;
				$skor_akhir--;
			}

			$skor_akhir = ($skor_keaktifan + $skor_pemahaman + $skor_tugas + $skor_forum) / $skor_akhir;

            $progress = $this->ProgressService->getProgress($id_peserta, $id_batch);
            $this->ProgressService->editData($progress->id, 'Penugasan');
			$last_id= $this->SkorMentoringService->addData($id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir);
		
			$this->_redirect('/penilai/nilai-mentoring/edit/id/'.$last_id);
		}
	}

    public function editAction() {	
        
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
            $skor_keaktifan = $this->getRequest()->getParam('skor_keaktifan');
			$skor_pemahaman = $this->getRequest()->getParam('skor_pemahaman');
			$skor_tugas = $this->getRequest()->getParam('skor_tugas');
			$skor_forum = $this->getRequest()->getParam('skor_forum');

            $skor_akhir=4;

			if($skor_keaktifan == ''){
				$skor_keaktifan = NULL;
				$skor_akhir--;
			}

			if($skor_pemahaman == ''){
				$skor_pemahaman = NULL;
				$skor_akhir--;
			}

			if($skor_tugas == ''){
				$skor_tugas = NULL;
				$skor_akhir--;
			}

			if($skor_forum == ''){
				$skor_forum = NULL;
				$skor_akhir--;
			}

			$skor_akhir = ($skor_keaktifan + $skor_pemahaman + $skor_tugas + $skor_forum) / $skor_akhir;

            $progress = $this->ProgressService->getProgress($id_peserta, $id_batch);
            $this->ProgressService->editData($progress->id, 'Penugasan');
			$last_id= $this->SkorMentoringService->editData($id, $id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir);
		
			$this->_redirect('/penilai/nilai-mentoring/edit/id/'.$id);
		} else {
			$this->view->row = $this->SkorMentoringService->getData($id);
			$this->view->batch = $this->BatchService->getData($this->view->row->id_batch);
			$this->view->peserta = $this->PesertaService->getData($this->view->row->id_peserta);
		}
	}

    public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->SkorMentoringService->softDeleteData($id);

		$this->_redirect('/penilai/nilai-mentoring/index');
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

    public function searchForumAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->MentoringService->getAllData();
	}

}