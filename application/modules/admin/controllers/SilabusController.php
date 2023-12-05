<?php
class Admin_SilabusController extends Zend_Controller_Action
{
	
	public function init() { 
		$this->_helper->_acl->allow('admin');
		$this->_helper->_acl->allow('super');
		$this->_helper->_acl->allow('user', array('index'));
	}
	
	public function preDispatch() {
		$this->SilabusService = new SilabusService();
		$this->PelatihanService = new PelatihanService();
		$this->MateriSilabusService = new MateriSilabusService();
	}
	
	public function indexAction()  {	
		$this->view->rows = $this->SilabusService->getAllData();
		$this->view->rows2 = $this->PelatihanService->getAllData();
		$this->view->rows3 = $this->MateriSilabusService->getAllData();
	}

	public function addAction() {	
		$id = $this->getRequest()->getParam('id');
		$this->view->row = $this->PelatihanService->getData($id);

		if ($this->getRequest()->isPost()) {
			$nama_silabus = $this->getRequest()->getParam('nama_silabus');
			$deskripsi = $this->getRequest()->getParam('deskripsi');

			$silabus = $this->SilabusService->getAllDataPelatihan($id);
			$urutan = count($silabus);

			foreach ($nama_silabus as $idx => $sil) {
				$arrDeskripsi = isset($deskripsi[$idx]) ? $deskripsi[$idx] : "-";

				$this->SilabusService->addData($sil, $arrDeskripsi, $id, ++$urutan); //addData($nama_silabus, $deskripsi, $id_pelatihan, $urutan)
			}
			
			$this->_redirect('/admin/pelatihan/edit/id/'.$id);
		}  else {
			$this->view->rows = $this->SilabusService->getAllData();
		}
	}

	public function editAction() {
		$id = $this->getRequest()->getParam('id');

		$this->view->row = $this->SilabusService->getData($id);
		
		$this->view->rows = $this->SilabusService->getAllData();
		$this->view->rows4 = $this->MateriSilabusService->getAllDataSilabus($id);

		if ($this->getRequest()->isPost()) {
			$nama_silabus = $this->getRequest()->getParam('nama_silabus');
			$deskripsi = $this->getRequest()->getParam('deskripsi');
			$id_pelatihan = $this->getRequest()->getParam('id_pelatihan');
			
			// $urutan = $this->getRequest()->getParam('urutan');
			// $urutan_old = $this->getRequest()->getParam('urutan_old');

			// $urutan = (int)$urutan;
			// $urutan_old = (int)$urutan_old;

			try {
				
				// if($urutan !== $urutan_old){
				// 	$silabus = $this->SilabusService->getAllDataPelatihan($id_pelatihan);
					
				// 	if($urutan > $urutan_old){
				// 		foreach($silabus as $val){
				// 			if($val->urutan > $urutan_old){
				// 				$this->SilabusService->editUrutan($val->id, ($val->urutan-1));
				// 			} else if($val->urutan == $urutan_old){
				// 				$this->SilabusService->editUrutan($val->id, $urutan);
				// 			}
				// 		}
				// 	} else {
				// 		foreach($silabus as $val){
				// 			if($val->urutan < $urutan_old){
				// 				$this->SilabusService->editUrutan($val->id, ($val->urutan+1));
				// 			} else if($val->urutan == $urutan_old){
				// 				$this->SilabusService->editUrutan($val->id, $urutan);
				// 			}
				// 		}
				// 	}
				// } 
				
				$this->SilabusService->editData($id, $nama_silabus, $deskripsi);
				$this->_redirect('/admin/silabus/edit/id/'.$id);
				
	
			} catch (Exception $e) {
				$this->view->error = $e->getMessage();
			}
			$this->_redirect('/admin/silabus/edit/id/'.$id);
		}	
	}

	public function deleteFilesAction() {
		$id = $this->getRequest()->getParam('id');
		$this->SilabusService->deleteFiles($id);
		$this->_redirect('/admin/silabus/edit/id/' . $id);
	}

	public function deleteAction() {
		$id = $this->getRequest()->getParam('id');
	
		$pelatihan = $this->SilabusService->getData($id);
		$id_pelatihan = $pelatihan['id_pelatihan'];

		$this->SilabusService->deleteData($id);
		$silabus = $this->SilabusService->getAllDataPelatihan($id_pelatihan);

		foreach($silabus as $key=>$val){
			$this->SilabusService->editUrutan($val->id, ($key+1));
		}
		
		$this->_redirect('/admin/pelatihan/edit/id/'.$id_pelatihan);
	}




	public function searchMateriSilabusAction() {
		$this->_helper->getHelper('layout')->disableLayout();
		$this->view->rows = $this->MateriSilabusService->getAllData();
	}

}