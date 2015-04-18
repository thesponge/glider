<?php
/**
 *  PHPDoc comment...
 */

/**
 * Toolbox
 *
 * @uses LibToolbox
 * @package Webdav
 * @version //autogen//
 * @copyright Copyright (c) 2010 All rights reserved.
 * @author
 * @license PHP Version 3.0 {@link http://www.php.net/license/3_0.txt}
 */
class Toolbox extends LibToolbox
{


    static function clearSubmit()
    {
        unset($_POST);
        header("Location: http://".$_SERVER['REQUEST_URI']);
    }

    static function http_response_code($uri)
    {
        $headers = get_headers($uri);
        return substr($headers[0], 9, 3);
    }

    static function dump($var, $varName = 'variable')
    {
        list(, $trace) = debug_backtrace(false);
        $file = substr(strrchr(dirname($trace['file']), '/'), 1);
        $file .= '/' . basename($trace['file']);

        printf(
            "<small>--> <b>%s</b> in <i>%s</i>:<b>%s</b> (%s::%s) \n",
            $varName, $file, $trace['line'], $trace['class'], $trace['function']
        );
        var_dump($var);
        print "</small>";
    }

    static function postRequest($url, $data, $referer='')
    {

    // Convert the data array into URL Parameters like a=b&foo=bar etc.
    $data = http_build_query($data);

    // parse the given URL
    $url = parse_url($url);

    if ($url['scheme'] != 'http') {
        die('Error: Only HTTP request are supported !');
    }

    // extract host and path:
    $host = $url['host'];
    $path = $url['path'];

    // open a socket connection on port 80 - timeout: 30 sec
    $socket = fsockopen($host, 80, $errno, $errstr, 30);

    if ($socket) {

        // send the request headers:
        fputs($socket, "POST $path HTTP/1.1\r\n");
        fputs($socket, "Host: $host\r\n");

        if ($referer != '')
            fputs($socket, "Referer: $referer\r\n");

        fputs($socket, "Content-type: application/x-www-form-urlencoded\r\n");
        fputs($socket, "Content-length: ". strlen($data) ."\r\n");
        fputs($socket, "Connection: close\r\n\r\n");
        fputs($socket, $data);

        $result = '';
        while (!feof($socket)) {
            // receive the results of the request
            $result .= fgets($socket, 128);
        }
    } else {
        return array(
            'status' => 'err',
            'error' => "$errstr ($errno)"
        );
    }

    // close the socket connection:
    fclose($socket);

    // split the result header from the content
    $result = explode("\r\n\r\n", $result, 2);

    $header = isset($result[0]) ? $result[0] : '';
    $content = isset($result[1]) ? $result[1] : '';

    // return as structured array:
    return array(
        'status' => 'ok',
        'header' => $header,
        'content' => $content
    );
    }

    static function relocate($location='', $ANCORA='',$paramAdd='')
    {
        unset($_POST);
        //$location = ($location=='' ? $_SERVER['REQUEST_URI'] :$location);
        if (empty($location)) {
            $location = Toolbox::curURL();
        }

        //header("Location: ".$location.$paramAdd.$ANCORA);

        echo "<script type='text/javascript'>window.location = '$location';</script>";
        echo "<a href='$location'>Click here if the browser does not redirect you automatically</a>";
        exit;
    }

    /* Filesystem tools */

    static function pathExists($file)
    {
        if (file_exists($file)) {
            return pathinfo($file);
        } else {
            throw new Exception(
                'File or directory does not exist ['. $file .']'
            );
        }
    }

    static function Fs_writeTo($file, $data, $mode='w')
    {
        try {
            self::pathExists($file);
        } catch (Exception $e) {
            error_log($e->getMessage() . '[trying to create]', E_USER_WARNING);

            // Cut the last path segment, based on forward slashes occurence
            $basedir = preg_replace("/^(.*)\/[^\/]+$/", "$1", $file);

            try {
                self::pathExists($basedir);
            } catch (Exception $e) {

                // Recursive mkdir() will try to create the eventually nested
                // directory structure
                if (!mkdir($basedir, 0777, true)) {
                //if (!self::rmkdir($basedir)) {
                    error_log(
                        "Error: mkdir() failed to create directory",
                        E_USER_WARNING
                    );
                }
                `chmod 777 -R $basedir`;
            }

            // Create the file
            try {
                self::createFile($file);
            } catch (Exception $e) {
                error_log(
                    "[Ivy] Error: failed to create file " . basename($file) . '!'
                    , E_USER_WARNING
                );
            }
        }
        // Open handle with requested mode, then let the data
        switch ($mode) {
        default:
        case 'w':
                file_put_contents($file, $data);
                break;
        case 'w+':
                file_put_contents($file, $data, FILE_APPEND);
                break;
        }
    }

    static function rmkdir($path, $mode = 0777)
    {
        $dirs = explode(DIRECTORY_SEPARATOR, $path);
        $count = count($dirs);
        $path = '.';
        for ($i = 0; $i < $count; ++$i) {
            $path .= DIRECTORY_SEPARATOR . $dirs[$i];
            if (!is_dir($path) && !mkdir($path, $mode)) {
                return false;
            }
        }
        return true;
    }

    static function createFile($file)
    {
        if (!touch($file)) {
            throw new Exception('Cannot create file ' . basename($file) . '!');
        } else {
            return true;
        }
    }

    static function createDir($dir)
    {
        if (!mkdir($dir)) {
            throw new Exception('Cannot create directory ' . $dir . '!');
        } else {
            return true;
        }
    }

    /* Traits import below */

    /* string tools */

    static function capitalize ($match)
    {
        return $match[1] . $match[2] . ucfirst($match[3]);
    }

    static function camelize ($match)
    {
        return ucfirst($match[3]);
    }

    static function sentenceCase($str)
    {
        // search for punctuation which should precede uppercase letters, then
        // send the matches to callback function
        //
        // TODO: sentence case quoted texts

        $str = preg_replace_callback(
            '[(\?|\!|\.)(\s)*(\w*)]', 'self::capitalize', $str
        );

        // regex doesn't make the first letter uppercase, doing it 'manually'
        return ucfirst($str);
    }

    static function camelCase($str)
    {
        $str = preg_replace_callback(
            '[(\?|\!|\.|\b)(\s)*(\w*)]', 'self::camelize', $str
        );
        return lcfirst($str);
    }

    function randomString($length)
    {
        $chars = "0123456789."
               . "/qwertyuiopasdfghjklzxcvbnmQWERTYUIOPASDFGHJKLZXCVBNM";
        //only allowed chars in the blowfish salt.
        $size = strlen($chars); $str = "";
        for ($i = 0; $i < $length; $i++)
            $str .= $chars[rand(0, $size - 1)]; // strings can be used as char arrays
            // Yes, I am aware this salt isn't generated using the OS source.
            // use mycrypt_create_iv or /dev/urandom/
        return $str;
    }

    static function trim_all( $str , $what = NULL , $with = ' ' )
    {
        if ( $what === NULL ) {
            //  Character      Decimal      Use
            //  "\0"            0           Null Character
            //  "\t"            9           Tab
            //  "\n"           10           New line
            //  "\x0B"         11           Vertical Tab
            //  "\r"           13           New Line in Mac
            //  " "            32           Space

            $what   = "\\x00-\\x20";    //all white-spaces and control chars
        }

        return trim(preg_replace("/[".$what."]+/", $with, $str), $what);
    }

    /* countryTools */

    static function codeToCountry ($code, $file='data/countries.json')
    {
        if (!is_file($file) || !is_readable($file)) {
            return false;
        } else {
            $match = array();
                preg_match(
                    '/({[^{]*?"'. $code .'"[^}]*?})/i',
                    file_get_contents($file),
                    $match
                );
            $json = json_decode($match[0]);
            $country = $json->name ?: 'n/a';
        }
        return $country;
    }


    /* urlTools */

    static function curURL()
    {
        $http = 'http://';

        if ((!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off')
            || $_SERVER['SERVER_PORT'] == 443) {
                $http = 'https://';
            }

        return $http.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }

    /* Database tools */

    /* public Db_getMaxID($table, mysqli $dbconn, $id = 'id') {{{ */
    /**
     * Db_getMax returns the maximum value of a field in a given table
     *
     * @param mixed $table
     * @param mysqli $dbconn
     * @param string $field
     * @static
     * @access public
     * @return void
     */
    static function Db_getMax($table, mysqli $dbconn, $field = 'id')
    {
        $query = "SELECT MAX($field) AS max FROM $table;";
        return $dbconn->query($query)->fetch_object()->max;
    }
    /* }}} */

}
/* vim: set ft=php: set fdm=marker: */
