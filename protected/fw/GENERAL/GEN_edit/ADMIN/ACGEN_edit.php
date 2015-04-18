<?php
/**
 * -  Aceasta clasa se ocupa inprincipal cu gestionarea paginilor (categoriilor) si interconectarea lor
 * -  Deasemnea cauta existenta unui obiect 'handle' pentru completarea acestei gestionari
 * -  Poate avea module aditionale pt alte functionalitati POSTparsers
 * - acesti POSTparsers - nu fac altceva decat sa parseze izolat postul si isi vor avea locul in GEN_edit
 * pana la standardizarea lor
 *
 */

class ACGEN_edit
{
    #====================================== [SPECIFICE] ================================================================

    var $POSTparsers = array('ACheaders');                                              #this are adittions to the general edit functionality



    var $types = array();                                        #literalmente
    #=======================================[ DEFAULTS ] ===============================================================
    var $C;               #main object
    var $DB;
    var $lang;
    var $lang2;
    var $langs;

    var $menus;
    var $TESTdisplay='oricealtceva ';



    function POSTparser()    {

        if(isset($_POST['SaveChanges']))
        {
            $CTRL_changes = new CTRL_SV_CHANGES($this);
            $this->TESTdisplay .= $CTRL_changes->TESTdisplay;
        }
    #___________________________________________________________________________________________________________________
        $basePath = FW_INC_PATH.'GENERAL/GEN_edit/ADMIN/';
        foreach($this->POSTparsers AS $parserName) {
            if(is_file($basePath.$parserName.'.php')) $this->$parserName = new $parserName();
        }

    }

#======================================================== [ DISPLAY functions ] ========================================
    # 1
    function get_List($menuKey='', $Pid='') {

        $LG = $this->lang;

      #_______________________________[free items]___________________________________________________________________
        if($menuKey=='freeITEMS')
        {
            $query = "SELECT ITEMS.id, name_{$LG} AS name, type
                        FROM ITEMS
                       WHERE ITEMS.id NOT IN (SELECT Cid from TREE)
                        ";

            $list = "\n<ol  id='children_new'>";
        }


        //_______________________________[menus items]_________________________________________
        elseif($menuKey)
        {
            $query = "SELECT ITEMS.id,name_{$LG} AS name,type from
                     MENUS join TREE on (MENUS.id = TREE.Cid) join ITEMS on (MENUS.id = ITEMS.id)
                     WHERE idM ='{$menuKey}'
                     ORDER BY poz ASC";

            $list = "\n<ol>";
        }


        //_________________________________[ recursiv ]___________________________________________
        elseif($Pid)
        {
            $query = "SELECT ITEMS.id,name_{$LG} AS name,type from
                       TREE join ITEMS on (TREE.Cid = ITEMS.id)
                       WHERE Pid='{$Pid}'
                       ORDER BY poz ASC";

            $list = "\n<ol>";
        }

        $res = $this->DB->query($query);
      //__________________________________________________________________________________________________________

        if($res)
        {


            while($item = $res->fetch_assoc())
            {
                $id = $item['id'];
                $type = $item['type'];
                $name = $item['name'];

               # if(!in_array($type,$this->C->MODELS) && !in_array($type,$this->C->PLUGINS)) $type='no-edit';

                $list .= "\n<li id='list_{$id}'>
                    <div class='{$type}'>$name</div>";
                $list .=$this->get_List('',$id);
                $list .="</li>";
            }
        }
        else
        {
            $list .=  $this->DB->error;
        }


        $list .="</ol>";

        return $list;





    }
    function get_Menus()                    {



#____________________________________________________ [ free items ] ___________________________________________________

        $freeITEMS = $this->get_List('freeITEMS');
$html ="
        <ol class='sortable'>
                       <li id='list_MenuFREE' >
                           <div>Free Items</div>
                           {$freeITEMS}
                       </li> \n
                                                                                                          ";
#____________________________________________________ [ menus items ] __________________________________________________

        foreach($this->menus AS $menuKey => $menuType)
        {
            $list = $this->get_List($menuKey);
$html .="
                    \n <li id='list_Menu{$menuKey}'>
                            <div>Meniu_{$menuKey} {$menuType}</div>
                            {$list}
                        </li>
                     \n
";      }
        $html .="</ol>";

   #_______________________
        return $html;
    }

    function get_Types_DB() {

           $RES = $this->DB->query("SELECT DISTINCT(type) FROM ITEMS ");
           while ($row = $RES->fetch_assoc())
               array_push($this->types,$row['type'] );
          /* {
               $type = $row['type'];
               $types .="<option class='$type'>{$type}</option>";
           }*/
       }
    function get_Types_TG() {

        if(count($this->types) == 0) $this->types = array_merge($this->C->MODELS,$this->C->LOCALS); # $this->get_Types_DB();             # daca nu au fost declarate tipuri le preia din BD

        $types_options = '';
        foreach($this->types AS $type)
            $types_options .="<option class='$type'>{$type}</option>";

        return $types_options;


    }
    function get_TOOLS()    {
        $RES = $this->DB->query("SELECT MAX(id) AS max_id  from ITEMS ")->fetch_assoc();
        $last_id = $RES['max_id'] + 1;
        $types_options = $this->get_Types_TG();
    #___________________________________________________________________________________________________________________
       $html ="<div id='indicatiiGE'>
                    <button id='Show_idicatiiGE' class='Tbar_but'>info</button>
                    <div id='indicatii_GENedit'>
                        <pre style='font-size: 11px; '>
* Edit categorie   - click pe categorie - edit

* Delete categorie - click pe categorie - delete
                   - daca o categorie va fii deletata ,
                     toate subcategoriile si produsele
                     din ea  vor fii deasemenea deletate

* Adauga categorie - scrie numele categoriei
                   - click pe add
                   - trage categoria la locul dorit din lista


* ATENTIE - modificarile nu se vor salva decat dupa
            ce s-a apasat pe save
          - in cazul in care s-a comis vreo gresala se poate iesi
            din General Edit fara ca acea gresala sa fie salvata
                        </pre>
                    </div>
                </div>";

       $html .="<div class='block_addNew'>
                            <input type='hidden' name='lastID' value='{$last_id}' />

                            <input type='text'   name='itemName'value='' placeholder='item name'>
                            <input type='text'   name='typeNew' value='' placeholder='new type' />
                            <span  class='types'>
                                 <select name='type' class='Tbar_but'>
                                    $types_options
                                    <option class='none'> none</option>
                                 </select>
                            </span>

                            <input type='button' name='addNew' value='add' onclick='addNewITEM()' class='Tbar_but' />

                            <form action='' method='post'>
                                <input type='submit' name='SaveChanges' value='Apply changes'  class='Tbar_but'/>
                            </form>
                 </div>
                                                                                                          ";
        return $html;
    }

    function set_DISPLAY()  {

      #  $dispPATH = RES_PATH.'PLUGINS/'.$this->lang.'/GEN_edit.html';
      # C->GET_resPath($modType='',$resName='', $modName='' ,$nameF='', $lang = '')

        $dispPATH = $this->C->Module_Get_pathRes($this,'GEN_edit');
       # echo 'dispPATH '.$dispPATH.'<br>';

        if(!file_exists($dispPATH))
        {

            $html = "<div id='GE_allPages'>
                           <div id='GE_allMenus'>".$this->get_Menus()."</div>
                           <div id='GE_tools'>
                                ".$this->get_TOOLS()."
                                 <div id='GE_pageCONF'>
                                        <div id='GE_ctrlDET'>
                                            <div id='GE_tabs'></div>
                                            <div id='GE_buts'></div>
                                        </div>
                                      <div id='GE_contDET'></div>
                                 </div>
                           </div>

                     </div>";
            # $html .= $this->TESTdisplay;  # provenit din $CTRL_changes->TESTdisplay - instanta al CTRL_SV_CHANGES
            file_put_contents($dispPATH,$html);
        }
    }


    function _init_(){

         $this->menus = &$this->C->menus;
         $this->lang2 = &$this->C->lang2;
         $this->langs = &$this->C->langs;


        //$this->C->TOOLbar->ADDbuttons();
        array_push($this->C->TOOLbar->buttons,
            "<input type='button'  value='General Edit' onclick=\"activatePOPUPedit('GEN_edit','GENERAL')\" >");

        $this->POSTparser();
        $this->set_DISPLAY();
    }

    function __construct($C){


    }
}