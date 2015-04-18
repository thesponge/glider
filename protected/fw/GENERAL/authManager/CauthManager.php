<?php
/**
 * CauthManager
 *
 * PHP Version 5.4
 *
 * @category  Accounts
 * @package   Auth
 * @author    Victor Nițu <victor@serenitymedia.ro>
 * @copyright 2010 Serenity Media
 * @license   http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 * @version   0.1.2
 */

class CauthManager extends authCommon implements Serializable {

    protected $loginName;
    protected $password;

    protected static $instance;

    final public static function getInstance() {
        return isset(static::$instance)
            ? static::$instance
            : static::$instance = new static(func_get_args());
    }

    final private function __construct() {
        # {{{ Dead code
        /*
         * This is a neat, not appropriate, object creation via ReflectionClass,
         * when the number of parameters is uncertain.
         */
        #$reflection = new ReflectionClass(__CLASS__);
        #return $reflection->newInstanceArgs(func_get_args());

        /*
         * This was the old post-construct callback
         */
        //$this->init(func_get_args()); # }}}

        //array_push(func_get_args(), $C);
        //print "construct: ";
        //var_dump(func_get_args());

        // Hack needed - double usage of func_get_args() increases array's depth by 1
        $args = func_get_args();
        call_user_func_array(array(__CLASS__, "init"), $args[0]);

    }

    final private function __wakeup()
    {}
    final private function __clone()
    {}

    /**
     * _render_
     *
     * @access public
     * @return void
     */
    public function _render_() {
        $display = '
          <form class="form-horizontal pull-right" action="" method="post">
            <div class="control-group">
              <div class="controls">
                <input class="loginInput input-small" name="loginName" type="text" id="inputEmail" placeholder="Email" />
                <input class="loginInput input-small" name="password" type="password" id="inputPassword" placeholder="Password" />
                <input name="login" type="hidden" value="CauthManager" />
                <input type="submit" class="btn btn-mini topbarBtn" value="Sign in" />
              </div>
            </div>
          </form>
        ';
        return $display;
    }

    public function __tostring() {
        return $this->loginName;
    }

    public function serialize() {
        return serialize(get_object_vars($this));
    }

    public function unserialize($data) {
        self::getInstance();

        // Set the values
        if (is_array($data)) {
            foreach ($data as $k => $v) {
                $this->$k = $v;
            }
        }
    }

    protected function sanitize ($var) {
        return $this->rodb->real_escape_string($var);
    }

    protected function getAllLoginDetails($loginName='', $type='email') {
        //TODO: docblock
        $loginQ = ( $type == 'email'
            ? "auth_users.email = '$loginName'"
            :  "auth_users.name = '$loginName'");
        $query = "SELECT auth_users.uid, auth_users.name AS uname, auth_users.active,
                         auth_users.cid,
                         auth_users.password, auth_users.email,
                         auth_user_details.language,
                         auth_user_details.country, auth_user_details.city,
                         auth_user_details.last_ip,
                         FROM_UNIXTIME(auth_user_details.creation, '%Y %D %M') AS joindate,
                         auth_user_details.first_name, auth_user_details.last_name,
                         auth_user_details.last_ip, auth_user_stats.failed_logins,
                         LOWER(auth_classes.name) AS uclass
                    FROM auth_users
                    JOIN auth_user_details
                        ON (auth_users.uid = auth_user_details.uid)
                    LEFT JOIN auth_user_stats
                        ON (auth_users.uid = auth_user_stats.uid)
                    LEFT JOIN auth_classes
                        ON (auth_users.cid = auth_classes.cid)
                    WHERE $loginQ;";

        $result = $this->rodb->query($query)
                    or die('Query failed: ' . $this->rodb->error);
        return $result;


    }

   /**
    * Get basic data for user
    * @param $loginName
    *
    * @return mixed
    *
    * old: getLoginDetails
    */
    protected function Get_loginDetails($loginName) {

        $loginQ = filter_var($loginName, FILTER_VALIDATE_EMAIL) != false
                ? "auth_users.email = '$loginName'"
                : "auth_users.name = '$loginName'";


        $query = "SELECT auth_users.uid,
                         auth_users.name AS uname,
                         auth_users.active,
                         auth_users.cid,
                         auth_users.password,
                         auth_users.token,
                         auth_users.email,
                         auth_users.active,

                         auth_user_stats.permissions,

                         auth_classes.name AS uclass
                    FROM auth_users
                    JOIN auth_classes
                         ON (auth_users.cid = auth_classes.cid)
                    LEFT OUTER JOIN auth_user_stats
                        ON (auth_users.uid = auth_user_stats.uid)

                    WHERE $loginQ ";

                 /*" JOIN auth_user_details
                        ON (auth_users.uid = auth_user_details.uid)
                    LEFT JOIN auth_classes
                        ON (auth_users.cid = auth_classes.cid)
                    ";*/

        $result = $this->rodb->query($query)
                    or die('Query failed: ' . $this->rodb->error);

        return $result;
    }

    /**
     * authCheck
     *
     * @param string $loginName
     * @param string $password
     * @static
     * @access public
     * @return void
     */
    public function authCheck($loginName='', $password='')
    {
        $this->rodb = new mysqli(DB_HOST,DB_RO_USER,DB_RO_PASS,DB_NAME);
        $this->rodb->set_charset("utf8");

        //echo "Setting login vars & Sanitizing login...";
        $this->loginName = $this->sanitize($loginName);
        $this->password  = $this->sanitize($password);


        if (strlen($loginName) < 1 && strlen($password) < 1) {
            return false;
        }

        $resUserData    = $this->Get_loginDetails($loginName);
        $this->userData = $resUserData->fetch_object();

        if ($this->userData->active != 1) {
            trigger_error("Inactive $loginName tried to log in", E_USER_NOTICE);
            return false;
        } elseif (md5($password) !== $this->userData->password) {
            trigger_error("Wrong password for $loginName", E_USER_NOTICE);
            return false;
        }
        return true;

    }

    /*public  function Set_toolbarButtons(&$C)
    {
        array_push($C->TOOLbar->buttons,"
            <a href='/cluj/?logOUT=1' id='logOUT'>
                Log out {$C->user->uname}
                [ id: {$C->user->uid} | class: {$C->user->uclass} ]
            </a>
        ");
    }*/

    /**
     * login
     *
     * @access protected
     * @return void
     */
    protected function login()
    {
        // true / false - autentificat sau nu
        $authStatus = $this->authCheck($_POST['loginName'], $_POST['password']);

        if ($authStatus) {

            $_SESSION['userData'] = $this->userData;
            // daca userul este autentificat
            $_SESSION['auth']     = true;

            // --------[ set session cookie ]-------
            //sessionManager::setSessionCookie($this->userData, 3600);
            //sessionManager::sessionToSQL(3600);
        } else {
            // Return 0, this means the check returned a Guest account
            unset($_SESSION['auth']);
            unset($_SESSION['userData']);
        }
        //Toolbox::clearSubmit();

        isset($_SESSION['auth']) || Toolbox::relocate('/cluj/');

        if (isset($_SESSION['postLoginURL'])) {
            $url = $_SESSION['postLoginURL'];
            unset($_SESSION['postLoginURL']);
            Toolbox::relocate($url);
        }
    }

    /**
     * logout
     *
     * @access protected
     * @return void
     */
    protected function logout()
    {
        //sessionManager::destroySession();
        //sessionManager::unsetCookies();
        global $session;
        $session->stop();
        Toolbox::relocate('/cluj/');
    }

    protected function init ()
    {
        if (isset($_POST['login']) && $_POST['login'] == __CLASS__) {
            $this->login();
        } elseif (isset($_GET['logOUT'])) {
            $this->logout();
        }

    }
}
