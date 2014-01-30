<?php
error_log(" "."/////////////////////////////////////////////////");
error_log(" "."//////////// [ BlackSea page load ] /////////////");
error_log(" "."/////////////////////////////////////////////////");
error_log(" "."                                                 ");
error_log(" "."                                                 ");

//header("Content-Security-Policy: script-src http://blacksea-beta.disqus.com");

//xdebug_start_trace('../trace.txt');

    //error_reporting(E_ERROR);

    require_once '../protected/etc/config.base.php';

    if (defined(ENV) && ENV == 'production') {
        error_reporting(0);
        @ini_set('display_errors', 0);
    }

    require_once FW_INC_PATH.'GENERAL/core/scripts/ivyStart.php';
    //$profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());


//var_dump($core);
//echo $_SESSION['auth']->name;
//var_dump($_SESSION['auth']);

//xdebug_stop_trace();

require_once FW_INC_PATH.'GENERAL/core/scripts/index.php';

