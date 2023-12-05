<?php
class KBAttachmentService{
	function __construct()
	{
		$this->attachment = new KBAttachment();
	}

	function getData($id)
	{
		$select = $this->attachment->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_lampiran'), array('*'))
			->where('a.id = ?', $id);
			//->where('a.status = 1');

		$result = $this->attachment->fetchRow($select);
		return $result;
	}

	function getDataByArticle($article_id){
		$select = $this->attachment->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_lampiran'), array('*'))
			->where('a.status = 1 AND a.artikel_id = ?', $article_id);
			//->where('a.status = 1');

		$result = $this->attachment->fetchAll($select);
		return $result;
	}

	function getAllData()
	{ 
		$select = $this->attachment->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_lampiran'), array('*'))
			->where('a.status = 1');

		$result = $this->attachment->fetchAll($select);
		return $result;
	}

	function addData($media_path, $artikel_id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'media_path' => $media_path,
			'artikel_id' => $artikel_id,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->attachment->insert($params);	
		$lastId = $this->attachment->getAdapter()->lastInsertId();
		return $lastId;
	}

	function editData($id, $media_path, $artikel_id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'media_path' => $media_path,
			'artikel_id' => $artikel_id,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->attachment->getAdapter()->quoteInto('id = ?', $id);
		$this->attachment->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->attachment->getAdapter()->quoteInto('id = ?', $id);
		$this->attachment->delete($where);
	}

	function softDeleteData($id)
	{ 		
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

 		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
 		
		$where = $this->attachment->getAdapter()->quoteInto('id = ?', $id);
		$this->attachment->update($params, $where);
	}
}