<?php
class Admin_UserController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->UserService = new UserService();
		$this->PelatihanService = new PelatihanService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->UserService->getAllData();
		$this->view->rows2 = $this->PelatihanService->getAllData();
	}

	public function addAction() {	
		if ($this->getRequest()->isPost()) {
			$nama_user = $this->getRequest()->getParam('nama_user');
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');

			$id=$this->UserService->addData($id_pelatihan, $nama_user);
		
			$this->_redirect('/admin/user/index');
		} else {
			$this->view->rows = $this->UserService->getAllData();
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');
		$row=$this->PelatihanService->getData($id);
		$this->view->row = $row;

		if ($this->getRequest()->isPost()) {
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			$nama_user = $this->getRequest()->getParam('nama_user');
	
			try {
				$this->UserService->editData($id, $id_pelatihan, $nama_user);
				$this->redirect('/admin/user/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/admin/user/index');
			}	

			$row = $this->UserService->getData($id);
			$this->view->row = $row;
			$this->view->nama_pelatihan = $this->PelatihanService->getData($row->id_pelatihan)->nama_pelatihan;
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->UserService->deleteFiles($id);
		$this->_redirect('/admin/user/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
		$this->UserService->deleteData($id);
		// $this->UserService->softDeleteData($id);

		$this->_redirect('/admin/user/index');
	}

	public function searchPelatihanAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->PelatihanService->getAllData();
	}

}