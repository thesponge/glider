<?php
class CHANGES {
   #==============================[ Mostenite ]==========================================================================
    var $lang;
    var $langs;
    var $C;
    var $DB;

    var $masterTREE;
    /*var $lastCHANGES = array();
    var $allCHANGES  = array();*/
    var $ENDpoints= array();             //this in Cproducts, here, and  ACproducts_handle_GENedit

    var $TMP_testMes = '';
   #____________________________________________________________________________________________________________________
    var $changes    = array( 'updateITEM', 'seoITEM','addNewITEM', 'deleteITEM'); #  'updateTREE' - tratat separat
    var $ARRchanges = array();                                                 #array-ul deserializat cu schimari
    var $change ;                                                              # unul din $changes
    var $pathChanges = 'tmp/GEN_edit/changes/';
    var $changePATH  = '';
    var $TESTchangePATH = '';


   #====================================================================================================================




    function TMP_updateTREE($ol,&$tree='', $Pid=-1, $t='') {

           //___________________________________________________________________________________
           $this->TMP_testMes .= "\n ";
           foreach($ol AS $poz=>$id_ch)
           {
               $id =  $id_ch['id'];


               if($Pid == -1)
               {

                   $this->TMP_testMes .="\n ".'Meniul '.$id.' ';
                   $tree[$id] = array();
                   if(isset($id_ch['children']))$this->TMP_updateTREE($id_ch['children'],$tree[$id], 0,'');

               }
               else
               {
                    $this->TMP_testMes .= "\n ".$t;
                    $this->TMP_testMes .='id-ul = '.$id.' ';
                    $this->TMP_testMes .=' si Pid-ul = '.$Pid.' ';
                    $this->TMP_testMes .=' si cu poz = '.$poz;

                    $tree[$id]['pid'] = $Pid;
                    $tree[$id]['poz'] = $poz;


                    if(isset($id_ch['children'])) $this->TMP_updateTREE($id_ch['children'],$tree, $id, $t."\t");
               }

           }

           if($Pid==-1)
           {
               $this->TMP_testMes .="\n \n".'Si acum vectorul';
               foreach($tree as $menuName=>$menu)
               {
                   $this->TMP_testMes .= "\n \n".'Meniul '.$menuName;
                   foreach($menu as $id=>$item)
                       $this->TMP_testMes .="\n ".'id-ul = '.$id.' is Pid = '.$item['pid'].' cu poz='.$item['poz'];

               }


              //_____________________________________________________________________________________

               return $tree;

              /* file_put_contents('TESTtree.txt','Am primit un POST'."\n\n".$this->TMP_testMes);
               file_put_contents('TREE.txt',serialize($tree));*/
           }

       }

    function TMP_updateITEM($updated)     {

        $id   = $_POST['id'];
        $val  = $_POST['val'];
        $type = $_POST['type'];

        $updated[$id]['val']  = $val;
        $updated[$id]['type'] = $type;

    #_______________________________________________[ TEST mes]________________________________________________________
        $this->TMP_testMes = "Au fost updatate \n";
        foreach($updated as $id => $detail)
           $this->TMP_testMes .='id = '.$id.' val='.$detail['val'].' type='.$detail['type']."\n";
    #___________________________________________________________________________________________________________________


        return $updated;

   /*      $serUpdated = serialize($updated);
        file_put_contents('updated.txt',$serUpdated);
        file_put_contents('TESTupdated.txt',$this->TMP_testMes);*/
    }
    function TMP_seoITEM($seo)            {

        # unde id = {'list_'$id};

        $id   = $_POST['id'];


        $seo[$id]['title_tag']        = $_POST['title_tag'];
        $seo[$id]['title_meta']       = $_POST['title_meta'];
        $seo[$id]['description_meta'] = $_POST['description_meta'];
        $seo[$id]['keywords_meta']    = $_POST['keywords_meta'];



     #_______________________________________________[ TEST mes]________________________________________________________
        $this->TMP_testMes = "Au fost updatate \n";
        foreach($seo as $id => $det)
           $this->TMP_testMes .= "\n id = ".$id.
                   "\n title_tag        = ".$det['title_tag'].
                   "\n title_meta       = ".$det['title_meta'].
                   "\n description_meta = ".$det['description_meta'].
                   "\n keywords_meta    = ".$det['keywords_meta'].
                  "\n";
    #___________________________________________________________________________________________________________________


        return $seo;
    }
    function TMP_addNewITEM($added)       {

        $id = $_POST['id'];
        $val = $_POST['val'];
        $type = isset($_POST['typeNew']) ? $_POST['typeNew'] : $_POST['type'];

        $added[$id]['val'] = $val;
        $added[$id]['type'] = $type;


        //_____________________________________________________________________________________
        $this->TMP_testMes = "Au fost adaugate \n ";
        foreach($added as $id => $detail)
           $this->TMP_testMes .='id = '.$id.' val='.$detail['val'].' type='.$detail['type']."\n";


        //_____________________________________________________________________________________

        return $added;

      /*  file_put_contents('TESTadded.txt',$this->TMP_testMes);
        $serAdded = serialize($added);
        file_put_contents('added.txt',$serAdded);*/
    }
    function TMP_deleteITEM($deleted)     {
           $id = $_POST['id'];


           $deleted[$id] = true;

           //_____________________________________________________________________________________
           $this->TMP_testMes = "Au fost deletate \n ";
           foreach($deleted as $id => $detail)
              $this->TMP_testMes .='id = '.$id."\n";


           //_____________________________________________________________________________________

           return $deleted;
       }
  #_____________________________________________________________________________________________________________________

    //TODO:    ATENTIE!!!   contact se introduce de 2 ori in baza de date cu aceleasi date

    function updateTREE($tree)         {

        $query  = 'DELETE from TREE';    $this->DB->query($query);
        $query2 = 'DELETE from MENUS';   $this->DB->query($query2);

        $mes = "A fost deletatat tot TREEul cu query-ul ".$query.' -- '.$query2."<br />";
        foreach($tree as $menuName=>$menu)
        {
            if($menuName!='MenuFREE')
            {
                $mes .= "<br/>".'Meniul '.$menuName."<br/>";
                $menuID = str_replace('Menu','',$menuName);

                foreach($menu as $id=>$item)
                {
                    $Pid = $item['pid'];
                    $poz = $item['poz'];

                    $res = $this->DB->query("SELECT Cid from TREE where Cid='{$id}' LIMIT 0,1 ");
                    if(!$res->num_rows)
                    {
                         $query = "INSERT into TREE (Pid,Cid,poz) values ('{$Pid}','{$id}','{$poz}')";
                         $this->DB->query($query);
                         $mes .=$query."<br/>";
                    }
                         if($Pid == 0)
                         {
                            $query = "INSERT into MENUS (id,idM) values ('{$id}','{$menuID}')";
                            $this->DB->query($query);
                            $mes .=$query."<br/>";
                         }


                }

            }

        }
        return $mes;

    }


    # in general detail poate fii 0 variabila sau un vector
    function seoITEM($id,$detail)      {


     #========================================[ OLD SEO DB ]============================================================
        $SEO = array();

        $query = "SELECT SEO from ITEMS WHERE id='{$id}'";
        $SEOres = $this->DB->query($query);
        if($SEOres->num_rows > 0) {$SEOarr = $SEOres->fetch_assoc(); $SEO = unserialize($SEOarr['SEO']);}
     #========================================[ NEW SEO ]===============================================================


            $SEO[$this->lang] = $detail;
            $SEO_arrSer = serialize($SEO);
     #========================================[ UPDATE DB ]=============================================================

             $query = "UPDATE ITEMS set SEO='{$SEO_arrSer}'   WHERE id='{$id}' ";
             $this->DB->query($query);


    #___________________________________________________________________________________________________________________
             return 'SEO pe id = '.$id."<br/>".$query;


    }
    function updateITEM($id, $detail)  {


              $LG = $this->C->lang;

              $name_old  = $this->masterTREE[$id]->{'name_'.$LG};
              $type_old  = $this->masterTREE[$id]->modName;
              $resFile_old = $this->masterTREE[$id]->resFile;


              $name_new  = trim($detail['val']);
              $type_new  = $detail['type'];
              $resFile_new = str_replace(' ','_',$name_new);


             if($this->lang != 'en' && $resFile_new!=$resFile_old)  $resFile_new = $resFile_old;


    #===============================================================================================================

            foreach($this->langs AS $LGS)
            {
                # GET_resPath($modType='',$resName='', $modName='' ,$resFile='', $lang = '')

                $modTypeOld = $this->C->get_modType($type_old);
                $modTypeNew = $this->C->get_modType($type_new);

                $fullPATH_old_LG  = $this->C->Get_resPath($modTypeOld,$type_old,$resFile_old,$LGS);
                if(is_file($fullPATH_old_LG)){

                    $fullPATH_new_LG  = $this->C->Get_resPath($modTypeNew,$type_new,$resFile_new,$LGS);


                    $content = $this->C->GET_resContent($fullPATH_old_LG);
                    unlink($fullPATH_old_LG);
                    file_put_contents($fullPATH_new_LG,$content);
                }


            }


    #===============================================================================================================
          #   $partialPATH_new = PUBLIC_PATH.'MODELS/'.$type_new.'/RES/';
          #   $partialPATH_old = PUBLIC_PATH.'MODELS/'.$type_old.'/RES/';


        //______________________________________________________________________________________________________________
        //__________________________________[ REFACTOR RES ]___________________________________________________________


/*            foreach($this->langs AS $LGS)
            {
                if(is_dir($partialPATH_old."{$LGS}/") && is_dir($partialPATH_new."{$LGS}/"))
                {
                    $fullPATH_old_LG = $partialPATH_old."{$LGS}/".$resFile_old.'.html';
                    $fullPATH_new_LG = $partialPATH_new."{$LGS}/".$resFile_new.'.html';

                    if(file_exists($fullPATH_old_LG))
                    {

                         $content = file_get_contents($fullPATH_old_LG); unlink($fullPATH_old_LG);
                         file_put_contents($fullPATH_new_LG,$content);

                    }
                }

            }*/


    #========================================[ UPDATE DB ]==============================================================
             $query = "UPDATE ITEMS set name_{$LG}='{$name_new}' , type='{$type_new}'  WHERE id='{$id}' ";
             $this->DB->query($query);


    #___________________________________________________________________________________________________________________
             return ' id = '.$id.' --- OLD ----'.$name_old.' ---NEW ---'. $name_new."<br/>".$query;




        //$query = "UPDATE ITEMS set name_[LG]='val' , type='type'  WHERE id='id' ";
        //$mes = "Au fost updatate cu query-ul ".$query."<br />";

    }
    function addNewITEM($id, $detail)  {


               $name_new = trim($detail['val']);
               $type_new = $detail['type'];
               $resFile_new = str_replace(' ','_',$name_new);



        #_______________________________________________________________________________________________________________
//???

                # C->GET_resPath($modType='',$resName='', $modName='' ,$resFile='', $lang = '')
                #  Get_resPath($modType, $modName,$lang='', $resName='')
              foreach($this->langs AS $LGS)
                #$fullPath_new = $this->C->GET_resPath('','',$type_new,$resFile_new,$LGS);
                $fullPath_new = $this->C->Get_resPath('',$type_new,$resFile_new,$LGS);
                        file_put_contents($fullPath_new, 'No contents added yet');


        #______________________________________[ADD to RES]_____________________________________________________________
               /*$partialPATH_new = PUBLIC_PATH.'MODELS/'.$type_new.'/RES/';

               foreach($this->langs AS $LGS)
                   if(is_dir($partialPATH_new."{$LGS}/"))
                         file_put_contents($partialPATH_new."{$LGS}/".$resFile_new.'.html', 'No contents added yet');*/

        #______________________________________[ ADD to DB ]____________________________________________________________

               $query ="INSERT into ITEMS (name_ro,name_en,type) values ('{$name_new}','{$name_new}','{$type_new}')";  $this->DB->query($query);


        #_______________________________________________________________________________________________________________
              return 'id = '.$id.' ---NEW---'.$fullPath_new.'en/'.$resFile_new."<br/>";




          //$query ="INSERT into ITEMS (name_ro,name_en,type) values (name_new,name_new,type)";
          //$mes = "Au fost adaugate cu query-ul ".$query."<br />";


    }
    function getENDpoints($id)
    {
         #propagation of DELETE in all children
         # toti descendetii id-ului deletat

        if (count($this->masterTREE[$id]->children) > 0) {
              $ch = $this->masterTREE[$id]->children;
              foreach ($ch AS $id_ch) {
                  $this->getENDpoints($id_ch);
              }
          }

          array_push($this->ENDpoints, $id) ;
      }

    function deleteITEM($id,$detail)
    {
         $this->getENDpoints($id);$ENDpoints_STR='';

         #_____________________________[DELETE from RES]_______________________________________________________________
         if (!$this->ENDpoints || count($this->ENDpoints) == 0) {

             error_log("[ ivy ] CHANGES - deleteITEMS : NU am reusit sa culeg "
                     ." ENDpoints pentru itemul cu id-ul = {$id} ");
             return "Nu s-a putut deleta itemul cu id = {$id} <br>";

         } else {

            foreach ($this->ENDpoints AS $idITEM) {
               $ENDpoints_STR .="'{$idITEM}',";

               if (isset($this->masterTREE[$idITEM])) {
                   $type = $this->masterTREE[$idITEM]->modName;
                   $resFile = $this->masterTREE[$idITEM]->resFile;
                   //=============================================================
                   foreach ($this->langs AS $LGS) {
                       # GET_resPath($modType='',$resName='', $modName='' ,$resFile='', $lang = '')
                       #$fullPath = $this->C->GET_resPath('','',$type,$resFile,$LGS);
                       $modType = $this->C->get_modType($type);
                       $fullPath  = $this->C->Get_resPath($modType,$type,$resFile,$LGS);

                       if (is_file($fullPath)) {
                           unlink($fullPath);
                       }
                   }
               }

               //==============================================================
               //$partialPATH = PUBLIC_PATH.'MODELS/'.$type.'/RES/';
               /*foreach($this->langs AS $LGS)
                   if(is_file($partialPATH."{$LGS}/".$resFile.'.html'))
                   unlink($partialPATH."{$LGS}/".$resFile.'.html');*/
            }

            //_____________________________________________________________________________________________________________
            $ENDpoints_STR = '('.substr($ENDpoints_STR,0,-1).')';      unset($this->ENDpoints);

            //_____________________________[DELETE from DB]________________________________________________________________

              $query1 ="DELETE from ITEMS where id IN {$ENDpoints_STR} ";  $this->DB->query($query1);

             //_____________________________________________________________________________________________________________
              return 'id = '.$id.'--- DELETE --- '.$ENDpoints_STR."<br />";
         }


    }
  #=====================================================================================================================



    function set_pathsChange($change){

        # change = ['updateITEM', 'seoITEM','addNewITEM', 'deleteITEM' ]
        # var $pathChanges = 'GENERAL/GEN_edit/RES/changes/';


        $this->change          =  $change;
        $this->changePATH      =  $this->pathChanges.$this->change.'.txt';
        $this->TESTchangePATH  =  $this->pathChanges.'TEST'.$this->change.'.txt';



        $this->ARRchanges      = (file_exists($this->changePATH) ?  unserialize(file_get_contents($this->changePATH)) : array() );
    #______________________________________________________________________________________________________________________________
        return (count($this->ARRchanges) > 0 ? true : false);

    }

    function deleteCHANGES()   {

        $arrTREE = array('updateTREE');
        $changes =array_merge( $this->changes,$arrTREE);

        foreach($changes AS $change){
            if(file_exists( $this->pathChanges.$change.'.txt')){
                unlink( $this->pathChanges.$change.'.txt');
                unlink( $this->pathChanges.'TEST'.$change.'.txt');
            }

        }



    }

    public function __construct(){


       $this->pathChanges = VAR_PATH.$this->pathChanges;

       # $this->set_pathChanges();

        # pentru a putea fosii cumsecade set_pathsChange;
        # poate dorim sa folosim aceasta clasa pur si simplu
    }

}
