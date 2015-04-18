<?php
/**
 * Metode referitoare la Module
 * Class Cmodules
 */
class Cmodules extends CsetModule
{
    //===================[ modules ]==========================================

    /**
     *  modulele utilizate  in cadrul proiectului
     *  retinute serializat in var/ tmp/ serUsedModules.txt
     * @var array mods['modName']['modType', 'admin', 'default', 'defaultAdmin', ];
     *
     */
    public $mods = array();
    public $modTypes = array();   // array('LOCATION1', 'LOCATION2', ...);
    public $adminMods = array();  // array('modName' =>  1, ..);

    public $GENERAL = array();   // array('modName');
    //public $MODELS = array();
    //public $PULGINS = array();
    //public $LOCALS = array();

    public $default_GENERAL = array(); // array('modName');
    //public $default_MODELS = array();
    //public $default_PULGINS = array();
    //public $default_LOCALS = array();

    public $defaultAdmin_GENERAL = array(); // array('modName');
    //public $defaultAdmin_MODELS = array();
    //public $defaultAdmin_PULGINS = array();
    //public $defaultAdmin_LOCALS = array();


    function Fs_deleteContentRes($dir, $prefix='')
    {
        foreach (glob("$dir/$prefix*.html") as $file) {
            #echo $file."<br>";
            unlink($file);
        }
    }
    function solveAffectedModules($affectedMods, $modType='PLUGINS')
    {
        #var_dump($affectedMods);
        if (is_array($affectedMods)) {
            foreach($affectedMods AS $modNAME)
            {
                $resPath = RES_PATH.$modType.'/'.$modNAME;
                $this->Fs_deleteContentRes($resPath);
            }
        }
    }
    function resetAffectedModules($affectedMods, $resetTreeMethod='')
    {
        if (isset($affectedMods)) {
           foreach($affectedMods AS $modType =>$mods)
            {
                $this->solveAffectedModules($mods,$modType);
            }
        }

        if ($resetTreeMethod && method_exists($this, $resetTreeMethod)) {
            $this->{$resetTreeMethod}();
        }
    }


    // core basics
    //===============================================================================

    public  function Set_module($modName, $modType){
        //error_log("*****[ivy] Cmodules - Set_module ".'$modType = '.$modType.' $modName = '.$modName."\n\n");

        $this->$modName =  $this->Module_Build($this, $modName, $modType);
        /*error_log("*****[ivy] Cmodules - Set_module "
            .(is_object($this->$modName)
                ? "modulul {$modName} a fost instantiat "
                : "Modulul {$modName}  nu a fost instantiat "
            ));*/

    }
    #2.2
    // dep??
    /**
    * Instantziaza si seteaza /configureaza modulul curent
    *  - modulul curent = requested by type/moduleName || idNode=> type
    */
    public function Set_currentModule()
    {
        //$this->Module_Build($this->mgrName,$this->modType) ;
        //$this->Module_Build($this->modName,$this->modType) ;
        $this->Set_module($this->mgrName, $this->mgrType);
    }

    #2.1
    /**
     * Sets default objects (modules) declared in yml files of core AND tmpl_core
     */
    public function Set_defaultModules()
    {
        foreach($this->modTypes AS $modType)
        {
            foreach($this->{'default_'.$modType} AS $modName)
            {
                 $this->Set_module($modName,$modType);
            }
        }
    }

    #1  - A | use:
    /**
     *LOGISTICS
     *
       *
       *  - try to set the type property
       *  - if idTree & idC exists => a tree[idTree].txt should exist in /public/GENERAL/core/RES_TREE
       *  - from that tree we should be albe to determine the current item with all of its properties
       *
       *  - if a type is set - set requested module
       *
       *  - sets the default mod.'s     => le instantiaza obiectele si seteaza tagurile  js/css aferente ;
      */
    protected  function Set_modules()
    {

        $this->Set_defaultModules();
        //pus aici pentru ca intai trebuie initializata limba
        //$this->SET_HISTORY($this->idNode);
        if ($this->mgrName) {
            //echo "Ccore - Set_modules : Current ModuleName is $this->mgrName <br>";
            //$this->Set_currentModule();
            $this->Set_module($this->mgrName, $this->mgrType);
        } else {
            error_log("[ ivy ] "."Ccore - Set_modules : "
                    . " Atentie nu este definit nici un modul manager!!!");
        }

    }

    #1
    /**
     * Seteaza modulul curent (manager / mgr ) bazat pe $_REQUEST['mgrName']
     */
    protected function Set_currentManager($mgrName)
    {
        error_log("[ ivy ] Cmodules - Set_currentManager : S-a setat un manager");
        // deprecated "modName" should be used instead
            $this->mgrName = $mgrName;
            // afla modType pentru acest node
            if(    in_array($this->mgrName,$this->MODELS )) $this->mgrType = 'MODELS';
            elseif(in_array($this->mgrName,$this->PLUGINS)) $this->mgrType = 'PLUGINS';
            elseif(in_array($this->mgrName,$this->LOCALS))  $this->mgrType = 'LOCALS';
    }

    #1
    /**
     * @todo: trebuie sa ma mai gandesc la integrarea ei
     *
     * Seteaza vectorul de module utilizare $this->mods
     * utilizat astfel:
     *
     * $this->mods[$modName]->modType
     * $this->mods[$modName]->admin
     * $this->mods[$modName]->default       - nu foarte utilizata
     * $this->mods[$modName]->defaultAdmin  - nu foarte utilizata
     */
    protected function Set_Fs_usedModules()
    {
        $path =    VAR_PATH.'/tmp/serUsedModules.txt';

        if (file_exists($path)) {
            $serMods = file_get_contents($path);
            $this->mods = unserialize($serMods);
            // daca vectorul nu a fost creat in modul admin => ii lipsesc setarile
            // din Acore.yml => trebuie recreat

            if ($this->admin && !isset($this->mods['adminYml'])) {
                unlink($path);
                $this->mods = array();
                $this->_Set_Fs_usedModules();
            }

        } elseif($this->admin) {
            // daca suntem in modul admin si nu este creat vectorul $this->mods
            // atunci il cream si inregistram ca este complet cu partea de admin
            $this->mods['adminYml'] = true;
            // construieste vectorul $mods
            if (isset($this->modTypes) && is_array($this->modTypes)) {
                foreach($this->modTypes AS $modType){
                    //seteaza modulele folosite in proiect
                    foreach($this->$modType AS $modName){
                        $this->mods[$modName] = new module($modType);

                        // seteaza daca modulul are sau nu admin
                        if (isset($this->adminMods[$modName])) {
                            $this->mods[$modName]->admin = 1;
                        }
                    }
                    // seteaza modulele default
                    foreach($this->{'default_'.$modType} AS $modName) {
                        if (isset($this->mods[$modName])) {
                            $this->mods[$modName]->default = 1;
                        }
                    }
                    foreach($this->{'defaultAdmin_'.$modType} AS $modName) {
                        if (isset($this->mods[$modName])) {
                            $this->mods[$modName]->defaultAdmin = 1;
                        }
                    }
                }
            } else {
                // cauta in baza de date dupa aceste setari
                // daca nu reuseste sa gaseasca nimic in BD ar trebui returnat un
                // error_log cu eroare
            }

            // scrie vectorul serializat in fisier
            if (count($this->mods) > 0) {
                $serMods = serialize($this->mods);
                $succes = file_put_contents($path, $serMods);
                if (!$succes) {
                    error_log("[ ivy ] "."Ccore - _Set_Fs_usedModules :" .
                            " Nu a putut scrie serializarea modulelor in fisier");
                }
            }
        }

       // var_dump($this->mods);
    }

}