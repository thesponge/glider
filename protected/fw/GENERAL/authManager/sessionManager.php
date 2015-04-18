<?php

/**
 * sessionManager
 * Universal session manager used by authentication controller.
 *
 * @uses authCommon
 * @package Auth
 * @version 0.1.2
 * @copyright Copyright (c) 2010 Serenity Media
 * @author  Victor Nițu <victor@serenitymedia.ro>
 * @license http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 */
class sessionManager extends authCommon {


    static function startSession($sid=NULL) {
        if($sid == NULL) {
            session_start();
        }
        else {
            session_start($sid);
        }
    }

    static function AJAXresumeSession() {
        assert(isset($_COOKIE['PHPSESSID']));
                // 'You must enable cookies for this to work properly!');
        session_start($_COOKIE['PHPSESSID']);
    }

    static function unsetSession() {
        $_SESSION = array();
        unset($_SESSION);
    }

    static function destroySession() {
        //unset($core);
        unset($_SESSION['auth']);
        unset($_SESSION['user']);
        unset($_SESSION['NR_pages']);
        session_destroy();
    }

    static function clearCache(){
        header("Cache-Control: no-cache, must-revalidate");
        header("Expires: Mon, 26 Jul 1997 05:00:00GMT");
        //header("Content-Type: application/xml; charset=utf-8");
    }

    static function unsetCookies($cookies = 'all') {
        if (isset($_SERVER['HTTP_COOKIE'])) {
            $cookies = explode(';', $_SERVER['HTTP_COOKIE']);
            foreach($cookies as $cookie) {
                $parts = explode('=', $cookie);
                $name = trim($parts[0]);
                setcookie($name, '', time()-1000);
                setcookie($name, '', time()-1000, '/');
            }
        }
    }

    static function setSessionCookie($userData,$expires=3600) {
        //TODO: set a cookie with session data
        //setcookie("user[uname]",$_SESSION['auth']->name.'');
        //setcookie("user[uid]",  $_SESSION['auth']->uid.'');
        foreach ($userData as $key => $value)
        {
            setcookie("auth[$key]", base64_encode($value), time()+$expires);
        }
        setcookie("auth['sid']", base64_encode(session_id()));
        //var_dump($_COOKIE);
    }

    static function sessionToSQL($expires=3600) {
        $DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
        $sid = $_COOKIE['PHPSESSID'];
        $uid = base64_decode($_COOKIE['auth']['uid']);
        $address = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];
        $time = time();

        //var_dump($DB);
        //var_dump($sid);
        echo "<br/>\r\n";

        $query = "REPLACE INTO auth_sessions
                        (sid, uid, address, agent, time, expires)
                    VALUES
                        ('$sid', '$uid', '$address', '$agent', '$time', '$expires');
                    ";
        $DB->query($query);
        if($DB->query($query) == FALSE)
            return FALSE;
        else
            return TRUE;
    }

    static function attemptCookieLogin() {
        $sid = base64_decode($_COOKIE['auth']['sid']);
        $uid = base64_decode($_COOKIE['auth']['uid']);
        $address = $_SERVER['REMOTE_ADDR'];
        $agent = $_SERVER['HTTP_USER_AGENT'];

        $rodb = new mysqli(DB_HOST,roDB_USER,roDB_PASS,DB_NAME);
        $query = "SELECT uid,address,agent,time,expires
                    FROM auth_sessions
                    WHERE sid = '$sid';";
        $result = $rodb->query($query);
        $dbSession = $result->fetchObject();

        if($dbSession->uid == $uid
            && $dbSession->address = $address
            && $dbSession->agent   = $agent
        ) {
            return TRUE;
        }

        // TODO: verify session against database
    }
}
