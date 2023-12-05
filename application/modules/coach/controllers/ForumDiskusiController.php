<?php
class Coach_ForumDiskusiController extends Zend_Controller_Action
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
		$this->PelatihanService = new PelatihanService();
	}

	public function indexAction()
	{
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login
		$rows = $this->ForumDiskusiService->getAllDataByCoach($user->id_coach);
		$this->view->rows = $rows;
		$this->view->batch = $this->BatchService->getAllData();
		$coach_batchs = $this->PesertaBatchService->getAllDataCoach($user->id_coach);
		$this->view->coach_batchs = $coach_batchs;
		$pelatihans = $this->PelatihanService->getAllData();
		$this->view->pelatihans = $pelatihans;
		$coachs = $this->CoachService->getAllData();
		$this->view->coachs = $coachs;
		$forum_diskusi = $this->ForumDiskusiService->getAllData();
		$this->view->forum_diskusi = $forum_diskusi;
	}

	public function replyAction() {
		$id = $this->getRequest()->getParam('id');
		$auth = Zend_Auth::getInstance()->getIdentity();

		if ($this->getRequest()->isPost()) {

			$id_user = $this->getRequest()->getParam('id_user');
			$id_reply = $this->getRequest()->getParam('id_reply');
			$role_user = $this->getRequest()->getParam('role_user');
			$isi = $this->getRequest()->getParam('isi');

			// upload file tugas
			$file_forum = $_FILES['file_forum']['name'];
			$file_type = $_FILES['file_forum']['type'];
			$file_size = $_FILES['file_forum']['size'];
			$file_tmp = $_FILES['file_forum']['tmp_name'];
			$file_error = $_FILES['file_forum']['error'];

			//Cek ukuran file tugas (maks 100mb)
			if($file_size > 100000000){
				echo "<script>
						Swal.fire({
							icon: 'warning',
							title: 'Warning',
							html: 'Ukuran file terlalu besar (maks 100 MB)',
							confirmButtonColor: '#D4A216',
							customClass: {
							popup: 'my-custom-popup-class',
							title: 'my-custom-title-class',
							content: 'my-custom-content-class',
							confirmButton: 'confirm-button-class'
							}
						});
					</script>";
				return false;
			}

			// upload file tugas
			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/forum";
				$path_info = pathinfo($file_forum);

				$nama_materi_kata = explode(" ", $auth->id_coach);
				$nama_materi_kata = $nama_materi_kata[0] . $nama_materi_kata[1];
				$file_forum = 'forum-'. $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_forum);
				}
				
				// $isi = array_filter($isi);
				// $isi = array_values($isi);
			
				// foreach ($isi as $value) {
					$last_id = $this->ChatDiskusiService->replyData($id, $id_reply, $id_user, $role_user, $isi, $file_forum); 
				// }			

				$this->_redirect('/coach/forum-diskusi/reply/id/'. $id);

				header("Refresh: 1"); 

		} else {		
			$auth = Zend_Auth::getInstance()->getIdentity();

			$this->view->chats = $this->ChatDiskusiService->getAllDataForum($id);
			$this->view->coach = $this->CoachService->getAllData();
			$this->view->mentor = $this->MentorService->getAllData();
			$this->view->peserta = $this->PesertaService->getAllData();
			$this->view->forums = $this->ForumDiskusiService->getData($id);
			// $this->view->reply =$this->ChatDiskusiService->getAllDataReply($id);

			$coach_id = $this->PesertaBatchService->getDataBatch($this->view->forums->id_batch);
			$pelatihan_id = $this->PesertaBatchService->getDataBatch($this->view->forums->id_batch);

			$this->view->id_coach = $coach_id->id_coach;
			$this->view->nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
			$this->view->fotocoach_uri = $this->CoachService->getData($coach_id->id_coach)->fotocoach_uri;
			$this->view->nama_pelatihan = $this->PelatihanService->getData($pelatihan_id->id_pelatihan)->nama_pelatihan;
			$this->view->id_pelatihan = $pelatihan_id->id_pelatihan;

			$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->search_peserta = $this->PesertaService->getAllDataBatch($peserta_id);

			$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->search_mentor = $this->MentorService->getAllDataBatch($mentor_id);

			$coach_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->search_coach = $this->CoachService->getAllDataBatch($coach_id);

			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;
		}
	}

	public function addAction()
	{
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		if ($this->getRequest()->isPost()) {
			
			$title = $this->getRequest()->getParam('title');
			$id_batch = $this->getRequest()->getParam('id_batch');
			$id_coach = $user->id_coach;
			
			$last_id = $this->ForumDiskusiService->addData($title,$id_batch,$id_coach);
			

			$this->_redirect('/coach/forum-diskusi/edit/id/'.$last_id);
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

			$this->_redirect('/coach/forum-diskusi/index');
		} else {
			$this->view->rows = $this->ForumDiskusiService->getData($id);
			$this->view->judul_batch = $this->BatchService->getData($this->view->rows->id_batch)->judul_batch;
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->ForumDiskusiService->softDeleteData($id);

		$this->_redirect('/coach/forum-diskusi/index');
	}

	public function searchBatchAction()
	{
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->BatchService->getAllDataByCoach($user->id_coach);
	}

}
