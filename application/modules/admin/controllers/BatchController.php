<?php
class Admin_BatchController extends Zend_Controller_Action
{

	public function init()
	{
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index', 'edit'));
	}

	public function preDispatch()
	{
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->CoachService = new CoachService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->PesertaService = new PesertaService();
		$this->MentorService = new MentorService();
		$this->PengajarService = new PengajarService();
		$this->ProgressService = new ProgressService();
		$this->SkorPretestService = new SkorPretestService();
		$this->SkorMentoringService = new SkorMentoringService();
		$this->MentoringService = new MentoringService();
		$this->PenugasanService = new PenugasanService();
		$this->ForumDiskusiService = new ForumDiskusiService();
	}

	public function indexAction()
	{
		$rows = $this->BatchService->getAllData();
		$pelatihan = $this->PelatihanService->getAllData();
		$coach = $this->CoachService->getAllData();
		$peserta = $this->PesertaBatchService->getAllData();
		$this->view->rows = $rows;
		$this->view->pelatihan = $pelatihan;
		$this->view->coach = $coach;
		$this->view->peserta = $peserta;
	}

	public function addAction()
	{
		$this->view->peserta = $this->PesertaService->getAllData();
		if ($this->getRequest()->isPost()) {
			//tabel batch
			$judul_batch = $this->getRequest()->getParam('judul_batch');
			$tgl_awal = $this->_helper->CDate($this->getRequest()->getParam('tgl_awal'));
			$tgl_akhir = $this->_helper->CDate($this->getRequest()->getParam('tgl_akhir'));
			$kuota_peserta = $this->_helper->IsNull($this->getRequest()->getParam('kuota_peserta'));
			$title = $this->getRequest()->getParam('title');
			$title_diskusi = $this->getRequest()->getParam('title_diskusi');


			$file_mentoring = isset($_FILES['file_mentoring']['name']) ? $_FILES['file_mentoring']['name'] : '';
			$file_type = $_FILES['file_mentoring']['type'];
			$file_size = $_FILES['file_mentoring']['size'];
			$file_tmp = $_FILES['file_mentoring']['tmp_name'];

			// upload file
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/mentoring";
				$path_info = pathinfo($file_mentoring);
				$file_mentoring = 'File-Forum-Mentoring'.'-'. uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_mentoring);	
			}
		
			$file_forum = isset($_FILES['file_forum']['name']) ? $_FILES['file_forum']['name'] : '';
			$file_type_forum = $_FILES['file_forum']['type'];
			$file_size_forum = $_FILES['file_forum']['size'];
			$file_tmp_forum = $_FILES['file_forum']['tmp_name'];

			// upload file
			if ($file_tmp_forum) {
				$path = "//172.16.30.157/www/mooc/forum";
				$path_info = pathinfo($file_forum);
				$file_forum = 'File-Forum-Diskusi'.'-'. uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp_forum, $path . "/" . $file_forum);		
			}

			//tabel peserta_batch editData($id, $title, $id_batch)
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			$id_coach = $this->getRequest()->getParam('id_coach');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$id_mentor = $this->getRequest()->getParam('id_mentor');
			$tipe_pelatihan = $this->getRequest()->getParam('tipe_pelatihan');

			if($tipe_pelatihan == 'nonclassical'){
				$kuota_peserta= 0;
				$current_year = date("Y");
				$tgl_awal = $this->_helper->CDate("01-01-" . $current_year);
				$tgl_akhir = $this->_helper->CDate("31-12-" . $current_year);
			}

			$id_batch = $this->BatchService->addData($judul_batch, $id_pelatihan, $id_coach, $kuota_peserta,$tgl_awal,$tgl_akhir);
			$mentoring_id = $this->MentoringService->addData($title, $file_mentoring, $id_batch);
			$coach_id = $this->ForumDiskusiService->addData($title_diskusi, $file_forum, $id_batch, $id_coach);
			
			// $cbDelete = $this->getRequest()->getParam('cbDelete');
			if($id_peserta !== NULL){
				foreach ($id_peserta as $idx => $idpeserta) {
					$this->PesertaBatchService->addData($id_pelatihan, $id_batch, $idpeserta, $id_mentor[$idx], $id_coach);
					$this->ProgressService->addData($idpeserta, $id_batch, 'Peserta');
					$this->SkorMentoringService->addSkor($id_batch, $idpeserta, $id_mentor[$idx]);
				}
			}

			$this->_redirect('/admin/batch/edit/id/'.$id_batch);
		} else {
			$this->view->rows = $this->BatchService->getAllData();
		}
	}

	public function editAction()
	{
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			//tabel batch
			$judul_batch = $this->getRequest()->getParam('judul_batch');
			$tgl_awal = $this->_helper->CDate($this->getRequest()->getParam('tgl_awal'));
			$tgl_akhir = $this->_helper->CDate($this->getRequest()->getParam('tgl_akhir'));
			$kuota_peserta = $this->_helper->IsNull($this->getRequest()->getParam('kuota_peserta'));
			$title = $this->getRequest()->getParam('title');
			$title_diskusi = $this->getRequest()->getParam('title_diskusi');


			$file_mentoring = isset($_FILES['file_mentoring']['name']) ? $_FILES['file_mentoring']['name'] : '';
			$file_type = $_FILES['file_mentoring']['type'];
			$file_size = $_FILES['file_mentoring']['size'];
			$file_tmp = $_FILES['file_mentoring']['tmp_name'];

			// upload file
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/mentoring";
				$path_info = pathinfo($file_mentoring);
				$file_mentoring = 'File-Forum-Mentoring'.'-'. uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_mentoring);		
			}

			$file_forum = isset($_FILES['file_forum']['name']) ? $_FILES['file_forum']['name'] : '';
			$file_type_forum = $_FILES['file_forum']['type'];
			$file_size_forum = $_FILES['file_forum']['size'];
			$file_tmp_forum = $_FILES['file_forum']['tmp_name'];

			// upload file
			if ($file_tmp_forum) {
				$path = "//172.16.30.157/www/mooc/forum";
				$path_info = pathinfo($file_forum);
				$file_forum = 'File-Forum-Diskusi'.'-'. uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp_forum, $path . "/" . $file_forum);		
			}
			
			
			//tabel peserta_batch
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			$id_mentoring = $this->getRequest()->getParam('id_mentoring');
			$id_forum_diskusi = $this->getRequest()->getParam('id_forum_diskusi');
			$id_coach = $this->getRequest()->getParam('id_coach');
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$id_mentor = $this->getRequest()->getParam('id_mentor');
			$tipe_pelatihan = $this->getRequest()->getParam('tipe_pelatihan');

			if($tipe_pelatihan == 'nonclassical'){
				$tgl_awal = date('Y-m-d H:i:s');
				$tgl_akhir = date('Y-m-d H:i:s', strtotime('+1 year', strtotime($tgl_awal))); ;
			}
			
			$this->BatchService->editData($id, $id_pelatihan, $id_coach, $judul_batch,$kuota_peserta,$tgl_awal,$tgl_akhir);

			$mentoring_id = $this->MentoringService->editData($id_mentoring, $title, $file_mentoring,  $id);
			$forum_diskusi_id = $this->ForumDiskusiService->editData($id_forum_diskusi, $title_diskusi, $file_forum, $id);
			// if($id_mentoring !== ''){
			// 	$mentoring_id = $this->MentoringService->editData($id_mentoring, $title,  $id);
			// } else {
			// 	$mentoring_id = $this->MentoringService->addData($title, $file_mentoring, $id);
			// }


			// $cbDelete = $this->getRequest()->getParam('cbDelete');

			$this->PesertaBatchService->deleteData($id);
			foreach ($id_peserta as $idx => $idpeserta) {
				$this->PesertaBatchService->addData($id_pelatihan, $id, $idpeserta, $id_mentor[$idx], $id_coach);
				$progress = $this->ProgressService->getProgress($idpeserta, $id);
			
				if($progress->status_progress == "Terdaftar"){
					$this->ProgressService->editData($progress->id, 'Peserta');
					$this->SkorMentoringService->addSkor($id, $idpeserta, $id_mentor[$idx]);
				} else if(!($progress->status_progress)){
					$this->ProgressService->addData($idpeserta, $id, 'Peserta');
				}
			}

			$this->_redirect('/admin/batch/edit/id/'.$id);

		} else {

			$global = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->mentoring = $this->MentoringService->getDataBatch($id);
			$this->view->forum_diskusi = $this->ForumDiskusiService->getDataBatch($id);

			$id_mentor = [];
			if(count($global) == 0){
				$batch = $this->BatchService->getData($id);
				$this->view->id_pelatihan = $batch->id_pelatihan;
				$this->view->id_coach = $batch->id_coach;
				$this->view->id_peserta = $g->id_peserta;
				$id_mentor[0] = NULL;
			} else {
				foreach($global as $g){
					$this->view->id_pelatihan = $g->id_pelatihan;
					$this->view->id_coach = $g->id_coach;
					$this->view->id_peserta = $g->id_peserta;
					
					$id_mentor[$g->id_peserta] = $g->id_mentor;
				}
			}

			$pelatihan = $this->PelatihanService->getData($this->view->id_pelatihan);
			
			$this->view->global = $global;
			$this->view->id_mentor = $id_mentor;
			$this->view->peserta = $this->PesertaService->getAllData();
			$this->view->mentor = $this->MentorService->getAllData();
			$this->view->row = $this->BatchService->getData($id);
			
			$this->view->nama_pelatihan = $pelatihan->nama_pelatihan;
			$this->view->tipe_pelatihan = $pelatihan->tipe_pelatihan;
			$this->view->nama_coach = $this->CoachService->getData($this->view->id_coach)->nama_coach;
			$this->view->skor_pretest = $this->SkorPretestService->getJawabanNontesPeserta($id);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->BatchService->softDeleteData($id);
		$this->PesertaBatchService->softDeleteBatch($id);
		$this->ProgressService->softDeleteBatch($id);
		$this->SkorMentoringService->softDeleteBatch($id);
		$this->MentoringService->softDeleteBatch($id);
		$this->PenugasanService->softDeleteBatch($id);
		// $this->PesertaBatchService->deleteBatch($id);
		// $this->PesertaBatchService->deleteBatch($id);
		// $this->ProgressService->deleteBatch($id);
		// $this->SkorMentoringService->deleteBatch($id);

		$this->_redirect('/admin/batch/index');
	}

	public function searchPelatihanAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PelatihanService->getAllData2();
		$this->view->pengajar = $this->PengajarService->getAllData();
	}

	public function searchCoachAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->CoachService->getAllData();
	}

	public function searchMentorAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->MentorService->getAllData();

		$id_peserta = $this->getRequest()->getParam('id_peserta');
		$this->view->peserta = $id_peserta;
	}

	public function searchPesertaAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PesertaService->getAllData();
	}

	public function deleteFilesAction() 
	{
		$id = $this->getRequest()->getParam('id');
		$mentor = $this->MentoringService->getDataBatch($id);
		$this->MentoringService->deleteFiles($mentor->id);
		$this->_redirect('/admin/batch/edit/id/' . $id);
	}
	public function deleteFilesdiskusiAction() 
	{
		$id = $this->getRequest()->getParam('id');
		$forum_diskusi = $this->ForumDiskusiService->getDataBatch($id);
		$this->ForumDiskusiService->deleteFiles($forum_diskusi->id);
		$this->_redirect('/admin/batch/edit/id/' . $id);
	}
}
