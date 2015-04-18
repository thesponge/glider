<?php
class Cpage{

    var $C;               //main object
    var $nodeResFile;
    var $LG;
    function _render_(){

        $LG = $this->LG;
        $idC = $this->C->idNode;

       # $path = PUBLIC_PATH."MODELS/page/RES/{$LG}/".$this->nodeResFile.'.html';
       # C->GET_resPath($modType='',$resName='', $modName='' ,$nodeResFile='', $lang = '')
        $path        = $this->C->GET_resPath('','','page',$this->nodeResFile);
        $pageContent = $this->C->GET_resContent($path);


        #_________________________________________________________________
        $display ="<div class='pageCont'>
                         <div class='SING FULLpage' id='FULLpage_{$idC}_{$LG}'>
                             <div class='EDeditor page'>   $pageContent </div>
                         </div>
                    </div>";

        return  $display;
    }

    function _init_(){ }
    function __construct($C){

        /*$this->C = &$C;
        $this->LG = &$C->lang;
        $this->nodeResFile = $this->C->nodeResFile;*/

        $C->GET_objREQ($this);
        $this->_init_();

    }
}