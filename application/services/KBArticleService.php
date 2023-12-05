<?php
class KBArticleService
{
	function __construct()
	{
		$this->article = new KBArticle();
	}

	// get data by id
	function getData($id)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.id = ?', $id);
		//->where('a.status = 1');

		$result = $this->article->fetchRow($select);
		return $result;
	}

	// get data by title
	function getDataByTitle($title, $multi)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.status = 1 AND a.judul = ?', $title);

		if ($multi == true) {
			$result = $this->article->fetchAll($select);
		} else {
			$result = $this->article->fetchRow($select);
		}
		return $result;
	}

	// get data content per page
	function getDataByContentPage($content, $page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.judul LIKE ?', '%' . $content . '%')
			->group('judul')
			->order('a.counter DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get data content per page (for Admin)
	function getDataByPageAdmin($page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.status = 1 ')
			->group('judul')
			->order('a.tanggal_update DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get count data content per page (for Admin)
	function getDataByPageCountAdmin()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('id'))
			->where('a.status = 1 ')
			->group('judul');

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get data content per page (for admin)
	function getDataByContentPageAdmin($content, $page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.status = 1 AND a.judul LIKE ?', '%' . $content . '%')
			// ->group('judul')
			->order('a.counter DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get count content (for admin)
	function getCountContentPageAdmin($content)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('count(user_update) as total'))
			->where('a.status = 1 AND a.judul LIKE ?', '%' . $content . '%');

		$result = $this->article->fetchRow($select);
		return $result;
	}

	// get data by content
	function getDataByContent($content)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1 AND a.judul LIKE "%' . $content . '%"')
			->group('judul')
			->order('a.counter DESC');

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get count data by content
	// function getDataCountByContent($content)
	// {
	// 	$select = $this->article->select()
	// 		->setIntegrityCheck(false)
	// 		->from(array('a' => 'kb_artikel'), array('count(judul) as total'))
	// 		->where('a.status = 1 AND a.publikasi = 1 AND a.judul LIKE "%' . $content . '%"')
	// 		->group('judul')
	// 		->order('a.counter DESC');

	// 	$result = $this->article->fetchRow($select);
	// 	return $result;
	// }

	// get data by keyword per page
	function getDataByKeywordPage($keyword, $page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1 AND a.keyword LIKE "%' . $keyword . '%"')
			->group('judul')
			->order('a.counter DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get data by keyword
	function getDataByKeyword($keyword)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1 AND a.keyword LIKE "%' . $keyword . '%"')
			->group('judul')
			->order('a.counter DESC');

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get data by category
	function getDataByKategori($kategori)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('id', 'judul', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1 AND a.kategori_id = ?', $kategori);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	function getDataSearch($key, $page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('status = 1 AND (judul LIKE "%' . $key . '%" OR keyword LIKE "%' . $key . '%")')
			->group('judul')
			->order('counter DESC')
			->limit($limit, $page);

			// judul LIKE ?', '%' . $key . '% OR deskripsi LIKE ?)', '%' . $key . '%'

		$result = $this->article->fetchAll($select);
		return $result;
	}

	function getDataSearchTotal($key)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('count(judul) as total'))
			->where('status = 1 AND (judul LIKE "%' . $key . '%" OR keyword LIKE "%' . $key . '%")')
			// ->group('judul')
			->order('counter DESC');
		$result = $this->article->fetchRow($select);
		return $result;
	}
	

	// get data custom (advance)
	function getDataCustom($kategori_id, $judul, $deskripsi, $keyword, $page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1 AND
			 a.kategori_id LIKE "%' . $kategori_id . '%" AND
			 a.judul LIKE "%' . $judul . '%" AND
			 a.deskripsi LIKE "%' . $deskripsi . '%" AND 
			 a.keyword LIKE "%' . $keyword . '%"')
			->group('judul')
			->order('a.counter DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get latest five data
	function getLatestFiveData()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1')
			->group('judul')
			->order('a.tanggal_update DESC')
			->limit(5);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get top five data
	function getTopFiveData()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1')
			->group('judul')
			->order('a.counter DESC')
			->limit(5);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get all data per page
	function getDataByPage($page, $limit)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.status = 1 AND a.publikasi = 1')
			->group('judul')
			->order('a.tanggal_update DESC')
			->limit($limit, $page);

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get all data custom (advance)
	function getAllDataCustom($kategori_id, $judul, $deskripsi, $keyword)
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('count(judul) as total'))
			->where('a.status = 1 AND a.publikasi = 1 AND
			 a.kategori_id LIKE "%' . $kategori_id . '%" AND
			 a.judul LIKE "%' . $judul . '%" AND
			 a.deskripsi LIKE "%' . $deskripsi . '%" AND 
			 a.keyword LIKE "%' . $keyword . '%"')
			// ->group('judul')
			->order('a.counter DESC');

		$result = $this->article->fetchRow($select);
		return $result;
	}

	// get all article data
	function getAllArticleData()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'deskripsi', 'keyword', 'publikasi', 'tanggal_update', 'counter', 'akses'))
			->where('a.status = 1 AND a.publikasi = 1')
			->group('judul');

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// get count all article data
	function getCountAllArticleData(){
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('count(judul) as total'))
			->where('a.status = 1 AND a.publikasi = 1');

		$result = $this->article->fetchRow($select);
		return $result;
	}

	// get all data
	function getAllData()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('*'))
			->where('a.status = 1');

		$result = $this->article->fetchAll($select);
		return $result;
	}

	// add data
	function addData($judul, $deskripsi, $keyword, $publikasi, $kategori_id, $akses)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'judul' => $judul,
			'deskripsi' => $deskripsi,
			'keyword' => $keyword,
			'publikasi' => $publikasi,
			'kategori_id' => $kategori_id,
			'akses' => $akses,
			'user_input' => $user_log,
			'tanggal_input' => $tanggal_log,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);
		$this->article->insert($params);
		$lastId = $this->article->getAdapter()->lastInsertId();
		return $lastId;
	}

	// edit data
	function editData($id, $judul, $deskripsi, $keyword, $publikasi, $akses, $kategori_id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'judul' => $judul,
			'deskripsi' => $deskripsi,
			'keyword' => $keyword,
			'publikasi' => $publikasi,
			'akses' => $akses,
			'kategori_id' => $kategori_id,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->article->getAdapter()->quoteInto('id = ?', $id);
		$this->article->update($params, $where);
	}

	// update counter
	function updateCounter($id, $newCounter)
	{
		$params = array(
			'counter' => $newCounter
		);

		$where = $this->article->getAdapter()->quoteInto('id = ?', $id);
		$this->article->update($params, $where);
	}

	// delete data
	public function deleteData($id)
	{
		$where = $this->article->getAdapter()->quoteInto('id = ?', $id);
		$this->article->delete($where);
	}

	// delete data by (soft delete)
	function softDeleteData($id)
	{
		$user_log = Zend_Auth::getInstance()->getIdentity()->pengguna;
		$tanggal_log = date('Y-m-d H:i:s');

		$params = array(
			'status' => 9,
			'user_update' => $user_log,
			'tanggal_update' => $tanggal_log
		);

		$where = $this->article->getAdapter()->quoteInto('id = ?', $id);
		$this->article->update($params, $where);
	}


	// DASHBOARD
	// get most author 
	function getMostAuthor()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('user_update', 'count(user_update) as total'))
			->group('user_input')
			->where('a.status = 1')
			->order('count(*) DESC');

		$result = $this->article->fetchRow($select);
		return $result;
	}

	// get most article viewed
	function getMostViewed()
	{
		$select = $this->article->select()
			->setIntegrityCheck(false)
			->from(array('a' => 'kb_artikel'), array('judul', 'counter'))
			// ->group('judul')
			->where('a.status = 1')
			->order('counter DESC');

		$result = $this->article->fetchRow($select);
		return $result;
	}
}
