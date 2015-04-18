<?php

trait tReadOnlyDB {

    public $rodb;

    public function readOnlyConnect() {
        $this->rodb = new mysqli(DB_HOST,DB_RO_USER,DB_RO_PASS,DB_NAME);
        $this->rodb->set_charset("utf8");
    }

}
