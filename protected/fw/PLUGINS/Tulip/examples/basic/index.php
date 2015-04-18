<?php

/**
 * First we require the tulipIP class
 */
require_once '../../tulipIP/tulipIP.class.php';

/**
 * this option will handle errors behavior
 *
 * Important : Using This Method Is Optional
 * =========
 * 
 * Note : tulipIP Class does not throw any kinds of errors
 * ====== or exceptions instead tuliIP log file will be created
 *        contains all errors or exception raised throw progress
 *        PLUS: errors or exception raised throw progress will
 *              be also writen to Apache log file
 *
 * First argument will allow tulipIP to write the log file - change it to false
 *                to prevent tuliIP to write errors to the log file
 * second argumengt if true:  exist log file will be removed then new one will be created
 *                  if false : tulipIP will write errors to exist log file
 *
 */
tulipIP::log(true, true);

/**
 * Load Image from source File
 * return gd resource in success false otherwise
 */
$path = "../../src.jpg";
$img = tulipIP::loadImage($path);

/**
 * Here We use tuliIP gray filter on loaded img
 * return true on success false otherwise
 */
if ($img) {
    $result = tulipIP::gray($img);
} else {
    echo "Unable To Load The Image";
}

/**
 * finally we save imag to directory and show the result in the browser
 *
 * Note : pass (null) as dest argument to output the image directly in the browser
 * ======
 * Note : you can change the outputted image format using one of
 * ====== supported tuliIP formats -> TIP_JPG , TIP_JPEG , TIP_PNG , TIP_GIF
 *
 * Important : Do Not Use TIP_JPG as content type when you send Header this
 * =========== will cause problems in (Safari) and (IE) browsers
 *             use TIP_JPEG instead 
 *
 * Note : we can change the quality of the outputted image by passing integer in range (0,100)
 * ====== to saveImage Method where better quality means bigger file(s) size
 */
$dest = "./";
if ($result) {
    // output the image directly to the browser
    header("Content-type:".TIP_JPEG);
    tulipIP::saveImage(null, $img);
    // output the image to the dest directory
    tulipIP::saveImage($dest, $img, TIP_JPG, "negate-filter", 100);
}

/**
 * Destroy the resource
 */
imagedestroy($img);
?>