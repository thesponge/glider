<?php
/**
 * Require The tuliIP class
 */
require_once '../../tulipIP/tulipIP.class.php';

/**
 * Load The Image From Source File
 */
$path = "../../src.jpg";
$image = tulipIP::loadImage($path);

/*
 * save compressed gd2 casg file so we can restore it after
 * page refresh or sudden shutdown or ....
 */

$dest = "./";
tulipIP::saveGD($dest, $image, "cash-file");

// destroy the resource
imagedestroy($image);

/**
 * Load The Casg File From dest
 */

$image=tulipIP::loadGD($dest, "cash-file");

// save the result
header('Content-type:' . TIP_PNG);
tulipIP::saveImage(null, $image);
tulipIP::saveImage($dest, $image, TIP_JPG, "Loaded From Cash File");
?>
