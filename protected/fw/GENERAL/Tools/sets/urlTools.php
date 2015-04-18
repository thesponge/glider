<?php

trait urlTools {

    static function curURL()
    {
        $https = $_SERVER['HTTPS'] == '' ? 'http://' : 'https://';
        return $https.$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
    }


}
