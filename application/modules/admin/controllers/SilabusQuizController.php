<?php
class Admin_SilabusQuizController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->SilabusQuizService = new SilabusQuizService();
		$this->PelatihanService = new PelatihanService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->SilabusQuizService->getAllData();
	}

	public function addAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$soal = $this->getRequest()->getParam('soal');
			$jawaban = $this->getRequest()->getParam('jawaban');
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');
			foreach ($soal as $idx => $soalnya) {
				$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban_'.($idx+1));
				$this->SilabusQuizService->addData($id, $soalnya, $batas_waktu, $jawaban[$idx][0], $jawaban[$idx][1], $jawaban[$idx][2], $jawaban[$idx][3], $jawaban[$idx][4], $kunci_jawaban);
			}
			
			$this->_redirect('/admin/pelatihan/edit/id/'.$id);
		} else {
			$this->view->rows = $this->SilabusQuizService->getAllData();
		}
		
		$this->view->pelatihan = $this->PelatihanService->getData($id);
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');

		if ($this->getRequest()->isPost()) {
			$soal = $this->getRequest()->getParam('soal');
			$jawaban = $this->getRequest()->getParam('jawaban');
			$batas_waktu = $this->getRequest()->getParam('batas_waktu');
			$this->SilabusQuizService->deletePelatihan($id);
			foreach ($soal as $idx => $soalnya) {
				$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban_'.($idx+1));
				$this->SilabusQuizService->editData($id, $soalnya, $batas_waktu, $jawaban[$idx][0], $jawaban[$idx][1], $jawaban[$idx][2], $jawaban[$idx][3], $jawaban[$idx][4], $kunci_jawaban);
			}
			
			$this->_redirect('/admin/pelatihan/edit/id/'.$id);
		} else {
			$this->view->rows = $this->SilabusQuizService->getAllData();
		}
		
		$this->view->pelatihan = $this->PelatihanService->getData($id);
		$this->view->rows = $this->SilabusQuizService->getAllDataPelatihan($id);
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->SilabusQuizService->deleteFiles($id);
		$this->_redirect('/admin/materi-silabus-quiz/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->SilabusQuizService->deleteData($id);
		// $this->SilabusQuizService->softDeleteData($id);

		$this->_redirect('/admin/materi-silabus-quiz/index');
	}
}