<?php
class Pengguna extends Zend_Db_Table {
	protected $_name;
	protected $_primary;
    protected $_schema;
	protected $_db;

    public function init() 
    {
        $this->_name = 'pengguna';
        $this->_schema  = 'db_mooc';
        $this->_db = Zend_Registry::get('db');
    }
}