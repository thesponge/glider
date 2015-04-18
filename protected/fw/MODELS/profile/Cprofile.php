<?php
class Cprofile extends Cuser{

    var $recordsArchive = array(); // articolele scrise de user in partea de archive
    var $recordsBlog  = array(); // articolele scrise de user in partea de blog

    // about page
    var $profiles = array();
    var $editors = array();

    function _hookRow_userData($row)
    {
        $row['editStatus'] = 'not';
        return $row;
    }
    function Set_userData($uid)
    {
        $queryProfile = "
        SELECT
            auth_classes.name AS uclass,
            auth_users.cid,
            auth_users.name AS uname,
            auth_users.active,
            auth_users.email,

            auth_user_details.uid,
            first_name,
            last_name,
	        CONCAT(first_name, ' ', last_name) AS fullName,
	        title,
	        bio,
	        phone,
	        photo,
	        site

	    FROM auth_user_details
	    JOIN auth_users
	      ON (auth_user_details.uid = auth_users.uid)
	    JOIN auth_classes
	      ON (auth_users.cid = auth_classes.cid)

	    WHERE auth_users.uid = $uid
        ";

        //$this->profile = new stdClass();
        //echo "Cprofile query = $queryProfile <br>";

        $profile =$this->C->Db_Get_procRows($this, '_hookRow_userData', $queryProfile);

        $this->profile =  (object) $profile[0];


        //var_dump($this->user);

    }
    function Set_profileData()
    {
        $uid = $_GET['uid'];
        $this->Set_userData($uid);

        $blog = new CblogExternal($this);
        $blogHandler = &$blog->handler;
        $blogHandler->profile_setData($uid);
        $this->recordsArchive = $blogHandler->recordsArchive;
        $this->recordsBlog = $blogHandler->recordsBlog;

        // echo "CblogSite - Set_profileData recordsArchive & recordsBlog";
        // var_dump($this->recordsArchive);
        // var_dump($this->recordsBlog);
    }



    // about page
    function _hookRow_aboutData($row)
    {
        $dbStrings = array('uploads/', '.png', '.jpg', '.PNG', '.JPG');
        $replacementStrings = array('uploads/.thumbs/', '_300x250.png', '_300x250.jpg', '_300x250.PNG', '_300x250.JPG');

        $row['photo'] = str_replace($dbStrings, $replacementStrings, $row['photo']);
        $row['hrefProfile'] = "?idT={$this->idTree}&idC={$this->idNode}&uid=".$row['uid'];

        return $row;
    }

    function Get_preProfileQuery()
    {
        $queryProfiles = "
                SELECT
                    auth_user_details.uid,
                    cid,
                    active,
        	        CONCAT(first_name, ' ', last_name) AS fullName,
        	        title,
        	        photo

        	    FROM auth_user_details
        	    JOIN auth_users
        	    ON (auth_user_details.uid = auth_users.uid)";

        return $queryProfiles;
    }
    function Get_Filter_publishers()
    {
        return "cid NOT IN (5, 6)   ORDER BY RAND() ";
    }
    function Get_Filter_editors()
    {
        return "cid = 5  ORDER BY RAND() ";
    }
    function Set_aboutData()
    {
        // not editors
        $sql  =
            $this->C->Db_Get_queryParts($this, 'Get_preProfileQuery', array('publishers' => ''));

        $this->profiles =
            $this->C->Db_Get_procRows($this, '_hookRow_aboutData', $sql->query);
        // echo "Cprofile - Set_aboutData : $queryProfiles <br>";
        // var_dump($this->profiles);

    }

    function _init_()
    {
        if($this->template_file) {
            $methHandler = $this->template_file;
            $this->{'Set_'.$methHandler.'Data'}();
        } else {
            error_log("[ ivy ] Cprofile - _init_ : No template_file was defined");
        }
    }

    function _handle_requests()
    {
        if($_GET['uid']) {
            $this->template_file = "profile";
        } else {
            /**
             * nu poate fi scris in blogSite.yml deoarece cand va ajunge la init
             * template_file va fi rescris
             *
             */
            $this->template_file = "about";
        }
         //echo "CblogSite _handle_requests: template_file {$this->template_file}<br>";

    }
    function __construct($C)
    {
            $this->_handle_requests();
    }
}
