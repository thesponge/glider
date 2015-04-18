<?php
/** {{{
 * Script containing the basic login and rights management procedure
 *
 * PHP Version 5.3+
 *
 * @category Accounts
 * @package  Auth
 * @author   Victor Nițu <victor@serenitymedia.ro>
 * @license  http://www.gnu.org/licenses/agpl-3.0.html AGPLv3
 * @link
 *     http://redmine.usr.sh/projects/ivy-framework/wiki/Authentication_system
 * }}}
*/


// ------[ get the class loader ]-------
require FW_INC_PATH.'GENERAL/core/scripts/classLoader.inc';
// =====================================


$dbLink = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME) or die('No database!');

/* change character set to utf8 */
if (!$dbLink->set_charset("utf8")) {
    printf("Error loading character set utf8: %s\n", $dbLink->error);
} else {
    //printf("Current character set: %s\n", $dbLink->character_set_name());
}


// config???
ini_set("session.gc_probability", 100);
ini_set("session.gc_divisor", 100);
ini_set("session.gc_maxlifetime", 3600);

$session = new Zebra_Session($dbLink, 'JbBJSvgtAdZY');

// ------[ create the auth object ]------
$auth = CauthManager::getInstance();
// ======================================


// ---------[ load the base class ]------
if (isset($_SESSION['auth'])) {
    $core = new ACLcore($dbLink);
    //$auth->Set_toolbarButtons($core);
} else {
    $core = new CLcore($dbLink);
}

if (isset($_GET['route']) && $_GET['route'] != 'invite') {
    $core->Set_lastURL();
}

// $_SESSION['core'] = &$core;
// ======================================

/*
 * Pentru a putea sa ma refer la core
 * din interiorul lui procesSCRIPT.php
 * */

$sercore     = serialize($core);
// $serSESSION = session_encode();

Toolbox::Fs_writeTo(
    VAR_PATH.'tmp/sessions/' . session_id() . '/sercore.txt',
    $sercore
);
// file_put_contents(FW_PUB_PATH.'GENERAL/core/RES/serSESSION.txt', $serSESSION);


//???
// $p = new Permissions($_SESSION['auth']->uid);



// Console::logMemory($core,'core');
// Console::logMemory($auth,'auth');
// Console::logMemory();

// file_put_contents('serial.txt', serialize($core));
