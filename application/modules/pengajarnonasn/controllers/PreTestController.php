<?php
class Pengajarnonasn_PreTestController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow();
	}
	
	public function preDispatch() {
		$session = new Zend_Session_Namespace('loggedInUser');
        if (!isset($session->user)) {
            $this->_redirect('/loginselainasn');
        }
		$this->PreTestService = new PreTestService();
		$this->PelatihanService = new PelatihanService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->PreTestService->getAllData();
	}

	public function addAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$soal = $this->getRequest()->getParam('soal');
			$jawaban = $this->getRequest()->getParam('jawaban');
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');

			// $batas_waktu = NULL;
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');

			// upload file tugas
			$file_nontes = $_FILES['nontes']['name'];
			$file_type = $_FILES['nontes']['type'];
			$file_size = $_FILES['nontes']['size'];
			$file_tmp = $_FILES['nontes']['tmp_name'];
			$file_error = $_FILES['nontes']['error'];

			//Cek ukuran file tugas (maks 10mb)
			if($file_size > 10000000){
				echo "<script>
						alert('Maks. 10MB');
						window.location.href='/pengajarnonasn/pretest/edit/id/".$id."';
					</script>";
				return false;
			}

			// upload file
			if ($file_tmp) {
				$path_nontes = "//172.16.30.157/www/mooc/soalpretest";
				$path_info = pathinfo($file_nontes);

				// $nama_materi_kata = explode(" ", $file_nontes);
				// $nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_nontes = 'File-'. uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path_nontes . "/" . $file_nontes);
			}


			foreach ($soal as $idx => $soalnya) {
				$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban_'.($idx+1));
				$this->PreTestService->addData($id, $soalnya, $batas_waktu, $jawaban[$idx][0], $jawaban[$idx][1], $jawaban[$idx][2], $jawaban[$idx][3], $jawaban[$idx][4], $kunci_jawaban, $file_nontes);
			}
			
			$this->_redirect('/pengajarnonasn/pelatihan/edit/id/'.$id);
		} else {
			$this->view->rows = $this->PreTestService->getAllData();
		}
		
		$this->view->pelatihan = $this->PelatihanService->getData($id);
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$soal = $this->getRequest()->getParam('soal');
			$jawaban = $this->getRequest()->getParam('jawaban');
			// $batas_waktu = NULL;
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');


			// upload file tugas
			// $file_nontes = isset($_FILES['nontes']['name']) ? $_FILES['nontes']['name'] : '';
			$file_nontes = $_FILES['nontes']['name'];
			$file_type = $_FILES['nontes']['type'];
			$file_size = $_FILES['nontes']['size'];
			$file_tmp = $_FILES['nontes']['tmp_name'];
			$file_error = $_FILES['nontes']['error'];
			

			//Cek ukuran file tugas (maks 10mb)
			if($file_size > 10000000){
				echo "<script>
						alert('Maks. 10MB');
						window.location.href='/pengajarnonasn/pretest/edit/id/".$id."';
					</script>";
				return false;
			}

			// upload file pdf
			if ($file_tmp) {
				$path_nontes = "//172.16.30.157/www/mooc/soalpretest";
				$path_info = pathinfo($file_nontes);

				// $nama_materi_kata = explode(" ", $file_nontes);
				// $nama_materi_kata = $nama_materi_kata[0] . " " . $nama_materi_kata[1];
				$file_nontes = 'File-'. uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path_nontes . "/" . $file_nontes);
			}

		
			if($file_nontes == '' ){
				$file_nontes = $this->getRequest()->getParam('nontes');
			}


			$this->PreTestService->deletePelatihan($id);
			foreach ($soal as $idx => $soalnya) {
				$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban_'.($idx+1));
				$this->PreTestService->editData($id, $soalnya, $batas_waktu, $jawaban[$idx][0], $jawaban[$idx][1], $jawaban[$idx][2], $jawaban[$idx][3], $jawaban[$idx][4], $kunci_jawaban, $file_nontes);
			}
			
			$this->_redirect('/pengajarnonasn/pelatihan/edit/id/'.$id);
		} else {
			$this->view->rows = $this->PreTestService->getAllData();
		}
		
		$this->view->pelatihan = $this->PelatihanService->getData($id);
		$this->view->rows = $this->PreTestService->getAllDataPelatihan($id);
	}
	
	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PreTestService->deleteData($id);
		// $this->PreTestService->softDeleteData($id);

		$this->_redirect('/pengajarnonasn/materi-silabus-quiz/index');
	}

	public function deletefilesnontesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PreTestService->deleteFilesNontes($id);
		$this->_redirect('/pengajarnonasn/pretest/edit/id/' . $id); 
	}
}