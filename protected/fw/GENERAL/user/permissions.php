<?php
/**
 * Se ocupa in general cu setarea permisiunilor
 * Aceaste metode vor fi utilizate doar daca vectorul 'permissions'
 * nu a fost inca setat din bd - auth_user_stats
 */
class permissions extends Auser {

    // comming from $_SESSION['userData'] & seted by CauthManager
    public $uid = 0;
    public $cid = 0;
    public $uname  = 'Guest';
    public $email  = 'Guest';
    public $uclass = 'guest';
    public $permissions;

    //public $classes = array();
    public $sets = array();

    // permission Man's
    private $tableNames = array();
    private $prefix = 'auth_permissions';
    private $groups = array();
    private $groupsCSV;
    private $jsonFile = 'permissionSets.json';
    private $currentPermission = NULL;


    //NMI
    private function markPermissions ($gid) {
        //$query = "SELECT * FROM ";
            //foreach ($this->sets as $key => $set) {
                //$query .= "`$key`, ";
                //$queryEnd = " WHERE `$key`.`gid` = '$gid';";
            //}
        //$query = substr($query, 0, -2);
        //$query .= $queryEnd;

        $query = "SELECT * FROM auth_permissions_sys ";
            foreach ($this->sets as $key => $set) {
                if ($key != 'auth_permissions_sys') {
                    $query .= "JOIN $key ON (auth_permissions_sys.gid = $key.gid) ";
                    $queryEnd = "WHERE auth_permissions_sys.gid = '$gid'";
                }
            }
        $query .= $queryEnd;

        //Toolbox::dump($query, 'Query');

        $permissions = $this->DB->query($query)->fetch_assoc();

        //Toolbox::dump($permissions, 'permissions');

        return $permissions;
    }

    private function permissionSet ($value = 1) {
        $this->currentPermission = $value;
        return 0;
    }

    private function selectPermission (&$name) {
        $this->currentPermission = &$name;
    }

    private function jsonSave () {
        Toolbox::Fs_writeTo($this->jsonFile, json_encode($this->sets));
        return 0;
    }

    //==========================================================================
    /**
     * Seteaza vectorul serializat cu permisiuni
     */
    private function Set_Db_permissions()
    {

        $serPermissions = serialize($this->permissions);
        $query = "REPLACE INTO auth_user_stats
                  SET permissions = '{$serPermissions}' , uid = {$this->uid}";
        //echo "<br> permissions - Set_Db_permissions : $query <br>";
        $this->DB->query($query);
    }

    /**
     * Seteaza permisiuni pe seturi de tabele
     *      - sets[{$tableName}][permissionName] = permissionValue
     *
     * 1. coloana 'gid' este eliminata din seturi pentru ca nu este necesara
     */
    private function Set_permissionSets()
    {

        foreach ($this->tableNames as $tableName) {

            $this->sets[$tableName] = array();

            $query = "DESCRIBE $tableName";
            $result = $this->DB->query($query);
            // returns $row[] = array('Field', 'Type', 'Null', 'Key', 'Default', 'Extra');
            while ($row = $result->fetch_assoc()) {
                $this->sets[$tableName][$row['Field']] =& $this->permissions[$row['Field']];
            }
            #1
            unset($this->sets[$tableName]['gid']);

            // elibereaza rezultatele
            $result->free();
        }
    }

    /**
     * Seteaza permisiunile atribuite userului
     * 1. Selecteaza permisiunile grupurilor din care face parte userul
     * 2. Daca userul face parte dintr-un singur grup
     *      - atunci permisiunile acelui grup vor fi retinute direct
     * 3.Altfel
     *      - Intersecteaza aceste permisiunile din toate grupurile din care
     *         face parte
     *
     * @param $gid
     */
    private function Set_permissions($cid)
    {
        #1
        $query = "SELECT * FROM "
                     .implode(' NATURAL JOIN ', $this->tableNames)
                        . " WHERE `{$this->tableNames[0]}`.`cid` = {$cid} ";

        // error_log( "[ ivy ] permissions - Set_permissions : query =  $query");
        // echo  "[ ivy ] permissions - Set_permissions : query =  $query <br>";
        $res = $this->DB->query($query);
        $this->permissions = $res->fetch_assoc();
        //var_dump($this->permissions);

    }

    /**
     * Seteaza numele tabelelor din care se extrag permisiunile userului
     * $this->tableNames = array ('auth_permissions _bog', '','');
     *
     * oldName: readPermissionSets ()
     */
    private function Set_tableNames ()
    {
        $dbName = DB_NAME;
        $prefix = &$this->prefix;
        // ce tabele incep cu $prefix (auth_permissions)
        $query = "SHOW TABLES WHERE Tables_in_$dbName
                              LIKE '$prefix%';";

        $result = $this->DB->query($query)
                        or die($query);

        while ($row = $result->fetch_row()) {
            /**
             * $row[0] = $row['Tables_in_blacksea_dev']
             *         = numele tabelelor cu permisiuni ( seturi de permisiuni )
             */
            array_push($this->tableNames, $row[0]);
        }
       // echo "permissions";
       // var_dump($this->tableNames);
    }

    /**
     *  Seteaza urmatoarele:
     *  1. $this->goups & $this->groupsCSV
     *  2. $this->tableNames
     *  3. $this->permission
     *  4. $this->sets
     *  5. Retine permisiunile in BD
     */
    public function _init_permissions()
    {
        $this->jsonFile = VAR_PATH.'tmp/' . $this->jsonFile;

        #1
        $this->Set_tableNames();
        #2
        $this->Set_permissions($this->cid);
        #3
        $this->Set_permissionSets();
        #4
        $this->Set_Db_permissions();


    }
}
