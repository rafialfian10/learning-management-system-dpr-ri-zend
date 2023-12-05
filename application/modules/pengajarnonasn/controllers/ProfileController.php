<?php
class Pengajarnonasn_ProfileController extends Zend_Controller_Action
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
		$this->PengajarService = new PengajarService();
	}
	
	public function indexAction()
	{
	

		$session = new Zend_Session_Namespace('loggedInUser');
        $auth= $session->user;



		$this->view->row = $this->PengajarService->getData($auth->id_pengajar);


		if ($this->getRequest()->isPost()) {

			$nama_pengajar = $this->getRequest()->getParam('nama_pengajar');
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$identitas_pengajar = $this->getRequest()->getParam('identitas_pengajar');
			$pangkat = $this->getRequest()->getParam('pangkat');
			$instansi = $this->getRequest()->getParam('instansi');
			$npwp = $this->getRequest()->getParam('npwp');
			$email = $this->getRequest()->getParam('email');
			$no_telp = $this->getRequest()->getParam('no_telp');
			$norekening = $this->getRequest()->getParam('norekening');
			$namabank = $this->getRequest()->getParam('namabank');
			$tempat_lahir = $this->getRequest()->getParam('tempat_lahir');
			$tanggal_lahir = $this->_helper->CDate($this->getRequest()->getParam('tanggal_lahir'));

			$ttl = $tempat_lahir .', '.$tanggal_lahir ;

			$file_name = $this->getRequest()->getParam('file_name');
			if ($file_name == "") {
				$file_name = $_FILES['file_name']['name'];
				$file_type = $_FILES['file_name']['type'];
				$file_size = $_FILES['file_name']['size'];
				$file_tmp = $_FILES['file_name']['tmp_name'];
			}

			if ($file_tmp) {
				$path = "//172.16.30.157/www/mooc/fotopengajar";
				$path_info = pathinfo($file_name);

				// get first three words of nama_pengajar
				$nama_pengajar_kata = explode(" ", $nama_pengajar);
				$nama_pengajar_kata = $nama_pengajar_kata[0] . " " . $nama_pengajar_kata[1];

				$file_name = 'fotopengajar-' . $nama_pengajar_kata . uniqid() . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);		
			}

			try {
				$this->PengajarService->editBiodata($auth->id_pengajar, $nama_pengajar, $jenis_kelamin, $identitas_pengajar, $pangkat,$instansi, $npwp, $email, $no_telp, $norekening, $namabank, $ttl, $file_name);


				$this->redirect('/pengajarnonasn/profile/index');
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
				$this -> _redirect('/pengajarnonasn/profile/index');
		}
	}	


	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->PengajarService->deleteFiles($id);
		$this->_redirect('/pengajarnonasn/profile/index');
	}
}