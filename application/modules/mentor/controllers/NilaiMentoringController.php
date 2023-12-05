<?php
class Mentor_NilaiMentoringController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->PesertaService = new PesertaService();
		$this->ForumDiskusiService = new ForumDiskusiService();
		$this->SkorMentoringService = new SkorMentoringService();
        $this->ProgressService = new ProgressService();
		$this->PenugasanService = new PenugasanService();
	}
	
	public function indexAction() {	
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login
        $this->view->batch = $this->BatchService->getAllData();
        $this->view->pelatihan = $this->PelatihanService->getAllData();
		$this->view->peserta = $this->PesertaService->getAllData();
		$this->view->rows = $this->SkorMentoringService->getAllDataByMentor($user->id_mentor);
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			// $id_forum = $this->getRequest()->getParam('id_forum');
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
			$last_id= $this->SkorMentoringService->addData($id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir);
		
			$this->_redirect('/mentor/nilai-mentoring/edit/id/'.$last_id);
		}
	}

    public function editAction() {	
        
        $id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$id_batch = $this->getRequest()->getParam('id_batch');
			// $id_forum = $this->getRequest()->getParam('id_forum');
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

			$last_id= $this->SkorMentoringService->editData($id, $id_batch, $id_peserta, $skor_keaktifan, $skor_pemahaman, $skor_tugas, $skor_forum, $skor_akhir);

			$penugasan = $this->PenugasanService->getDataSkorPenugasan($id_batch, $id_peserta);

			if($penugasan->title == NULL || $penugasan->title == ''){
				$progress = $this->ProgressService->getProgress($id_peserta, $id_batch);
            	$this->ProgressService->editData($progress->id, 'Penugasan');
				$this->_redirect('/mentor/penugasan/tambah/id/'.$id_batch.'/peserta/'.$id_peserta);
			} else {
				$this->_redirect('/mentor/nilai-mentoring/index');
			}
			
		} else {
			$this->view->row = $this->SkorMentoringService->getData($id);
			$this->view->batch = $this->BatchService->getData($this->view->row->id_batch);
			$this->view->peserta = $this->PesertaService->getData($this->view->row->id_peserta);
		}
	}

    public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->SkorMentoringService->softDeleteData($id);

		$this->_redirect('/mentor/nilai-mentoring/index');
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
		$this->view->rows = $this->PesertaService->getAllData();
	}

    public function searchForumAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->ForumDiskusiService->getAllData();
	}

}