<?php
class KBArticle extends Zend_Db_Table 
{
	protected $_name;
    protected $_schema;
	protected $_db;

    public function init() 
    {
        $this->_name = 'kb_artikel';
        $this->_schema  = 'db_belajar';
        $this->_db = Zend_Registry::get('db_belajar');
    }
}