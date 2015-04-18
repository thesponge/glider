<?php

/**
 * POST data:
 *  urlPic
 *  idRecord = idRecord_[idRecordValue]
 *
 *  WORKING db - TABLE
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
 *  RET:
 *  insert_id - cheia primara inserata
 *            - utila pt picManager.js - carousel_addPic - pt a crea corect domul
 *
 *  LOGISTICS
 *  - urmatoarele campuri vor fii adaugate de update-ul pe poza
 *
 */
$url          =  $_POST['urlPic'];
$idRecord_arr =  explode('_',$_POST['idRecord']) ;
$idRecord     =  $idRecord_arr[1];

$DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$query = "INSERT into blog_picManager
                    (idRecord, picUrl)
             values ($idRecord, '$url')";

$DB->query($query);

/*echo "_POST['idRecord'] ".$_POST['idRecord']."\n"
        .'query '.$query."\n insert id ".$DB->insert_id;*/

echo $DB->insert_id;