<?php

class Cgldproject
{

    /* Database manipulation callbacks */

    public function add ()
    {
    }
    public function update ()
    {}
    public function moderate ($status = true)
    {}

    public function Db_getProject($project_id)
    {
        $query  = "SELECT *, concat( glider_people.first_name, ' ', glider_people.last_name) AS fname,
                    glider_people.url AS leader_url,
                    glider_people.email AS leader_email,
                    glider_projects.id AS project_id,
                    glider_projects.url AS project_url
                    FROM glider_projects
                    JOIN glider_people
                    ON (glider_projects.leader = glider_people.id)
                    WHERE glider_projects.id = $project_id
                ";
        $this->project = $this->C->Handle_Db_fetch($this, $query);
    }

    public function Db_getProjects()
    {
        $query  = "SELECT *, concat( glider_people.first_name, ' ', glider_people.last_name) AS fname,
                    glider_projects.id AS project_id
                    FROM glider_projects
                    JOIN glider_people
                    ON (glider_projects.leader = glider_people.id)";
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
            $this->Db_getProjects();
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
