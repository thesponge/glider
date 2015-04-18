<?php
class CMENU_h_multiLevel
{


       public function SET_menu($type='h',$level='',$idT=0,$idC=0, $name='') {

           $res = $this->DB->query(" SELECT Cid,name_ro,name_en from view_TREE where Pid='$idC' ORDER BY poz ASC");

           if($res->num_rows)
           {
               $LG = $this->LG;
               if($idC)
                   $menu = "<span tabindex='1'>$name</span>";
                   $menu .= "<ul class='{$type}{$level}'> \n";

               if($level<2)$level++;
               #_______________________________________________________________________________


               while($row  = $res->fetch_assoc())
               {
                   $name = $row['name_'.$LG];
                   $idC   = $row['Cid'];

                   if(!$idT)$id_T = $idC; else $id_T = $idT;
                  #_______________________________________________________________________
                   $menu .="<li>".$this->SET_menu('v',$level,$id_T,$idC, $name)."</li> \n";
               }
               $menu .= "</ul> \n";

               return $menu;
           }

           elseif($idC)
           {
               if(!$idT)$idT = $idC;
               $href = "index.php?idT=$idT&idC=$idC";
               $menu = "<a href='$href' id='$idC'>{$name}</a>";

               return $menu;
           }
       }
       public function _render_()
       {
           $PATH = RES_PATH.'PLUGINS/'.$this->LG.'/menu_multiLevel.html';
           if(file_exists($PATH)) return file_get_contents($PATH);
           else
           {
               $menu =  "<div id='menuH'>".$this->SET_menu().'</div>';
               file_put_contents($PATH,$menu);
               return $menu;
           }
       }

       public function __construct($C)
       {

       }
}