<?php
class Peserta_PenugasanController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->PenugasanService = new PenugasanService();
	}

	public function indexAction()  
	{	
		$id = $this->getRequest()->getParam('id');
		$batch = $this->PenugasanService->getData($id);

		if ($this->getRequest()->isPost()) {
			
			$auth = Zend_Auth::getInstance()->getIdentity();

			$this->PenugasanService->addTugas($id, $auth->id_peserta, $batch->id_pelatihan, $db_jawaban, $db_kunci, $db_nilai);
			$this->_redirect('/peserta/pretest/index/id/'.$id);
		} else {
			$this->view->rows = $this->PretestService->getAllDataPelatihan($id);
		}
	}
}