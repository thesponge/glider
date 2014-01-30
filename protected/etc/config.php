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

define('BASE_URL', 'http://'.$_SERVER['HTTP_HOST'].'/');
define('BASE_PATH', dirname($_SERVER['DOCUMENT_ROOT']).'/');

define('PUBLIC_URL', BASE_URL.'');
define('PUBLIC_PATH', BASE_PATH.'public/');

//========================[ Locations ]=========================================

define('INC_PATH', BASE_PATH.'protected/');

define('FW_PUB_PATH', PUBLIC_PATH.'fw/');
define('FW_PUB_URL', PUBLIC_URL.'fw/');
define('FW_INC_PATH', INC_PATH.'fw/');

define('ETC_PATH', INC_PATH.'etc/');
define('VAR_PATH', INC_PATH.'var/');
define('LOG_PATH', VAR_PATH.'log/');
define('RES_PATH', PUBLIC_PATH.'RES/');
define('RES_URL', PUBLIC_URL.'RES/');

define('FW_RES_TREE', VAR_PATH.'trees/');

//========================[ Data Base ]=========================================

//define('DB_HOST', 'dev.linuxd.net');
define('DB_HOST', 'localhost');
define('DB_NAME', 'blacksea_dev');
define('DB_USER', 'blacksea');
define('DB_PASS', 'XTfUyJ7DsvWfjcDy');

define('DB_RO_USER', 'roblacksea');
define('DB_RO_PASS', 'Z3FE2bPH9uyw3Sn3');


define('DSN', 'mysqli://'.DB_USER.':'.DB_PASS.'@'.DB_HOST.'/'.DB_NAME);
define('DSN_RO', 'mysqli://'.DB_RO_USER.':'.DB_RO_PASS.'@'.DB_HOST.'/'.DB_NAME);

//========================[ pt mail ]===========================================
define('SMTP_SERVER', 'mail.serenitymedia.ro');
define('SMTP_USER', 'noreply@serenitymedia.ro');
define('SMTP_PASS', 'donotreply');
define('SMTP_PORT', 587);


//set_include_path(get_include_path() . ':' . BASE_PATH . 'protected');




