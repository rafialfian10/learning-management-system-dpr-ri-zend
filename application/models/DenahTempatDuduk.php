<?php
class DenahTempatDuduk extends Zend_Db_Table {
	protected $_name;
    protected $_schema;
	protected $_db;

    public function init() 
    {
        $this->_name = 'denah_tempat_duduk';
        $this->_schema  = 'db_protokol';
        $this->_db = Zend_Registry::get('db_protokol');
    }
}