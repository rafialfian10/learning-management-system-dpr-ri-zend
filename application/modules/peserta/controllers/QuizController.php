<?php
class Peserta_QuizController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->SkorBelajarService = new SkorBelajarService();
		$this->SilabusQuizService = new SilabusQuizService();
		$this->PelatihanService = new PelatihanService();
		$this->BatchService = new BatchService();
	}

	public function indexAction()  
	{	
		$id = $this->getRequest()->getParam('id');
		$batch = $this->BatchService->getData($id);

		if ($this->getRequest()->isPost()) {
			$jawaban = $this->getRequest()->getParam('jawaban');

			$soal = $this->SilabusQuizService->getAllDataPelatihan($batch->id_pelatihan);

			$kunci_jawaban = [];
			
			foreach($soal as $key=>$val){
				$kunci_jawaban[]= $val->kunci_jawaban;
			}

			$db_jawaban = '';
			$db_kunci = '';

			$nilai = 0;
			$jumlah = 0;
			
			foreach ($jawaban as $idx => $jawab) {
				if($jawab == $kunci_jawaban[$idx]){
					$nilai++;
				}
				$jumlah++;
				if($idx == 0){
					$db_jawaban .= $jawab;
					$db_kunci .= $kunci_jawaban[$idx];
				} else {
					$db_jawaban .= ','.$jawab;
					$db_kunci .= ','.$kunci_jawaban[$idx];
				}
			}

			$db_nilai = ($nilai/$jumlah) * 100;
			$auth = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

			$this->SkorBelajarService->addNilai($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
			$this->_redirect('/peserta/quiz/index/id/'.$id);
		} else {
			$this->view->rows = $this->SilabusQuizService->getAllDataPelatihan($batch->id_pelatihan);
		}

	}
}