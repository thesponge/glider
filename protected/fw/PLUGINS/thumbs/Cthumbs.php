<?php
class thumbsSet{

    var $postFix_table   ;
    var $extKey_name  ;
    var $extKey_value ;
    var $thumbsUp   = 0  ;
    var $thumbsDown = 0  ;

}

class Cthumbs{


    var $setName;


    function getThumbs(){

           $setObj   = &$this->{$this->setName};

           $query    = "SELECT * FROM {$setObj->DB_table}
                                 WHERE {$setObj->DB_extKey_name} =  '{$setObj->DB_extKey_value}'
                        ";
           $thumbsRes = $this->C->GET_objProperties($setObj , $query);

           # var_dump($setObj);
           # echo $query;
    }


    /**
     * functie apelata de cel care cere un ratind de acest tip
     * - set nameul ar trebui sa fie unic si preferabil cu un prefix SET_
     *
     * setul
     *  -  setObj trebuie sa contina toate var necesare template_vars
     *  -  variabile sunt utilizate de  javaScript pt a putea  trimite datele necesare pentru procesare DB
     *
     * @param $table_postfix   - postfixul tavelului de thumbs => tabelName = thumbs_[postFix_table]
     * @param $extKey_name  - numele cheii externe din tabel
     * @param $extKey_value - valoarea cheii externe
     * @param $setName      - numele unic al setului
     */

    function setINI($table_prefix,$extKey_name,$extKey_value, $setName){

        # CEVA MAI GENERALIZAT???
        $this->setName = $setName;
        if(!isset($this->$setName))
        {

            $this->$setName = new thumbsSet();
            $set = &$this->$setName;

            /**
             * fucking fishy... un astfel de aproach este foarte ilizibil ca sa zic asa
             * SET_tableRelations_settings (&$obj,$extKname, $extKvalue, $tbOrigin, $tbPostfix='', $tbPrefix='', $bond='_')
             *
             * SETEAZA
                  * $obj->DB_table         = prefix + origin + postfix
                  *
                  *                           @param        $obj           - obiectul pentru care se fac setarile
                  * $obj->DB_extKey_name      @param        $extKname      - numele cheii externe
                  * $obj->DB_extKey_value     @param        $extKvalue     - valoarea cheii externe
                  * $obj->DB_table_origin     @param        $tbOrigin      - numele tabelului de origine
                  * $obj->DB_table_Postfix    @param string $tbPostfix
                  * $obj->DB_table_Prefix     @param string $tbPrefix
                  *                           @param string $bond          - concatenare nume DB_table

             */
            /*$this->C->SET_tableRelations_settings
                         ($this->$setName, $extKey_name,  $extKey_value, 'thumbs', $table_postfix);*/

            $set->DB_table        = $table_prefix.'_thumbs';
            $set->DB_table_prefix = $table_prefix;
            $set->DB_extKey_value = $extKey_value;
            $set->DB_extKey_name  = $extKey_name;

            $this->getThumbs();

        }
    }

    function __construct($C){}
}