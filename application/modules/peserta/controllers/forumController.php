<?php
	class Peserta_ForumController extends Zend_Controller_Action {

		public function init() {
			$this->_helper->_acl->allow();
		}

		public function preDispatch() {
			$this->BatchService = new BatchService();
			$this->PelatihanService = new PelatihanService();
			$this->PesertaBatchService = new PesertaBatchService();
			$this->PesertaService = new PesertaService();
			$this->CoachService = new CoachService();
			$this->MentorService = new MentorService();
			$this->ForumDiskusiService = new ForumDiskusiService();
			$this->ChatDiskusiService = new ChatDiskusiService();
		}

		public function indexAction() {
			$auth = Zend_Auth::getInstance()->getIdentity();

			$batchs = $this->BatchService->getAllData();
			$this->view->batchs = $batchs;
			$batch = $this->BatchService->getAllDataByPeserta($auth->id_peserta);
			$this->view->batch = $batch;
			$peserta_batchs = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batchs = $peserta_batchs;
			$pelatihans = $this->PelatihanService->getAllData();
			$this->view->pelatihans = $pelatihans;
			$coachs = $this->CoachService->getAllData();
			$this->view->coachs = $coachs;
			$forums = $this->ForumDiskusiService->getAllData();
			$this->view->forums = $forums;
			$chats = $this->ChatDiskusiService->getAllData();
			$this->view->chats = $chats;

			$this->view->page = 'Forum - ';
		}

		public function addAction() {
			if ($this->getRequest()->isPost()) {
				
				$title = $this->getRequest()->getParam('title');
				$id_batch = $this->getRequest()->getParam('id_batch');
				
				$last_id = $this->ForumDiskusiService->addData($title,$id_batch);
				

				$this->_redirect('/peserta/forum/edit/id/'.$last_id);
			} else {
				$this->view->forums = $this->ForumDiskusiService->getAllData();
			}
		}

		public function editAction() {
			$id = $this->getRequest()->getParam('id');

			if ($this->getRequest()->isPost()) {

				$title = $this->getRequest()->getParam('title');
				$id_batch = $this->getRequest()->getParam('id_batch');
				
				$last_id = $this->ForumDiskusiService->editData($id, $title, $id_batch);

				$this->_redirect('/peserta/forum/index');
			} else {
				$this->view->forums = $this->ForumDiskusiService->getData($id);
				$this->view->judul_batch = $this->BatchService->getData($this->view->forums->id_batch)->judul_batch;
			}
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

					$nama_materi_kata = explode(" ", $auth->id_peserta);
					$nama_materi_kata = $nama_materi_kata[0] . $nama_materi_kata[1];
					$file_forum = 'forum-'. $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

						move_uploaded_file($file_tmp, $path . "/" . $file_forum);
					}
					
					// $isi = array_filter($isi);
					// $isi = array_values($isi);
				
					// foreach ($isi as $value) {
						$this->ChatDiskusiService->replyData($id, $id_reply, $id_user, $role_user, $isi, $file_forum); 
					// }			

					$this->_redirect('/peserta/forum/reply/id/'. $id);
			} else {		
				$auth = Zend_Auth::getInstance()->getIdentity();

				$chats = $this->ChatDiskusiService->getAllDataForum($id);
				$this->view->chats = $chats;

				$coachs = $this->CoachService->getAllData();
				$this->view->coachs = $coachs;

				$mentors = $this->MentorService->getAllData();
				$this->view->mentors = $mentors;

				$pesertas = $this->PesertaService->getAllData();
				$this->view->pesertas = $pesertas;

				$forums = $this->ForumDiskusiService->getData($id);
				$this->view->forums = $forums;

				$coach_id = $this->PesertaBatchService->getDataBatch($this->view->forums->id_batch);
				$this->view->id_coach = $coach_id->id_coach;
				
				$pelatihan_id = $this->PesertaBatchService->getDataBatch($this->view->forums->id_batch);
				$this->view->id_pelatihan = $pelatihan_id->id_pelatihan;
				
				$nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
				$this->view->nama_coach = $nama_coach;

				$fotocoach_uri = $this->CoachService->getData($coach_id->id_coach)->fotocoach_uri;
				$this->view->fotocoach_uri = $fotocoach_uri;

				$nama_pelatihan = $this->PelatihanService->getData($pelatihan_id->id_pelatihan)->nama_pelatihan;
				$this->view->nama_pelatihan = $nama_pelatihan;

				$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);
				$this->view->search_peserta = $this->PesertaService->getAllDataBatch($peserta_id);

				$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
				$this->view->search_mentor = $this->MentorService->getAllDataBatch($mentor_id);

				$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
				$this->view->peserta_batch = $peserta_batch;

				// data chat
				$chatsData = [];
				$lastChat = null;

				foreach ($chats as $chat) {
					// Simpan data saat ini ke variabel $lastChat
					$lastChat = [
						'id' => $chat->id,
						'id_forum' => $chat->id_forum,
						'id_reply' => $chat->id_reply,
						'id_user' => $chat->id_user,
						'role_user' => $chat->role_user,
						'isi' => $chat->isi,
						'file_forum' => $chat->file_forum,
						'tanggal_input' => $chat->tanggal_input,
					];
				}

				// Tambahkan data terakhir ke array $chatsData
				if ($lastChat) {
					$chatsData[] = $lastChat;
				}

				// data perserta
				$pesertasData = [];
				foreach($pesertas as $peserta) { 
					$pesertasData[] = array(
						'id' => $peserta->id,
						'nama' => $peserta->nama,
					);
				}
			
				// data coach
				$coachsData = [];
				foreach($coachs as $coach) { 
					$coachsData[] = array(
						'id' => $coach->id,
						'nama_coach' => $coach->nama_coach,
					);
				}
			
				// data mentor
				$mentorsData = [];
				foreach($mentors as $mentor) { 
					$mentorsData[] = array(
						'id' => $mentor->id,
						'nama_mentor' => $mentor->nama_mentor,
					);
				}

				$response = [
					'auth' => $auth,
					'chats' => $chatsData,
					'coachs' => $coachsData,
					'mentors' => $mentorsData,
					'pesertas' => $pesertasData,				
				];

				// Mengirimkan response sebagai JSON
				if ($this->getRequest()->isXmlHttpRequest()) {
					$this->_helper->json($response);
					return;
				}
			}
		}

		public function getChatsDataAction() {
			$chatsData = [];
			$chats = $this->ChatDiskusiService->getAllData();

			foreach ($chats as $chat) {
				$chatsData[] = [
					'id' => $chat->id,
					'id_forum' => $chat->id_forum,
					'id_reply' => $chat->id_reply,
					'id_user' => $chat->id_user,
					'role_user' => $chat->role_user,
					'isi' => $chat->isi,
					'file_forum' => $chat->file_forum,
					'tanggal_input' => $chat->tanggal_input,
				];
			}

			return $chatsData;
		}

		public function deleteAction() {
			$id = $this->getRequest()->getParam('id');
			$this->ForumDiskusiService->softDeleteData($id);

			$this->_redirect('/peserta/forum/index');
		}

		public function searchBatchAction() {
			$this->_helper->getHelper('layout')->disableLayout();
			$this->view->forums = $this->BatchService->getAllData();
		}

		public function searchMentorAction() {
			$id = $this->getRequest()->getParam('id');
			$this->_helper->getHelper('layout')->disableLayout();
			$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
			$this->view->forums = $this->MentorService->getAllDataBatch($mentor_id);
		}
	}
?>
