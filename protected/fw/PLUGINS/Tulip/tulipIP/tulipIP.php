<?php

require_once dirname(__FILE__) . '/constant.php';

/**
 * tulip image progresser
 
 * This library is free software; you can redistribute it and/or
 * modify it under the terms of the GNU Lesser General Public
 * License as published by the Free Software Foundation; either
 * version 2.1 of the License, or (at your option) any later version.
 *
 * This library is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU
 * Lesser General Public License for more details.
 *
 * You should have received a copy of the GNU Lesser General Public
 * License along with this library; if not, write to the Free Software
 * Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307  USA
 *
 * You may contact the authors by e-mail at:
 * tiribthe4hyyan@gmail.com
 *
 *
 * @author Hyyan Abo Fakher <tiribthe4hyyan@gmail.com>
 * @package tulipIP
 * @version 1.0
 */
class tulipIP {

    private static $log = true;

    /**
     * Check for GD Lib.<br/>
     * @return boolean true if GD Lib exist false otherwise
     */
    public static function checkGDLib() {
        return function_exists('gd_info');
    }

    /**
     * enable or disable sending errors to log file
     * @param boolean $active if true enable sending errors if false disable sending errors
     * @param boolean $newLogFile if true new log file error will be created if false <br/>
     * append errors to last created log file
     */
    public static function log($active=true, $newLogFile=true) {
        if ($newLogFile) {
            @unlink(LOGGER);
        }
        if ($active) {
            self::$log = true;
        }
    }

    /**
     * Sends an error message to a tulip log file and Apache log file.
     * @param string $message The error message that should be logged. 
     * @param constant $type TULIP_ERROR - TULIP_NOTICE where TULIP_ERROR = default
     */
    private static function logger($methodName, $message, $type=TULIP_ERROR) {
        if (self::$log) {
            $message1 = "\n" . $type . " [Method : $methodName] " . $message;
            $message2 = " [Method : $methodName] " . $message;
            @error_log("[ ivy ] ".$message2);
            @error_log("[ ivy ] ".$message1, 3, TULIP_LOGGER);
        }
    }

    /**
     * getMime method return type of image as mime
     * @param string $fsrc the file source
     * @return string returns mime on success false otherwise
     */
    public static function getMime($fsrc) {
        if (@file_exists($fsrc)) {
            $imageInfo = @ getimagesize($fsrc);
            if ($imageInfo != false) {
                return $imageInfo['mime'];
            }
            self::logger("getMime", "Unable to get the mime for ($fsrc)", TULIP_NOTICE);
            return false;
        }
        self::logger("getMime", "Unable to get the mime for ($fsrc) - File Not Exist");
        return false;
    }

    /**
     * getWidth return width of gd resource or image file
     * @param string $src file path or gd resource
     * @return string width on success false otherwise
     */
    public static function getWidth($src) {
        if (@is_resource($src) && @get_resource_type($src) == TIP_RT) {
            $result = @imagesx($src);
            if (!$result)
                self::logger("getWidth", "Unable To Get The Width For The Given GD Resource - ($src)", ALETIP_RT);
            return $result;
        } elseif (@file_exists($src)) {
            $imageInfo = @ getimagesize($src);
            if ($imageInfo != false) {
                return $imageInfo[0];
            }
            self::logger("getWidth", "Unable To Get The Width For The Given Image File - ($src)", ALETIP_RT);
            return false;
        }
        return false;
    }

    /**
     * getHeight return Height of gd resource or image file
     * @param string $src file path or gd resource
     * @return string Height on success false otherwise
     */
    public static function getHeight($src) {
        if (@is_resource($src) && @get_resource_type($src) == TIP_RT) {
            $result = @imagesy($src);
            if (!$result)
                self::logger("getHeight", "Unable To Get The Height For The Given GD Resource - ($src)", ALETIP_RT);
            return $result;
        } elseif (@file_exists($src)) {
            $imageInfo = @ getimagesize($src);
            if ($imageInfo != false) {
                return $imageInfo[1];
            }
            self::logger("getHeight", "Unable To Get The Height For The Given Image File - ($src)", ALETIP_RT);
            return false;
        }
        return false;
    }

    /**
     * isMimeSupported Check If The tulipIP Support Mime type
     * @param string $mime the mime returned by tulipIP::getMime() Method
     * @return boolean returns true on success false otherwise
     */
    public static function isMimeSupported($mime) {
        switch ($mime) {
            case $mime == @ image_type_to_mime_type(IMAGETYPE_JPEG):
                return true;
                break;
            case $mime == @ image_type_to_mime_type(IMAGETYPE_PNG):
                return true;
                break;
            case $mime == @ image_type_to_mime_type(IMAGETYPE_GIF):
                return true;
                break;
            case $mime == TIP_JPG:
                return true;
                break;
            default:
                self::logger("isMimeSupported", "($mime) is Not Supported Mime Type");
                return false;
                break;
        }
    }

    /**
     * convert mime type into extension
     * @param string $mime one of supported tulipIP mime types
     * TIP_JPG , TIP_JPEG , TIP_PNG , TIP_GIF
     * @return string extension on success false otherwise
     */
    public static function extension($mime) {
        switch ($mime) {
            case TIP_JPEG:
                return ".jpeg";
                break;
            case TIP_JPG:
                return ".jpg";
                break;
            case TIP_PNG:
                return ".png";
                break;
            case TIP_GIF:
                return ".gif";
                break;
            default:
                return false;
                break;
        }
    }

    /**
     * convert Hex color to RGB color
     * @param string $hex color in hex ex:('#000')
     * @return array array with rgb color false otherwise
     */
    public static function toRGB($hex) {
        $org = $hex;
        $hex = @preg_replace("/[^0-9A-Fa-f]/", '', $hex);
        $rgb = array();
        if (strlen($hex) == 6) {
            $color = @hexdec($hex);
            $rgb[0] = 0xFF & ($color >> 0x10);
            $rgb[1] = 0xFF & ($color >> 0x8);
            $rgb[2] = 0xFF & $color;
        } elseif (strlen($hex) == 3) {
            $rgb[0] = @hexdec(@str_repeat(@substr($hex, 0, 1), 2));
            $rgb[1] = @hexdec(@str_repeat(@substr($hex, 1, 1), 2));
            $rgb[2] = @hexdec(@str_repeat(@substr($hex, 2, 1), 2));
        } else {
            self::logger("toRGB", "Unable To Convert ($org) Into RGB Color", TULIP_NOTICE);
            return false;
        }
        return $rgb;
    }

    /**
     * save full alpha channel information (as opposed to single-color transparency) when saving PNG images.
     * @param resource $virtual_image gd resource
     */
    private static function activateAlphaChannel($virtual_image) {
        if (@imagealphablending($virtual_image, false)) {
            if (!@imagesavealpha($virtual_image, true)) {
                self::logger("activateAlphaChannel", "Unable To Save Alpha Channel Information For The Given Gd Resource", TULIP_NOTICE);
            }
        }
    }

    /**
     * create new virtual image from file source
     * @param string $fsrc the file source
     * @return resource returns gd resource on success false otherwise
     */
    public static function loadImage($fsrc) {
        if (@file_exists($fsrc)) {
            $fmime = self::getMime($fsrc);
            if (self::isMimeSupported($fmime)) {
                switch ($fmime) {
                    case @ image_type_to_mime_type(IMAGETYPE_JPEG):
                        $jpeg = @ imagecreatefromjpeg($fsrc);
                        return $jpeg;
                        break;
                    case @ image_type_to_mime_type(IMAGETYPE_PNG):
                        $png = @ imagecreatefrompng($fsrc);
                        self::activateAlphaChannel($png);
                        return $png;
                        break;
                    case @ image_type_to_mime_type(IMAGETYPE_GIF):
                        $gif = @ imagecreatefromgif($fsrc);
                        return $gif;
                        break;
                    default:
                        return false;
                        break;
                }
            }
        } else {
            self::logger("loadImage", "Unable To Load The Image $fsrc - File Not Exist");
            return false;
        }
    }

    /**
     * convert resource into image and save it to the given dest directory or ouputted to the browser
     * @param string $dest the dest path if dest was null then image will be<br/>
     * outputted directly to the browser
     * @param resource $virtual_image gd resource
     * @param string $fmime the mime returned by tulipIP::getMime() Method <br/>
     * or one of TuliIP Constant TIP_JPG , TIP_JPEG , TIP_PNG , TIP_GIF
     * @param string $name saved file name
     * @param integer $quality Indicates the quality ( in range(0,100) )<br/>
     * of the output image where better quality means bigger file size.<br/>
     * <b>Note : </b> this option affects jpeg and png files only and does not have<br/>
     * any effect on gif files.<br/>
     * <b>Note : </b> if the php version older and only loder than (5.1.2) <br/>
     * this option will not work for png files.
     * @return boolean true on success false otherwise
     */
    public static function saveImage($dest, $virtual_image, $fmime=TIP_PNG, $name=null, $quality=null) {
        if (self::isMimeSupported($fmime)) {
            if (!@is_dir($dest)) {
                if ($dest != null)
                    self::logger("saveImage", "Dest Directory Is Not Exist ($dest)");
            }
            if (@get_resource_type($virtual_image) == TIP_RT) {
                $quality = $quality == null ? 75 : $quality;
                $quality = $quality >= 0 && $quality <= 100 ? $quality : 75;
                switch ($fmime) {
                    case TIP_JPG:
                        if ($dest != null)
                            $dest = $dest . $name . ".jpg";
                        $result = @ imagejpeg($virtual_image, $dest, $quality);
                        if (!$result)
                            self::logger("saveImage", "Unable To Save Image Into ($dest)");
                        return $result;
                        break;
                    case @ image_type_to_mime_type(IMAGETYPE_JPEG):
                        if ($dest != null)
                            $dest = $dest . $name . @ image_type_to_extension(IMAGETYPE_JPEG);
                        $result = @ imagejpeg($virtual_image, $dest, $quality);
                        if (!$result)
                            self::logger("saveImage", "Unable To Save Image Into ($dest)");
                        return $result;
                        break;
                    case @ image_type_to_mime_type(IMAGETYPE_PNG):
                        if ($dest != null)
                            $dest = $dest . $name . @ image_type_to_extension(IMAGETYPE_PNG);
                        if (@version_compare(PHP_VERSION, '5.1.2') >= 0) {
                            $quality = 9 - @floor(($quality * 9) / 100);
                            self::activateAlphaChannel($virtual_image);
                            $result = @ imagepng($virtual_image, $dest, $quality);
                            if (!$result)
                                self::logger("saveImage", "Unable To Save Image Into ($dest)");
                            return $result;
                        }
                        $result = @ imagepng($virtual_image, $dest);
                        if (!$result)
                            self::logger("saveImage", "Unable To Save Image Into ($dest)");
                        return $result;
                        break;
                    case @ image_type_to_mime_type(IMAGETYPE_GIF):
                        if ($dest != null)
                            $dest = $dest . $name . @ image_type_to_extension(IMAGETYPE_GIF);
                        $result = @ imagegif($virtual_image, $dest);
                        if (!$result)
                            self::logger("saveImage", "Unable To Save Image Into ($dest)");
                        return $result;
                        break;
                    default:
                        return false;
                        break;
                }
            }else {
                self::logger("saveImage", " Not A GD Resource");
            }
        }
        return false;
    }

    /**
     * save gd resource to the given dest in (.gd2) extension
     * @param string $dest the dest path ex:('images/preview/')
     * @param resource $resource gd resource
     * @param string $name the file name
     * @return boolean true on success false otherwise
     */
    public static function saveGD($dest, $resource, $name) {
        if (@get_resource_type($resource) == TIP_RT) {
            if (!@is_dir($dest)) {
                self::logger("saveGD", "Dest Directory IS Not Exist ($dest)");
            }
            $dest = $dest . $name . '.gd2';
            $result = @ imagegd2($resource, $dest, null, IMG_GD2_COMPRESSED);
            if (!$result)
                self::logger("saveGD", "Unable To Save GD Resource Into ($dest)");
            return $result;
        }
        self::logger("saveGD", " Not A GD Resource");
        return false;
    }

    /**
     * load gd resource from dest file
     * @param resource $fsrc the gd2 file source
     * @return boolean true on success false otherwise
     */
    public static function loadGD($fsrc, $fileName) {
        if (@file_exists($fsrc)) {
            $fsrc = $fsrc . $fileName . ".gd2";
            $result = @ imagecreatefromgd2($fsrc);
            if (!$result)
                self::logger("loadGD", "Unable To Load GD Resource Into ($dest)");
            return $result;
        }
        self::logger("loadGD", "($fsrc) Is Not Exist");
        return false;
    }

    /**
     * clone the given gd resource 
     * @param resource $virtual_image gd resource
     * @return resource gd resource on success false otherwise
     */
    public static function gdClone($virtual_image) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gdClone", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $width = self::getWidth($virtual_image);
        $height = self::getHeight($virtual_image);
        $clone = @imagecreatetruecolor($width, $height);
        if (!$clone) {
            self::logger("gdClone", "Unable To Create A Copy Of The Given GD Resource");
            return false;
        }
        self::activateAlphaChannel($clone);
        if (@imagecopy($clone, $virtual_image, 0, 0, 0, 0, $width, $height)) {
            return $clone;
        }
        self::logger("gdClone", "Unable To Create A Copy Of The Given GD Resource");
        return false;
    }

    /**
     * resize given image as gd resource to the new given width and height.<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param integer $new_width
     * new width ( integer > 0 ) or <br/>
     * constant (TIP_FIXED) To auto define the new width or <br/>
     * constant(TIP_CURRENT) to keep the source width
     * @param integer $new_height
     * new height ( integer > 0 ) or <br/>
     * constant (TIP_FIXED)To auto define the new height or <br/>
     * constant(TIP_CURRENT) to keep the source height
     * @return resource gd resource on success false otherwise.<br/>
     * <b>Note:</b>
     * if the params ($new_width,$new_height)  were one of this states :<br/>
     * <code> TIP_FIXED TIP_FIXED </code>
     * <code> TIP_FIXED TIP_CURRENT </code>
     * <code> TIP_CURRENT TIP_FIXED</code>
     * <code> TIP_CURRENT TIP_CURRENT</code>
     * a copy of given gd resource will be returned <br/><br/>
     */
    public static function resize($virtual_image, $new_width, $new_height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("resize", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $width = self::getWidth($virtual_image);
        $height = self::getHeight($virtual_image);
        switch ($new_width) {
            case $new_width > 0:
                switch ($new_height) {
                    case $new_height > 0:
                        $new_virtual_image = @ imagecreatetruecolor($new_width, $new_height);
                        self::activateAlphaChannel($new_virtual_image);
                        $result = @imagecopyresized($new_virtual_image, $virtual_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        if ($result) {
                            self::activateAlphaChannel($result);
                            return $new_virtual_image;
                        }
                        break;
                    case TIP_FIXED:
                        $new_height = @ floor($height * ($new_width / $width));
                        if ($new_height == 0)
                            return false;
                        $new_virtual_image = @imagecreatetruecolor($new_width, $new_height);
                        self::activateAlphaChannel($new_virtual_image);
                        $result = @imagecopyresized($new_virtual_image, $virtual_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        if ($result) {
                            self::activateAlphaChannel($result);
                            return $new_virtual_image;
                        }
                        break;
                    case TIP_CURRENT:
                        $new_virtual_image = @imagecreatetruecolor($new_width, $height);
                        self::activateAlphaChannel($new_virtual_image);
                        $result = @imagecopyresized($new_virtual_image, $virtual_image, 0, 0, 0, 0, $new_width, $height, $width, $height);
                        if ($result) {
                            self::activateAlphaChannel($result);
                            return $new_virtual_image;
                        }
                        break;
                    default:
                        return false;
                        break;
                }
            case TIP_CURRENT:
                switch ($new_height) {
                    case $new_height > 0:
                        $new_virtual_image = @ imagecreatetruecolor($width, $new_height);
                        self::activateAlphaChannel($new_virtual_image);
                        $result = @imagecopyresized($new_virtual_image, $virtual_image, 0, 0, 0, 0, $width, $new_height, $width, $height);
                        if ($result) {
                            self::activateAlphaChannel($result);
                            return $new_virtual_image;
                        }
                        break;
                    case TIP_FIXED:
                    case TIP_CURRENT:
                        return self::gdClone($virtual_image);
                        break;
                    default:
                        return false;
                        break;
                }

            case TIP_FIXED:
                switch ($new_height) {
                    case $new_height > 0:
                        $new_width = @ floor($width * ($new_height / $height));
                        if ($new_width == 0)
                            return false;
                        $new_virtual_image = @ imagecreatetruecolor($new_width, $new_height);
                        self::activateAlphaChannel($new_virtual_image);
                        $result = @imagecopyresized($new_virtual_image, $virtual_image, 0, 0, 0, 0, $new_width, $new_height, $width, $height);
                        if ($result) {
                            self::activateAlphaChannel($result);
                            return $new_virtual_image;
                        }
                        break;
                    case TIP_FIXED:
                    case TIP_CURRENT:
                        return self::gdClone($virtual_image);
                        break;
                    default:
                        return false;
                        break;
                }
            default:
                self::logger("resize", "($new_width) and ($new_height) Are Not Supported");
                return false;
                break;
        }
    }

    /**
     * crop given gd Resource<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start cropping from
     * @param integer $y y coordinate to start cropping from
     * @param integer $width  x coordinate where to end the cropping
     * @param integer $height y coordinate where to end the cropping
     * @return resource return gd resource on success false otherwise
     */
    public static function crop($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("crop", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $new_virtual_image = @ImageCreateTrueColor($width, $height);
        self::activateAlphaChannel($virtual_image);
        if ($new_virtual_image) {
            $result = @imagecopyresampled($new_virtual_image, $virtual_image, 0, 0, $x, $y, $width, $height, $width, $height);
            if ($result)
                return $new_virtual_image;
        }
        return false;
    }

    /**
     * add waterMark to the given gd resource
     * @param string $stamp path for image stamp or gd resource
     * @param resource $virtual_image gd resource
     * @param constant $position TIP_TOP_LEFT <br/> TIP_TOP_RIGHT<br/>  TIP_BOTTOM_LEFT <br/>
     * TIP_BOTTOM_RIGHT <br/> TIP_CENTER<br/>  TIP_LEFT_CENTER<br/>  TIP_RIGHT_CENTER<br/>
     * TIP_TOP_CENTER<br/>  TIP_BOTTOM_CENTER
     * @param integer $margin stamp margin <br/>
     * 1 - may be array with 2 integer first number for x margin second for y margin ex:array(100,20)<br/>
     *     or array with 1 integer means x margin = y margin. ex:array(50)<br/>
     * 2 - may be string ex:"100 2" - ex:"50" or just integer ex:50
     * @return boolean true on success false otherwise
     */
    public static function addWatermark($virtual_image, $stamp, $position, $margin=null) {

        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("addWatermark", " Not A GD Resource");
            return false;
        }
        if (@is_array($margin)) {
            if (count($margin) == 2) {
                $margin_x = (int) @$margin[0];
                $margin_y = (int) @$margin[1];
            } else {
                if (count($margin) == 1) {
                    $margin_x = (int) @$margin[0];
                    $margin_y = (int) @$margin[0];
                }
            }
        } else {
            if (@is_string($margin)) {
                $margin_arr = @explode(' ', @trim($margin));
                if ($margin_arr && count($margin_arr) == 2) {
                    $margin_x = (int) @$margin_arr[0];
                    $margin_y = (int) @$margin_arr[1];
                } else {
                    if ((int) $margin != 0) {
                        $margin_x = $margin_y = $margin;
                    } else {
                        $margin_x = $margin_y = 0;
                    }
                }
            } else {
                if (@is_integer($margin)) {
                    $margin_x = $margin_y = $margin;
                } else {
                    $margin_x = $margin_y = 0;
                }
            }
        }
        $stamp_width = self::getWidth($stamp);
        $stamp_height = self::getHeight($stamp);
        if (@get_resource_type($stamp) == TIP_RT) {
            self::activateAlphaChannel($stamp);
            $stamp_from_file = $stamp;
            self::activateAlphaChannel($stamp_from_file);
        } else {
            $stamp_from_file = self::loadImage($stamp);
            self::activateAlphaChannel($stamp_from_file);
        }

        $vi_width = self::getWidth($virtual_image);
        $vi_height = self::getHeight($virtual_image);

        switch ($position) {
            case TIP_TOP_LEFT:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                $margin_x,
                                $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_TOP_RIGHT:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                $vi_width - $stamp_width - $margin_x,
                                $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_BOTTOM_LEFT:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                $margin_x,
                                $vi_height - $stamp_height - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_BOTTOM_RIGHT:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                $vi_width - $stamp_width - $margin_x,
                                $vi_height - $stamp_height - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_CENTER:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                ($vi_width / 2) - ($stamp_width / 2) - (-$margin_x),
                                ($vi_height / 2) - ($stamp_height / 2) - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_TOP_CENTER:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                ($vi_width / 2) - ($stamp_width / 2) - (-$margin_x),
                                $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_BOTTOM_CENTER:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                ($vi_width / 2) - ($stamp_width / 2) - (-$margin_x),
                                $vi_height - $stamp_height - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_LEFT_CENTER:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                $margin_x,
                                ($vi_height / 2) - ($stamp_height / 2) - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            case TIP_RIGHT_CENTER:
                $copy = @ imagecopy(
                                $virtual_image,
                                $stamp_from_file,
                                ($vi_width ) - ($stamp_width) - ($margin_x),
                                ($vi_height / 2) - ($stamp_height / 2) - $margin_y,
                                0,
                                0,
                                $stamp_width,
                                $stamp_height
                );
                if ($copy) {
                    return true;
                }
            default:
                self::logger("addWatermark", "($position) Is Not Supported Watermark Position");
                return false;
                break;
        }
    }

    /**
     * Add text For the given gd resource
     * @param resource $virtual_image gd resource
     * @param string $fontFile path for font (TTF) or (OTF) Format
     * @param string $text text to be written
     * @param constant $position TIP_TOP_LEFT <br/> TIP_TOP_RIGHT<br/>  TIP_BOTTOM_LEFT <br/>
     * TIP_BOTTOM_RIGHT <br/> TIP_CENTER<br/>  TIP_LEFT_CENTER<br/>  TIP_RIGHT_CENTER<br/>
     * TIP_TOP_CENTER<br/>  TIP_BOTTOM_CENTER
     * @param integer $margin text margin <br/>
     * 1 - may be array with 2 integer first number for x margin second for y margin ex:array(100,20)<br/>
     *     or array with 1 integer means x margin = y margin. ex:array(50)<br/>
     * 2 - may be string ex:"100 2" - ex:"50" or just integer ex:50
     * @param array $color text color as array where black = default
     * @param integer $fontSize text size where 20 = default
     * @param integer $angle text angle in range (-360,360) where 0 = default
     * @return boolean true on success false otherwise
     */
    public static function addText($virtual_image, $fontFile, $text, $position, $margin=5, $color=null, $fontSize=20, $angle=0) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("addText", " Not A GD Resource");
            return false;
        }
        if (!file_exists($fontFile)) {
            self::logger("addText", "Font File ($fontFile) Is Not Exist");
            return false;
        }
        if ($text == null || $text == '')
            return false;
        $color = (is_array($color) || count($color) == 3) ?
                @imagecolorallocate($virtual_image, $color[0], $color[1], $color[2]) :
                @imagecolorallocate($virtual_image, 0, 0, 0);

        $fontSize = (int) $fontSize;
        $fontSize = $fontSize > 0 && $fontSize <= 72 ? $fontSize : 20;

        $angle = (int) $angle;
        $angle = $angle >= -360 && $angle <= 360 ? $angle : 0;

        if (@is_array($margin)) {
            if (count($margin) == 2) {
                $margin_x = (int) @$margin[0];
                $margin_y = (int) @$margin[1];
            } else {
                if (count($margin) == 1) {
                    $margin_x = (int) @$margin[0];
                    $margin_y = (int) @$margin[0];
                }
            }
        } else {
            if (@is_string($margin)) {
                $margin_arr = @explode(' ', @trim($margin));
                if ($margin_arr && count($margin_arr) == 2) {
                    $margin_x = (int) @$margin_arr[0];
                    $margin_y = (int) @$margin_arr[1];
                } else {
                    if ((int) $margin != 0) {
                        $margin_x = $margin_y = $margin;
                    } else {
                        $margin_x = $margin_y = 0;
                    }
                }
            } else {
                if (@is_integer($margin)) {
                    $margin_x = $margin_y = $margin;
                } else {
                    $margin_x = $margin_y = 0;
                }
            }
        }
        $bbox = @imageftbbox($fontSize, $angle, $fontFile, $text);

        if (!$bbox)
            return false;

        // upper left positions
        $upper_left_corner_x = abs($bbox[6]);
        $upper_left_corner_y = abs($bbox[7]);

        // upper right positions
        $upper_right_corner_x = abs($bbox[4]);
        $upper_right_corner_y = abs($bbox[5]);

        // lower left positions
        $lower_left_corner_x = abs($bbox[0]);
        $lower_left_corner_y = abs($bbox[1]);

        // lower right positions
        $lower_right_corner_x = abs($bbox[2]);
        $lower_right_corner_y = abs($bbox[3]);

        // box width and height
        $box_width = abs($upper_left_corner_x - $lower_right_corner_x);
        $box_height = abs($upper_right_corner_y - $lower_left_corner_y);

        $src_width = self::getWidth($virtual_image) - 1;
        $src_height = self::getHeight($virtual_image) - 1;

        switch ($position) {
            case TIP_TOP_LEFT:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                $upper_left_corner_x + $fontSize + $margin_x,
                                $upper_left_corner_y + $fontSize + $margin_y,
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_TOP_RIGHT:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                $src_width - ($upper_right_corner_x + $fontSize + $margin_x),
                                ($upper_left_corner_y + $fontSize + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_TOP_CENTER:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                ($src_width / 2) - (($upper_right_corner_x + $fontSize) / 2 + (-$margin_x)),
                                ($upper_left_corner_y + $fontSize + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_BOTTOM_LEFT:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                ($upper_left_corner_x + $fontSize + $margin_x),
                                $src_height - ($lower_left_corner_y + $fontSize + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_BOTTOM_RIGHT:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                $src_width - ($upper_right_corner_x + $fontSize + $margin_x),
                                $src_height - ($lower_left_corner_y + $fontSize + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_BOTTOM_CENTER:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                ($src_width / 2) - (($upper_right_corner_x + $fontSize) / 2 + $margin_x),
                                $src_height - ($lower_left_corner_y + $fontSize + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_RIGHT_CENTER:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                $src_width - ($upper_right_corner_x + $fontSize + $margin_x),
                                ($src_height / 2) - (($lower_right_corner_y - $fontSize) / 2 + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_LEFT_CENTER:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                $upper_left_corner_x + $fontSize + $margin_x,
                                ($src_height / 2) - (($lower_right_corner_y - $fontSize) / 2 + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            case TIP_CENTER:
                $result = @imagettftext(
                                $virtual_image,
                                $fontSize,
                                $angle,
                                ($src_width / 2) - (($upper_right_corner_x + $fontSize) / 2 + (-$margin_x)),
                                ($src_height / 2) - (($lower_right_corner_y - $fontSize) / 2 + $margin_y),
                                $color,
                                $fontFile,
                                $text
                );
                if ($result) {
                    return true;
                }
            default:
                self::logger("addText", "($position) Is Not Supported Text Position");
                return false;
                break;
        }
    }

    /**
     * rotate image according to constant position<br/>
     * <b>Note:</b>
     * this method work if imagerotate function exist
     * if not it will also work but it will take more time<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param constant $position TIP_ROTATE_LEFT , TIP_ROTATE_RIGHT , TIP_ROTATE_UPSIDE_DOWN
     * @return resource gd Resource on success false otherwise
     */
    public static function rotate($virtual_image, $position=null) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("rotate", " Not A GD Resource");
            return false;
        }
        $is_exist = function_exists('imagerotate');
        if ($is_exist == true) {
            self::activateAlphaChannel($virtual_image);
            switch ($position) {
                case TIP_ROTATE_LEFT:
                    $roteted_virtual_image = @ imagerotate($virtual_image, TIP_ROTATE_LEFT, 0);
                    if ($roteted_virtual_image) {
                        self::activateAlphaChannel($roteted_virtual_image);
                        return $roteted_virtual_image;
                    }
                    return false;
                    break;
                case TIP_ROTATE_RIGHT:
                    $roteted_virtual_image = @ imagerotate($virtual_image, TIP_ROTATE_RIGHT, 0);
                    if ($roteted_virtual_image) {
                        self::activateAlphaChannel($roteted_virtual_image);
                        return $roteted_virtual_image;
                    }
                    return false;
                    break;
                case TIP_ROTATE_UPSIDE_DOWN:
                    $roteted_virtual_image = @ imagerotate($virtual_image, TIP_ROTATE_UPSIDE_DOWN, 0);
                    if ($roteted_virtual_image) {
                        self::activateAlphaChannel($roteted_virtual_image);
                        return $roteted_virtual_image;
                    }
                    return false;
                    break;
                default:
                    self::logger("rotate", "($position) Is Not Supported Rotate Position");
                    return false;
                    break;
            }
        } else {
            self::activateAlphaChannel($virtual_image);
            $roteted_virtual_image = null;
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);

            switch ($position) {
                case TIP_ROTATE_LEFT:
                    $roteted_virtual_image = @ imagecreatetruecolor($height, $width);
                    self::activateAlphaChannel($roteted_virtual_image);
                    break;
                case TIP_ROTATE_RIGHT:
                    $roteted_virtual_image = @ imagecreatetruecolor($height, $width);
                    self::activateAlphaChannel($roteted_virtual_image);
                    break;
                case TIP_ROTATE_UPSIDE_DOWN:
                    $roteted_virtual_image = @ imagecreatetruecolor($width, $height);
                    self::activateAlphaChannel($roteted_virtual_image);
                    break;
                default:
                    self::logger("rotate", "($position) Is Not Supported Rotate Position");
                    return false;
                    break;
            }
            switch ($position) {
                case TIP_ROTATE_LEFT:
                    for ($x = 0; $x < $width; $x++) {
                        for ($y = 0; $y < $height; $y++) {
                            $color_index = @ imagecolorat($virtual_image, $x, $y);
                            if (!@imagesetpixel($roteted_virtual_image, $y, ($width - 1) - $x, $color_index)) {
                                self::logger("rotate", "Unable To Set Pixels - TIP_ROTATE_LEFT");
                                return false;
                            }
                        }
                    }
                    if ($roteted_virtual_image) {
                        return $roteted_virtual_image;
                    }
                    break;
                case TIP_ROTATE_RIGHT:
                    for ($x = 0; $x < $width; $x++) {
                        for ($y = 0; $y < $height; $y++) {
                            $color_index = @ imagecolorat($virtual_image, $x, $y);
                            if (!@imagesetpixel($roteted_virtual_image, ($height - 1) - $y, $x, $color_index)) {
                                self::logger("rotate", "Unable To Set Pixels - TIP_ROTATE_RIGHT");
                                return false;
                            }
                        }
                    }
                    if ($roteted_virtual_image) {
                        return $roteted_virtual_image;
                    }
                    break;
                case TIP_ROTATE_UPSIDE_DOWN:
                    for ($x = 0; $x < $width; $x++) {
                        for ($y = 0; $y < $height; $y++) {
                            $color_index = @ imagecolorat($virtual_image, $x, $y);
                            if (!@imagesetpixel($roteted_virtual_image, ($width - 1) - $x, ($height - 1) - $y, $color_index)) {
                                self::logger("rotate", "Unable To Set Pixels - TIP_ROTATE_UPSIDE_DOWN");
                                return false;
                            }
                        }
                    }
                    if ($roteted_virtual_image) {
                        return $roteted_virtual_image;
                    }
                    break;
                default:
                    self::logger("rotate", "($position) Is Not Supported Rotate Position");
                    break;
            }
            return false;
        }
    }

    /**
     * add grayscale effect for given gd resource<br/>
     * <b>Note :</b>
     * gray method work with imagefilter function or imagecopymergegray if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @return boolean true on success false otherwise
     */
    public static function gray($virtual_image) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gray", " Not A GD Resource");
            return false;
        }
        $is_exist = function_exists('imagefilter');
        if ($is_exist == true) {
            self::activateAlphaChannel($virtual_image);
            return @ imagefilter($virtual_image, IMG_FILTER_GRAYSCALE);
        } else {
            self::activateAlphaChannel($virtual_image);
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);
            $result = @imagecopymergegray($virtual_image, $virtual_image, 0, 0, 0, 0, $width, $height, 0);
            if ($result)
                return true;
            self::logger("gray", "GD Lib Does Not Support Gray Advanced Method Running Slow Mode", TULIP_NOTICE);
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color_index = @imagecolorat($virtual_image, $x, $y);
                    $rgb = @imagecolorsforindex($virtual_image, $color_index);
                    $fixed_rgb = @round(($rgb['red'] + $rgb['green'] + $rgb['blue']) / 3);
                    $new_color = @imagecolorallocate($virtual_image, $fixed_rgb, $fixed_rgb, $fixed_rgb);
                    if (!@imagesetpixel($virtual_image, $x, $y, $new_color)) {
                        self::logger("gray", "Error Setting Pixels");
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * add grayscale effect for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _gray_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _gray_part($virtual_image, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $gray_image = self::gray($copy);
                if ($gray_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $gray_image = self::gray($virtual_image);
                if ($gray_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * add grayscale effect for part of the given gd resource<br/>
     * <b>Note :</b>
     * gray_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function gray_part($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gray_part", " Not A GD Resource");
            return false;
        }
        return self::_gray_part($virtual_image, $x, $y, $width, $height, true);
    }

    /**
     * add grayscale effect for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * gray_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function gray_invert($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gray_invert", " Not A GD Resource");
            return false;
        }
        return self::_gray_part($virtual_image, $x, $y, $width, $height, false);
    }

    /**
     * add negate effect for given gd resource<br/>
     * <b>Note :</b>
     * negate method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @return boolean true on success false otherwise
     */
    public static function negate($virtual_image) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("negate", " Not A GD Resource");
            return false;
        }
        $is_exist = function_exists('imagefilter');
        self::activateAlphaChannel($virtual_image);
        if ($is_exist == true) {
            return @ imagefilter($virtual_image, IMG_FILTER_NEGATE);
        } else {
            self::logger("negate", "GD Lib Does Not Support Negate Advanced Method Running Slow Mode", TULIP_NOTICE);
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color_index = @imagecolorat($virtual_image, $x, $y);
                    $rgb = @imagecolorsforindex($virtual_image, $color_index);
                    $color = @imagecolorallocate(
                                    $virtual_image,
                                    255 - $rgb['red'],
                                    255 - $rgb['green'],
                                    255 - $rgb['blue']
                    );
                    if (!@imagesetpixel($virtual_image, $x, $y, $color)) {
                        self::logger("negate", "Error Setting Pixels");
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * add negate effect for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _negate_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _negate_part($virtual_image, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $negate_image = self::negate($copy);
                if ($negate_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $negate_image = self::negate($virtual_image);
                if ($negate_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * add negate effect for part of the given gd resource<br/>
     * <b>Note :</b>
     * negate_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function negate_part($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("negate_part", " Not A GD Resource");
            return false;
        }
        return self::_negate_part($virtual_image, $x, $y, $width, $height, true);
    }

    /**
     * add negate effect for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * negate_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function negate_invert($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("negate_invert", " Not A GD Resource");
            return false;
        }
        return self::_negate_part($virtual_image, $x, $y, $width, $height, false);
    }

    /**
     * change brightness for given gd resource<br/>
     * <b>Note :</b>
     * brightness method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-255,255) where  0 = no change
     * @return boolean true on success false otherwise
     */
    public static function brightness($virtual_image, $level) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("brightness", " Not A GD Resource");
            return false;
        }
        $level = $level >= -255 && $level <= 255 ? $level : 0;
        if ($level == 0)
            return true;
        self::activateAlphaChannel($virtual_image);
        $is_exist = function_exists('imagefilter');
        if ($is_exist == true) {
            return @imagefilter($virtual_image, IMG_FILTER_BRIGHTNESS, $level);
        } else {
            self::logger("brightness", "GD Lib Does Not Support Brightness Advanced Method Running Slow Mode", TULIP_NOTICE);
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color_index = @imagecolorat($virtual_image, $x, $y);
                    $rgb = @imagecolorsforindex($virtual_image, $color_index);

                    $red = $rgb['red'];
                    $green = $rgb['green'];
                    $blue = $rgb['blue'];

                    $red+=$level;
                    $green+=$level;
                    $blue+=$level;

                    $red = ($red > 255) ? 255 : (($red < 0 ) ? 0 : $red);
                    $green = ($green > 255 ) ? 255 : (($green < 0) ? 0 : $green);
                    $blue = ($blue > 255 ) ? 255 : (($blue < 0 ) ? 0 : $blue);

                    $color = @imagecolorallocate($virtual_image, $red, $green, $blue);

                    if (!@imagesetpixel($virtual_image, $x, $y, $color)) {
                        self::logger("brightness", "Error Setting Pixels");
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * change brightness for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _brightness_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-255,255) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _brightness_part($virtual_image, $level, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $brightness_image = self::brightness($copy, $level);
                if ($brightness_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $brightness_image = self::brightness($virtual_image, $level);
                if ($brightness_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * change brightness for part of the given gd resource<br/>
     * <b>Note :</b>
     * brightness_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-255,255) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function brightness_part($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("brightness_part", " Not A GD Resource");
            return false;
        }
        return self::_brightness_part($virtual_image, $level, $x, $y, $width, $height, true);
    }

    /**
     * change brightness for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * brightness_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-255,255) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function brightness_invert($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("brightness_invert", " Not A GD Resource");
            return false;
        }
        return self::_brightness_part($virtual_image, $level, $x, $y, $width, $height, false);
    }

    /**
     * change contrast for given gd resource<br/>
     * <b>Note :</b>
     * contrast method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-100,100) where  0 = no change
     * @return boolean true on success false otherwise
     */
    public static function contrast($virtual_image, $level) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("contrast", " Not A GD Resource");
            return false;
        }
        $level = $level >= -100 && $level <= 100 ? $level : 0;
        if ($level == 0)
            return true;
        self::activateAlphaChannel($virtual_image);
        $is_exist = function_exists('imagefilter');
        if ($is_exist == true) {
            return @imagefilter($virtual_image, IMG_FILTER_CONTRAST, $level);
        } else {
            self::logger("contrast", "GD Lib Does Not Support Contrast Advanced Method Running Slow Mode", TULIP_NOTICE);
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);

            $contrast = (double) (100.0 - $level) / 100.0;
            $contrast *= $contrast;
            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color_index = @imagecolorat($virtual_image, $x, $y);
                    $rgb = @imagecolorsforindex($virtual_image, $color_index);

                    $r = (double) $rgb['red'] / 255.0;
                    $r = $r - 0.5;
                    $r = $r * $contrast;
                    $r = $r + 0.5;
                    $r = $r * 255.0;

                    $g = (double) $rgb['green'] / 255.0;
                    $g = $g - 0.5;
                    $g = $g * $contrast;
                    $g = $g + 0.5;
                    $g = $g * 255.0;

                    $b = (double) $rgb['blue'] / 255.0;
                    $b = $b - 0.5;
                    $b = $b * $contrast;
                    $b = $b + 0.5;
                    $b = $b * 255.0;

                    $r = (int) ($r > 255.0) ? 255.0 : (($r < 0.0) ? 0.0 : $r);
                    $g = (int) ($g > 255.0) ? 255.0 : (($g < 0.0) ? 0.0 : $g);
                    $b = (int) ($b > 255.0) ? 255.0 : (($b < 0.0) ? 0.0 : $b);

                    $new_color = @imagecolorallocate($virtual_image, $r, $g, $b);

                    if (!$new_color || $new_color == -1) {
                        $closest_color = @imagecolorclosest($virtual_image, $r, $g, $b);
                        $new_color = @imagecolorsforindex($virtual_image, $closest_color);
                    }
                    if (!@imagesetpixel($virtual_image, $x, $y, $new_color)) {
                        self::logger("contrast", "Error Setting Pixels");
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * change contrast for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _contrast_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-100,100) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _contrast_part($virtual_image, $level, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $contrast_image = self::contrast($copy, $level);
                if ($contrast_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $contrast_image = self::contrast($virtual_image, $level);
                if ($contrast_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * change contrast for part of the given gd resource<br/>
     * <b>Note :</b>
     * contrast_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-100,100) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function contrast_part($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("contrast_part", " Not A GD Resource");
            return false;
        }
        return self::_contrast_part($virtual_image, $level, $x, $y, $width, $height, true);
    }

    /**
     * change contrast for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * contrast_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level in range(-100,100) where  0 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    public static function contrast_invert($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("contrast_invert", " Not A GD Resource");
            return false;
        }
        return self::_contrast_part($virtual_image, $level, $x, $y, $width, $height, false);
    }

    /**
     * add colorize effect for given gd resource<br/>
     * <b>Note :</b>
     * colorize method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param array $color RGB Color as array ex: tulipIP::toRGB('#f00')
     * @return boolean true on success false otherwise
     */
    public static function colorize($virtual_image, $color) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("colorize", " Not A GD Resource");
            return false;
        }
        if ($color != null && is_array($color)) {
            if (count($color) == 3) {
                $red = $color[0];
                $green = $color[1];
                $blue = $color[2];
            } else {
                return false;
            }
        } else {
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $is_exist = function_exists('imagefilter');
        if ($is_exist == true) {
            return @imagefilter($virtual_image, IMG_FILTER_COLORIZE, $red, $green, $blue);
        } else {
            self::logger("colorize", "GD Lib Does Not Support Colorize Advanced Method Running Slow Mode", TULIP_NOTICE);

            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);

            for ($x = 0; $x < $width; $x++) {
                for ($y = 0; $y < $height; $y++) {
                    $color_index = @imagecolorat($virtual_image, $x, $y);
                    $rgb = @imagecolorsforindex($virtual_image, $color_index);

                    $r = (double) $color[0] + (double) $rgb['red'];
                    $g = (double) $color[1] + (double) $rgb['green'];
                    $b = (double) $color[2] + (double) $rgb['blue'];

                    $r = (int) ($r > 255.0) ? 255.0 : (($r < 0.0) ? 0.0 : $r);
                    $g = (int) ($g > 255.0) ? 255.0 : (($g < 0.0) ? 0.0 : $g);
                    $b = (int) ($b > 255.0) ? 255.0 : (($b < 0.0) ? 0.0 : $b);

                    $new_color = @imagecolorallocate($virtual_image, $r, $g, $b);

                    if (!$new_color || $new_color == -1) {
                        $closest_color = @imagecolorclosest($virtual_image, $r, $g, $b);
                        $new_color = @imagecolorsforindex($virtual_image, $closest_color);
                    }
                    if (!@imagesetpixel($virtual_image, $x, $y, $new_color)) {
                        self::logger("colorize", "Error Setting Pixels");
                        return false;
                    }
                }
            }
            return true;
        }
    }

    /**
     * add colorize effect for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _colorize_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param array $color RGB Color as array
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _colorize_part($virtual_image, $color, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $colorize_image = self::colorize($copy, $color);
                if ($colorize_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $colorize_image = self::colorize($virtual_image, $color);
                if ($colorize_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * add colorize effect for part of the given gd resource<br/>
     * <b>Note :</b>
     * colorize_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param array $color RGB Color as array
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function colorize_part($virtual_image, $color, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("colorize_part", " Not A GD Resource");
            return false;
        }
        return self::_colorize_part($virtual_image, $color, $x, $y, $width, $height, true);
    }

    /**
     * add colorize effect for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * colorize_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param array $color RGB Color as array
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function colorize_invert($virtual_image, $color, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("colorize_invert", " Not A GD Resource");
            return false;
        }
        return self::_colorize_part($virtual_image, $color, $x, $y, $width, $height, false);
    }

    /**
     * Apply Gaussian blur effect for given gd resource.<br/>
     * <b>Note :</b>
     * Gblur method work with imagefilter or imageconvolution function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level Blur level in range (0,100) where 0=no change
     * @return boolean true on success false otherwise
     */
    public static function Gblur($virtual_image, $level) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("Gblur", " Not A GD Resource");
            return false;
        }
        $level = $level >= 0 && $level <= 100 ? $level : 0;
        if ($level == 0)
            return false;
        self::activateAlphaChannel($virtual_image);
        $is_exist = false; //function_exists('imagefilter');
        if ($is_exist == true) {
            for ($x = 0; $x < $level; $x++) {
                if (!@imagefilter($virtual_image, IMG_FILTER_GAUSSIAN_BLUR))
                    return false;
            }
            return true;
        } else {
            $result = false;
            if (function_exists('imageconvolution')) {
                for ($x = 0; $x < $level; $x++) {
                    $matrix = array(array(1 / 9, 1 / 9, 1 / 9), array(1 / 9, 1 / 9, 1 / 9), array(1 / 9, 1 / 9, 1 / 9));
                    $result = @imageconvolution($virtual_image, $matrix, 1, 0);
                    if (!$result)
                        break;
                }
            }
            if ($result)
                return true;
            self::logger("Gblur", "GD Lib Does Not Support Gaussian Blur Advanced Method Running Slow Mode", TULIP_NOTICE);
            $width = self::getWidth($virtual_image);
            $height = self::getHeight($virtual_image);
            $distance = 1;
            $pct = 70;
            for ($x = 0; $x < $level; $x++) {
                $temp_im = @ImageCreateTrueColor($width, $height);
                @ImageCopy($temp_im, $virtual_image, 0, 0, 0, 0, $width, $height);
                @ImageCopyMerge($temp_im, $temp_virual_image, 0, 0, 0, $distance, $width - $distance, $height - $distance, $pct);
                @ImageCopyMerge($virtual_image, $temp_im, 0, 0, $distance, 0, $width - $distance, $height, $pct);
                @ImageCopyMerge($temp_im, $virtual_image, 0, $distance, 0, 0, $width, $height, $pct);
                @ImageCopyMerge($virtual_image, $temp_im, $distance, 0, 0, 0, $width, $height, $pct);
            }
            return true;
        }
        return false;
    }

    /**
     * Apply Gaussian blur effect for part of the given gd resource<br/>
     * or for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * _Gblur_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level Blur level in range (0,100) where 0=no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _Gblur_part($virtual_image, $level, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $croped_image = self::crop($virtual_image, $x, $y, $width, $height);
        self::activateAlphaChannel($croped_image);
        if ($croped_image) {
            if ($invert == true) {
                if (self::Gblur($croped_image, $level))
                    return @ImageCopyMerge($virtual_image, $croped_image, $x, $y, 0, 0, $width, $height, 100);
            }
            if ($invert == false) {
                if (self::Gblur($virtual_image, $level))
                    return @ImageCopyMerge($virtual_image, $croped_image, $x, $y, 0, 0, $width, $height, 100);
            }
        }
        return false;
    }

    /**
     * Apply Gaussian blur effect for part of the given gd resource.<br/>
     * <b>Note :</b>
     * GBlur_part method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level Blur level in range (0,100) where 0=no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function GBlur_part($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("GBlur_part", " Not A GD Resource");
            return false;
        }
        return self::_Gblur_part($virtual_image, $level, $x, $y, $width, $height, true);
    }

    /**
     * Apply Gaussian blur effect for the given gd resource except the selected area.<br/>
     * <b>Note :</b>
     * GBlur_invert method work with imagefilter function if exist but if not
     * it will also work but it will take more time
     * @param resource $virtual_image gd resource
     * @param integer $level Blur level in range (0,100) where 0=no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function GBlur_invert($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("GBlur_invert", " Not A GD Resource");
            return false;
        }
        return self::_Gblur_part($virtual_image, $level, $x, $y, $width, $height, false);
    }

    /**
     * Apply Gamma Correction for given gd resource
     * @param resource $virtual_image gd resource
     * @param float $level level of correction in range(0.01,4.99) where 1.00 = no change
     * @return boolean true on success false otherwise
     */
    public static function gamma($virtual_image, $level) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gamma", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $level = (float) $level;
        $level = $level >= 0.01 && $level <= 4.99 ? $level : 1.00;
        return @imagegammacorrect($virtual_image, 1.00, $level);
    }

    /**
     * Apply Gamma Correction for part of the given gd resource.<br/>
     * or for the given gd resource except the selected area
     * @param resource $virtual_image gd resource
     * @param float $level level of correction in range(0.01,4.99) where 1.00 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _gamma_part($virtual_image, $level, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $gamma_image = self::gamma($copy, $level);
                if ($gamma_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $gamma_image = self::gamma($virtual_image, $level);
                if ($gamma_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * Apply Gamma Correction for part of the given gd resource.<br/>
     * @param resource $virtual_image gd resource
     * @param float $level level of correction in range(0.01,4.99) where 1.00 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function gamma_part($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gamma_part", " Not A GD Resource");
            return false;
        }
        return self::_gamma_part($virtual_image, $level, $x, $y, $width, $height, true);
    }

    /**
     * Apply Gamma Correction for the given gd resource except the selected area.<br/>
     * @param resource $virtual_image gd resource
     * @param float $level level of correction in range(0.01,4.99) where 1.00 = no change
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function gamma_invert($virtual_image, $level, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("gamma_invert", " Not A GD Resource");
            return false;
        }
        return self::_gamma_part($virtual_image, $level, $x, $y, $width, $height, false);
    }

    /**
     * flip given gd resource vertical or horizental or both.<br/>
     * or the given gd resource except the selected area vertical or horizental.<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param constant $position TIP_FLIP_HORIZENTAL or FLIP_VETIP_RTICAL or TIP_FLIP_BOTH
     * @return resource gs resource on success false otherwise
     */
    public static function flip($virtual_image, $position) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("flip", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $src_width = self::getWidth($virtual_image);
        $src_height = self::getHeight($virtual_image);
        $dst_image = @imagecreatetruecolor($src_width, $src_height);
        self::activateAlphaChannel($dst_image);
        if (!$dst_image)
            return false;
        switch ($position) {
            case TIP_FLIP_VERTICAL:
                $result = @imagecopyresampled(
                                $dst_image,
                                $virtual_image,
                                0,
                                0,
                                0,
                                $src_height - 1,
                                $src_width,
                                $src_height,
                                $src_width,
                                -$src_height
                );
                if ($result) {
                    return $dst_image;
                }
                return false;
                break;
            case TIP_FLIP_HORIZONTAL:
                $result = @imagecopyresampled(
                                $dst_image,
                                $virtual_image,
                                0,
                                0,
                                $src_width - 1,
                                0,
                                $src_width,
                                $src_height,
                                -$src_width,
                                $src_height
                );
                if ($result) {
                    return $dst_image;
                }
                return false;
                break;
            case TIP_FLIP_BOTH:
                $result = @imagecopyresampled(
                                $dst_image,
                                $virtual_image,
                                0,
                                0,
                                $src_width - 1,
                                $src_height - 1,
                                $src_width,
                                $src_height,
                                -$src_width,
                                -$src_height
                );
                if ($result) {
                    return $dst_image;
                }
                return false;
                break;
            default:
                self::logger("flip", "($position) Is Not Supported Flip Position");
                return false;
                break;
        }
    }

    /**
     * flip part of the given gd resource  vertical or horizental or both.<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param constant $position HORIZENTAL or VETIP_RTICAL or BOTH
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return resource gs resource on success false otherwise
     */
    private static function _flip_part($virtual_image, $position, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $croped_image = self::crop($virtual_image, $x, $y, $width, $height);
                if ($croped_image) {
                    self::activateAlphaChannel($croped_image);
                    $flip_image = self::flip($croped_image, $position);
                    if ($flip_image) {
                        if (@ImageCopyMerge($copy, $flip_image, $x, $y, 0, 0, $width, $height, 100)) {
                            return $copy;
                        }
                    }
                }
            }

            if ($invert == false) {
                $croped_image = self::crop($virtual_image, $x, $y, $width, $height);
                if ($croped_image) {
                    self::activateAlphaChannel($croped_image);
                    $flip_image = self::flip($copy, $position);
                    if ($flip_image) {
                        if (@ImageCopyMerge($flip_image, $croped_image, $x, $y, 0, 0, $width, $height, 100)) {
                            return $flip_image;
                        }
                    }
                }
            }
        }
        return false;
    }

    /**
     * flip part of the given gd resource vertical or horizental or both.<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param constant $position HORIZENTAL or VETIP_RTICAL or BOTH
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return resource gs resource on success false otherwise
     */
    public static function flip_part($virtual_image, $position, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("flip_part", " Not A GD Resource");
            return false;
        }
        return self::_flip_part($virtual_image, $position, $x, $y, $width, $height, true);
    }

    /**
     * flip the given gd resource except the selected area vertical or horizental or both.<br/>
     * <b>Important:</b>
     * This method will return new gd resource and won't affect the given gd Resource
     * @param resource $virtual_image gd resource
     * @param constant $position HORIZENTAL or VETIP_RTICAL or BOTH
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return resource gs resource on success false otherwise
     */
    public static function flip_invert($virtual_image, $position, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("flip_invert", " Not A GD Resource");
            return false;
        }
        return self::_flip_part($virtual_image, $position, $x, $y, $width, $height, false);
    }

    /**
     * Apply edge effect for the given gd resource<br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @return boolean true on success false otherwise
     */
    public static function edge($virtual_image) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("edge", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        if (function_exists('imageconvolution')) {
            $matrix = array(array(0, -1, 0), array(-1, 4, -1), array(0, -1, 0));
            return @imageconvolution($virtual_image, $matrix, 1, 0);
        }
        self::logger("edge", "GD Lib Does Not Support Edge Method");
        return false;
    }

    /**
     * Apply edge effect for part of the given gd resource <br/>
     * or the given gd resource except the selected area<br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _edge_part($virtual_image, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $edge_image = self::edge($copy);
                if ($edge_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $edge_image = self::edge($virtual_image);
                if ($edge_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * Apply edge effect for part of the given gd resource <br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function edge_part($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("edge_part", " Not A GD Resource");
            return false;
        }
        return self::_edge_part($virtual_image, $x, $y, $width, $height, true);
    }

    /**
     * Apply edge effect the given gd resource except the selected area.<br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function edge_invert($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("edge_invert", " Not A GD Resource");
            return false;
        }
        return self::_edge_part($virtual_image, $x, $y, $width, $height, false);
    }

    /**
     * apply emboss effect for the given gd resource<br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $offset color offset in range (1,100) where 1 == default
     * @param integer $normalization normalization in range (0,360) where 127 == default
     * @return boolean true on success false otherwise
     */
    public static function emboss($virtual_image, $offset, $normalization) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("emboss", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        $offset = $offset >= 1 && $offset <= 100 ? $offset : 1;
        $normalization = $normalization >= 0 && $normalization <= 360 ? $normalization : 127;
        if (function_exists('imageconvolution')) {
            $matrix = array(array(2, 0, 0), array(0, -1, 0), array(0, 0, -1));
            return @imageconvolution($virtual_image, $matrix, $offset, $normalization);
        }
        self::logger("emboss", "GD Lib Does Not Support Emboss Method");
        return false;
    }

    /**
     * apply emboss effect for the given gd resource except the selected area <br>
     * or part of given gd resource <br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $offset color offset in range (1,100) where 1 == default
     * @param integer $normalization normalization in range (0,360) where 127 == default
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _emboss_part($virtual_image, $offset, $normalization, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $emboss_image = self::emboss($copy, $offset, $normalization);
                if ($emboss_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $emboss_image = self::emboss($virtual_image, $offset, $normalization);
                if ($emboss_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * apply emboss effect for part of the given gd resource <br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $offset color offset in range (1,100) where 1 == default
     * @param integer $normalization normalization in range (0,360) where 127 == default
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function emboss_part($virtual_image, $offset, $normalization, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("emboss_part", " Not A GD Resource");
            return false;
        }
        return self::_emboss_part($virtual_image, $offset, $normalization, $x, $y, $width, $height, true);
    }

    /**
     * apply emboss effect for the given gd resource except the selected area <br>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $offset color offset in range (1,100) where 1 == default
     * @param integer $normalization normalization in range (0,360) where 127 == default
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function emboss_invert($virtual_image, $offset, $normalization, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("emboss_invert", " Not A GD Resource");
            return false;
        }
        return self::_emboss_part($virtual_image, $offset, $normalization, $x, $y, $width, $height, false);
    }

    /**
     * apply light effect for the given gd resource<br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @return boolean true on success false otherwise
     */
    public static function light($virtual_image) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("light", " Not A GD Resource");
            return false;
        }
        self::activateAlphaChannel($virtual_image);
        if (function_exists('imageconvolution')) {
            $matrix = array(array(0, 0, 1), array(0, 1, 0), array(1, 0, 0));
            return @imageconvolution($virtual_image, $matrix, 1, 0);
        }
        self::logger("light", "GD Lib Does Not Support Light Method");
        return false;
    }

    /**
     * apply light effect for the given gd resource except the selected area <br>
     * or part of given gd resource <br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @param boolean $invert if true apply effect for selected area if false all except the selected area
     * @return boolean true on success false otherwise
     */
    private static function _light_part($virtual_image, $x, $y, $width, $height, $invert) {
        self::activateAlphaChannel($virtual_image);
        $copy = self::gdClone($virtual_image);
        if ($copy) {
            self::activateAlphaChannel($copy);
            if ($invert == true) {
                $light_image = self::light($copy);
                if ($light_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }

            if ($invert == false) {
                $light_image = self::light($virtual_image);
                if ($light_image) {
                    return @ImageCopyMerge($virtual_image, $copy, $x, $y, $x, $y, $width, $height, 100);
                }
            }
        }
        return false;
    }

    /**
     * apply light effect for part of given gd resource <br/>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function light_part($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("light_part", " Not A GD Resource");
            return false;
        }
        return self::_light_part($virtual_image, $x, $y, $width, $height, true);
    }

    /**
     * apply light effect for the given gd resource except the selected area <br>
     * <b>Important:</b>
     * This method require PHP to be compiled with the bundled version of the GD library.
     * @param resource $virtual_image gd resource
     * @param integer $x x coordinate to start point
     * @param integer $y y coordinate to start point
     * @param integer $width  width
     * @param integer $height height
     * @return boolean true on success false otherwise
     */
    public static function light_invert($virtual_image, $x, $y, $width, $height) {
        if (@get_resource_type($virtual_image) != TIP_RT) {
            self::logger("light_invert", " Not A GD Resource");
            return false;
        }
        return self::_light_part($virtual_image, $x, $y, $width, $height, false);
    }

}
?>