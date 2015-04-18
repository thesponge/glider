<?php
//var_dump($_REQUEST);
global $core;
define('TMPL_INC', PUBLIC_PATH . "fw/LOCALS/{$core->mainModel}/tmpl_{$core->mainTemplate}/tmpl/");
define('TMPL_URL', PUBLIC_URL . "fw/LOCALS/{$core->mainModel}/tmpl_{$core->mainTemplate}/tmpl/");


require_once(FW_PUB_PATH.'GENERAL/core/tmpl/header.php');
require_once(FW_PUB_PATH.'GENERAL/core/tmpl/content.php');
require_once(FW_PUB_PATH.'GENERAL/core/tmpl/footer.php');
