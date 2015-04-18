<?php

$db = new mysqli('localhost','root','hibernia@!','blacksea_dev');

ob_start();
print_r($_POST);
$buffer = ob_get_clean();


$col   = trim($_POST['column']);
$value = trim($_POST['value']);
$uid   = trim($_POST['uid']);

$query = "UPDATE auth_users_datatables SET $col = '$value' WHERE uid = '$uid';";
$db->query($query);

file_put_contents('fisier_json.txt',"Buffer contents: \n".$buffer."\n\n $query \n".$db->error."\n\n");

//return (json_encode($_POST['value']));
echo $_POST['value'];
?>
