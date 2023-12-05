<?php
class KBCategoryService
{
	function __construct()
	{
		$this->category = new KBCategory();
	}

	function getData($id)
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('*'))
			->where('a.id = ?', $id);
		//->where('a.status = 1');

		$result = $this->category->fetchRow($select);
		return $result;
	}

	function getAllId(){
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('id'))
			->where('a.status = 1');

		$result = $this->category->fetchAll($select);
		return $result;
	}

	function getAllData()
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('*'))
			->where('a.status = 1')
			->order('a.parent_id ASC');

		$result = $this->category->fetchAll($select);
		return $result;
	}

	function getDataByPage($page, $limit)
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('*'))
			->where('a.status = 1')
			->order('a.parent_id ASC')
			->limit($limit, $page);

		$result = $this->category->fetchAll($select);
		return $result;
	}

	function getCountData()
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('count(user_update) as total'))
			->where('a.status = 1');

		$result = $this->category->fetchRow($select);
		return $result;
	}

	function getDataWithParentRoot()
	{
		$select = $this->category->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_kategori'), array('*'))
			->where('a.status = 1')
			->where('a.parent_id = 1');
		$result = $this->category->fetchAll($select);
		return $result;
	}

	function checkcategory()
	{

		$parentKey = '0';

		$sql = "SELECT id, nama_kategori FROM kb_kategori WHERE parent_id = '$parentKey' AND status = 1";
		$result = $this->category->getAdapter()->fetchAll($sql);
		// check if there is any data
		if(count($result) > 0){
			$data = $this->membersTree($parentKey);
		}
		else{
			$data=["id"=>"0","nama_kategori"=>"kosong","children"=>[]];
		}

		$data = $this->membersTree($parentKey);

		// replace string {"1":{ with {"Data":{
		$data = str_replace('{"1":{', '{"Data":{', json_encode($data));
		// echo $data;
		return json_encode($data);
	}

	function membersTree($parentKey)
	{
		$sql = "SELECT id, nama_kategori FROM kb_kategori WHERE parent_id = '$parentKey' AND status = 1";
		$result = $this->category->getAdapter()->fetchAll($sql);
		
		foreach($result as $row){
			$id = $row['id'];
			$row1[$id]['id'] = $row['id'];
			$row1[$id]['nama_kategori'] = $row['nama_kategori'];
			if($this->membersTree($row['id']) != null){
				$row1[$id]['children'] = array_values($this->membersTree($row['id']));
			}
		}

		return $row1;
	}

	function addData($nama_kategori, $parent)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'nama_kategori' => $nama_kategori,
			'parent_id' => $parent,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->category->insert($params);
		$lastId = $this->category->getAdapter()->lastInsertId();
		return $lastId;
	}

	function editData($id, $nama_kategori, $parent)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'nama_kategori' => $nama_kategori,
			'parent_id' => $parent,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->update($params, $where);
	}

	public function deleteData($id)
	{
		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->delete($where);
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

		$where = $this->category->getAdapter()->quoteInto('id = ?', $id);
		$this->category->update($params, $where);
	}
}
