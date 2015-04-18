<?php
/**
 *  $DB_table_origin='picManager'
 *  $DB_table = blog_picManager
 *
 *
 * POST DATA
 * BLOCK_id = 8
 * idRecord = 9
 *
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
 */

$DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

$idPic      =  $_POST['BLOCK_id'];
$idRecord   =  $_POST['idRecord'];

$query = "DELETE from blog_picManager WHERE idPic = $idPic ";
$DB->query($query);

$affected_rows = $DB->affected_rows;

echo $affected_rows;
