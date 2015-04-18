<?php
class usrDashboard extends ACusrManager  {

    public function __construct ($DB=NULL) {
        if($DB === NULL)
            $this->connectDB();
    }

    public function connectDB() {
        require_once '../../../../etc/config.php';
        $this->DB = new mysqli();
    }

    public function countUsers() {}

    public function countAdmins() {}

    public function countModerators() {}

    public function countPublishers() {}

    public function countEditors() {}

    public function countInactive() {}

    public function countSilent() {}
}
