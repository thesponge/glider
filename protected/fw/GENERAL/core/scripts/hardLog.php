<?php

//session_start();
// ------[ get the class loader ]-------
require FW_INC_PATH.'GENERAL/core/scripts/classLoader.inc';
require INC_PATH.'etc/hardLogKey.php';

//$psw = 'adminPro';
#=======================================


    // ---------[ admin.php login ]---------
    # /!\ DEPRECATED
    if (isset($_POST['password'])) {
        if($_POST['password']== $psw) $_SESSION['admin']=1;
    }
    #=======================================

    // ----------[ destroy session ]----------
    if (isset($_GET['logOUT'])) {
      unset($_SESSION['admin']);
    }
    #=======================================

    // ---------[ load the base class ]---------
    if (isset($_SESSION['admin'])
        || $_POST['password'] === $psw
    ) {
        $core = new ACLcore();
    }
    else {
        $core = new CLcore();
    }

    /*
     * Pentru a putea sa ma refer la core
     * din interiorul lui procesSCRIPT.php*/

    $sercore     = serialize($core);
    //$serSESSION = session_encode();

    file_put_contents(VAR_PATH.'tmp/sercore.txt', $sercore);
