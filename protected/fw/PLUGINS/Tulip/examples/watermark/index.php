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

$watermark = "../../watermark.png";

/**
 * You can choose the stamp(watermark) position using one of the suppored
 * TuliIp positions :
 * ===================
 * 
 * TIP_TOP_LEFT
 * TIP_TOP_CENTER
 * TIP_TOP_RIGHT
 *
 * TIP_LEFT_CENTER
 * TIP_CENTER
 * TIP_RIGHT_CENTER
 *
 * TIP_BOTTOM_LEFT
 * TIP_BOTTOM_CENTER
 * TIP_BOTTOM_RIGHT
 *
 * And add Margin(Optional) according to the selected position
 * in this example margin=0
 *
 * Note: tulipIP Does not check if the stamp width and height bigger than the width
 * ===== and height of the source image.so source image will be watermarked anayway
 *       even if the watermark won't be visibale
 */
tulipIP::addWatermark($image, $watermark, TIP_BOTTOM_RIGHT, 0);

$dest = "./";
header('Content-type:' . TIP_PNG);
tulipIP::saveImage(null, $image);
tulipIP::saveImage($dest, $image, TIP_JPG, "Watermark");
?>
