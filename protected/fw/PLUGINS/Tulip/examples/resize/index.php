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
 * Important: This method will return new gd resource and won't affect the given gd Resource
 * ==========
 */
$dest = "./";

//resize image to (100*100)
$resized = tulipIP::resize($image, 100, 100);
tulipIP::saveImage($dest, $resized, TIP_PNG, "100-100");
imagedestroy($resized);

// resize image with the given width(100) and let tulipIP handle the new height (aspect ratio)
$resized = tulipIP::resize($image, 100, TIP_FIXED);
tulipIP::saveImage($dest, $resized, TIP_PNG, "100-FIXED");
imagedestroy($resized);

// resize image with the given height(150) and let tulipIP handle the new width (aspect ratio)
$resized = tulipIP::resize($image, TIP_FIXED, 150);
tulipIP::saveImage($dest, $resized, TIP_PNG, "FIXED-150");
imagedestroy($resized);

/**
 * resize image with the given width(100) and let tulipIP keep original height
 * You can also do the following
 * $resized = tulipIP::resize($image, 100,tulipIP::getHeight($image));
 */
$resized = tulipIP::resize($image, 100, TIP_CURRENT);
tulipIP::saveImage($dest, $resized, TIP_PNG, "100-original");
imagedestroy($resized);

/**
 * resize image with the given height(150) and let tulipIP keep original width
 * You can also do the following
 * $resized = tulipIP::resize($image, tulipIP::getWidth($image),150);
 */
$resized = tulipIP::resize($image, TIP_CURRENT, 150);
tulipIP::saveImage($dest, $resized, TIP_PNG, "original-150");
imagedestroy($resized);


/**
 * Note: if the params ($new_width,$new_height) were one of this states :
 * TIP_FIXED TIP_FIXED
 * TIP_FIXED TIP_CURRENT
 * TIP_CURRENT TIP_FIXED
 * TIP_CURRENT TIP_CURRENT
 * a copy of given gd resource will be returned with no modifition
 */
// destroy the source
imagedestroy($image);
?>
