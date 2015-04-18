<?php
/**
 *  $DB_table_origin='picManager'
 *  $DB_table = blog_picManager
 *
 *
 *  idPic = [nr]  sau [nr]new
 *
 * dar de unde sa iau eu prefixul?
 * sa il las asa deocamdata? ...sau...hm...cred ca il las asa
 * deocamdata...o sa ajung eu sa ma dau cu capul
 *
 * - STEPS:
 *
 *  - conectarea la baza de date
 *  - datele care le primesc
 *
 *  - daca primesc un id = [nr]new
 *      => voi deletajj dupa url pentru ca nu am cum sa preiau id-ul lui
 *
 *- decat daca atunci cand il salvez sa returnez id-ul
 *si cumva acel id sa fie pus de java script cand poza este
 *adaugata
 * `
 * ATENTIE!!! - trebuie sa il pun si pe idRecord
 * in ecuatia de la picManager
 *
 */
/**
 * WORKING db - TABLE
 *  TB - blog_picManager
 *  idPic
 *  idRecord
 *  picUrl
 *  picTitle
 *  picAuth
 *  picLoc
 *  picDescr
 *  picDate
 *

 * POST DATA
 *
 * BLOCK_id  =  1
 *
 * idRecord  =  9
 *
 * picTitle_en  =  some title kkk
 *
 * picDescr_en  =  in beijing
 *
 * picAuth_en  =  cineva
 *
 * picLoc_en  =  un loc
 *
 * picDate_en  =  2012-09-05
 *
 */

 $DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

 $idPic      =  $_POST['BLOCK_id'];
 $idRecord   =  $_POST['idRecord'];
 $picTitle   =  trim($_POST['picTitle_en']);
 $picDescr   =  trim($_POST['picDescr_en']);
 $picAuth    =  trim($_POST['picAuth_en']);
 $picLoc     =  trim($_POST['picLoc_en']);
 $picDate    =  $_POST['picDate_en'];

 $query = "UPDATE blog_picManager SET

            idRecord   =$idRecord   ,
            picTitle   ='$picTitle' ,
            picDescr   ='$picDescr' ,
            picAuth    ='$picAuth'  ,
            picLoc     ='$picLoc'   ,
            picDate    ='$picDate'
            WHERE
            idPic      =$idPic
            ";

 $DB->query($query);
 $affectedRows = $DB->affected_rows;

echo $affectedRows;













