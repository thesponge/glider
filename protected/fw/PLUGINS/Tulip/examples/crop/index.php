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

//x coordinate to start cropping from
// Note you can get the width of $path instead of $image (it's the same)
$x = floor(tulipIP::getWidth($image)/2);
//y coordinate to start cropping from
$y=0;
//  x coordinate where to end the cropping
$width=  tulipIP::getWidth($path);
//  y coordinate where to end the cropping
$height=  tulipIP::getHeight($path);

/**
 * Note: crop method return new gd resource and has no effects in the given gd gd resource($image)
 * =====
 * crop the second half of the image
 */
$croped_image=  tulipIP::crop($image, $x, $y, $x, $height);
/**
 * Save The result
 */
$dest = "./";
header('Content-type:'.TIP_PNG);
tulipIP::saveImage(null, $croped_image);
tulipIP::saveImage($dest, $croped_image, TIP_JPG, "croped-image");
?>