<?php
class Penilai_ProfileController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index', 'edit'));
	}
	
	public function preDispatch() 
	{
		$this->PenilaiService = new PenilaiService();
	}
	
	public function indexAction()
	{
		$auth = Zend_Auth::getInstance()->getIdentity(); // Dapatkan id user yang login

		$this->view->row = $this->PenilaiService->getData($auth->id_penilai);


		if ($this->getRequest()->isPost()) {

			$nama_penilai = $this->getRequest()->getParam('nama_penilai');
			$identitas_penilai = $this->getRequest()->getParam('identitas_penilai');
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

				// get first three words of nama_penilai
				$nama_penilai_kata = explode(" ", $nama_penilai);
				$nama_penilai_kata = $nama_penilai_kata[0] . " " . $nama_penilai_kata[1];

				$file_name = 'fotopenilai-' . $nama_penilai_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->PenilaiService->editBiodata($auth->id_penilai, $nama_penilai, $identitas_penilai, $instansi, $email, $no_telp, $file_name);


				$this->redirect('/penilai/profile/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/penilai/profile/index');
		}
	}	


	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PenilaiService->deleteFiles($id);
		$this->_redirect('/penilai/profile/index');
	}
}