<?php

/**
 * tulip image progresser constants
 * @author Hyyan Abo Fakher
 */
// Ignore generated errors for broken jpg files
ini_set('gd.jpeg_ignore_warning', true);

define('TIP_RT', 'gd');

define('TIP_FIXED', 'TIP_FIXED');
define('TIP_CURRENT', 'TIP_CURRENT');

define('TIP_TOP_LEFT', 'TIP_TOP_LEFT');
define('TIP_TOP_RIGHT', 'TIP_TOP_RIGHT');
define('TIP_BOTTOM_LEFT', 'TIP_BOTTOM_LEFT');
define('TIP_BOTTOM_RIGHT', 'TIP_BOTTOM_RIGHT');
define('TIP_CENTER', 'TIP_CENTER');
define('TIP_TOP_CENTER', 'TIP_TOP_CENTER');
define('TIP_BOTTOM_CENTER', 'TIP_BOTTOM_CENTER');
define('TIP_LEFT_CENTER', 'TIP_LEFT_CENTER');
define('TIP_RIGHT_CENTER', 'TIP_RIGHT_CENTER');

define('TIP_ROTATE_LEFT', 90);
define('TIP_ROTATE_UPSIDE_DOWN', 180);
define('TIP_ROTATE_RIGHT', 270);

define('TIP_FLIP_HORIZONTAL', 1);
define('TIP_FLIP_VERTICAL', 2);
define('TIP_FLIP_BOTH', 3);

define('TIP_JPEG', @image_type_to_mime_type(IMAGETYPE_JPEG));
define('TIP_JPG', "image/jpg");
define('TIP_PNG', @image_type_to_mime_type(IMAGETYPE_PNG));
define('TIP_GIF', @image_type_to_mime_type(IMAGETYPE_GIF));

define('TULIP_LOGGER', dirname(__FILE__) . '/TulipIP-log.txt');
define('TULIP_NOTICE', '[Notice]');
define('TULIP_ERROR', '[Error]');
?>
