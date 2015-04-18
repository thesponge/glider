<?php
/**
 * ATENTIE:
 *
 * $_POST['restoreCore']
 *  - poate veni via $.post() dintr-un js
 *      dar atentie : restoreCore : true => valoarea aceasta va fii privita ca string
 *  - poate veni dintr-un formular dar aici iar s-ar putea sa existe probleme de tipul informatiei
 *
 * => se vor folosi doar numere 0 / 1
*/
require FW_INC_PATH.'GENERAL/core/scripts/classLoader.inc';
//error_log("in axajProxy.php = ".$_POST['sessionId']);

// se face restore la core doar daca se cere explicit
//$_POST['restoreCore'] = !isset($_POST['restoreCore']) ? 0 : intval($_POST['restoreCore']);
if ($_POST['sessionId'] ) {

    $sercorePath = VAR_PATH.'tmp/sessions/'.$_POST['sessionId'].'/sercore.txt' ;

    if (file_exists($sercorePath)) {
        //echo "Sunt incerc sa preiau core din ".$sercorePath;
        //var_dump($_POST);

        error_log("[ ivy ] "."Restore Core cu sessionId  = ".$_POST['sessionId']);
        $sercore  = file_get_contents($sercorePath);
        $core     = unserialize($sercore);
        $core->wakeup();


    }

} else {
    error_log( "Nu se cere Restore Core ");
}

//=========================================================================
if (isset($_POST['parsePOSTfile'])) {
    include_once FW_INC_PATH.$_POST['parsePOSTfile'];
}
//trigger_error($_REQUEST['ajaxReqFile'],E_USER_NOTICE);

if (isset($_REQUEST['ajaxReqFile'])) {
    include_once FW_INC_PATH.$_REQUEST['ajaxReqFile'];
}


 /* echo FW_INC_PATH.$_POST['parsePOSTfile'];*/
