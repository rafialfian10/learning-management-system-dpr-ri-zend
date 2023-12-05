<?php
class Admin_PenggunaController extends Zend_Controller_Action
{
	public function init()
	{  
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('super.mkd');
		$this->_helper->_acl->allow('user.mkd');
		$this->_helper->_acl->allow('super.sipera');
		$this->_helper->_acl->allow('user.sipera');
	}
	
	public function preDispatch() 
	{
		$this->PenggunaService = new PenggunaService();	
		$this->RestService = new RestService();
	}
	
	public function indexAction()
	{
		$this->view->rows = $this->PenggunaService->getAllData();
	}
  
	public function addAction()	
	{		
		$jenis = $this->getRequest()->getParam('jenis');
		if ( $this->getRequest()->isPost() ) 
		{
			$nama = $this->getRequest()->getParam('nama');
			$password = $this->getRequest()->getParam('password');
			$confirm_password = $this->getRequest()->getParam('confirm_password');

			$nip = $this->getRequest()->getParam('nip');
			$id_pengguna = $this->getRequest()->getParam('id_pengguna');
			$id_satker = $this->getRequest()->getParam('id_satker');
			$jabatan = $this->getRequest()->getParam('jabatan');
			$unit_kerja = $this->getRequest()->getParam('unit_kerja');
			list($id_instansi, $instansi) = explode('|', $this->getRequest()->getParam('id_instansi'));
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$email = $this->getRequest()->getParam('email');
			$pengguna = $this->getRequest()->getParam('pengguna');
			if ($jenis == 0)
				$pengguna = $email;
			$handphone = $this->getRequest()->getParam('handphone');
			$telepon = $this->getRequest()->getParam('telepon');
			$peran = $this->getRequest()->getParam('peran');

			$_SESSION['flash']['data'][] = $nama;
			$_SESSION['flash']['data'][] = $email;

			if ($password != $confirm_password) {
				$_SESSION['flash']['error'] = 'Konfirmasi kata sandi tidak sama, mohon periksa kembali.';
				$this->_redirect('/admin/pengguna/add');
			}

			$id = $this->PenggunaService->addData($jenis, $nama, $pengguna, $email, $nip, $jabatan, $unit_kerja, $id_instansi, $instansi, $jenis_kelamin, $handphone, $telepon, $peran, $password, $id_pengguna, $id_satker);

			$this->_redirect('/admin/pengguna/edit/id/'.$id);
		} else {
			$this->view->jenis = $jenis;
			$this->view->InstansiRows = $this->RestService->getAllData('instansi');
		}
	}

	public function changePasswordAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$password = $this->getRequest()->getParam('password');
			$confirm_password = $this->getRequest()->getParam('confirm_password');
			$sandi_random = $password;
			if ($jenis == 0)
			{
				$sandi_random = null;
			}

			if ($password != $confirm_password) {
				$_SESSION['flash']['error'] = 'Konfirmasi kata sandi tidak sama, mohon periksa kembali.';
				$this->_redirect('/admin/pengguna/change-password/id/'.$id);
			}

			$this->PenggunaService->editSandiData($id, $sandi_random);

			$this->_redirect('/admin/pengguna/edit/id/'.$id);
		} else {
			$this->view->id = $id;
			$this->view->row = $this->PenggunaService->getData($id);
			$this->view->InstansiRows = $this->RestService->getAllData('instansi');
		}
	}

	public function editAction()	
	{
		$id = $this->getRequest()->getParam('id');
		
		if ( $this->getRequest()->isPost() ) 
		{
			$jenis = $this->getRequest()->getParam('jenis');
			$nama = $this->getRequest()->getParam('nama');
			$nip = $this->getRequest()->getParam('nip');
			$jabatan = $this->getRequest()->getParam('jabatan');
			$unit_kerja = $this->getRequest()->getParam('unit_kerja');
			list($id_instansi, $instansi) = explode('|', $this->getRequest()->getParam('id_instansi'));
			$jenis_kelamin = $this->getRequest()->getParam('jenis_kelamin');
			$email = $this->getRequest()->getParam('email');
			$pengguna = $this->getRequest()->getParam('pengguna');
			if ($jenis == 0)
			{
				$pengguna = $email;
			}
			$handphone = $this->getRequest()->getParam('handphone');
			$telepon = $this->getRequest()->getParam('telepon');
			$peran = $this->getRequest()->getParam('peran');
			$this->PenggunaService->editData($id, $jenis, $nama, $pengguna, $email, $nip, $jabatan, $unit_kerja, $id_instansi, $instansi, $jenis_kelamin, $handphone, $telepon, $peran);

			$file_name = $_FILES['file']['name'];
			$file_type = $_FILES['file']['type'];
			$file_size = $_FILES['file']['size'];
			$file_tmp = $_FILES['file']['tmp_name'];

			if ($file_tmp) {
				$path = "//172.16.30.157/www/siperdana/berkas";
				$path_info = pathinfo($file_name);
				$file_name = 'BERKAS-' . $id . '-' . date('Ymd') . '-' . date('his') . '-' . rand(1000, 9999) . '.' . $path_info['extension'];

				move_uploaded_file($file_tmp, $path . "/" . $file_name);
				$this->PenggunaService->editFile($id, $file_name, $file_type, $file_size);
			}

			$image_name = $_FILES['image']['name'];
			$image_type = $_FILES['image']['type'];
			$image_size = $_FILES['image']['size'];
			$image_tmp = $_FILES['image']['tmp_name'];

			if ($image_tmp) {
				$path = "//172.16.30.157/www/siperdana/foto";
				$path_info = pathinfo($image_name);
				$image_name = 'FOTO-' . $id . '-' . date('Ymd') . '-' . date('his') . '-' . rand(1000, 9999) . '.' . $path_info['extension'];

				move_uploaded_file($image_tmp, $path . "/" . $image_name);
				$this->PenggunaService->editFoto($id, $image_name, $image_type, $image_size);
			}

			$this->_redirect('/admin/pengguna/edit/id/'.$id);
		} else {
			$this->view->id = $id;
			$this->view->row = $this->PenggunaService->getData($id);
			$this->view->InstansiRows = $this->RestService->getAllData('instansi');
		}
	}
	
	public function deleteAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->PenggunaService->softDeleteData($id);
		$this->_redirect('/admin/pengguna');
	}

	public function searchAction()
	{
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->RestService->getAllData('pegawai');
	}
	
	public function deleteFileAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->PenggunaService->softDeleteFile($id);
		$this->_redirect('/admin/pengguna/edit/id/'.$id);
	}
	
	public function deleteImageAction()
	{
		$id = $this->getRequest()->getParam('id');
		$this->PenggunaService->softDeleteImage($id);
		$this->_redirect('/admin/pengguna/edit/id/'.$id);
	}
}