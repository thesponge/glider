<?php
//define('DB_HOST', '192.168.5.150'); // zero
define('DB_HOST', '192.168.5.1'); // phobos
//define('DB_HOST', 'localhost');
define('DB_NAME', 'ACE');
define('DB_USER', 'ace');
define('DB_PASS', 'ace');

function getSiteMap($DB, $Pid=0)
{

     $query = "SELECT ITEMS.id,name_en AS name,type from
                   TREE join ITEMS on (TREE.Cid = ITEMS.id)
                   WHERE Pid='{$Pid}'
                   ORDER BY poz ASC";


    $res = $DB->query($query);

    if($res)
    {
        $list = "<ol>";

        while($item = $res->fetch_assoc())
        {
            $id = $item['id'];
            $type = $item['type'];
            $name = $item['name'];

            $list .= "<li id='list_{$id}'>
                        <div class='{$type}'>$name</div>";
            $list .=getList($DB,'',$id);
            $list .="</li>";
        }

        $list .="</ol>";

        return $list;
    }
    else
    {
        return   $DB->error;;
    }
}


function getList($DB, $menuKey='', $Pid='')
{
    if($menuKey)
    {
        $query = "SELECT ITEMS.id,name_en AS name,type from
                 MENUS join TREE on (MENUS.id = TREE.Cid) join ITEMS on (MENUS.id = ITEMS.id)
                 WHERE idM ='{$menuKey}'
                 ORDER BY poz ASC";


    }

    elseif($Pid)
    {
        $query = "SELECT ITEMS.id,name_en AS name,type from
                   TREE join ITEMS on (TREE.Cid = ITEMS.id)
                   WHERE Pid='{$Pid}'
                   ORDER BY poz ASC";
    }

    $res = $DB->query($query);

    if($res)
    {
        $list = "<ol>";

        while($item = $res->fetch_assoc())
        {
            $id = $item['id'];
            $type = $item['type'];
            $name = $item['name'];

            $list .= "<li id='list_{$id}'>
                        <div class='{$type}'>$name</div>";
            $list .=getList($DB,'',$id);
            $list .="</li>";
        }

        $list .="</ol>";

        return $list;
    }
    else
    {
        return   $DB->error;;
    }
}
function getMenus(&$DB,$menus)
{
    $html =
            " <div class='block_addNew'>
                        <input type='hidden' name='lastID' value='113' />
                        <input type='button' name='addNew' value='add' onclick='addNewITEM()' />
                        <input type='text'   name='itemName'value='' placeholder='item name'>
                        <span  class='types'>
                             <select name='type'>
                                 <option class='none'> none</option>
                                 <option class='contact'> contact</option>
                                 <option class='page'> page</option>
                                 <option class='single'> single</option>
                                 <option class='about'> about</option>
                                 <option class='portof'> portof</option>
                                 <option class='portof_ch'> portof_ch</option>
                                 <option class='news'> news</option>
                                 <option class='expertise'> expertise</option>
                             </select>
                        </span>
                    </div>";
    $html .="
    <ol class='sortable'>
                   <li id='list_MenuFREE' style='display: none;'>
                       <div>New Item</div>
                       <ol id='children_new'></ol>
                   </li>
";
    foreach($menus AS $menuKey => $menuType)
    {
        $html .="<li id='list_Menu{$menuKey}'>
                    <div>Meniu_{$menuKey} {$menuType}</div>";

        $html .=getList($DB,$menuKey);
        $html .="</li>";
    }
    $html .="</ol>";

    return $html;
}


$DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
$menus[1] = 'ivyMenu';

echo getMenus($DB,$menus);