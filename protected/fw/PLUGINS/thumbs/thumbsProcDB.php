<?php


    $DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

    $DB_table_prefix  = $_POST['DB_table_prefix'];
    $DB_extKey_name   = $_POST['DB_extKey_name'];
    $DB_extKey_value  = $_POST['DB_extKey_value'];

    $thumbsUp   = intval($_POST['thumbsUp']);
    $thumbsDown = intval($_POST['thumbsDown']);



$query = "REPLACE INTO {$DB_table_prefix}_thumbs
                  ( $DB_extKey_name , thumbsUp, thumbsDown)
                    VALUES('$DB_extKey_value', $thumbsUp, $thumbsDown)";


$DB->query($query);

#echo $query;