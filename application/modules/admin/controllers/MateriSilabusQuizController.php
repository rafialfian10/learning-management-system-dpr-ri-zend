<?php
class Admin_MateriSilabusQuizController extends Zend_Controller_Action {
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->MateriSilabusQuizService = new MateriSilabusQuizService();
		$this->MateriSilabusService = new MateriSilabusService();
		$this->SilabusQuizService = new SilabusQuizService();
		$this->PengajarService = new PengajarService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->MateriSilabusQuizService->getAllData();
		$this->view->rows2 = $this->MateriSilabusService->getAllData();
		$this->view->rows3 = $this->PengajarService->getAllData();
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {

			$soal = $this->getRequest()->getParam('soal');
			$jawaban = $this->getRequest()->getParam('jawaban');
			foreach ($soal as $idx => $soalnya) {
				$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban_'.$idx);
				$this->SilabusQuizService->addData('1', $soalnya, $jawaban[$idx][0], $jawaban[$idx][1], $jawaban[$idx][2], $jawaban[$idx][3], $jawaban[$idx][4], $kunci_jawaban);
			}
			
			$this->_redirect('/admin/materi-silabus-quiz/index');
		} else {
			$this->view->rows = $this->MateriSilabusQuizService->getAllData();
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$this->view->rows = $this->MateriSilabusQuizService->getAllData();
		$row=$this->MateriSilabusQuizService->getData($id);
		$this->view->row = $row;

		if ($this->getRequest()->isPost()) {
			$id_materi_silabus = $this->getRequest()->getParam('id_materi_silabus');
			$pertanyaan = $this->getRequest()->getParam('pertanyaan');
			$jawaban1 = $this->getRequest()->getParam('jawaban1');
			$jawaban2 = $this->getRequest()->getParam('jawaban2');
			$jawaban3 = $this->getRequest()->getParam('jawaban3');
			$jawaban4 = $this->getRequest()->getParam('jawaban4');
			$jawaban5 = $this->getRequest()->getParam('jawaban5');
			$kunci_jawaban = $this->getRequest()->getParam('kunci_jawaban');
			$id_pengajar = $this->getRequest()->getParam('id_pengajar');
			
			try {
				$this->MateriSilabusQuizService->editData($id, $id_materi_silabus, $pertanyaan,  $jawaban1, $jawaban2, $jawaban3, $jawaban4, $jawaban5, $kunci_jawaban, $id_pengajar);
				$this->redirect('/admin/materi-silabus-quiz/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/admin/materi-silabus-quiz/index');
			}
				
			$row = $this->MateriSilabusQuizService->getData($id);
			$this->view->row = $row;
			$this->view->nama_materi = $this->MateriSilabusService->getData($row->id_materi_silabus)->nama_materi;
			$this->view->nama_pengajar = $this->PengajarService->getData($row->id_pengajar)->nama_pengajar;
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MateriSilabusQuizService->deleteFiles($id);
		$this->_redirect('/admin/materi-silabus-quiz/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MateriSilabusQuizService->deleteData($id);
		// $this->MateriSilabusQuizService->softDeleteData($id);

		$this->_redirect('/admin/materi-silabus-quiz/index');
	}

	public function searchMateriSilabusAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->MateriSilabusService->getAllData();
	}

	public function searchPengajarAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PengajarService->getAllData();
	}
}