<?php
class Coach_ProfileController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->CoachService = new CoachService();
	}
	
	public function indexAction()
	{
		$auth = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		$this->view->row = $this->CoachService->getData($auth->id_coach);


		if ($this->getRequest()->isPost()) {

			$nama_coach = $this->getRequest()->getParam('nama_coach');
			$identitas_coach = $this->getRequest()->getParam('identitas_coach');
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
				$path = "//172.16.30.157/www/mooc/fotocoach";
				$path_info = pathinfo($file_name);

				// get first three words of nama_coach
				$nama_coach_kata = explode(" ", $nama_coach);
				$nama_coach_kata = $nama_coach_kata[0] . " " . $nama_coach_kata[1];

				$file_name = 'fotocoach-' . $nama_coach_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->CoachService->editBiodata($auth->id_coach, $nama_coach, $identitas_coach, $instansi, $email, $no_telp, $file_name);


				$this->redirect('/coach/profile/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/coach/profile/index');
		}
	}	


	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->CoachService->deleteFiles($id);
		$this->_redirect('/coach/profile/index');
	}
}