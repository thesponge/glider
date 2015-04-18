<?php

/**
 * authCommon
 * Manager class for controlling the authentication mechanics
 *
 * @package Auth
 * @category
 * @version 0.1.2
 * @copyright Copyright (c) 2010 Serenity Media
 * @license http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 * @author  Victor Nițu <victor@serenitymedia.ro>
 */
class authCommon {

    static function isAuth() {
        if(isset($_SESSION['auth']) && is_object($_SESSION['auth']))
            return true;
    }

    static function isAdmin() {
        if($_SESSION['auth']->cid == 1 || $_SESSION['auth'] == 7)
            return TRUE;
        else
            return FALSE;
    }

    protected function incrementFails($uid) {
        //TODO: docblock
        $query = "UPDATE auth_user_stats
                    SET failed_logins = failed_logins+1
                    WHERE uid='$uid'";
        if(!$this->rodb->query($query))
            throw new Exception('Query failed: ' . $this->DB->error);
        else
            return true;
    }

}
