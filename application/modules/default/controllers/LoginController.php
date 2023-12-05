<?php
class LoginController extends Zend_Controller_Action {
	
	public function init()
	{		
		$this->_helper->_acl->allow();
	}
	
	public function preDispatch() {
		$this->UserService = new UserService();
	}
	
	public function indexAction() {		
		$this->_helper->getHelper('layout')->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$ivenc = $_COOKIE["mehong1"];
		$encrypted_strenc = $_COOKIE["mehong2"];
		$appname = "belajar";
		
		if (isset($ivenc) && isset($encrypted_strenc)) {
			$iv = base64_decode($ivenc);
			
			$strenc = mcrypt_decrypt(MCRYPT_RIJNDAEL_256, 'in1,k0nci g3rbang kenikm4tan! *5', base64_decode($encrypted_strenc), MCRYPT_MODE_CFB, $iv);	
			$strenc = gzuncompress($strenc);
			$obj = json_decode($strenc);
			$obj->peran = (array)$obj->peran;
			
			if(isset($obj->pengguna)) 
			{
				if ($obj->peran[$appname] == "") {
					$provider_auth_uri = 'http://portal.dpr.go.id/login?service='.$appname.'&t='.time();
					$this->_redirect($provider_auth_uri);
				}
				
				$data = new stdClass;

				if($obj->id == NULL) {
					$obj->id = 1;
				}

				$row = $this->UserService->getData($obj->id);

				$data->id = $obj->id;
				$data->pengguna = $obj->pengguna;
				$data->nama = $obj->nama;
				$data->nip = $obj->nip;
				$data->departemen = $obj->departemen;
				$data->peran = $obj->peran[$appname];
				$data->id_peserta = $row->id_peserta;
				$data->id_mentor = $row->id_mentor;
				$data->id_coach = $row->id_coach;
				$data->id_pengajar = $row->id_pengajar;
				$data->id_penilai = $row->id_penilai;
				$data->id_admin = $row->id_admin;
				
				$auth = Zend_Auth::getInstance();
				$auth->setStorage(new Zend_Auth_Storage_Session());
				$auth->getStorage()->write($data);
		
				
				if($row->id_peserta && $row->id_admin){
					$this->_redirect('/portal/index');
				} 
				else if($row->id_coach){
					$this->_redirect('/coach/index');
				}
				else if($row->id_mentor){
					$this->_redirect('/mentor/index');
				}
				else if($row->id_pengajar){
					$this->_redirect('/pengajar/index');
				}
				else if($row->id_penilai){
					$this->_redirect('/penilai/index');
				}
				else if($row->id_admin){
					$this->_redirect('/admin/index');
				}
				else if($row->id_peserta){
					$this->_redirect('/peserta/index');
				} 
				else {
					
					$this->_redirect('/peserta/index');
				} 
			}
		} else {
			$provider_auth_uri = 'http://portal.dpr.go.id/login?service='.$appname.'&t='.time();
			$this->_redirect($provider_auth_uri);
		}
	}

	public function loginAction(){

    }
	 
	public function logoutAction() {
		Zend_Auth::getInstance()->clearIdentity();

		setcookie('mehong1', '', time() - 28800, '/', 'dpr.go.id');
		setcookie('mehong2', '', time() - 28800, '/', 'dpr.go.id');

		unset($_COOKIE["mehong1"]); 
		unset($_COOKIE["mehong2"]); 
		$this->_redirect('/');
	}
}
	