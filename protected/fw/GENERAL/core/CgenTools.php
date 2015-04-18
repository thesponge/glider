<?php
/**
 * CgenTools
 *
 * @package Core
 * @version 1.0
 * @copyright Copyright (c) 2012 Serenity Media
 * @author  Ioana Cristea
 * @license AGPLv3 {@link http://www.gnu.org/licenses/agpl-3.0.txt}
 */
class CgenTools extends CmethDB{

   var $rendermod;
   var $historyArgs;   #array setat de model


   public function SET_HISTORYargs($id, $concat='',$argString='')
   {

        $end_ul = '';

         if($argString=='' && is_array($this->historyArgs)){

             $this->simpleHref_history = '<ul class="breadcrumb">';                               #nu cred ca ar trebui sa trebuiasca sa fac neaparat asta , functia SET_HISTORY nu ar mai trebui apelata
             foreach($this->historyArgs AS $argName=>$argValue) $argString .=  '&'.$argName.'='.$argValue;
             $ul = '</ul>';
         }


         if(isset($this->tree[$id]->idParent)) $this->SET_HISTORYargs($this->tree[$id]->idParent,"<span class='divider'>/</span>", $argString);

         if($id)
         {
             $backName  = $this->tree[$id]->name;

             $simpleHref = "href='index.php?idT={$this->idTree}&idC={$id}{$argString}'";
             $this->simpleHref_history .= "<li><a {$simpleHref}> $backName </a> $concat </li>".$end_ul;


         }





       }
   /**
    * daca level=3 => 4-level = 1;
    *      level=2 => 4-2 = 2;
    *      level=1 => 4-1 = 3
    *
    * if(!idParent) =>level=1;
    * if(!$id) = > am ajuns la sfarsit
    */
   public function SET_HISTORY($id,$concat='')
   {

           if(!isset($this->simpleHref_history) || $this->simpleHref_history==''){

               # concat este un simplu caracter cu care se concateneaza
               if(isset($this->tree[$id]->idParent))$trueLEVEL = $this->SET_HISTORY($this->tree[$id]->idParent,"<span class='small9'>&gt;&gt;</span>");
               if($id)
               {

                   array_push($this->history,$id);
                   $backName  = $this->tree[$id]->name;
                   $backNameF = str_replace(' ','_',$backName);

                   $this->history_TITLE_keywords .= $backName.' ';
                   $this->history_TITLE          .= $backNameF.'/';

                   #old href:    index.php?idC={$idT}&idT={$idT}&level=nr
                   #newFormat:   idT-idC-L{level} / backName;

                   $href = ($trueLEVEL==2 || $this->idTree==1  ? '': "href='".$this->idTree."-{$id}-L{$trueLEVEL}/$this->history_TITLE'");
                   $this->history_HREF .="<a {$href}> $backName $concat </a>";

                   $simpleHref = "href='index.php?idT={$this->idTree}&idC={$id}'";
                   $this->simpleHref_history .= "<a {$simpleHref}> $backName $concat </a>";

                   return $trueLEVEL+1;
               }
               else return 1;
           }


       }
   public function GET_pagination($query,$nrItems,$GETargs,$uniq,&$mod='', $ANCORA='')
   {

          #echo '<b>pagination Query</b> '.$query."<br>";
           //@todo: probabil ca hreful ar trebui cumva refacut

           $CURRENTpage = (isset($_GET['Pn']) ? $_GET['Pn'] : 1);

          if(!isset($_SESSION['NR_pages'][$uniq]) || $this->admin) #daca nu este setata pagination sau daca ne aflamin admin
           {

               $NRrows = $this->DB->query($query)->num_rows;
               $pages =  ceil($NRrows / $nrItems);

               unset($_SESSION['NR_pages'][$uniq]);
               $_SESSION['NR_pages'][$uniq] = $pages;

               #echo 'GET_pagination '.$NRrows;
           }
           else
           {
               $pages = $_SESSION['NR_pages'][$uniq];
           }
           #echo 'Nr pages '.$pages.'<br>';


           #=========================================================================================
           # daca se trimite un pointer al obiectului care cere paginarea atunci pentru acest obiect
           # se vor seta urmatoarele variabile
           # LimitStart poate sa aiba presetata limita
               if(is_object($mod))
               {

                    $mod->LimitStart += ($CURRENTpage - 1)*$nrItems;
                    #$mod->LimitEnd   = $mod->LimitStart + $nrItems;
                    $mod->Pn = $CURRENTpage;

               }

           #=========================================================================================

           if(isset($pages) && $pages>1)   #altfel nu mai are rost sa se faca o paginare
           {
               $argString = '';
               foreach($GETargs AS $argName=>$argValue) $argString .=$argName.'='.$argValue.'&';

               $pagination = "<div class='pagination'><ul>";

                   for($i=1;$i<=$pages;$i++) {

                       $classCURRENT  = ($i==$CURRENTpage ? " class='active' " : '');
                       $pagination .=" <li $classCURRENT><a href='index.php?{$argString}Pn=$i{$ANCORA}'> $i </a></li> ";
                   }

               $pagination .="</ul></div>";

               return $pagination;
           }
           else return ' ';

       }
   public function get_modType($modName)
   {
       $modType = '';
        if(    in_array($modName,$this->MODELS )) $modType = 'MODELS';
        elseif(in_array($modName,$this->PLUGINS)) $modType = 'PLUGINS';
        elseif(in_array($modName,$this->LOCALS)) $modType = 'LOCALS';

       return $modType;
   }

    /*=================[ meth - images] ====================================*/
    /**
     * Returneaza un array cu path-urile imaginilor uploadate pentru un modul
     * @param $modName
     *
     * @return array
     */
    public function get_resImages_paths($modName)
    {
        /**
         * USE
         *
         * $results=glob("{includes/*.php,core/*.php}",GLOB_BRACE);
         * GLOB_BRACE - Expands {a,b,c} to match 'a', 'b', or 'c'
         *
         * foreach (glob("$dir/$prefix*.html") as $file) {   #echo $file."<br>";  }
         *
         * <?php
             $path_parts = pathinfo('/www/htdocs/inc/lib.inc.php');

             echo $path_parts['dirname'], "\n";    =>  /www/htdocs/inc
             echo $path_parts['basename'], "\n";   =>  lib.inc.php
             echo $path_parts['extension'], "\n";  =>  php
             echo $path_parts['filename'], "\n";   => lib.inc
             ?>
         *
         *
         *  'RES_PATH'
         *  'RES_URL'
         *
        */
        $resDir = RES_PATH."uploads/images/{$modName}";
        $imgFiles_Paths = glob("$resDir/*.jpg");
        return $imgFiles_Paths;
    }
    /**
     * Transforma path-urile pentru imagini in URL-uri
     * @param $modName
     *
     * @return array
     */
    public function get_resImages_urls($modName)
    {
        $imgFiles_Paths = $this->get_resImages_paths($modName);
        $imgFiles_Urls = array();
        foreach($imgFiles_Paths AS $key=>$filePath){
            $imgFiles_Urls[$key] = str_replace(RES_PATH,RES_URL,$filePath);
        }

        return $imgFiles_Urls;
    }
    /**
     * Extrage baseUrl din src-ul imaginii
     *
     * @param $full_urlPath
     *
     * @return mixed
     */
    public function set_imgRelativePath( $full_urlPath)
    {
        return str_replace(BASE_URL, '', $full_urlPath);
    }

    /**
    * resName poate fi aflat in 2 moduri
    *  1. din numele modulului
    *  2. sau din numele resFile ( declarat in tabelul ITEMS )
    *
    * 1 - este valabil pentru orice mdoul
    * 2 - este valabil doar pentru acel modul care este manager curent al paginii
    * adica $mod->modName = $this->mgrName;
    */
    public function Module_Get_pathRes(&$mod, $resName='', $lang='')
    {
        if (!$resName) {
           // daca modulul este managerul curent al paginii
           // see Ccore->Set_currentNode()
            //if ($this->mgrName == $mod->modName)
            $resName = $this->mgrName == $mod->modName
                     ? $this->nodeResFile
                     : $mod->modName;
        }
        if (!$lang) {
            $lang = $this->lang;
        }

        /**
         * presupunem deci ca modulul a fost instantiat prin core
         * si core a setat lucruri precum modDir..
         */
        $modResDir = RES_PATH.$mod->modDir;
         if (!is_dir($modResDir)) {
            mkdir($modResDir,0777,true);
        }

        $resPath = $modResDir."{$lang}_{$resName}.html";

        return $resPath;

    }


    /*=================[ END - meth - images] ====================================*/

    static function readYml($file_yml, &$mod ='')
    {
        if (!$mod) {
            $mod = new stdClass();
            $RETmod = true;
        }
        Ccore::Module_configYamlProps($mod,$file_yml);
        if (isset($RETmod)) {
            return $mod;
        }
    }
    # manage function - should be put in TgenTools respectiv create ATgenTools
    static function debugMess($mess)
    {
        return '';
    }

}
