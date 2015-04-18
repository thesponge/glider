<?php
class Ccore extends Cunstable
{
    //===================[ownProps]=============================================

    public $modName      = 'core';
    public $modType      = 'GENERAL';
    public $modTypePub      = 'GENERAL';
    public $modDirPub      = 'GENERAL/core';
    public $mainTemplate = '';
    public $mainModel    = '';

    public $DB; //data base pointer
    //===================[Project Setting]======================================
    public $tree;
    //public $tempTree;

    public $menus;  //array(idMenu= >'MenuName', ...)
    public $lang;
    public $lang2;
    public $langs = array();

    public $dsn = '';

    //========================[ current node / current page settings]===========
    public $id;
    public $idTree;
    public $idNode;
    public $mgrName;
    public $mgrtype;
    public $nodeName;
    public $nodeName_ro;
    public $nodeName_en;
    public $nodeResFile;
    public $nodeChildren;
    public $nodeId;
    public $nodeIdParent;
    public $nodeLevel;

//================================================[handle posts ]================

    /**
     * Utila pentru apelarea neautomata a unui controler
     * @param $mod
     * @param $methName
     *
     * @return string
     */
    public function Handle_post($mod, $methName)
    {


        $handlingSupport = true;
        // ======================================[test handling support]====
        if(!is_object($mod) || !method_exists($mod,$methName)){
            /**
                * poate ca aici ar trebui sa incerc sa instantiez obiectul daca nu exista
                * deci nu stiu ..hmm..cum imi dau seama de ce modType este
                */
            $handlingSupport = false;
            if (!is_object($mod)) {
                error_log("[ ivy ] "." Ccore - Handle_post"
                           ."There is no object ");
            }
            if (!method_exists($mod,$methName)) {
                error_log("[ ivy ] "." Ccore - Handle_post"
                          . " Object has no method ".$methName);
            }
            error_log("[ ivy ] "." Ccore - Handle_post"
                  . " Object was found with method ".$methName);
            return '';
        }


        /**
             * Daca exista o metoda care sa parseze posturile
             *  - exemplu validare , trim-uire
             *  - aruncarea de erori
             *
             * => atunci va fii apelata aceasta metoda intai
             * daca returneaza true => datele sunt valide si se poate procesa introducerea lor
             * daca nu se mai intampla nimic
            */
        if(method_exists($mod,'_hook_'.$methName)) {
            $handlingSupport = $mod->{'_hook_'.$methName}();
        }
       /* echo "<b>Ccore - Handle_post </b> methName = $methName
               handlingSupport = ".($handlingSupport ? 'true' : 'false')
              ."<br>";*/

        //========================================[ handle request data]========

        $relocate = false;
        if($handlingSupport) {
           //echo "<b>Ccore - Handle_post </b> methName = $methName <br>";
            $relocate = $mod->{$methName}();
            /*if ($autoRelocate && $relocate) {
                $this->reLocate();
            }*/
        }
        return $relocate;

    }

    public function Get_postMod($modName)
    {
        //ex: modName = 'blog, handler, otherObject' => mod =  blog->hadler->otherObject
        error_log("[ ivy ] "." Ccore - Handle_post"
                    ." objectName requiered $modName");

        $modsTree = explode(',', $modName);
        $mod = &$this;


        foreach($modsTree AS $modName)
        {
            $modName = trim($modName);
            $mod = &$mod->$modName;
            if(!is_object($mod)){
                error_log("[ ivy ] "." Ccore - Handle_post : "
                    ." There is no object $modName");
                return '';
            }
        }

        return $mod;

    }
    public function Get_postMethNames($methName, $mod)
    {
         $methNames = explode(',', $methName);
         $standardMethNames = array();

         foreach ($methNames AS $methName) {
              $methName = trim($methName);

              if (!method_exists($mod,$methName)) {
                error_log("[ ivy ] "." Ccore - Handle_post"
                          . " Object has no method ".$methName);
             } else {
                  array_push($standardMethNames, $methName);
              }
         }

        return $standardMethNames;

    }
    public function parse_modMethNames($mod, $methNames)
    {
        $relocate = true;
        foreach($methNames AS $methName)
        {
            // daca exista obiectul
            $methName = trim($methName);
            //echo "<b>Ccore - Handle_postRequest </b> modName = $modName && methName = $methName <br>";
            $relocate &= $this->Handle_post($mod, $methName);
        }
        return $relocate;

    }
    /**
     * Apel direct la un modul->metoda pentru rezolvarea requesturilor
     */
    public function Handle_postRequest()
    {

        //echo "Ccore - Handle_postRequest POST: "; var_dump($_POST);
        //error_log("[ivy] Handle_postRequest modName = ".$_POST['modName']." methName = ".$_POST['methName']);
        if (isset($_POST['modName'])
            && isset($_POST['methName'])
            && strlen($_POST['methName']) > 0
        ) {

            $mod = $this->Get_postMod($_POST['modName']);

            if($mod) {
                $methNames = $this->Get_postMethNames($_POST['methName'], $mod);
                $relocate = $this->parse_modMethNames($mod, $methNames);
                // daca toate metodele au fost apelate si au returnat true => se va face relocate
                //$relocate = false;
                if($relocate) {
                    $this->reLocate();
                }
            }

        }

    }
//===============================================================================

    protected function Set_currentPage()
    {
        //REQUESTS :
        /**
         * $_REQUEST['mgrName']
         * $_GET['idT']
         * $_GET['idC']
         */
        $idTree = isset($_GET['idT']) ? $_GET['idT'] : '';
        $idNode = isset($_GET['idC']) ? $_GET['idC'] : '';

        // echo "Ccore - Set_currentPage REQUEST";
        //var_dump($_REQUEST);
        /**
         * Daca s-a reusit sa se steze atat idTree cat si idNode
         * - are sens sa  culegem un tree
         * - sa se setam nodul curent
         *
         * Daca nu , incercam sa vedem daca s-a cerut un manager
         * pentru pagina
        */
        if ($this->Set_currentTreeNode($idTree, $idNode)) {
            if($this->Set_currentTree()) {
                $this->Set_currentNode();
            }

        } else {
            /**
             * pentru a initia un modul putem trimite "mgrName"
             * nu neapara idC || idTree
             * - aceasta proprietate poate fi setata si din cofigul lui core.yml
             */
            if(isset($_REQUEST['mgrName']))
            $this->Set_currentManager($_REQUEST['mgrName']);
        }

    }

    #1.4
    protected function addModuleUser()
    {
        // var_dump($userData);
        /**
         * Set modul user
         * pentru ca obiectul nu este instantiat de core deci
         * nu are proprietatile necesare pentru templatind
         * modName, modType
        */
        //$this->user = &$userData->user;

        if (isset($_SESSION['user'])) {
            error_log("[ ivy ] Ccore - _init_ exista SESSION['user']");
            $this->user = unserialize($_SESSION['user']);
            $this->user->afterInit($this);

            //echo "Ccore - addModuleUser : User prin session<br>";
        } else {
            array_push($this->default_GENERAL,'user');
            //echo "Ccore - addModuleUser - default_GENERAL <br>";
        }


    }
    #1.3
    protected function Set_mainModule()
    {
        /**
         * Local project settings
         */
       if (isset($this->mainModel) && isset($this->mainTemplate)) {
            #  Set_incFiles($modName,$modType,$extension,$folder='',$template='',$adminFolder='')
            $this->Set_incFiles($this->mainModel, 'LOCALS', 'css','', $this->mainTemplate);
            $this->Set_incFiles($this->mainModel, 'LOCALS', 'js','',  $this->mainTemplate);
       }

    }
    #1.2
    protected function Set_core()
    {
        #atentie daca nu are template o sa includa tot din core/js si core/css
        // declarate la inceputul clasei ca proprietati
        //$this->modName = 'core';
        //$this->modType = 'GENERAL';
        $this->Module_Fs_configYamlProps($this);          #seteaza variabilele personalizate
        $this->Module_Set_incFilesJsCss($this);
        //var_dump($this);

    }
    #1.1
    public function Set_db()
    {
        /**
          * DataBase connection
         */
        // $this->mdb =& MDB2::singleton(DSN)->setCharset('utf8');
         // $this->DB = new IvyDb(DSN);
        $this->DB = new mysqli('p:'.DB_HOST, DB_USER, DB_PASS, DB_NAME);
        $this->DB->set_charset('utf8');

    }

    function _init_($dbLink)
    {
        if(defined(PROFILER) && PROFILER == 1) {
            $this->profiler = new PhpQuickProfiler(PhpQuickProfiler::getMicroTime());
        }

        if ($dbLink == NULL) {
            die ('Database connection not established!');
        } else {
            $this->DB = $dbLink;
            $this->DB->set_charset("utf8");
        }

        //$this->Set_db();
        $this->Set_core();
        $this->Set_mainModule();
        $this->addModuleUser();

        $this->Set_currentPage();
        // instantiaza modulele default + current
        $this->Set_modules();
       // var_dump($_POST);
        // handling post request based on "methName & modName Posts"
        $this->Handle_postRequest();

        // set vectorul de module utilizate
        //$this->Set_Fs_usedModules();
        //var_dump($_SESSION['userData']);
        //var_dump($_SESSION['user']);

        #echo 'Ccore: __construct';
        //var_dump($this);



    }
    # COMMENT THIS!!!
    function __construct(& $dbLink)
    {
        $this->_init_($dbLink);

    }


    /**
     * ATENTIE
     *  - aceasta functie este apelata din interiorul __wakeup-ului altor module
     * de ce nu se creaza o metoda de wakeup a core-ului care sa apeleze aceasta metoda??
     *
    */
    public function DB_reConnect ()
    {
        /**
         * DE ce nu unset($this->DB) - explicatie
         * nu am dat unset pentru ca se va pierde locatia din memorie a lui DB
         * => obiectele care au pointer la $this->DB vor da in gol
         * deci degeaba recreez eu conexiunea pentru ca aceasta ar fii
         * valabila doar pentru core*/

        $this->DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);

        $DBstat = isset($this->DB) ? $this->DB->ping() : false;
        if ($DBstat == false) {

            $this->DB = '';
            $this->DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
            $this->DB->set_charset("utf8");
           /* echo "A fost apelat core wakeup si DB NU este connectat <br>".
                ($this->DB->ping() ? '<b>Dar acum este </b>' : ' TOT nu este conectat ');*/

        } else {
            $this->DB->set_charset("utf8");
           // echo "A fost apelat core -> DB_reConnect este connectat <br>";
        }

        return true;

    }
    public function wakeup()
    {
       // echo "wakeup core ";
        $this->DB_reConnect();
        //error_log("[ivy]-  core wakeup");
        $this->Handle_postRequest();
    }
    public function __destruct()
    {
        if(defined(PROFILER) && PROFILER == 1)
            $this->profiler->display($this->DB);
    }
    /**
     * public function __clone  ()
    { }*/
}
