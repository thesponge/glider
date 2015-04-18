<?php
class IvyDb extends MDB2
{

    public function __construct($dsn)
    {
        //$dsn .= '?charset=utf-8';
        $this->db = MDB2::singleton($dsn);
    }

    public function query($query)
    {
        $res = $this->db->query($query);
        if (!$this->db->isError($res)) {
            return $res->getResource();
        }
    }

    public function set_charset()
    {
        return true;
    }

    public function ping()
    {
        return true;
    }

}
