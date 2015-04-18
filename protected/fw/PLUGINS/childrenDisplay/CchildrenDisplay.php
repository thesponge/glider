<?php
class CchildrenDisplay{
    var $C;

    function DISPLAY_children($children, $idT){

        if($children)
        {
            $HTML_string = "
            <div id='children_display'>
                <ul>";
                foreach($children AS $id_ch)
                {
                    $name = $this->C->tree[$id_ch]->name;

                    //$HTML_string .="<li><a id='$id_ch' href='".FW_PUB_URL."index.php?idT=$idT&idC=$id_ch'>$name</a></li>";
                    $currentClass = $this->idNode == $id_ch ? 'currentPage' : '';
                    $HTML_string .="<li class='{$currentClass}'>
                                            <a id='$id_ch' href='".PUBLIC_URL."?idT={$idT}&idC={$id_ch}"."'>$name</a>
                                    </li>";
                }

             $HTML_string .= "
                </ul>
             </div>";

            return $HTML_string;
        }
       // else return 'No children to display for ';
    }
    function DISPLAY_siblings(){

        $idParent = $this->C->idParent;

        $children   = $this->C->tree[$idParent]->children;
        $idT        = $this->C->idTree;

        return $this->DISPLAY_children($children, $idT);
    }

    function DISPLAY_mainChildren(){

        $idT = $this->idTree;
        $children = $this->C->tree[$idT]->children;

        return $this->DISPLAY_children($children, $idT);

    }


    function __construct($C)
    {


    }

}
