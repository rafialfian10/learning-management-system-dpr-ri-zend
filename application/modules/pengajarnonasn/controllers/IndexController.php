<?php
class Pengajarnonasn_IndexController extends Zend_Controller_Action
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
		$session = new Zend_Session_Namespace('loggedInUser');
        $auth= $session->user;
	}	
}