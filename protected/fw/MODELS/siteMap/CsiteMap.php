<?php
class CsiteMap{

    var $C;               //main object
    var $nodeResFile;
    var $LG;
    var $RESpath;

    function setDISPLAY() {

       # $menu1_PATH = PUBLIC_PATH."PLUGINS/MENUhorizontal/RES/".$this->LG."/MENUhorizontal1_class1.html";
       # $menu2_PATH = PUBLIC_PATH."PLUGINS/MENUhorizontal/RES/".$this->LG."/MENUhorizontal2_class2.html";
      #  $menu3_PATH = PUBLIC_PATH."PLUGINS/menuPROD/RES/".$this->LG."/menuPROD.html";


        $menu1_PATH = $this->C->GET_resPath('PLUGINS','MENUhorizontal1_class1');
        $menu2_PATH = $this->C->GET_resPath('PLUGINS','MENUhorizontal2_class2');
        $menu3_PATH = $this->C->GET_resPath('PLUGINS','menuPROD');



        $siteMap_html = ( file_exists($menu1_PATH) ? file_get_contents($menu1_PATH) : '');
        $siteMap_html .= ( file_exists($menu2_PATH) ? file_get_contents($menu2_PATH) : '');
        $siteMap_html .= ( file_exists($menu3_PATH) ? file_get_contents($menu3_PATH) : '');

        return $siteMap_html;

    }
    function _render_(){



        if(file_exists($this->RESpath))
             $pageContent = file_get_contents($this->RESpath);
        else
        {
            $pageContent = "<div id='siteMap_cont'>".$this->setDISPLAY()."</div>";
            file_put_contents($this->RESpath,$pageContent);
        }


        #_________________________________________________________________

        $display =$pageContent;

        return  $display;
    }

    function __construct($C){

        $this->C = &$C;
        $this->LG = &$C->lang;
        $this->nodeResFile = $this->C->nodeResFile;


      #  $this->RESpath = PUBLIC_PATH."MODELS/siteMap/RES/".$this->LG."/siteMap.html";
        $this->RESpath = $this->C->GET_resPath('','siteMap');
        $this->setDISPLAY();
    }
}