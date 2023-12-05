<?php
class Portal_IndexController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
	}
	
	public function indexAction()
	{
		if ($this->getRequest()->isPost()) {
			$auth = Zend_Auth::getInstance()->getIdentity();
			$username = $auth->pengguna;
			$id_peserta = $this->getRequest()->getParam('id_peserta');
			$title = $this->getRequest()->getParam('title');
			$skor_materi = $this->getRequest()->getParam('skor_materi');
			$skor_mentoring = $this->getRequest()->getParam('skor_mentoring');
			$skor_penugasan = $this->getRequest()->getParam('skor_penugasan');
			$skor_akhir = $this->getRequest()->getParam('skor_akhir');

			$last_id=$this->SertifikatService->addData($id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);

			$this->_redirect('/coach/sertifikat/edit/id/'.$last_id);
		} 
	}	
}