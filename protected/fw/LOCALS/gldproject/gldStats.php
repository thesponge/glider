<?php

class gldStats
{
    public $projects
          ,$leaders
          ,$participants
          ;

    public function _init_()
    {
        $this->getProjects();
        $this->getLeaders();
        $this->getParticipants();
    }

    public function getProjects()
    {
        $q = "SELECT
                projects.id, projects.title, projects.added,
                people.first_name, people.last_name,
                people.email, people.role
            FROM
                glider_projects AS projects
            JOIN glider_people AS people
                ON (projects.leader = people.id)
            WHERE
                projects.status = '1';
        ";
        return $this->projects = $this->C->Handle_Db_fetch($this, $q);
        //$this->_projects = $this->gldproject->Db_getProjects();
    }

    public function getLeaders()
    {
        $q = "SELECT
                projects.id, projects.title, projects.added,
                people.first_name, people.last_name,
                people.email, people.role
            FROM
                glider_projects AS projects
            JOIN glider_people AS people
                ON (projects.leader = people.id)
            WHERE
                projects.status = '1'
            GROUP BY people.id;
        ";
        return $this->leaders = $this->C->Handle_Db_fetch($this, $q);
    }

    public function getParticipants()
    {
        $q = "SELECT
                projects.id AS pid, projects.title, projects.added,
                people.first_name, people.last_name,
                people.role, people.email
            FROM
                glider_projects AS projects
            JOIN glider_people_projects_map AS map
                ON (projects.id = map.project)
            JOIN glider_people AS people
                ON (people.id = map.person)
            WHERE people.id
                NOT IN
                (SELECT leader FROM glider_projects)
        ";
        return $this->participants = $this->C->Handle_Db_fetch($this, $q);
    }

    public function render()
    {
    }

}
