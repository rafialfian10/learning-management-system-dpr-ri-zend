<?php
	class Peserta_PelatihanSayaController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow();
		}
	
		public function preDispatch() {
			$this->SilabusService = new SilabusService();
			$this->NontesService = new NontesService();
			$this->PretestService = new PreTestService();
			$this->SkorPretestService = new SkorPretestService();

			$this->PelatihanService = new PelatihanService();
			$this->BatchService = new BatchService();
			$this->MateriSilabusService = new MateriSilabusService();

			$this->ProgressService = new ProgressService();
			$this->RiwayatService = new RiwayatService();
			
			$this->SilabusQuizService = new SilabusQuizService();
			$this->SkorBelajarService = new SkorBelajarService();
			
			$this->MentoringService = new MentoringService();
			$this->ChatMentoringService = new ChatMentoringService();
			$this->SkorMentoringService = new SkorMentoringService();
			$this->SkorNontestService = new SkorNontestService();
			$this->PenugasanService = new PenugasanService();
			$this->RatingService = new RatingService();

			$this->PesertaBatchService = new PesertaBatchService();
			$this->PesertaService = new PesertaService();
			$this->MentorService = new MentorService();
			$this->CoachService = new CoachService();
			$this->PengajarService = new PengajarService();

			$this->SertifikatService = new SertifikatService();
		}

		public function ajaxAction()  {
			if ($this->getRequest()->isPost()) {
				// Lakukan proses
				$data['id'] = 51;
				$data['nilai'] = 69;
				$result = $data; // Hasil proses
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);
				// Konversi data ke format JSON
				$responseData = ['result' => $result];
				$jsonResponse = Zend_Json::encode($responseData);
				
				// Set header content-type sebagai JSON
				$this->getResponse()
					->setHeader('Content-Type', 'application/json')
					->setBody($jsonResponse);
				
				return $this->getResponse();
			}
		}

		public function menyerahAction() {
			if ($this->getRequest()->isPost()) {
				$id = $this->getRequest()->getPost('id');
				$id_peserta = $this->getRequest()->getPost('id_peserta');
				$id_batch = $this->getRequest()->getPost('id_batch');
				$statusProgress = $this->getRequest()->getPost('status_progress');
				$statusRiwayat = $this->getRequest()->getPost('status_riwayat');

				$this->RiwayatService->addData($id, $id_peserta, $id_batch, $statusProgress, $statusRiwayat);
				$this->ProgressService->softDeleteData($id);
				$this->PesertaBatchService->softDeleteData2($id_batch,$id_peserta);
			}
		}
	
		public function selesaipelatihanAction() {
			if ($this->getRequest()->isPost()) {
				$id = $this->getRequest()->getPost('id');
				$id_peserta = $this->getRequest()->getPost('id_peserta');
				$id_batch = $this->getRequest()->getPost('id_batch');
				$statusProgress = $this->getRequest()->getPost('status_progress');
				$statusRiwayat = $this->getRequest()->getPost('status_riwayat');

				$this->RiwayatService->addData($id, $id_peserta, $id_batch, $statusProgress, $statusRiwayat);
				$this->ProgressService->softDeleteData($id);
				$this->PesertaBatchService->softDeleteData2($id_batch,$id_peserta);
			}
		}

		public function pretestAction()  {
			$auth = Zend_Auth::getInstance()->getIdentity();
			$id = $this->getRequest()->getParam('id');
			$batch = $this->BatchService->getData($id);	
			$nontes = $this->NontesService->getDataNontest($batch->id_pelatihan);
			$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			
			if($progress->status_progress == "Peserta"){
				$this->ProgressService->editData($progress->id, 'Pretest');
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			}
			
			if ($this->getRequest()->isPost()) {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$jawaban = $this->getRequest()->getParam('jawaban');
				$id = $this->getRequest()->getParam('id');
				$batch = $this->BatchService->getData($id);
				$soal = $this->PretestService->getAllDataPelatihan($batch->id_pelatihan);
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);

				if ($_FILES['jawaban_nontes']['name']) {
					$jawaban_nontes = $_FILES['jawaban_nontes']['name'];
					$file_type = $_FILES['jawaban_nontes']['type'];
					$file_size = $_FILES['jawaban_nontes']['size'];
					$file_tmp = $_FILES['jawaban_nontes']['tmp_name'];
					$file_error = $_FILES['jawaban_nontes']['error'];
		
					if ($file_size > 128000000) {
						echo "<script>
								Swal.fire({
									icon: 'warning',
									title: 'Warning',
									html: 'Ukuran file terlalu besar (maks 128 MB)',
									confirmButtonColor: '#4CAF50',
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
		
					$file_extension = pathinfo($jawaban_nontes, PATHINFO_EXTENSION);
					$allowed_extensions = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'png', 'jpeg', 'mp3', 'wav', 'mp4', 'mkv');
					if (!in_array($file_extension, $allowed_extensions)) {
						echo "<script>
								Swal.fire({
									icon: 'warning',
									title: 'Warning',
									html: 'Ekstensi file tidak valid (pdf, doc, docx, ppt, pptx, txt, jpg, png, jpeg, mp3, wav, mp4, mkv)',
									confirmButtonColor: '#4CAF50',
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
		
					$path = "//172.16.30.157/www/mooc/jawabanpretest";
					$nama_materi_kata = explode(" ", $auth->id_peserta);
					$nama_materi_kata = $nama_materi_kata[0] . "" . $nama_materi_kata[1];
					$jawaban_nontes = 'jawaban-nontes-' . $auth->nama . '-' . uniqid() . '.' . $file_extension;
		
					move_uploaded_file($file_tmp, $path . "/" . $jawaban_nontes);
		
					$this->SkorNontestService->submitTugas($auth->id_peserta, $batch->id, $batch->id_pelatihan, $nontes->title, $nontes->file_nontes, $jawaban_nontes, $file_extension);
					$this->_redirect('peserta/pelatihan-saya/pretest/id/'. $id .' ');
					return;
				}

				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
				$this->ProgressService->editData($progress->id, 'Penilaian');

				$db_jawaban = '';
				$db_kunci = '';

				$nilai = 0;
				$jumlah = 0;

				foreach($soal as $key => $val){
					if($val->kunci_jawaban == $jawaban[$key]){
						$nilai++;
					}
					$jumlah++;

					if($key == 0){
						$db_kunci .= $val->kunci_jawaban;
						$db_jawaban .= $jawaban[$key];
					} else {
						$db_kunci .= ','.$val->kunci_jawaban;
						$db_jawaban .= ','.$jawaban[$key];
					}
				}
				
				$db_nilai = number_format((($nilai/$jumlah) * 100), 0);
				$cek = $this->SkorPretestService->cekNilai($id, $auth->id_peserta, $batch->id_pelatihan);

				if(!($cek->skor_akhir)){
					$this->ProgressService->editData($progress->id, 'Materi-1-1');
					$this->SkorPretestService->addNilai($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
				}

				$data['id'] = $id;
				$data['nilai'] =$db_nilai;

				$result = $data; // Hasil proses
				
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);
				
				$responseData = ['result' => $result];
				$jsonResponse = Zend_Json::encode($responseData);
				
				$this->getResponse()
					->setHeader('Content-Type', 'application/json')
					->setBody($jsonResponse);
				
				return $this->getResponse();
			} else {
				$batch = $this->BatchService->getData($id);
				$auth = Zend_Auth::getInstance()->getIdentity();

				$this->view->skor = $this->SkorPretestService->getDataSkor($auth->id_peserta, $batch->id);
				$this->view->soal = $this->PretestService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->nontes = $this->NontesService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->skor_nontes = $this->SkorNontestService->getDataSkorNontest($auth->id_peserta, $batch->id_pelatihan);
				$this->view->pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
				$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->batch = $batch;
				$this->view->progress = $progress;
			}	
		}

		public function postestAction()  {
			$auth = Zend_Auth::getInstance()->getIdentity();
			$id = $this->getRequest()->getParam('id');
			$batch = $this->BatchService->getData($id);
			$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			$statusprogress = explode("-", $progress->status_progress);
			

			if($statusprogress[0] == 'Materi'){
				$this->ProgressService->editData($progress->id, 'Postest');
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			}
			
			if ($this->getRequest()->isPost()) {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$jawaban = $this->getRequest()->getParam('jawaban');
				$id = $this->getRequest()->getParam('id');
				$batch = $this->BatchService->getData($id);
				$pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
				$soal = $this->PretestService->getAllDataPelatihan($batch->id_pelatihan);
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
				$this->ProgressService->editData($progress->id, 'Penilaian');

				$db_jawaban = '';
				$db_kunci = '';

				$nilai = 0;
				$jumlah = 0;

				foreach($soal as $key => $val){
					if($val->kunci_jawaban == $jawaban[$key]){
						$nilai++;
					}
					$jumlah++;

					if($key == 0){
						$db_kunci .= $val->kunci_jawaban;
						$db_jawaban .= $jawaban[$key];
					} else {
						$db_kunci .= ','.$val->kunci_jawaban;
						$db_jawaban .= ','.$jawaban[$key];
					}
				}
				
				$db_nilai = number_format((($nilai/$jumlah) * 100), 0);
				$cek = $this->SkorBelajarService->cekNilai($id, $auth->id_peserta, $batch->id_pelatihan);
				
				if(!($cek->skor_akhir)){
					if($pelatihan->tipe_pelatihan != "classical") {
						$this->ProgressService->editData($progress->id, 'Mentoring');
						$this->SkorBelajarService->addNilai($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
					} else {
						$this->ProgressService->editData($progress->id, 'Penugasan');
						$this->SkorBelajarService->addNilai($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
					}
				}

				$data['id'] = $id;
				$data['nilai'] =$db_nilai;

				$result = $data; // Hasil proses
				
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);
				
				$responseData = ['result' => $result];
				$jsonResponse = Zend_Json::encode($responseData);
				
				$this->getResponse()
					->setHeader('Content-Type', 'application/json')
					->setBody($jsonResponse);
				
				return $this->getResponse();
			} else {
				$batch = $this->BatchService->getData($id);
				$auth = Zend_Auth::getInstance()->getIdentity();

				$this->view->skor = $this->SkorBelajarService->getDataSkor($auth->id_peserta, $batch->id);
				$this->view->soal = $this->PretestService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
				$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->batch = $batch;
				$this->view->progress = $progress;
			}	
		}

		public function quizAction()  {
			$id = $this->getRequest()->getParam('id');
			$batch = $this->BatchService->getData($id);
			$this->view->batch = $batch;

			if ($this->getRequest()->isPost()) {
				
				$auth = Zend_Auth::getInstance()->getIdentity();
				
				$jawaban = $this->getRequest()->getParam('jawaban');
				$soal = $this->PretestService->getAllDataPelatihan($batch->id_pelatihan);
	
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);

				$db_jawaban = '';
				$db_kunci = '';

				$nilai = 0;
				$jumlah = 0;

				foreach($soal as $key => $val){
					if($val->kunci_jawaban == $jawaban[$key]){
						$nilai++;
					}
					$jumlah++;

					if($key == 0){
						$db_kunci .= $val->kunci_jawaban;
						$db_jawaban .= $jawaban[$key];
					} else {
						$db_kunci .= ','.$val->kunci_jawaban;
						$db_jawaban .= ','.$jawaban[$key];
					}
				}

				$db_nilai = number_format((($nilai/$jumlah) * 100), 0);
				$cek = $this->SkorBelajarService->cekNilai($id, $auth->id_peserta, $batch->id_pelatihan);

				if(!($cek->skor_akhir)){
					$this->ProgressService->editData($progress->id, 'Mentoring');
					$this->SkorBelajarService->addNilai($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
				}

				$data['id'] = $id;
				$data['nilai'] =$db_nilai;

				$result = $data; // Hasil proses
				
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender(true);
				
				$responseData = ['result' => $result];
				$jsonResponse = Zend_Json::encode($responseData);
				
				$this->getResponse()
					->setHeader('Content-Type', 'application/json')
					->setBody($jsonResponse);
				
				return $this->getResponse();

				header("Refresh: 1");
			} else {
				$batch = $this->BatchService->getData($id);
				$auth = Zend_Auth::getInstance()->getIdentity();

				$this->view->skor = $this->SkorBelajarService->getDataSkor($auth->id_peserta, $batch->id);
				$this->view->soal = $this->SilabusQuizService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$this->view->pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
				$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
				$progress = $this->view->progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			}
		}
	
		public function belajarAction()  {	
			$id = $this->getRequest()->getParam('id');
			$silabus = $this->getRequest()->getParam('silabus');
			$materi = $this->getRequest()->getParam('materi');
			
			if ($this->getRequest()->isPost()) {
				$id_silabus = $this->getRequest()->getParam('id_silabus');
				$id_materi = $this->getRequest()->getParam('id_materi');
				$id_progress = $this->getRequest()->getParam('id_progress');
				if($id_silabus == 'postest'){
					$this->ProgressService->editData($id_progress, 'Postest');
				} else {
					$this->ProgressService->editData($id_progress, 'Materi-'.$id_silabus.'-'.$id_materi);
				}
			} else {
				$batch = $this->BatchService->getData($id);
				$auth = Zend_Auth::getInstance()->getIdentity();
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
				
				$this->view->batch = $batch;
				$this->view->progress = $progress;
				
				$this->view->pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
				$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
				

				$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);

				$get_silabus = $this->SilabusService->getDataUrutan($batch->id_pelatihan, $silabus);
				$get_materi = $this->MateriSilabusService->getDataUrutan($batch->id_pelatihan, $get_silabus->id, $materi);
				
				$this->view->get_materi = $get_materi;
			}
		}

		public function mentoringAction()  {
			$auth = Zend_Auth::getInstance()->getIdentity();
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

			$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batch = $peserta_batch;
			
			$this->view->batch = $this->BatchService->getData($id);
			
			$batch_id = $this->BatchService->getData($id);
			$this->view->pelatihan = $this->PelatihanService->getData($batch_id->id_pelatihan);
			$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch_id->id_pelatihan);
			$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch_id->id_pelatihan); 
			$this->view->skor_mentoring = $this->SkorMentoringService->getDataScoreMentoring($batch_id->id, $auth->id_peserta); 
			$this->view->progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			
		}

		public function replyMentoringAction() {
			$id = $this->getRequest()->getParam('id');
			$id_batch = $this->getRequest()->getParam('batch');
			$auth = Zend_Auth::getInstance()->getIdentity();
			
			if ($this->getRequest()->isPost()) {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$id_user = $this->getRequest()->getParam('id_user');
				$id_reply_mentoring = $this->getRequest()->getParam('id_reply_mentoring');
				$role_user = $this->getRequest()->getParam('role_user');
				$isi = $this->getRequest()->getParam('isi');

				$file_mentoring = $_FILES['file_mentoring']['name'];
				$file_type = $_FILES['file_mentoring']['type'];
				$file_size = $_FILES['file_mentoring']['size'];
				$file_tmp = $_FILES['file_mentoring']['tmp_name'];
				$file_error = $_FILES['file_mentoring']['error'];
				
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

				if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/mentoring";
					$path_info = pathinfo($file_mentoring);
					$nama_materi_kata = explode(" ", $auth->id_peserta);
					$nama_materi_kata = $nama_materi_kata[0] . $nama_materi_kata[1];
					$file_mentoring = 'mentoring-' . $nama_materi_kata . '-' . uniqid() . '.' . $path_info['extension'];

					move_uploaded_file($file_tmp, $path . "/" . $file_mentoring);
				}
			
				$last_id = $this->ChatMentoringService->replyMentoring($id, $id_reply_mentoring, $id_user, $role_user, $isi, $file_mentoring);
							
				$this->_redirect("/peserta/pelatihan-saya/reply-mentoring/batch/$id_batch/id/$id");

				header("Refresh: 1"); 
			} else {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$batch_id = $this->BatchService->getData($id_batch);
				$peserta_id = $this->PesertaBatchService->getAllDataBatch($id);

				$mentoring = $this->MentoringService->getData($id);
				$this->view->mentoring = $mentoring;

				$coach_id = $this->PesertaBatchService->getDataBatch($this->view->mentoring->id_batch);
				$this->view->id_coach = $coach_id->id_coach;

				$pelatihan_id = $this->PesertaBatchService->getDataBatch($this->view->mentoring->id_batch);
				$this->view->id_pelatihan = $pelatihan_id->id_pelatihan;

				$nama_coach = $this->CoachService->getData($coach_id->id_coach)->nama_coach;
				$this->view->nama_coach = $nama_coach;

				$fotocoach_uri = $this->CoachService->getData($coach_id->id_coach)->fotocoach_uri;
				$this->view->fotocoach_uri = $fotocoach_uri;

				$nama_pelatihan = $this->PelatihanService->getData($pelatihan_id->id_pelatihan)->nama_pelatihan;
				$this->view->nama_pelatihan = $nama_pelatihan;

				$search_peserta = $this->PesertaService->getAllDataBatch($peserta_id);
				$this->view->search_peserta = $search_peserta;

				$mentor_id = $this->PesertaBatchService->getAllDataBatch($id);
				$this->view->search_mentor = $this->MentorService->getAllDataBatch($mentor_id);

				$chat_mentorings = $this->ChatMentoringService->getAllDataMentoring($id);
				$this->view->chat_mentorings = $chat_mentorings;

				$reply_mentorings =$this->ChatMentoringService->getAllDataReply($id);
				$this->view->reply_mentorings = $reply_mentorings;

				$coachs = $this->CoachService->getAllData();
				$this->view->coachs = $coachs;
				
				$mentors = $this->MentorService->getAllData();
				$this->view->mentors = $mentors;

				$pesertas = $this->PesertaService->getAllData();
				$this->view->pesertas = $pesertas;

				$peserta_batch = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
				$this->view->peserta_batch = $peserta_batch;

				$batch = $this->BatchService->getData($id_batch);
				$this->view->batch = $batch;

				$pelatihan = $this->PelatihanService->getData($batch_id->id_pelatihan);
				$this->view->pelatihan = $pelatihan;

				$silabus = $this->SilabusService->getAllDataPelatihan($batch_id->id_pelatihan);
				$this->view->silabus = $silabus;

				$materi = $this->MateriSilabusService->getAllDataPelatihan($batch_id->id_pelatihan); 
				$this->view->materi = $materi;

				$skor_mentoring = $this->SkorMentoringService->getDataScoreMentoring($batch_id->id, $auth->id_peserta); 
				$this->view->skor_mentoring = $skor_mentoring;

				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id_batch);
				$this->view->progress = $progress;

				// data chat
				$chatsMentoringData = [];
				$lastChatMentoringData = null;

				foreach ($chat_mentorings as $chat_mentoring) {
					// Simpan data saat ini ke variabel lastChatMentoringData
					$lastChatMentoringData = [
						'id' => $chat_mentoring->id,
						'id_mentoring' => $chat_mentoring->id_mentoring,
						'id_reply_mentoring' => $chat_mentoring->id_reply_mentoring,
						'id_user' => $chat_mentoring->id_user,
						'role_user' => $chat_mentoring->role_user,
						'isi' => $chat_mentoring->isi,
						'file_mentoring' => $chat_mentoring->file_mentoring,
						'tanggal_input' => $chat_mentoring->tanggal_input,
					];
				}

				// Tambahkan data terakhir ke array chatsMentoringData
				if ($lastChatMentoringData) {
					$chatsMentoringData[] = $lastChatMentoringData;
				}

				$response = [
					'chats_mentoring' => $chatsMentoringData,				
				];

				// Mengirimkan response sebagai JSON
				if ($this->getRequest()->isXmlHttpRequest()) {
					$this->_helper->json($response);
					return;
				}
			}
		}

		public function penugasanAction() {	
			$auth = Zend_Auth::getInstance()->getIdentity();
			$id = $this->getRequest()->getParam('id');

			$penugasan = $this->PenugasanService->getDataTugas($auth->id_peserta, $id);
			$this->view->penugasan = $penugasan;

			$this->view->batch = $this->BatchService->getData($id);
			$batch_id = $this->BatchService->getData($id);
			$this->view->pelatihan = $this->PelatihanService->getData($batch_id->id_pelatihan);
			$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch_id->id_pelatihan);
			$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch_id->id_pelatihan); 
			$this->view->progress = $this->ProgressService->getProgress($auth->id_peserta, $id);


			if ($this->getRequest()->isPost() && !($penugasan->skor_akhir)) {
				$title = $penugasan->title;
				$deskripsi_tugas = $this->getRequest()->getPost('deskripsi_tugas');

				$file_tugas = $_FILES['file_tugas']['name'];
				$file_type = $_FILES['file_tugas']['type'];
				$file_size = $_FILES['file_tugas']['size'];
				$file_tmp = $_FILES['file_tugas']['tmp_name'];
				$file_error = $_FILES['file_tugas']['error'];

				if($file_size > 128000000){
					echo "<script>
							Swal.fire({
								icon: 'warning',
								title: 'Warning',
								html: 'Ukuran file terlalu besar (maks 128 MB)',
								confirmButtonColor: '#4CAF50',
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
				
				$file_extension = pathinfo($file_tugas, PATHINFO_EXTENSION);
				$allowed_extensions = array('pdf', 'doc', 'docx', 'ppt', 'pptx', 'txt', 'jpg', 'png', 'jpeg', 'mp3', 'wav', 'mp4', 'mkv');
				if(!in_array($file_extension, $allowed_extensions)){
					echo "<script>
							Swal.fire({
								icon: 'warning',
								title: 'Warning',
								html: 'Ekstensi file tidak valid (pdf, doc, docx, ppt, pptx, txt, jpg, png, jpeg, mp3, wav, mp4, mkv)',
								confirmButtonColor: '#4CAF50',
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

				if ($file_tmp) {
					$path = "//172.16.30.157/www/mooc/penugasan";
					$nama_materi_kata = explode(" ", $title);
					$nama_materi_kata = $nama_materi_kata[0] . "" . $nama_materi_kata[1];
					$file_name = 'jawaban-penugasan-' . $auth->nama .'-'. uniqid() . '.' . $file_extension;
					move_uploaded_file($file_tmp, $path . "/" . $file_name);	
				}
				$progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
				$this->ProgressService->editData($progress->id, 'Penilaian');

				$this->PenugasanService->submitTugas($penugasan->id, $deskripsi_tugas, $file_name, $file_extension);
				echo"<script>
						Swal.fire({
							icon: 'success',
							title: 'Success',
							html: 'Tugas berhasil di upload',
							confirmButtonColor: '#4CAF50',
							customClass: {
								popup: 'my-custom-popup-class',
								title: 'my-custom-title-class',
								content: 'my-custom-content-class',
								confirmButton: 'confirm-button-class'
							}
						});
					</script>";

				header("Refresh: 1"); 
			} else {
				$this->view->rows = $this->PenugasanService->getDataTugas($auth->id_peserta, $id);
			}
		}

		public function sertifikatAction() {
			$id = $this->getRequest()->getParam('id');
			$auth = Zend_Auth::getInstance()->getIdentity();
			
			$this->view->rows1 = $this->SilabusService->getAllDataPelatihan($id);
			$this->view->rows2 = $this->PelatihanService->getData3($id);
			$this->view->rows3 = $this->MateriSilabusService->getAllData();
			$this->view->progress = $this->ProgressService->getProgress($auth->id_peserta, $id);
			$this->view->riwayat = $this->RiwayatService->getRiwayat($auth->id_peserta, $id);
			$this->view->id_peserta = $auth->id_peserta;
			
			$batchs = $this->BatchService->getAllData();
			$this->view->batchs = $batchs;

			$batch = $this->BatchService->getDataForSertifikat($id);
			$this->view->batch = $batch;

			$pelatihans = $this->PelatihanService->getAllData();
			$this->view->pelatihans = $pelatihans;

			$pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
			$this->view->pelatihan = $pelatihan;

			$silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$this->view->silabus = $silabus;

			$materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$this->view->materi = $materi;

			$pretest = $this->SkorPretestService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->pretest = $pretest;

			$posttest = $this->SkorBelajarService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->posttest = $posttest;

			$penugasan = $this->PenugasanService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->penugasan = $penugasan;

			$belajar = $this->SkorBelajarService->getDataSertifikat($batch->id, $auth->id_peserta);
			$this->view->belajar = $belajar;

			$pengajars = $this->PengajarService->getAllData();
			$this->view->pengajars = $pengajars;

			$pengajar = $this->PengajarService->getData($pelatihan->id_pengajar);
			$this->view->pengajar = $pengajar;

			$mentors = $this->MentorService->getAllData();
			$this->view->mentors = $mentors;

			$pesertas = $this->PesertaService->getAllData();
			$this->view->pesertas = $pesertas;

			$peserta = $this->PesertaService->getData($auth->id_peserta);
			$this->view->peserta = $peserta;

			$sertifikats = $this->SertifikatService->getAllData();
			$this->view->sertifikats = $sertifikats;

			$peserta_batchs = $this->PesertaBatchService->getAllDataPeserta($auth->id_peserta);
			$this->view->peserta_batchs = $peserta_batchs;

			$this->view->mentoring = $this->SkorMentoringService->getDataSertifikat($id, $auth->id_peserta);

			if ($this->getRequest()->isPost()) {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$id_peserta = $this->getRequest()->getPost('id_peserta');
				$id_batch = $this->getRequest()->getPost('id_batch');
				$id_pelatihan = $this->getRequest()->getPost('id_pelatihan');
				$star = $this->getRequest()->getPost('star');

				$this->RatingService->addData($auth->id_peserta, $id_batch, $id_pelatihan, $star);

				$this->_redirect("/peserta/pelatihan-saya/sertifikat/id/". $id);

				header("Refresh: 1"); 
			} else {
				$auth = Zend_Auth::getInstance()->getIdentity();
				$id = $this->getRequest()->getParam('id');

				$batch = $this->BatchService->getData($id);
				$this->view->batch = $batch;

				$rating = $this->RatingService->getRatingPeserta($auth->id_peserta, $batch->id);
				$this->view->rating = $rating;
			}
		}

		public function fileAction() {
			$this->_helper->getHelper('layout')->disableLayout();
			$this->view->id = $this->getRequest()->getParam('id');
			$this->view->id_batch = $this->getRequest()->getParam('batch');
			$this->view->id_peserta = $this->getRequest()->getParam('peserta');
		}

		public function materiAction()  {	
			$id = $this->getRequest()->getParam('id');
			$silabus = $this->getRequest()->getParam('silabus');
			$materi = $this->getRequest()->getParam('materi');
			$batch = $this->BatchService->getData($id);
			$this->view->batch = $batch;
			$auth = Zend_Auth::getInstance()->getIdentity();

			$this->view->silabus = $this->SilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$this->view->pelatihan = $this->PelatihanService->getData($batch->id_pelatihan);
			$this->view->materi = $this->MateriSilabusService->getAllDataPelatihan($batch->id_pelatihan);
			$get_silabus = $this->SilabusService->getDataUrutan($batch->id_pelatihan, $silabus);
			$get_materi = $this->MateriSilabusService->getDataUrutan($batch->id_pelatihan, $get_silabus->id, $materi);

			$this->view->rows2 = $this->PelatihanService->getAllData();
			$this->view->rows3 = $this->MateriSilabusService->getAllData();
			$this->view->rows4 = $this->MateriSilabusService->getData($konten);
			$this->view->rows5 = $this->PelatihanService->getData3($id);
			$this->view->rows6 = $this->SilabusService->getAllDataSilabus($silabus);
		}

		public function suratAction() {
			$this->view->id = $this->getRequest()->getParam('id');
			$this->_helper->getHelper('layout')->disableLayout();
		}

		public function deleteFilesAction() {
			$id = $this->getRequest()->getParam('id');
			$file = $this->getRequest()->getParam('file');
			$this->PenugasanService->deletefiles($file);
			$this->_redirect('/peserta/pelatihan-saya/penugasan/id/' . $id);
		}

		public function deleteFilesJawabanNontestAction() {
			$id = $this->getRequest()->getParam('id');
			$auth = Zend_Auth::getInstance()->getIdentity();
			$batch = $this->SkorNontestService->getDataSkorNontestBatch($id, $auth->id_peserta);
		
			$this->SkorNontestService->deleteFilesJawabanNontest($id);
			$this->_redirect('/peserta/pelatihan-saya/pretest/id/' . $batch->id_batch);
		}
	}
?>