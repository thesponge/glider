<?php
/**
 * Clasa de validare scrisa initial pentru CARP "Omenia" in cadrul SEAD
 *
 * @author    Victor Nitu <victor[at]serenitymedia[.]ro>
 *
 * @desc Clasa de validare scrisa pentru CARP "Omenia" in cadrul SEAD, neterminata
 *
 * TODO: refactoring
 * TODO: callbackuri neergonomice, restructurare necesara
 */
class Validation{

    const pattern_email = '/^([a-zA-Z0-9_\-\.]+)@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.)|(([a-zA-Z0-9\-]+\.)+))([a-zA-Z]{2,4}|[0-9]{1,3})(\]?)$/';
    const pattern_phone = '/^[0-9]{10}$/';
    const pattern_numeric_punctuation = '/^[0-9\.\-\_\s]{6,}$/';
    const pattern_ip    = '/([0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3})/';
    //var $pattern_url   = '/^(http|https|ftp):\/\/(www\.)?.+\.([.]{2,4})$/';
    //var $pattern_url   = '/(?i)\b((?:[a-z][\w-]+:(?:\/{1,3}|[a-z0-9%])|www\d{0,3}[.]|[a-z0-9.\-]+[.][a-z]{2,4}\/)(?:[^\s()<>]+|\(([^\s()<>]+|(\([^\s()<>]+\)))*\))+(?:\(([^\s()<>]+|(\([^\s()<>]+\)))*\)|[^\s`!()\[\]{};:\'".,<>?«»“”‘’]))/';
    const pattern_url   = "#((http|https|ftp)://(\S*?\.\S*?))(\s|\;|\)|\]|\[|\{|\}|,|\"|'|:|\<|$|\.\s)#ie";
    const pattern_cnp   = '/(^1|2)([0-9]{2})((([0]{1})([0-9]{1}))|(([1]{1})([1-2]{1})))((([0]{1})([0-9]{1}))|(([1-2]{1})([0-9]{1}))|(([3]{1})([0-1]{1})))([0-9]{6})(\b$)/';
    const pattern_serieBI = '/^([a-zA-Z]{2})$/';
    const pattern_nrBI    = '/^([0-9]{6})$/';

    public function __construct() {
        //throw new Exception("Cannot instantiate Validation!");
        //exit;
    }


    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere ale alfabetului
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere ale alfabetului
     * @return bool
     */
    static function alpha($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_alpha="/^[a-zA-Z]{".$min.",".$max."}$/";
        return (preg_match($pattern_alpha,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice scrise cu litere mici
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice scrise cu litere mici
     * @return bool
     */
    static function alphaLower($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_alphalow="/^[a-z]{".$min.",".$max."}$/";
        return (preg_match($pattern_alphalow,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice scrise cu litere mari
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice scrise cu litere mari
     * @return bool
     */
    static function alphaUpper($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_alphaup="/^[A-Z]{".$min.",".$max."}$/";
        return (preg_match($pattern_alphaup,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere numerice
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere numerice
     * @return bool
     */
    static function numeric($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_numeric="/^[0-9]{".$min.",".$max."}$/";
        return (preg_match($pattern_numeric,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice
     * @return bool
     */
    static function alphanum($string,$min = 0,$max = 'n'){
        $max = str_replace('n','',$max);
            $pattern_alphanum="/^[0-9a-zA-Z\pL]{".$min.",".$max."}$/u";
        return (preg_match($pattern_alphanum,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice plus cratime, spatii si apostrof
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere alfanumerice plus cratime, spatii si apostrof
     * @return bool
     */
    static function name($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_name="/^[\w\pL-'\s]{".$min.",".$max."}$/u";
	    $pattern_name_mb="^[a-zA-Z-'\s]{".$min.",".$max."}$";
        return (preg_match($pattern_name,$string)>0 ? 1 : 0);
        //return (mb_ereg_match($pattern_name_mb,$string)==TRUE ? 1 : 0);
    }

    /**
     * Valideaza stringul de intrare daca acesta este constituit din caractere specifice unei fraze (litere si punctuatie)
     * @param int $num_chars numarul de caractere permis
     * @param string $behave defineste comportamentul fata de numarul de caractere: min, max sau exact
     * @desc Valideaza stringul de intrare daca acesta este constituit din caractere specifice unei fraze (litere si punctuatie)
     * @return bool
     */
    static function text($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_name="/^[\S\s]{".$min.",".$max."}$/";
        return (preg_match($pattern_name,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul daca reprezinta o adresa de email valida
     * @desc Valideaza stringul daca reprezinta o adresa de email valida
     * @return bool
     */
    static function email(){
        //return (preg_match($this->pattern_email,func_get_arg(0))>0 ? 1 : 0);
        return (filter_var(filter_var(func_get_arg(0), FILTER_SANITIZE_EMAIL),FILTER_VALIDATE_EMAIL) ? 1 : 0);
    }

    /**
     * Valideaza stringul daca reprezinta un numar de telefon valid
     * @desc Valideaza stringul daca reprezinta un numar de telefon valid
     * @return bool
     */
    static function phone(){
        return (preg_match(Validation::pattern_phone,func_get_arg(0))>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul daca e alcatuit din numere si semne de punctuatie
     * @desc Valideaza stringul daca e alcatuit din numere si semne de punctuatie
     * @return bool
     */
    static function numeric_punctuation($string,$min,$max){
        $max = str_replace('n','',$max);
            $pattern_name="/^[0-9\.\-\_\s]{".$min.",".$max."}$/";
        return (preg_match($pattern_name,$string)>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul daca reprezinta o adresa IP valida
     * @desc Valideaza stringul daca reprezinta o adresa IP valida
     * @return bool
     */
    static function ip_address($ip){
        return (preg_match(Validation::pattern_ip,func_get_arg(0))>0 ? 1 : 0);
    }

    /**
     * Valideaza stringul daca reprezinta o adresa URL valida
     * @desc Valideaza stringul daca reprezinta o adresa URL valida
     * @return bool
     */
    static function url(){
        //return (preg_match($this->pattern_url,func_get_arg(0))>0 ? 1 : 0);
        return (filter_var('http://'.filter_var(func_get_arg(0), FILTER_SANITIZE_URL),FILTER_VALIDATE_URL) ? 1 : 0);
        //return func_get_arg(0);
    }
    /**
     * Valideaza stringul daca reprezinta o adresa URL valida
     * @desc Valideaza stringul daca reprezinta o adresa URL valida
     * @return bool
     */
    static function checkbox($name,$value=''){
        if(is_array($_POST[$name])){
            if(isset($_POST[$value])) return 1;
            else return 0;
        }
        elseif(isset($_POST[$value])) return 1;
        else return 0;
    }

    static function trusted($name) {
        return 1;
    }
}
