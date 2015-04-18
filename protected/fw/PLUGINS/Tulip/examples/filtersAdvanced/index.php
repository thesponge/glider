<?php

require_once '../../tulipIP/tulipIP.class.php';

/**
 * Optional :
 * extend the the time limit so avoid time out
 */
set_time_limit(0);


/**
 * apply filter for part of the given gd resource
 * 
 * Note:
 * =====
 * tulipIP has 2 extended methods of every filter make it able to
 * to do it's work in part of the given gd resource
 * example:
 * tulip has filter named (gray) to add grayscale effect for the given gd resource
 * also has :
 * method named (gray_part) to apply gray filter for the selected area
 * of the given gd resource only .
 * also has :
 * method named (gray_invert) to apply gray filter for the given gd resource
 * except the selected area
 *
 * and so on
 *
 *      Filter            Extended Part         Extended Invert
 *      ========          ===============       =======================
 *      gray()            gray_part()           gray_invert()
 *      negate()          negate_part()         negate_invert()
 *      brightness()      brightness_part()     brightness_invert()
 *      contrast()        contrast_part()       contrast_invert()
 *      colorize()        colorize_part()       colorize_invert()
 *      Gblur()           Gblur_part()          Gblur_invert()
 *      gamma()           gamma_part()          gamma_invert()
 *      edge()            edge_part()           edge_invert()
 *      emboss()          emboss_part()         emboss_invert()
 *      light()           light_part()          light_invert()
 *      flip()            flip_part()           flip_invert()
 *
 *  All (Extended Part) And (Extended Invert) Methods
 *  share the params :
 *  1- virtual_image -> gd resource
 *  2- $x -> x coordinate to start point
 *  3- $y -> y coordinate to start point
 *  4- $width -> end x coordinate
 *  5- $height -> end y coordinate
 * 
 */
/**
 * Load The Image From Source File
 */
$path = "../../src.jpg";
$image = tulipIP::loadImage($path);

/**
 * Appley gray filter for the second half of the gd resource ($image) only
 */
$dest = "./";

$x = floor(tulipIP::getWidth($image) / 2);
$y = 0;
$width = tulipIP::getWidth($image);
$height = tulipIP::getHeight($image);

$copy = tulipIP::gdClone($image);
tulipIP::gray_part($copy, $x, $y, $width, $height);
tulipIP::saveImage($dest, $copy, TIP_PNG, 'gray_part');
imagedestroy($copy);

/**
 * apply gray filter for the given gd resource except the selected area
 * where selected area is Square (100*100) in the top left corner of the image
 */
$copy = tulipIP::gdClone($image);
tulipIP::gray_invert($copy, 0, 0, 100, 100);
tulipIP::saveImage($dest, $copy, TIP_PNG, 'gray_invertr');
imagedestroy($copy);

// destroy the source
imagedestroy($image);
?>
