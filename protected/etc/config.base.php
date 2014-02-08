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

define('ENV', 'development');

require_once 'config.' . ENV . '.php';

//TODO: Move this line to environmental configuration files!
define('SITE_NAME', 'Open Data Hackathon');

//========================[ Locations ]=========================================

define('FW_PUB_PATH', PUBLIC_PATH.'fw/');
define('FW_PUB_URL', PUBLIC_URL.'fw/');
define('FW_INC_PATH', INC_PATH.'fw/');

define('ETC_PATH', INC_PATH.'etc/');
define('VAR_PATH', INC_PATH.'var/');
define('LOG_PATH', VAR_PATH.'log/');
define('RES_PATH', PUBLIC_PATH.'RES/');
define('RES_URL', PUBLIC_URL.'RES/');

define('FW_RES_TREE', VAR_PATH.'trees/');


define('FAV_ICON', FW_PUB_URL . 'LOCALS/glider/tmpl_glider/css/img/glider_icon.png');


