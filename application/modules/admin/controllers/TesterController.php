<?php
class Admin_TesterController extends Zend_Controller_Action
{
	
	public function init()
	{ 
		$this->_helper->_acl->allow('admin');
	}
	
	public function indexAction() 
	{	
	}
	
	public function nextAction() 
	{	
	}
}