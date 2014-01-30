<?php
//ini_set('unserialize_callback_func', array(ClassLoader::getInstance(),
    //'loadClass'));

//session_set_save_handler("SessionManager::open",
                         //"SessionManager::close",
                         //"SessionManager::read",
                         //"SessionManager::write",
                         //"SessionManager::destroy",
                         //"SessionManager::gc"
                        //);


//define('PROFILER', '1');
define('UMASK', '0755');
//define('AVATAR',FALSE);
define('FAV_ICON', 'http://serenitymedia.ro/uploads/favicon.png');


//========================[ Locations ]=========================================

define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('BASE_PATH', dirname($_SERVER['DOCUMENT_ROOT']).'/');

define('PUBLIC_URL', BASE_URL.'');
define('PUBLIC_PATH', BASE_PATH.'htdocs');

define('INC_PATH', BASE_PATH.'protected/');

//========================[ Data Base ]=========================================

//define('DB_HOST', 'dev.linuxd.net');
define('DB_HOST', 'localhost');
define('DB_NAME', '');
define('DB_USER', '');
define('DB_PASS', '');

define('DB_RO_USER', '');
define('DB_RO_PASS', '');


define('DSN', 'mysqli://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);
define('DSN_RO', 'mysqli://'.DB_RO_USER.':'.DB_RO_PASS.'@'.DB_HOST.'/'.DB_NAME);

//========================[ pt mail ]===========================================
//define('SMTP_SERVER', 'mail.serenitymedia.ro');
//define('SMTP_USER', 'noreply@serenitymedia.ro');
//define('SMTP_PASS', 'donotreply');
//define('SMTP_PORT', 587);
define('SMTP_SERVER', '');
define('SMTP_USER', '');
define('SMTP_PASS', '');
define('SMTP_PORT', 587);



