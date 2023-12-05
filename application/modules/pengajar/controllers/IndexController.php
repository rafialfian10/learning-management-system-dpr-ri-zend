<?php
class Pengajar_IndexController extends Zend_Controller_Action
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
		$user = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login
	}	
}