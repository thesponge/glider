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

/**
 * Note: flip method return new gd resource and has no effects in the given gd gd resource($image)
 */
$rotate_left = tulipIP::rotate($image, TIP_ROTATE_LEFT);
$rotate_right = tulipIP::rotate($image, TIP_ROTATE_RIGHT);
$rotate_upside_down = tulipIP::rotate($image, TIP_ROTATE_UPSIDE_DOWN);


/**
 * Save All created resources
 */

$dest = "./";

tulipIP::saveImage($dest, $image, TIP_PNG, "original");
tulipIP::saveImage($dest, $rotate_left, TIP_PNG, "rotate-left");
tulipIP::saveImage($dest, $rotate_right, TIP_PNG, "rotate-right");
tulipIP::saveImage($dest, $rotate_upside_down, TIP_PNG, "rotate-upside-down");
?>