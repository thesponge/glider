<?php
error_log(" "."/////////////////////////////////////////////////");
error_log(" "."//////////// [ BlackSea page load ] /////////////");
error_log(" "."/////////////////////////////////////////////////");
error_log(" "."                                                 ");
error_log(" "."                                                 ");

//header("Content-Security-Policy: script-src http://blacksea-beta.disqus.com");

//xdebug_start_trace('../trace.txt');

    //error_reporting(E_ERROR);

    if (file_exists('../../protected/cluj/etc/config.base.php')) {
        require_once '../../protected/cluj/etc/config.base.php';
    } else {
        die("Config files not found!");
    }

    if (defined(ENV) && ENV == 'production') {
        error_reporting(0);
        @ini_set('display_errors', 0);
    }

    if (file_exists(FW_INC_PATH.'GENERAL/core/scripts/ivyStart.php')) {
        require_once FW_INC_PATH.'GENERAL/core/scripts/ivyStart.php';
    } else {
        die("Init not found!");
    }
    //$profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());


//var_dump($core);
//echo $_SESSION['auth']->name;
//var_dump($_SESSION['auth']);

//xdebug_stop_trace();

require_once FW_INC_PATH.'GENERAL/core/scripts/index.php';

