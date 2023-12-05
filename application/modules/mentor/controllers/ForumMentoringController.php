<?php
class Mentor_ForumMentoringController extends Zend_Controller_Action
{

	public function init()
	{
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		// $this->_helper->_acl->allow('user', array('index', 'edit'));
	}

	public function preDispatch()
	{
		$this->MentoringService = new MentoringService();
		$this->ChatMentoringService = new ChatMentoringService();
		$this->BatchService = new BatchService();
		$this->PelatihanService = new PelatihanService();
		$this->PesertaService = new PesertaService();
		$this->PesertaBatchService = new PesertaBatchService();
		$this->MentorService = new MentorService();
		$this->CoachService = new CoachService();
	}

	public function indexAction()
	{
		// $rows = $this->MentoringService->getAllData();
		// $this->view->batch = $this->BatchService->getAllData();
        // $this->view->pelatihan = $this->PelatihanService->getAllData();
		// $this->view->rows = $rows;

		$auth = Zend_Auth::getInstance()->getIdentity();

		$batchs = $this->BatchService->getAllData();
		$this->view->batchs = $batchs;
		$mentor_batchs = $this->PesertaBatchService->getAllDataMentor($auth->id_mentor);
		$this->view->mentor_batchs = $mentor_batchs;
		$pelatihans = $this->PelatihanService->getAllData();
		$this->view->pelatihans = $pelatihans;
		$coachs = $this->CoachService->getAllData();
		$this->view->coachs = $coachs;
		$mentoring = $this->MentoringService->getAllData();
		$this->view->mentoring = $mentoring;
		$chats = $this->ChatMentoringService->getAllData();
		$this->view->chats = $chats;
	}

	public function replyAction()
	{
		$id = $this->getRequest()->getParam('id');
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		if ($this->getRequest()->isPost()) {

			//reply yang lama udh jadi tapi ada bug
			// $isi = $this->getRequest()->getParam('isi');
			// $id_reply = $this->getRequest()->getParam('id_reply');
			
			// $last_id = $this->ChatMentoringService->replyMentoring($id,$id_reply,$user->id_mentor,'Mentor', $isi);

			// $this->_redirect('/mentor/forum-mentoring/reply/id/'.$id);

				$auth = Zend_Auth::getInstance()->getIdentity();
				$id_user = $this->getRequest()->getParam('id_user');
				$id_reply_mentoring = $this->getRequest()->getParam('id_reply_mentoring');
				$role_user = $this->getRequest()->getParam('role_user');
				$isi = $this->getRequest()->getParam('isi');

				// upload file tugas
				$file_mentoring = $_FILES['file_mentoring']['name'];
				$file_type = $_FILES['file_mentoring']['type'];
				$file_size = $_FILES['file_mentoring']['size'];
				$file_tmp = $_FILES['file_mentoring']['tmp_name'];
				$file_error = $_FILES['file_mentoring']['error'];
				
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
					$path = "//172.16.30.157/www/mooc/mentoring";
					$path_info = pathinfo($file_mentoring);
					$nama_materi_kata = explode(" ", $auth->id_peserta);
					$nama_materi_kata = $nama_materi_kata[0] . $nama_materi_kata[1];
					$file_mentoring = 'mentoring-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_mentoring);
				}
			
				$last_id = $this->ChatMentoringService->replyMentoring($id, $id_reply_mentoring, $id_user, $role_user, $isi, $file_mentoring);
							
				$this->_redirect('/mentor/forum-mentoring/reply/id/'.$id);
				header("Refresh: 1"); 


		} else {
			$this->view->rows = $this->MentoringService->getData($id);
			$coach_id = $this->PesertaBatchService->getDataBatch($this->view->rows->id_batch);

			$this->view->nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
			$this->view->id_coach = $coach_id->id_coach;

			$this->view->chats = $this->ChatMentoringService->getAllDataMentoringReply($id);
			// var_dump($this->view->chats);
			$this->view->reply =$this->ChatMentoringService->getAllDataReply($id);
			$this->view->nama = $this->MentorService->getData($user->id_mentor)->nama_mentor;
			$this->view->coach = $this->CoachService->getAllData();
			$this->view->mentor = $this->MentorService->getAllData();
			$this->view->peserta = $this->PesertaService->getAllData();
			$this->view->chat_mentorings = $this->ChatMentoringService->getAllDataMentoring($id);
			
			$auth = Zend_Auth::getInstance()->getIdentity();
			$this->view->peserta_batch = $this->PesertaBatchService->getAllDataMentor($auth->id_mentor);

			$this->view->batch = $this->BatchService->getData($this->view->rows->id_batch);
			$this->view->pelatihan = $this->PelatihanService->getData($this->view->batch->id_pelatihan);
		}
	}

	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->MentoringService->softDeleteData($id);

		$this->_redirect('/mentor/forum-diskusi/index');
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
		$chat_diskusi = $this->ChatMentoringService->getAllPesertaMentoring($id);
		$this->view->rows = $chat_diskusi;
	}

}
