<?php
class Portalnonasn_IndexController extends Zend_Controller_Action
{
    public function init()
    {  
        $this->_helper->_acl->allow();
    }
    
    public function preDispatch() 
    {
        $session = new Zend_Session_Namespace('loggedInUser');
        if (!isset($session->user)) {
            $this->_redirect('/loginselainasn');
        }
    }
    
    public function indexAction()
    {

        // $session = new Zend_Session_Namespace('loggedInUser');
        // $pengguna = $session->user;
        // var_dump($pengguna->username);

		// $auth = Zend_Auth::getInstance()->getIdentity();
		// var_dump($auth );

        if ($this->getRequest()->isPost()) {
            // Process the form submission
            $auth = Zend_Auth::getInstance()->getIdentity();
            $username = $auth->pengguna;
            
            $id_peserta = $this->getRequest()->getParam('id_peserta');
            $title = $this->getRequest()->getParam('title');
            $skor_materi = $this->getRequest()->getParam('skor_materi');
            $skor_mentoring = $this->getRequest()->getParam('skor_mentoring');
            $skor_penugasan = $this->getRequest()->getParam('skor_penugasan');
            $skor_akhir = $this->getRequest()->getParam('skor_akhir');

            $last_id = $this->SertifikatService->addData($id_batch, $id_peserta, $title, $skor_materi, $skor_mentoring, $skor_penugasan, $skor_akhir);

            $this->_redirect('/coach/sertifikat/edit/id/' . $last_id);
        } 
    }    
}
