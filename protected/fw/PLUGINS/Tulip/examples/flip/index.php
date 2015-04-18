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
$flip_horizental = tulipIP::flip($image, TIP_FLIP_HORIZONTAL);
$flip_vertical = tulipIP::flip($image, TIP_FLIP_VERTICAL);
$flip_both = tulipIP::flip($image, TIP_FLIP_BOTH);


/**
 * Save All created resources
 */

$dest = "./";

tulipIP::saveImage($dest, $image, TIP_PNG, "original");
tulipIP::saveImage($dest, $flip_horizental, TIP_PNG, "flib-horizental");
tulipIP::saveImage($dest, $flip_vertical, TIP_PNG, "flip-vertical");
tulipIP::saveImage($dest, $flip_both, TIP_PNG, "flip-both");
?>