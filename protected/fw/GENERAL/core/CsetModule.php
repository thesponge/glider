<?php
/**
 * Usage:
 * **Create object Module**
 *
 * **proprietati magice**
 *
 * * resPath
 * * displayPage
 * * displayPathRes
 *
 * **metode magice**
 *
 * * _init_
 * * _setRes_
 * * _render_()
 *
 * **Setting properties for Module**
 *
 * * configCorePointers
 *
 *  - DB
 *  - admin
 *  - LG
 *  - lang
 *  - nodeResFile
 *  - idTree
 *  - mgrName
 *
 * * configAttributes
 *
 *   + modName
 *   + modType
 *   + modDir  = modType / modName
 *
 * * FS_configYamlProps
 *   + objREQ  = daca se doresc proprietati ale altor module
 *   + include = daca se doreste citirea unui alt yaml
 *   + template
 *   + template_file
 *
 *   + assetsInc['js'] = array ('paths to other js like: /assets/...js');
 *   + assetsInc['css']
 *      - alte js-uri , css-uri de inclus
 *
 *   + assetsInc_{tmplFileName}['js', 'css']
 *      - alte js-uri , css-uri de inclus in functie de un template_file
 *
 * * automatizari speciale:
 *
 *   - module->_init_()
 *      - apelarea unei metode "magice" daca exista , dupa ce modulul a fost setat
 *   -_setRes_($resPath)
 *        - care ar trebui sa seteze o variabila resPath
 *
 */
class CsetModule extends CgenTools
{
    public $cssInc = '';
    public $cssIncPaths = array();
    public $jsIncPaths = array();
    //Modulele vor seta aceste jsTalk so the php can communicate with scrips
    public $jsTalk ;
    public $jsInc = '';
    public $toolbarBtts = array();


    #=============================================[ module incHtmlTags ]========
    # 1
    public function Get_IncTag_js($SrcPath)
    {
        //echo "Get_IncTag_js = $SrcPath <br>";
        return "<script type='text/javascript'  src='".$SrcPath."'></script>"."\n";
    }
    # 1
    public function Get_IncTag_css($SrcPath)
    {
        //echo "Get_IncTag_css = $SrcPath <br>";
        return "<link rel='stylesheet' href= '".$SrcPath."'  />"."\n";
    }
    # 2
    public function Get_incHtmlTags($extension, $incPaths)
    {
        if (!method_exists($this,"Get_IncTag_".$extension)) {
            error_log("[ ivy ]".'Get_incHtmlTags no method = '."Get_IncTag_".$extension.'<br>');
            return '';
        }

        //echo "<br> Get_incHtmlTags cu extPath = $extPath <br>";
        $tags = '';
        foreach($incPaths AS $srcPath) {
             $tags .= $this->{"Get_IncTag_".$extension}($srcPath);
        }

        return $tags;

    }
    public function Get_incPaths($extension, $extPath, $extSrcPath){

        if (!is_dir($extPath)) {
            return false;
        }

        $incPaths = array();
        $dir = dir($extPath);
        while(false!== ($file=$dir->read()) )
        {
            $arr_file = explode('.',$file);
            //echo "file found in $file <br>";

            if (end($arr_file) == $extension) {
                array_push($incPaths, $extSrcPath.$file);

            }
        }
        return $incPaths;
    }
    # 3 - A
    /**
     * utilizata cand se doreste css-ul sau js-ul unui anumit model
     * (istantiat sau NEinstantiat)
     *  - tagul de includere a fisierului va fi retinut in $this->INC_[extension]
     *
     *
     * @param $modName        - modulul de la care se doresc preluate fisierule cu extensia ceruta
     * @param $modType        - tipul modelului GENERAL / MODELS /PLUGINS
     * @param $extension       - extensia ex: js/ css
     * @param string $folder   - folderul din cadrul caruia sa fie preluate fisierele cu extensia ceruta
     * @param string $template - templateul daca este necesar
     * @param string $adminFolder - ADMIN
     */
    public function Set_incFiles($modName,$modType,$extension,$folder,
        $template='',$adminFolder='')
    {
         if ($folder=='') {
            $folder = $extension;
         }
         # $tmpl =/ [tmpl_name] /
         $tmpl      =  $template ? 'tmpl_'.$template.'/' : '';  #daca s-a trimis un template modelul are un template
         // $adminFolder .=  $adminFolder ? '/' : '';

         $ext_PATH         =   FW_PUB_PATH.$modType.'/'.$modName.'/'.$tmpl.$adminFolder."$folder/";
         $ext_SRC_PATH     =   FW_PUB_URL.$modType.'/'.$modName.'/'.$tmpl.$adminFolder."$folder/";


         $incPaths = &$this->{$extension."IncPaths"};
         $htmlTags = &$this->{$extension."Inc"};

         $newIncPaths = $this->Get_incPaths($extension,$ext_PATH,$ext_SRC_PATH);
        if($newIncPaths) {

            //var_dump($newIncPaths);
            $incPaths  = array_merge($incPaths, $newIncPaths );
            $htmlTags .=  $this->Get_incHtmlTags($extension,$newIncPaths);;
            //  echo "Set_incFiles - modName =  $modName  && htmltag = ".$htmlTag.'<br>';
        }



    }
    # 4
    /**
     * Automatic hmtl tag inclusion for an object
     * @param $obj
     * @param $extension
     * @param string $folder
     * @param string $adminFolder
     */
    public function Module_Set_incFiles(&$obj, $extension,$folder,$adminFolder='')
    {

        $template = isset($obj->template) ? $obj->template : '';

        $this->Set_incFiles($obj->modName,$obj->modTypePub,$extension,$folder,$template,$adminFolder);

        //echo "CsetModule - Module_Set_incFiles {$obj->modName} template_file = $obj->template_file <br> ";
        /**
         * daca obiectul are setat un template file atunci se va cauta un
         * path = modType/ modName/ tmpl_tmplName/ js/ js_templateFileName/ ....js
        */
        if (isset($obj->template_file)) {
             $folder = $folder."/"."{$folder}_".$obj->template_file;
             $this->Set_incFiles($obj->modName,$obj->modTypePub,$extension,$folder,$template,$adminFolder);
        }

    }
    # 5
   /**
     * Automatic html tag css/js inclusion for object
     *
     * @param $obj
     * @param string $adminFolder
     */
   public function Module_Set_incFilesJsCss(&$obj,$adminFolder='')
   {

        $this->Module_Set_incFiles($obj,'js','js',$adminFolder);
        $this->Module_Set_incFiles($obj,'css','css',$adminFolder);
   }

   #============================================[hard includes]=================
    // from local/ framework assets
    #1
    public function Module_Set_incFilesHard($extension,$srcPath)
   {
       if (method_exists($this,"Get_IncTag_".$extension)) {
           $this->{$extension."IncPaths"}[] = $srcPath;
           $this->{$extension."Inc"} .= $this->{"Get_IncTag_".$extension}($srcPath);
       }
   }
    #2
    /**
     * Refer yml conf:  assetsInc[js: [] ,css: []]  & assetsInc_tmlFileName[]
     * @param $mod
     */
    public function Module_Set_incFilesAssets($mod)
    {
        if (isset($mod->assetsInc)) {
           foreach($mod->assetsInc AS $extension => $paths){
               foreach($paths AS $srcPath) {
                   $this->Module_Set_incFilesHard($extension, $srcPath);
               }
           }
        }

        // assetsInc_templateFileName
        if ( isset($mod->template_file)
         && isset($mod->{'assetsInc_'.$mod->template_file})
        ) {
            /*echo "[ivy]Module_Set_incFilesAssets modName = ".$mod->modName
                        ." template_file = ".$mod->template_file."<br>";*/

            $tmplFile_assets = $mod->{'assetsInc_'.$mod->template_file};

            foreach($tmplFile_assets AS $extension => $paths) {
                foreach($paths AS $srcPath) {
                    $this->Module_Set_incFilesHard($extension, $srcPath);
                    /*echo "[ivy]Module_Set_incFilesAssets extenstion = ". $extension
                          ." srcPath = ".$srcPath."<br>";*/
                }
            }
        }
   }
    // from external
    public function Set_incTagsExtern($extension, $tags)
    {
        $this->{$extension."Inc"} .= $tags;
    }
    //assetsExternal
    /**
     * Refer yml conf:  assetsExtern[js: '' ,css: '']  & assetsExtern_tmlFileName[]
     * @param $mod
     */
    public function Module_Set_incFilesExtern($mod)
    {
        if (isset($mod->assetsExtern)) {
           foreach($mod->assetsExtern AS $extension => $tags){
               $this->Set_incTagsExtern($extension, $tags);
           }
        }

        // assetsExtern_templateFileName
        if ( isset($mod->template_file)
         && isset($mod->{'assetsExtern_'.$mod->template_file})
        ) {
            $tmplFile_assets = &$mod->{'assetsExtern_'.$mod->template_file};

            foreach($tmplFile_assets AS $extension => $tags) {
                $this->Set_incTagsExtern($extension, $tags);
            }
        }

    }

    # 1
    /**
     * @param        $mod
     * @param string $adminFolder
     */
    public function Module_incs($mod, $adminFolder='')
    {
        # preia si seteaza toate cele necesare pentru respectivul model
        # exemplu: seteaza configurarea lui din etc, ii seteaza cateva variabile utile cum ar fii DB, lang, LG, nodeResFile
        # si incearca sa gaseasca o metoda set INI care actioneaza ca un al doilea construct
        $this->Module_Set_incFilesJsCss($mod,$adminFolder);
        $this->Module_Set_incFilesAssets($mod);
        $this->Module_Set_incFilesExtern($mod);
    }

    #=========================================[ Yaml config]====================
    # 1
    /**
     * **1.populeaza obiectul** $mod cu date dintr-un fisier de config yaml
     * daca obiectul are deja setat un array atunci configul va adauga la acel array
     * daca nu va adauga ca proprietate noua
     *
     * **2.include yaml IN yaml**
     * daca fisierul yaml contine un vector "include" cu path-uri atunci la el se adauga INC_PATH si se reapeleaza aceasta fct
     * configurile acelui yaml vor fii atribuite obiectului curent
     *
     * @param $mod obiectul modul
     * @param $filePathYml file path catre fisierul de config al yml-ului
     * @return bool
     */
    static function Module_configYamlProps(&$mod,$filePathYml)
    {
        if (file_exists($filePathYml)) {

            $yml_array = Spyc::YAMLLoad($filePathYml);
            #var_dump($yml_array);

            #1
            foreach($yml_array AS $var_name => $var_value)
            {
                $notEmptyArray = (  isset($mod->$var_name)
                                 && is_array($mod->$var_name)
                                 && count($mod->$var_name) > 0 );

                $mod->$var_name = $notEmptyArray
                                  ? array_merge_recursive($mod->$var_name,$var_value)
                                  : $var_value;
            }

            #2
            if (isset($yml_array['include']) && is_array($yml_array['include'])) {
                foreach($yml_array['include'] AS $incFile_yml)
                {
                    # echo 'inluded file '.$incFile_yml."<br>";
                    self::Module_configYamlProps($mod,INC_PATH.$incFile_yml);
                }
            }

            #===================================================================

            if (defined('DEBUG') && DEBUG == 1) {
                error_log("[ ivy ] ".'File is present: '.$filePathYml);
            }

            return true;

        } else {
            if (defined('DEBUG') && DEBUG == 1) {
                error_log("[ ivy ] ".'File is not present: '.$filePathYml);
            }
            return false;
        }


    }
    # 2 - A
    /**
     * **configurarea obiectelor via yaml**
     *
     * # 2
     *  daca in configul modelului gaseste declarat un template
     *  atunci incearca sa vada daca nu cumva acel template are si el un config - tmpl_[A][templateName].yml
     *
     * @param $mod
     * @param string $adminFolder      [A / '']
     * @param string $template  - numele templateului
     */
    public function Module_Fs_configYamlProps(&$mod, $adminPrefix='', $template='')
    {
        $modType = $mod->modType;
        $modName = $mod->modName;

        $filePathYml = INC_PATH . 'etc/'
                     . $modType . '/'
                     . $modName . '/'
                     . ($template == ''
                        ? $adminPrefix . $modName . '.yml'
                        : 'tmpl_' . $adminPrefix . $template . '.yml');

        $this->Module_configYamlProps($mod, $filePathYml);

        #2
        $templateExists = (  isset($mod->template)
                          && $mod->template!=''
                          && $template == '' );

        if ($templateExists) {
            self::Module_Fs_configYamlProps($mod,$adminPrefix,$mod->template);
        }

    }

    #=========================================[ Module Configuration ]==========
    # 1
    /**
     * proprietati adaugate la orice obiect [model]
     * @param $mod
     * @param $modType
     * @param $modName
     */
    public function Module_configCorePointers(&$mod)
    {

        $mod->C      =  &$this;
        # situatie core
        $mod->DB     =  &$this->DB;
        $mod->admin  =  &$this->admin;
        //$mod->LG     =  &$this->lang;
        $mod->lang   =  &$this->lang;

        // acelasi lucru cu modName
        $mod->mgrName =  &$this->mgrName;
        $mod->mgrType =  &$this->mgrType;

    }
    public function Module_configNodePointers(&$mod)
    {
        # date ale modulului curent
        $mod->tree        =  &$this->tree;
        $mod->idNode      =  &$this->idNode;
        $mod->idTree      =  &$this->idTree;

        $mod->nodeLevel   =  &$this->nodeLevel;
        $mod->nodeResFile =  &$this->nodeResFile;

    }
    public function Module_configAttributes(&$mod,$modType,$modName)
    {
        #date despre acest modul
        $mod->modName    = $modName;
        $mod->modType    = $modType;
        $mod->modDir     = $modType.'/'.$modName.'/';

        // modulele pot avea partea de templating locala de exemplu
        $mod->modTypePub =  is_dir(FW_PUB_PATH.'LOCALS/'.$modName)
                            ? 'LOCALS'
                            : $mod->modType;
        $mod->modDirPub  = $mod->modTypePub.'/'.$modName.'/';

        //echo "modName = {$modName} , {$modType} , pubPart = {$mod->modDirPub} <br> ";


        #error_log("[ ivy ] ".'modName '.$modName."\n\n");
    }
    /**
     * configure external properties ( pointers) from other objects
     * @param $mod
     * @param $objREQ = ['modName': 'varName1', 0: 'varName2']
     *     array cu numele variabilelor dorite din CsetINI
     *     sau dintr-un anumit model ex model: nume variabila  sau model:[var1, var2]
     *     daca key-ul nu este string atunci se cauta variabila in core
     */
    public function Module_configExternalPointers(&$mod, $objREQ)
    {
        foreach($objREQ AS $key=>$propName)
        {
            if (is_string($key)) {
                # atunci se cere obiectul cu numele key si cu prop propName
                if (is_array($propName)) {
                    # daca $propName este un array atunci inseamna ca se doresc mai multe
                    # proprietati ale obiectului cu numele  $key
                    foreach($propName AS $subPropName)
                        $mod->$subPropName = &$this->$key->$subPropName;

                } else {
                    $mod->$propName = &$this->$key->$propName;
                }
            } else {
                $mod->$propName = &$this->$propName;
            }
            #echo $key.' '.var_dump($propName).'<br>';
        }

    }

    /**
     * cofigure external object as pointers tu object $obj from $mod
     * @param $mod - obiectul care contine metodele
     * @param $obj - obiectul pentru care vor fi creti pointerii
     * @param $links - array cu numele obiectelor din cadrul obiectului mod la care
     *               se doreste sa se creeze un pointer
     *               = ['handler1', 'handler2', 'handler3', ....]
     *
     * @uses
     */
    public function Obj_configExtraLinks(&$mod, &$obj, $links)
    {
        error_log("[ivy]");
        error_log(" [ivy] CsetModule - Obj_configExtraLinks : "
            ." Incerc sa DEPENDINTELE obiectul cu numele {$obj->objName} ");
        foreach($links as $linkName)
        {
            if(is_object($mod->$linkName)) {
                error_log("[ivy] CsetModule - Obj_configExtraLinks ".
                    "{$obj->objName} -> $linkName");
                $obj->$linkName = &$mod->$linkName;
            }
        }
    }
    # 2 + #2 - objConf
    /**
     * standard confing of a model REQ, CONF, mod-> [ objREQ, _init_() ]
     *
     * STEPS:
     *  - setarea proprietatilor standard ( pointeri la prop ale modulului principal ( core )
     *  - citirea configului yml ( redirectat catre cel de adminFolder )
     *  - objREQ [opt] Setarea a proprietatilor in plus din core sau din alte module
     *
     * @param $mod
     * @param $modType
     * @param $modName
     */
    public function Module_config(&$mod,$modType,$modName)
    {
        # i dont know if this is really necessary
        /*if($res) $mod->RESpath = $this->GET_resPath($this->modType,
                                                    '',
                                                    $this->mgrName,
                                                    $this->nodeResFile,
                                                    $this->lang);*/

        $this->Module_configCorePointers($mod);
        $this->Module_configNodePointers($mod);
        $this->Module_configAttributes($mod,$modType,$modName);
        $this->Module_Fs_configYamlProps($mod);

        if (isset($mod->objREQ)) {
            $this->Module_configExternalPointers($mod,$mod->objREQ);
        }

        #TODO: atentie la adminFolder!!!

    }

    #=============================================[ Module initialization]======

    public function Build_object($caller,  $objFolder, $objModName, $objName,
        $adminFolder='', $adminPrefix='')
    {
        #1
        if (isset($caller->$objName) && is_object($caller->$objName) ) {
            //echo "Obiectul care exista deja $modName <br>";
            //var_dump($this->$modName);
            error_log("[ ivy ] "."CsetModule - Build_object : Obiectul $objName este deja instantiat ");
            return $caller->$objName;

        }

        $className        = $adminPrefix.$objName;
        $classPath        = FW_INC_PATH . $objFolder . "/$objModName/" . $adminFolder . $className . '.php';
        // in cazul in care sunt scripturi , clase LOCALE
        $classPathLocal   = FW_INC_PATH . "LOCALS/$objModName/" . $adminFolder . $className . '.php';

        if (!file_exists($classPath) && !file_exists($classPathLocal)) {
            error_log("[ ivy ] "."CsetModule - Build_object : Nu  exista fisierul: $classPath ");
            return false;
        }

        #2
        $obj = new $className($caller);
        error_log("[ivy]");
        error_log("[ ivy ] "."CsetModule - Build_object : Modul instantiat = $className");
        if(!is_object($obj)) {
            error_log("[ ivy ] "."CsetModule - Build_object : ATENTIE
            Modul instantiat = $className nu a fost instantiat corect");
        }

        return $obj;
    }

    # 2  + incTags #5  - A
    /**
     * Instantierea unui modul - obiect cu tot ce ii trebuie + css, js html tags for inclusion
     *
     * **#1** testeaza daca modulul poate fi instantiat
     * - nu este deja instantziat
     * - nu este obiect
     * - exista fisierul la care ar trebui sa se afle clasa modulului
     *
     ***#2** instantiaza modulul
     *
     ***#3** seteaza requierments pentru acest modulul
     * - config yml
     * - js / css tags
     *
     ***#4** apeleaza daca exista un al doilea __construct   al obiectului (daca metoda exista)
     *       util pentru procesele care depind de configurilea modulului
     *
     *
     * @uses
     * @return bool -  obiectul creat / false daca nu a creat nimic
     */

    public function Module_Build($caller, $modName, $modType, $adminFolder='',
        $adminPrefix='C')
    {
        #1
        //$this->$modName = new $className($this);
        error_log("[ ivy ] CsetModule  - Module_Build trying to buid - {$modType} / {$modName}" );
        $obj = $this->Build_object($caller, $modType, $modName, $modName, $adminFolder, $adminPrefix);
        if(!is_object($obj)) {
            error_log("[ ivy ] CsetModule  - Modulul NU a fost instantiat corect" );
            return false;
        } /*else {
            error_log("[ ivy ] CsetModule  - Modulul Pare sa fi fost instantiat corect" );
        }*/
        #2
        $this->Module_config($obj,$modType,$modName);
        #4
        if (method_exists($obj,"_init_")) {
            $obj->_init_();
        }
        #3
        $this->Module_incs($obj, $adminFolder);
        #4
        return $obj;

    }

    public function Module_configObjProp(&$mod, &$obj, $objName)
    {
        error_log("[ivy] CsetModule - Module_configObjrop : "
            ." Incerc sa configurez obiectul cu numele {$objName}");
        $modName = $mod->modName;

        $obj->$modName = &$mod;
        $obj->caller   = $modName;
        $obj->objName  = $objName;

        $this->Module_configCorePointers($obj);
        $this->Module_configNodePointers($obj);
        $this->Module_configAttributes($obj,$mod->modType,$mod->modName);

        /**
         * modulul / callerul isi asuma raspunderea de necesitatile handlerului
         * in yml vor fi definite props ale objProps_links
         */
        if(isset($mod->objProps_links[$objName])){
            $this->Obj_configExtraLinks($mod, $obj, $mod->objProps_links[$objName]);
        } else {
            error_log("[ivy] CsetModule - Module_configObjrop : "
            ." ***** Nu exista dependente pentru obiectul cu numele {$objName}");
        }
    }

    /**
     * Construieste handlere pentru un anumit modul ( clase care exista tot in
     * acelasi modul ) . Aici ne referim mai mult la idea de obiect si nu modul - obiect
     * @param        $mod
     * @param        $objName
     * @param string $adminFolder
     * @param string $adminPrefix
     *
     * @uses
     * @return bool|object
     */
    public function Module_Build_objProp($mod, $objName, $adminFolder='', $adminPrefix='')
    {
        #1
        $obj = $this->Build_object($mod, $mod->modType, $mod->modName, $objName,
                                    $adminFolder, $adminPrefix);
        if(!is_object($obj)) {
           /* echo "[ivy] <b>Module_Build_objProp obiectul nu a putut fi instantiat
            </b>- objName = {$objName} <br>";*/

            return false;
        }

        #2
        $this->Module_configObjProp($mod, $obj, $objName);
        #3
        if (method_exists( $obj,"_init_")) {
            $obj->_init_();
        }
        #4
        //echo "[ivy] <b>Module_Build_objProp </b>- objName = {$objName} <br>";
        // var_dump($obj);
        return $obj;

    }

}
