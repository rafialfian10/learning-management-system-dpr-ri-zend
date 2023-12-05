<?php
	class Pesertanonasn_ProfileController extends Zend_Controller_Action {
		public function init() {  
			$this->_helper->_acl->allow();
		}
		
		public function preDispatch() {
			$session = new Zend_Session_Namespace('loggedInUser');
			if (!isset($session->user)) {
				$this->_redirect('/');
			}
			$this->PesertaService = new PesertaService();
		}
		
		public function indexAction() {
			$id = $this->getRequest()->getParam('id');
			$session = new Zend_Session_Namespace('loggedInUser');
        	$auth= $session->user;

			$peserta = $this->PesertaService->getData($id);
			$this->view->peserta = $peserta;
		}	
	}
?>