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

//font file in (TTF) OR (OTF) Format
$fontFile = "put your font path here";

// text to be written
$text = "Powered By TulipIP";

/*
 * text position one of supported tulipIP positions
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
 */
$position = TIP_BOTTOM_RIGHT;
// font size(Optional) in range(0,72) where default=20
$fontSize = 20;
// font angel(Optional) in range(-360,360) where default=0
$angle = 30;
// text margin (Optional) where default=0
$margin = 5;
// text color(Optional) where default=black
$color = tulipIP::toRGB("#fff");

tulipIP::addText($image, $fontFile, $text, $position, $margin, $color, $fontSize, $angle);

// save the result
$dest = "./";
header('Content-type:'.TIP_PNG);
tulipIP::saveImage(null, $image);
tulipIP::saveImage($dest, $image, TIP_JPG, "Watermark");
?>
