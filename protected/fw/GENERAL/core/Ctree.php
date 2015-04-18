<?php
/**
 * Ccore
 *
 * @uses Ctree
 * @package Core
 * @version 1.0
 * @copyright Copyright (c) 2012 Serenity Media
 * @author  Ioana Cristea
 * @license AGPLv3 {@link http://www.gnu.org/licenses/agpl-3.0.txt}
 */
class Ctree extends Cmodules
{
    var $idTree;
    var $idNode;
    var $tree = array();

    // ===============================[ tree methods] ==========================
    /**
    * Regenereaza toate tree-urile deletate de Build_masterTree
    */
    function regenerateAllTrees()
    {
        $queryRES = $this->DB->query("SELECT Cid AS idT from TREE WHERE Pid='0' ");
        while($row = $queryRES->fetch_assoc())
        {
            $this->Build_Db_tree(array($row['idT']), $row['idT']);
            // echo "<b>Cmanage - regenerateAllTrees</b> cu idTree = ".$row['idT']."<br>";
            // var_dump($this->tempTree);
            $this->Set_Fs_Tree(FW_RES_TREE.'tree'.$row['idT'].'.txt',  $row['idT']);
            $this->tempTree = array();
        }
    }
    function resetTree($treeId)
    {
        unlink(FW_RES_TREE."tree{$treeId}.txt");
    }
    function resetCurrentTree()
    {
           unlink(FW_RES_TREE."tree{$this->idTree}.txt");
    }
    function resetAllTrees()
    {
        foreach(glob(FW_RES_TREE.'*.txt') as $treeFile)
        {
            unlink($treeFile);
        }
    }
    /**
     * - Creaza un master un array multidimensional cu toate tree-urile
     * - deleteaza in acelasi timp toate tree-urile
     * - urmand sa fie regenerate de metoda regenerateAllTrees()
     * @param bool $unlinkTrees
     *
     * @return array
     */
    function Build_masterTree($unlinkTrees = true)
    {
        $resTree = FW_RES_TREE ;

        if (is_dir($resTree)) {
            $dir = dir($resTree);
            $masterTREE = array();

            while (false !== ($file=$dir->read())) {
                $arr_file = explode('.', $file);
                if (end($arr_file) == 'txt') {
                    $file_path  = $resTree.$file;
                    $treeSer    = file_get_contents($file_path);
                    $tree       = unserialize($treeSer);

                    if(is_array($tree)) {

                        //echo "Cmanage - Build_masterTree cu file_path = $file_path <br>";
                        //echo "Cmanage - Build_masterTree cu treeSer = $treeSer <br>";
                        //var_dump($tree);

                        $masterTREE = $masterTREE + $tree;
                    } else {
                        // tree-ul nu a fost creat corect deci
                        // trebuie deletat
                        if ($unlinkTrees) {
                            //stergem toate TREE-urile;
                            unlink($file_path);
                        }
                    }
                }
            }
           /* if($this->masterTREE)
                foreach($this->masterTREE AS $id=>$item)
                    echo 'id='.$id.' nameF='.$item->resFile.' type='.$item->mgrName."<br/>";*/
            return $masterTREE;
        }
    }

//=============================================[ set - TREE ]====================


    # 1
    /**
     * Functie recursiva care creaza un tree al paginilor
     *
     * WORKING with
     *
     *   TB: ITEMS
     *      id
     *      name_ro     - numele paginii in engleza / romana
     *      name_en
     *      type        - numele modulului care gestioneaza pagina
     *      SEO         - array serioalizat cu tagurile de SEO
     *
     *   TB: TREE
     *      Pid         - id-ul parintelui
     *      Cid         - id-ul copiilor
     *      poz         - pozitia copilului
     *
     *
     *
     * LOGISTICS
     *  - orice pagina care nu are un parinte are un tree serializat
     *  - orice pagina este un obiect item cu
     *  - functia este reapelata pentro orice pagina/ item care are copii
     *
     *      name        - numele curent bazat pe limba curenta
     *      name_ro
     *      name_en
     *      modName        -
     *      id
     *      parentId        - id-ul parintelui
     *      resFile       - numele fisierului de resursa
     *      children    = array( [poz] => [Cid],... );
     *
     *
     * @param $children
     * @param string $parentId
     */
    public function Build_Db_tree($children, $idTree, $parentId='', $level=0)
    {
/*        if(!$idTree) {

        }*/
        foreach ($children AS $idCh) {
            $this->tempTree[$idCh] = new item();
            $node  = &$this->tempTree[$idCh];

            $query = "SELECT name_ro, name_en, type, opt
                      FROM ITEMS
                      WHERE id='$idCh' ";
            $qArr  = $this->DB->query($query)->fetch_assoc();

            $node->name    = $qArr['name_'.$this->langs[0]];
            $node->name_ro = $qArr['name_ro'];
            $node->name_en = $qArr['name_en'];
            // deprecated type
            $node->modName = $qArr['type'];

            // to be handled by the manager if it choses it to do so...
            $node->modOpt  = !$qArr['opt'] ? '' : json_decode($qArr['opt']);
            $node->id      = $idCh;
            $node->idParent= $parentId;

            // deprecated idT
            $node->idTree  = $idTree;
            $node->level   = $level;
            $node->resFile = str_replace(' ', '_', $node->name);

            //deprecated
            $node->type    = $qArr['type'];
            $node->idT     = $idTree;

             // afla modType pentru acest node
            if (in_array($node->modName, $this->MODELS)) {
                $node->modType = 'MODELS';
            } elseif (in_array($node->modName, $this->PLUGINS)) {
                $node->modType = 'PLUGINS';
            } elseif (in_array($node->modName, $this->LOCALS)) {
                 $node->modType = 'LOCALS';
            }


             // retine copii acestui nod
             $query    = " SELECT Pid,Cid,poz FROM TREE where Pid='$idCh' ORDER BY poz ASC ;";
             $queryRes = $this->DB->query($query);

             while ($ch_arr = $queryRes->fetch_assoc()) {
                 $node->children[ $ch_arr['poz'] ] = $ch_arr['Cid'];
             }
             //var_dump($node);
             // pentru fiecare copil al acestui node reapeleaza functia
             if ($queryRes->num_rows) {
                 if($idTree) {
                     $this->Build_Db_tree($node->children, $idTree, $idCh, $level+1);
                 } else {
                     error_log("[ ivy ] Ccore - Build_Db_tree : An empty idTree was provided");
                 }
             }

         }
    }

    # 2
    /**
     * Returneaza un vector temporar al tree-ului cerut din BD
     * @param $pathTree     - calea unde ar trebui sa stea tree-ul
     * @param $idT          - id-ul treeului
     * @return mixed
     */
    public function Set_Fs_tree($pathTree, $idT)
    {
          //try si catch
          //echo '<b>ACcore - Set_Fs_tree</b> pt tree-ul '.$idT;
          //var_dump($this->tempTree);
          $treeSer = serialize($this->tempTree);
          #umask(0777);
          $succes  = Toolbox::Fs_writeTo($pathTree,$treeSer);
          //$succes  = Toolbox::Fs_writeTo($pathTree, $treeSer);

          //if(defined('UMASK')) umask(UMASK);
          if (!$succes) {
              error_log( "[ ivy ]"
                      . "<b>Core - Set_Fs_tree :  Fail file_put_contents in </b>"
                      . " $pathTree <br>" );
          }
          return $this->tempTree;

      }

    # 3
    /**
     * Returneaza un vector deserializat al tree-ului curent
     *
     * STEPS:
     *  - daca se gaseste fisierul cu tree-ul serializat
     *  - daca nu se preia din BD(care creaza un vector temporar - deaceea trebuie unset)
     *
     * @param $idTree - id-ul treeului curent
     * @return mixed
     */
    public function Get_tree($idTree)
    {
          $pathTree = FW_RES_TREE.'tree'.$idTree.'.txt';

          if (is_file($pathTree)) {
              //echo "Ctree - Get_tree : $pathTree<br>";
              return  unserialize(file_get_contents($pathTree));

          } else {
	          // Build_Db_tree
	          // scrie tree-ul in res Set_Fs_tree
              $this->Build_Db_tree(array($idTree), $idTree);
              //var_dump($this->tempTree);
              $tree =  $this->Set_Fs_tree($pathTree, $idTree);

              $this->tempTree = array();
              return $tree;
          }
    }


//=============================================[ ESETIALS ]======================

    # 1.3
    /**
     * Seteaza nodul curent
     *
     * @todo: ma gandesc ca toate aceste proprietati poate ar trebui sa
     * stea intr-un obiect gen $this->current
     */
    protected function Set_currentNode()
    {
        $curentNode         =  &$this->tree[$this->idNode];
        if(!$curentNode) {
            error_log("[ ivy ] Ccore - Set_currentNode "
                    ."Nu s-a gasit nici un node pentru idNode = $this->idNode ");
            return false;
        }
        $this->nodeName_ro  =  &$curentNode->name_ro;  /*$this->name_ro*/
        $this->nodeName_en  =  &$curentNode->name_en;  /*$this->name_en*/

        $this->nodeName     =  &$curentNode->name;     /*$this->name*/
        $this->nodeResFile  =  &$curentNode->resFile;  /*$this->nameF*/

        $this->nodeChildren =  &$curentNode->children; /*$this->children*/
        $this->nodeId       =  &$curentNode->id;       /*$this->id*/
        $this->nodeIdParent =  &$curentNode->idParent; /*$this->idParent*/
        $this->nodeLevel    =  &$curentNode->level;    /*$this->level*/

        $this->mgrName      =  &$curentNode->modName;
        error_log("[ ivy ] "."Ccore - Set_currentNode mgrName = $this->mgrName");
        $this->mgrType      =  &$curentNode->modType;
        error_log("[ ivy ] "."Ccore - Set_currentNode mgrType = $this->mgrType");

    }

    # 1.2
    /**
     * Seteaza tree-ul curent bazat pe idTree - requested
     * @return bool - daca a reusit sau nu sa returneze tree-ul
     */
    public function Set_currentTree()
    {
        $this->tree = $this->Get_tree($this->idTree);

        if (is_array($this->tree)) {
            //echo "Ccore - Set_currentTree() : <br>";var_dump($this->tree);
            return true;

        } else {
            //$this->tree = array();
            error_log("[ ivy ] ".'Ccore - Set_currentTree : Nu am reusit sa creez treeul');
            return false;
        }

    }

    # 1.1
    /**
     * SET: idTree = primary parent, idNode = id ITEMS / page
     *
     * atentie idTree si idNode pot fi setate si din core.yml
     *
     *
     * @param string $idTree
     * @param string $idNode
     *
     * @return bool
     */
    protected function Set_currentTreeNode($idTree= '', $idNode='')
    {
        if($idTree) {
            $this->idTree = $idTree;
        }
        if($idNode) {
            $this->idNode = $idNode;
        }
        if(!$this->idNode && $this->idTree) {
            $this->idNode = $this->idTree;
        }

        // returns
        /**
         * Atentie aceste proprietati pot fi setate si default din
         * core.yml
         */
        if($this->idTree && $this->idNode) {
            error_log("[ ivy ] ".'Ccore - Set_currentTreeNode :' .
                            "Se incearca setarea currentNode cu "
                             ."idTree = {$this->idTree} si idNode = {$this->idNode}"
                      );
            return true;
        } else {
            if(!$this->idTree) {
                error_log("[ ivy ] ".'Ccore - Set_currentTreeNode :' .
                    ' Nu am reusit sa identific un idTree');
            }
            if(!$this->idNode) {
                error_log("[ ivy ] ".'Ccore - Set_currentTreeNode :' .
                    ' Nu am reusit sa identific un idNode');

            }
            return false;
        }

    }



}
