<?php
class CTRL_SV_CHANGES extends CHANGES
{
    var $masterTREE = array();
    var $TESTdisplay ='';
    # problema la trecerea de la un proiect la altul
    # yaml ?
    var $affected_MODELS  = array('siteMap');
    var $affected_PLUGINS = array('MENUhorizontal','ivyMenu');
    var $affected_GENERAL = array('GEN_edit');
    var $affected_LOCALS  = array();

    function getOBJECT_handle($change_type,$id,$detail)          {

         /**
          * REQ: [type] /ADMIN/ 'AC'.$nameTYPE.'_handle_GENedit.php';   - to handle changes
          */
         #   $changes = array( 'addNewITEM', 'updateITEM', 'deleteITEM','updateTREE',);

         #    OLD_TYPE = $this->masterTREE[$id]->type;  is the old_type  available only for existent ITEMS
         #    NEW_TYPE = (isset(detail['type']) : )   if exists and is diffrent than OLD_TYPE
         #    => both types should be adresed



      #==================================================================================================================
         $types['OLD'] = $OLD_type = (isset($this->masterTREE[$id]->modName) ? $this->masterTREE[$id]->modName : '');
         $types['NEW'] = $NEW_type = (isset($detail['type']) && $detail['type']!=$OLD_type ? $detail['type'] : '');
         $TESTdisplay='';
     #===================================================================================================================

         foreach($types AS $statusTYPE => $nameTYPE)
             if(isset($nameTYPE))
             {
                 $ob_handle_NAME = 'AC'.$nameTYPE.'_handle_GENedit';

                 if(file_exists(FW_INC_PATH."MODELS/$nameTYPE/ADMIN/".$ob_handle_NAME.'.php'))
                 {
                     $handle = new $ob_handle_NAME($this->DB, $change_type, $id, $detail,$statusTYPE, $this->masterTREE);  unset($handle);

                     $TESTdisplay .="HANDLE $statusTYPE =".$nameTYPE."<br />";
                 }
             }

     #===================================================================================================================
         $TESTdisplay = (isset($TESTdisplay) ? $TESTdisplay : 'No handle for '.$OLD_type);
         return "<br/>".$TESTdisplay."<br />";


     }
    function parseCHANGES()        {


          foreach($this->changes AS $change)                                                  # =  array( 'addNewITEM', 'updateITEM','seoITEM','deleteITEM');

                    if( $this->set_pathsChange($change))                                      #RET: true - exista schimbari , sets $this->[ change, changePATH, TESTchangePATH, ARRchanges]

                            foreach($this->ARRchanges as $list_id => $detail)                 #parsam prin vectorul de schimbari
                            {
                                $idarr    = explode('_',$list_id);                            #extragem id-ul elementului schimbat
                                $id       = end($idarr);
                            #______________________________________________________________________________________________________________
                                $mes  = $this->{$change}($id,$detail);                        #procesez schimbarea

                                $mes2 = $this->getOBJECT_handle($change,$id,$detail);         #testez daca exista un obiect, care sa se descurce cu aceste schimbari
                            #______________________________________________________________________________________________________________
                                $type_old = ( isset($this->masterTREE[$id]->modName) ?  $this->masterTREE[$id]->modName : '');
                                $this->TESTdisplay .= " {$change} type =  {$type_old} <br/> {$mes} <br/> {$mes2} ";

                            }

     }
    function parseCHANGES_TREE()   {

         if($this->set_pathsChange('updateTREE'))  # sets $this->[ change, changePATH, TESTchangePATH , ARRchanges]
         {
             $mes = $this->updateTREE($this->ARRchanges);
             $this->TESTdisplay .="updateTREE <br/>$mes <br/>";

         }

         #________________________________________________________
         $this->TESTdisplay ="<div>".$this->TESTdisplay."</div>";
    }

    function __construct($GE)       {
        $this->C     = &$GE->C;
        $this->DB    = &$GE->C->DB;
        $this->lang  = &$GE->C->lang;
        $this->lang2 = &$GE->C->lang2;
        $this->langs = &$GE->C->langs;

    #===================================================================================================================
       # $this->pathChanges = FW_PUB_PATH.$this->pathChanges;
        parent::__construct();

        $this->masterTREE = $this->C->Build_masterTree();
        $this->parseCHANGES();
        $this->parseCHANGES_TREE();

        $this->C->regenerateAllTrees();
       # var_dump($this->affected_MODELS);

        $this->C->solveAffectedModules($this->affected_GENERAL,'GENERAL');
        $this->C->solveAffectedModules($this->affected_PLUGINS,'PLUGINS');
        $this->C->solveAffectedModules($this->affected_MODELS,'MODELS');

    }
}