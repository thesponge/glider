<?php
    require_once dirname($_SERVER['DOCUMENT_ROOT']) . '/protected/etc/config.base.php';
    require_once FW_INC_PATH.'GENERAL/core/scripts/classLoader.inc';

    $DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME) or die('No database');

    $session = new Zebra_Session($DB, 'JbBJSvgtAdZY');
    $cid = $_SESSION['userData']->cid;
    //var_dump($_SESSION);

    $options = '';

    $result = $DB->query("SELECT cid, name FROM auth_classes WHERE cid >= $cid");
    while ($row = $result->fetch_assoc()) {
        $options .= "<option value = '".$row['cid']."'>".$row['name']."</option>>";
    }
?>

<form action='' method='post'  style='text-align: center;'>
    <span>User type: </span>
    <select name='cid'>
        <?php echo $options; ?>
    </select>
    <br>
    <br>
    <input type='text' name='email' placeholder='email'  class='ivy-light ivy-padded'  />
    <br>

    <input type='hidden' name='modName' value='user' />
    <input type='hidden' name='methName' value='inviteUser' />
    <input type='hidden' name='ref' value='<?php echo $cid; ?>' />
    <input type='hidden' name='sid' value='<?php echo $_GET['sid']; ?>' />
    <br>
    <input type='submit' name='inviteMember' value='Invite' class='ivy' />

</form>
