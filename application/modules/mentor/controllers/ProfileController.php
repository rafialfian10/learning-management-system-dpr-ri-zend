<?php
class Mentor_ProfileController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->MentorService = new MentorService();
	}
	
	public function indexAction()
	{
		$auth = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		$this->view->row = $this->MentorService->getData($auth->id_mentor);


		if ($this->getRequest()->isPost()) {

			$nama_mentor = $this->getRequest()->getParam('nama_mentor');
			$identitas_mentor = $this->getRequest()->getParam('identitas_mentor');
			$instansi = $this->getRequest()->getParam('instansi');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');

			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotomentor";
				$path_info = pathinfo($file_name);

				// get first three words of nama_mentor
				$nama_mentor_kata = explode(" ", $nama_mentor);
				$nama_mentor_kata = $nama_mentor_kata[0] . " " . $nama_mentor_kata[1];

				$file_name = 'fotomentor-' . $nama_mentor_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				// var_dump($auth->id_mentor, $nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $file_name); die();
				$this->MentorService->editBiodata($auth->id_mentor, $nama_mentor, $identitas_mentor, $instansi, $email, $no_telp, $file_name);


				$this->redirect('/mentor/profile/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/mentor/profile/index');
		}
	}	


	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->MentorService->deleteFiles($id);
		$this->_redirect('/mentor/profile/index');
	}
}