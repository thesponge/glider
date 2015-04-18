<?php
class CMENUhorizontal
{
    var $LG;
    var $DB;
    var $menuName;
    var $menuPath;
    public function SET_menu($ID,$classID) {
        /**
         * ATENTIE!!!
         *
         * DB:  MENUS id, idM
         *      ITEMS id, name_ro, name_en, type
         *      TREE  Cid, Pid, poz;
         *
         *
         * Cvars: menus= array($ID, $type);
         *
         *
         * PARAM: $ID       = MENUS.id
         *        $classID  = posibilitatea de a avea 2 meniuri identice cu clase diferite
         *
         *
         *
         * HTML RESULT:
         *
         *  <ul class='MENUhorizontal[classID]'  id='menu[ID]_[LG]'>
         *      <li>  <a href='$href' id='menu[$ID]_CLS[$classID]_[ID_ITEM]'> ITEMname </a> </li>
         *
         *
         * HTML RES: 'MENUhorizontal'.$ID.'_class'.$classID.'.html'
         *
         */

        $res = $this->DB->query(" SELECT ITEMS.id,name_ro,name_en
                                    from ITEMS
                                         join MENUS on (ITEMS.id = MENUS.id)
                                         join TREE  on (ITEMS.id = TREE.Cid)
                                  WHERE idM='{$ID}' ORDER BY poz ASC");

        if($res->num_rows)
        {
            $LG = $this->LG;


            #_______________________________________________________________________________
            $menu = "<ul class='MENUhorizontal{$classID}' id='menu{$ID}_{$LG}'> \n";

            while($row  = $res->fetch_assoc())
            {


                $name = $row['name_'.$LG];
                $id   = $row['id'];

                $queryRES = $this->DB->query("SELECT Cid from TREE where Pid='{$id}' ORDER BY poz ASC  LIMIT 0,1" )->fetch_assoc();


                $Cid = ($queryRES['Cid'] ? $queryRES['Cid'] : $id);

                // old1 $href = "index.php?idT={$id}&idC={$Cid}";
                // old2 $href = "{$id}-{$Cid}/".str_replace(' ','_',$name);
                $href = str_replace(' ','_',$name);
               #___________________________________________________
                $menu .="<li>  <a href='$href' id='menu{$ID}_CLS{$classID}_$id'> $name </a> </li> \n";
            }

            $menu .= "</ul> \n";

            #________________________________________________________________________________

                   file_put_contents( $this->menuPath,$menu);

            return $menu;
        }
    }
    public function _render_($ID=1,$classID=1)
    {
        $this->menuName = 'MENUhorizontal_'.$ID.'_class'.$classID.'.html';
        $this->menuPath = RES_PATH.'PLUGINS/'.$this->LG.'/'.$this->menuName;

        if(file_exists($this->menuPath)) return file_get_contents($this->menuPath);
        else return $this->SET_menu($ID,$classID);
    }

    public function __construct($C)
    {
        $this->LG = &$C->lang;
        $this->DB = &$C->DB;
    }

}