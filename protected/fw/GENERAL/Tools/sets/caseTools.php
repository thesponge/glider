<?php

trait caseTools {

    static function capitalize ($match){
        return $match[1] . $match[2] . ucfirst($match[3]);
    }

    static function camelize ($match){
        return ucfirst($match[3]);
    }

    static function sentenceCase($str){
        // search for punctuation which should precede uppercase letters, then
        // send the matches to callback function
        //
        // TODO: sentence case quoted texts

        $str = preg_replace_callback ('[(\?|\!|\.)(\s)*(\w*)]', 'self::capitalize', $str);
        // regex doesn't make the first letter uppercase, so I'm doing it "manually"
        return ucfirst($str);
    }

    static function camelCase($str){
        $str = preg_replace_callback ('[(\?|\!|\.|\b)(\s)*(\w*)]', 'self::camelize', $str);
        return lcfirst($str);
    }
}
