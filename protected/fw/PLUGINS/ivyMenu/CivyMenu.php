<?php
class menuSet{

    var $idMenu = 1;                # id-ul meniului si implicit al setului
    var $templateMethod='default';  # postfix al metodei pentru crearea meniului
    var $levels = 1;                # numarul de level-uri ale meniului
    /**
     * ->items
             [0]
                'name'
                'idC'
                'idT'
                'href'
                'items'
                     [0]
                        'name'
                         'idC'
                         'idT'
                         'href'
    */
    var $items = array();
    var $strUl = '';                # stringul HTML cu meniul

    function __construct($options){

        $this->idMenu         = isset($options['idMenu']) ? $options['idMenu'] : '';
        $this->templateMethod = isset($options['templateMethod']) ? $options['templateMethod'] : '';
        $this->levels         = isset($options['levels']) ? $options['levels'] : '';
    }

}
class CivyMenu{

    var $masterTree;
    var $current_idMenu = 1;
    var $menuSet1 = array(//'idMenu' => 1,
                          //'templateMethod' => 'default',
                          //'levels' => 1
                         );

    /**
     * Functie apelata in cazul in care menuSet->levels > 1
    */
    function setChildren(&$levels,&$item,  $ch,$idT,  $level=1){
       # echo "<b>getChildren - ch </b> <br>";
       # var_dump($ch);
        if( $levels > $level && $ch)
        {
            $item['items'] = array();

            foreach($ch as $idCh){

                $idC = $idCh;

                $name = $this->masterTree[$idC]->{'name_' . $this->lang};
                $href = PUBLIC_URL . "?idT={$idT}&idC={$idC}";

                array_push($item['items'],
                            array('name'=>$name,
                                  'idC'=>$idC,
                                  'idT'=>$idT,
                                  'href'=>$href )
                         );

                if($this->masterTree[$idC]->children)
                    $this->setChildren($levels,end($item['items']), $this->masterTree[$idC]->children, $idT, $level++ );
            }
        #    echo "<b>getChildren - item </b> <br>";
        #    var_dump($item);
        }

    }

    /**
     *Seteaza menuSet ->items
                         [0]
                            'name'
                            'idC'
                            'idT'
                            'href'
                            'items'
                                 [0]
                                    'name'
                                     'idC'
                                     'idT'
                                     'href'
     *
     * STEPS
     *  - preia primele elemente ale meniului
     *  - adauga elementele necesare la vectorul items
     *  - daca meniul a fost declarat cu mai multe leveluri va prelua si celelate submeniuri
        */
    function setMenu_multiLevel($idMenu= 1){

       # daca suntem la primul level al meniului


       $currentSet =  &$this->{'menuSet'.$idMenu};

       $query = " SELECT ITEMS.id,name_ro,name_en
                      from ITEMS
                           join MENUS on (ITEMS.id = MENUS.id)
                           join TREE  on (ITEMS.id = TREE.Cid)
                    WHERE idM='{$idMenu}' ORDER BY poz ASC ";

       $res = $this->DB->query($query);
       if($res->num_rows)
       {
              if(!isset($this->masterTree))
              {
                  $this->C->regenerateAllTrees();
                  $this->masterTree = $this->C->Build_masterTree(false);
              }

             //echo "<br><b>getMenu_multilevel - masterTree </b> <br>";
             //var_dump($this->masterTree);
              while($row  = $res->fetch_assoc())
              {
                      $name  = $row['name_'.$this->lang];
                      $idC   = $row['id'];
                      $idT   = $this->masterTree[$idC]->idTree;

                      # atentie ...aici trebuie apelata o metoda statica probabil
                      # a Ccore
                      $href = PUBLIC_URL . "?idT={$idT}&idC={$idC}";

                      array_push($currentSet['items'],
                                  array('name'=>$name,
                                        'idC'=>$idC,
                                        'idT'=>$idT,
                                        'href'=>$href)
                                );
              }

        #   echo "<br><b>getMenu_multilevel - items </b> <br>";
        #   var_dump($currentSet->items);
           # daca suntem la alt level al meniului si nu am depasit limita maxima de leveluri
           /**
            * Preia copii..pentru care $id este parinte
            */
           if( $currentSet['levels'] > 1)
           {
               foreach($currentSet['items'] AS $key => $item){

                   $idC = $item['idC'];
                   $idT = $item['idT'];
                   $this->setChildren(
                       $currentSet['levels'],
                       $currentSet['items'][$key],
                       $this->masterTree[$idC]->children,
                       $idT );
               }

           }


       }



    }



     /**
     * Creaza HTML-ul pentru meniu  care va fii restinut in menuSet['strUl']
     *  - bazat pe ->menuSet->items
    */
    function iterateMenu_default($items, $idMenu=1, $ulLevel=1){
         /**
          * use multidimensional array
         * items
         *  idC
         *  idT
         *  href
         *  items
        */
        #var_dump($items);
       $currentSet =  &$this->{'menuSet'.$idMenu};

       $ulClass = isset($currentSet['ulclass'.$ulLevel]) ? $currentSet['ulclass'.$ulLevel]  : '';

       $currentSet['strUl'] .="<ul class='{$ulClass}'>";

        foreach($items AS $item){

            $currentSet['strUl'] .="<li><a href='".$item['href']."'>".$item['name']."</a>";
            if(isset($item['items']))
                # recursive call
                $currentSet['strUl'] .= $this->iterateMenu_default($item['items'], $idMenu, $ulLevel + 1);
            $currentSet['strUl'] .="</li>";


        }
        $currentSet['strUl'] .='</ul>';

    }


    # magic function -- apelata automata  de TrenderTmpl daca nu gaseste respath
    /**
     * Seteaza res-ul meniului
     *
     * $currentSet   = pointer la setul curent al meniului
     * creaza array-ul multidimensional cu elementele meniului
     * apeleaza functia "templateMethod" aferenta meniului cerut
     * scrie fisierul static pentru meniu
     *
    */
    function _setRes_($resPath){

        $currentSet = &$this->{'menuSet'.$this->current_idMenu};
        $currentSet['strUl'] = '';

        $this->setMenu_multiLevel($this->current_idMenu);


        $methodName = 'iterateMenu_' . $currentSet['templateMethod'];

        $this->{$methodName}($currentSet['items'], $this->current_idMenu);

        Toolbox::Fs_writeTo($resPath, $currentSet['strUl']);

       # var_dump($this->menuSet1);

        return true;

    }

    /**
     * Apelata inaintea cererii de display a meniului pentru a reseta meniul
     * $this->current_idMenu
    */
    function set_menuSet($idMenu){

        if(isset($this->{'menuSet'.$idMenu}))
            $this->current_idMenu = $idMenu;
    }

    function _init_(){
        # var_dump($this->menuSet1);
    }

    function __construct(&$C){}
}
