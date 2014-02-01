<?php

class gldproject
{
    public $dataset = array();

    public function addPerson ()
    {
        // Populate the person info payload
        $this->dataset['person'] = array(
            'first_name' => '',
            'last_name'  => '',
            'email'      => '',
            'url'        => '',
            'bio'        => '',
            'misc'       => ''
        );
    }

    public function addProject ($leader)
    {

        // Populate the project info payload
        $this->dataset['project'] = array(
            'title'             => '',
            'url'               => '',
            'added'             => '',
            'leader'            => $leader,
            'description'       => '',
            'short_description' => '',
            'tech_details'      => '',
            'misc'              => ''
        );

        // Build the query
        $query = "";
        $dataset = "";
    }

    public function addMember ($project, $person)
    {
        // Populate the people-project map
        $this->dataset['map'] = array(
            'project' => $project,
            'person'  => $person
        );
    }
}
