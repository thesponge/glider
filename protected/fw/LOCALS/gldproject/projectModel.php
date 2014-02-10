<?php

class projectModel
{

    private $_personPost,
            $_projectPost;
    public $dbPrefix;
    public $peopleTable;
    public $projectsTable;
    //public $dbPrefix      = 'glider';
    //public $peopleTable   = 'people';
    //public $projectsTable = 'projects';
    public $dataset       = array();

    public function checkPerson($email = null)
    {
        if ($email == null) {
            error_log('Attempt to check on an empty email!', E_USER_WARNING);
            die();
        }
        $query = "SELECT id FROM {$this->dbPrefix}_{$this->peopleTable}
            WHERE email = '$email'";

        return $this->DB
            ->query($query)
            ->fetch_object()->id;
    }
    public function addPerson ()
    {
        // Create the assignment query out of $_POST values
        $personValues  = $this->C->Db_setFromAssoc($this->_personPost);

        // Build the final query
        $personQ = Toolbox::trim_all(
            "INSERT INTO {$this->dbPrefix}_{$this->peopleTable}
            SET $personValues"
        );

        //var_dump($personQ);
        //var_dump($personValues);
        return $this->DB->query($personQ);
    }

    public function updatePerson ($id)
    {
        // Create the assignment query out of $_POST values
        $personValues  = $this->C->Db_setFromAssoc($this->_personPost);

        // Build the final query
        $personQ = Toolbox::trim_all(
            "UPDATE {$this->dbPrefix}_{$this->peopleTable}
            SET $personValues
            WHERE id = '$id'"
        );

        //var_dump($personQ);
        return $this->DB->query($personQ);
    }

    public function addProject ()
    {
        // Create the assignment query out of $_POST values
        $projectValues = $this->C->Db_setFromAssoc($this->_projectPost);

        // Build the final query
        $projectQ = Toolbox::trim_all(
            "INSERT INTO {$this->dbPrefix}_{$this->projectsTable}
             SET $projectValues
             "
        );

        //var_dump($projectQ);
        $this->DB->query($projectQ);

        $this->C->jsTalk .= "alert('Proiectul a fost adăugat și a ajuns în faza de moderare.'); window.location='/proiecte';";

    }
    public function updateProject ($id)
    {
        // Create the assignment query out of $_POST values
        $projectValues = $this->C->Db_setFromAssoc($this->_projectPost);

        // Build the final query
        $projectQ = Toolbox::trim_all(
            "UPDATE {$this->dbPrefix}_{$this->projectsTable}
             SET $projectValues
             WHERE id = '$id'
             "
        );

        //var_dump($projectQ);
        $this->DB->query($projectQ);
    }

    public function _hook_addMember ($project, $person)
    {
        $this->gldproject->personFields['role'] = 'person_role';

        $this->_personPost  = handlePosts::Get_postsFlexy(
            $this->gldproject->personFields
        );
        unset($this->_personPost->url);

        return true;
    }

    public function addMember ()
    {
        $project = $_REQUEST['projectid'];

        $person_id = $this->checkPerson($this->_personPost->email);
        if($person_id > 0) {
            $this->updatePerson($person_id);
            $this->_personPost->id = $person_id;

            $mapQ = Toolbox::trim_all(
                "UPDATE
                {$this->dbPrefix}_{$this->peopleTable}_{$this->projectsTable}_map
                SET
                 project = '$project'
                 WHERE person  = '$person_id'
                 "
            );
        } else {
            $person  =& $this->_personPost->id;
            $this->_personPost->id = Toolbox::Db_getMax(
                $this->dbPrefix.'_'.$this->peopleTable,
                $this->DB
            )+1;
            $this->addPerson();

            $mapQ = Toolbox::trim_all(
                "INSERT INTO
                {$this->dbPrefix}_{$this->peopleTable}_{$this->projectsTable}_map
                SET
                 project = '$project',
                 person  = '$person'
                "
        );
        }
        // Populate the people-project map

        //var_dump($mapQ);
        $this->DB->query($mapQ);
        Toolbox::relocate(Toolbox::curURL());
    }

    public function _hook_add()
    {
        $this->_personPost  = handlePosts::Get_postsFlexy(
            $this->gldproject->personFields
        );
        $this->_projectPost = handlePosts::Get_postsFlexy(
            $this->gldproject->projectFields
        );

        return true;
    }

    public function _hook_update()
    {
        $this->_personPost  = handlePosts::Get_postsFlexy(
            $this->gldproject->personFields
        );
        $this->_projectPost = handlePosts::Get_postsFlexy(
            $this->gldproject->projectFields
        );
        return true;
    }

    public function update()
    {
        $personValues  = $this->C->Db_setFromAssoc($this->_personPost);
        $personQ = Toolbox::trim_all(
            "UPDATE {$this->dbPrefix}_{$this->peopleTable}
            SET $personValues
            WHERE email = '{$this->_personPost->email}'"
        );

        $this->updateProject($_REQUEST['projectid']);
        $this->updatePerson($this->gldproject->project[0]['leader']);
        //var_dump($this->gldproject->project);

        Toolbox::relocate(Toolbox::curURL());
    }

    public function add()
    {
        // Check if designed project leader exists in the database
        // (perform an email lookup)
        $leader = $this->checkPerson($this->_personPost->email);

        // Define the next person/project leader id, based on the maximum
        // value found in the database (yes, I broke the auto increment
        // magic).
        if ($leader == null) {
            $this->_projectPost->leader = Toolbox::Db_getMax(
                $this->dbPrefix . '_' . $this->peopleTable,
                $this->DB
            ) + 1;

            // Create a pointer for the project leader id
            $this->_personPost->id =& $this->_projectPost->leader;
        } else {
            $this->_projectPost->leader = $leader;
        }


        $this->_projectPost->id
            = Toolbox::Db_getMax(
                $this->dbPrefix . '_' . $this->projectsTable,
                $this->DB
            ) + 1;

        if ($leader == null) {
            $this->addPerson();
        } else {
            $personValues  = $this->C->Db_setFromAssoc($this->_personPost);
            $personQ = Toolbox::trim_all(
                "UPDATE {$this->dbPrefix}_{$this->peopleTable}
                SET $personValues
                WHERE id = '$leader'"
            );
            //var_dump($personQ);
            $this->DB->query($personQ);
        }

        $this->addProject();

    }


    public function doStuff($param1 = 1, $param2 = 2)
    {
        /*
        echo "<b>This is a static callback example.
            Parameters are $param1 and $param2.</b>";
        //echo "<b>Database prefix is " . self::dbPrefix . ".</b>";
        die("<br><p style='text-align:right;'>Execution stopped.</p><br>");
         */
    }


    /*public function __callStatic($name, $arguments)
    {
        $args = func_get_args();
        return
            call_user_func_array(
                array(self::getInstance(), $name),
                $args[0]
            );
    }*/

    public function _init_()
    {
        $this->dbPrefix      =& $this->gldproject->dbPrefix;
        $this->peopleTable   =& $this->gldproject->peopleTable;
        $this->projectsTable =& $this->gldproject->projectsTable;
    }
}
