<?php

/**
 * This Example contains all supported filters by tuliIP
 */
require_once '../../tulipIP/tulipIP.class.php';

/**
 * Optional :
 * extend the the time limit so avoid time out
 */
set_time_limit(0);


/**
 * Load The Image From Source File
 */
$path = "../../src.jpg";
$image = tulipIP::loadImage($path);

/**
 * apply filters on copy of loaded image then save the result
 */
$dest = "./";
$mime = TIP_PNG;

// 1 - gray filter
$copy = tulipIP::gdClone($image);
tulipIP::gray($copy);
tulipIP::saveImage($dest, $copy, $mime, 'gray-filter');
imagedestroy($copy);

// 2 - negate filter
$copy = tulipIP::gdClone($image);
tulipIP::negate($copy);
tulipIP::saveImage($dest, $copy, $mime, 'negate-filter');
imagedestroy($copy);

// 3 - Gaussian Blur filter where level in range (0,100)
$copy = tulipIP::gdClone($image);
tulipIP::Gblur($copy, 15);
tulipIP::saveImage($dest, $copy, $mime, 'Gblur-filter');
imagedestroy($copy);

// 4 - Brightness filter where level in range (-255,255)
$copy = tulipIP::gdClone($image);
tulipIP::brightness($copy, -100);
tulipIP::saveImage($dest, $copy, $mime, 'Brightness-filter');
imagedestroy($copy);

// 5 - Contrast filter where level in range (-100,100)
$copy = tulipIP::gdClone($image);
tulipIP::contrast($copy, -60);
tulipIP::saveImage($dest, $copy, $mime, 'Contrast-filter');
imagedestroy($copy);

// 6 - Colorize filter
$copy = tulipIP::gdClone($image);
$color = tulipIP::toRGB("#f00"); // resturn array(255,0,0)
tulipIP::colorize($copy, $color);
tulipIP::saveImage($dest, $copy, $mime, 'Colorize-filter');
imagedestroy($copy);

// 7 - Gamma Correction where correction level in range(0.01,4.99)
$copy = tulipIP::gdClone($image);
tulipIP::gamma($copy, 0.40);
tulipIP::saveImage($dest, $copy, $mime, 'Gamma-Correction');
imagedestroy($copy);

/**
 *  8 - Edge Filter
 * Imporatnt : This method require PHP to be compiled with the
 * =========== bundled version of the GD library.
 */
$copy = tulipIP::gdClone($image);
tulipIP::edge($copy);
tulipIP::saveImage($dest, $copy, $mime, 'Edge-filter');
imagedestroy($copy);

/*
 *  9 - Emboss Filter
 * Imporatnt : This method require PHP to be compiled with the
 * =========== bundled version of the GD library.
 */
$copy = tulipIP::gdClone($image);
$offset = 1; // color offset in range(1,100) where 1= default
$normalization = 127; // color normalization in range (0,360) where 172= default
tulipIP::emboss($copy, $offset, $normalization);
tulipIP::saveImage($dest, $copy, $mime, 'Emboss-filter');
imagedestroy($copy);

/*
 *  10 - Light Filter
 * Imporatnt : This method require PHP to be compiled with the
 * =========== bundled version of the GD library.
 */
$copy = tulipIP::gdClone($image);
tulipIP::light($copy);
tulipIP::saveImage($dest, $copy, $mime, 'Light-filter');
imagedestroy($copy);


// destroy the original source
imagedestroy($image);
?>
