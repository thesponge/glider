<?php

class Cgldproject
{

    private $_gldproject;
    public  $personFields,
            $projectFields,
            $dbPrefix,
            $peopleTable,
            $projectsTable;

    /* Database manipulation callbacks */

    public function _hook_addMember ()
    {
        require_once PUBLIC_PATH . 'assets/securimage-git/securimage.php';
        $securimage = new Securimage();
        if ($securimage->check($_POST['captcha_code']) == false) {
            $this->C->feedback->Set_mess('error', 'CAPTCHA', 'Cod CAPTCHA incorect!');
            return false;
        }

        $this->_gldproject = $this->C->Module_Build_objProp($this, 'projectModel');

        return $this->_gldproject->_hook_addMember();
    }

    public function addMember ()
    {
        $this->_gldproject->addMember();
    }

    public function _hook_add ()
    {
        require_once PUBLIC_PATH . 'assets/securimage-git/securimage.php';
        $securimage = new Securimage();
        //var_dump($securimage);
        if ($securimage->check($_POST['captcha_code']) == false) {
            //$this->C->feedback->Set_mess('error', 'CAPTCHA', 'Cod CAPTCHA incorect!');
            echo $_POST['captcha_code'] . ' is ' . var_dump($securimage->check($_POST['captcha_code']), true);
            return false;
        } else {
            //var_dump($_POST['captcha_code']);
            unset($_SESSION['feedback']);
        }

        $this->_gldproject = $this->C->Module_Build_objProp($this, 'projectModel');


        return $this->_gldproject->_hook_addMember();
    }

    public function add ()
    {
        $this->_gldproject->add();
    }

    public function _hook_update ()
    {
        $this->_gldproject = $this->C->Module_Build_objProp($this, 'projectModel');

        return $this->_gldproject->_hook_update();
    }

    public function update ()
    {
        $this->_gldproject->update();
    }

    public function updateStatus ($id, $status = 1)
    {
        $id = $_REQUEST['projectid'];

        if ($this->C->admin != true) {
            return false;
        }

        $q = Toolbox::trim_all(
            "UPDATE
            {$this->dbPrefix}_{$this->projectsTable}
            SET status = $status
            WHERE id = $id;"
        );
        $this->DB->query($q);
        //var_dump($q);
        Toolbox::relocate('/proiecte');
    }

    public function Db_getProject($project_id)
    {
        $project_query  = "SELECT *, concat( glider_people.first_name, ' ', glider_people.last_name) AS fname,
                    glider_people.url AS leader_url,
                    glider_people.email AS leader_email,
                    glider_projects.id AS project_id,
                    glider_projects.url AS project_url
                    FROM glider_projects
                    JOIN glider_people
                    ON (glider_projects.leader = glider_people.id)
                    WHERE glider_projects.id = $project_id
                ";
        $members_query = "SELECT glider_people.* FROM glider_people
            JOIN glider_people_projects_map AS map
                ON (glider_people.id = map.person)
            WHERE map.project = '$project_id';
        ";

        $this->project = $this->C->Handle_Db_fetch($this, $project_query);
        $this->members = $this->C->Handle_Db_fetch($this, $members_query);
    }

    public function Db_getProjects($status = 1)
    {
        $query  = "SELECT *, concat( glider_people.first_name, ' ', glider_people.last_name) AS fname,
                    glider_projects.id AS project_id
                    FROM glider_projects
                    JOIN glider_people
                    ON (glider_projects.leader = glider_people.id)
                    WHERE glider_projects.status >= $status";
        $this->projects = $this->C->Handle_Db_fetch($this, $query);
    }


    public function route($queryString)
    {
        parse_str($queryString);
        // Breaks the HTTP query string into local variables

        switch($route)
        {
        default:
        case "list":
            if ($this->C->admin == true) {
                $this->Db_getProjects(0);
            } else {
                $this->Db_getProjects(1);
            }
            break;
        case "toggleStatus":
            $status = $_REQUEST['status'];
            $this->updateStatus($_REQUEST['project_id'], $status);
            break;
        case "edit":
            // Fetch the project id
            $this->project_id =& $_REQUEST['projectid'];
            // Populate the project properties
            $this->Db_getProject($this->project_id);

            // Employ the "add" form to insert new stuff
            $this->template_file = "editform";
            // do stuff
            break;
        case "add":
            // Fetch the title, if any
            $this->title =& $_REQUEST['title'];

            // Employ the "add" form to insert new stuff
            $this->template_file = "addform";
            // do stuff
            break;
        case "show":
            // Fetch the title, if any
            $this->project_id =& $_REQUEST['projectid'];
            // Populate the project properties
            $this->Db_getProject($this->project_id);

            // Employ the "show" form to view/update the project
            $this->template_file = "showproject";
            // do stuff
            break;
        }
    }


    function _init_()
    {
        if (isset($_GET['route'])) {
            // A route has been given, so send the query string to route()
            $this->route($_SERVER['QUERY_STRING']);
        } else {
            // Assume a general listing
            $this->Db_getProjects();
        }

    }
}
