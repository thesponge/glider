<?php
/**
 * Used to:
 * - metodele "staging" din cadrul claselor din core
 * nestandardizate, nesigure dar generale
 */
class Cunstable extends Ctree{



    // autonoma
    public function Get_resPath($modType, $modName, $resName, $lang='')
    {

        $lang        = $lang ? $lang : $this->lang;
        $mod_resDir  = RES_PATH."{$modType}/{$modName}/";
        $mod_resPath = $mod_resDir."{$lang}_{$resName}.html";

        if (!is_dir($mod_resDir)) {
            mkdir($mod_resDir,0777,true);
        }

       return $mod_resPath;
    }



    /*==========================================================*/
    /*=================[from CrenderTmpl]==========================*/
    /*==========================================================*/




    /*==========================================================*/
    /*=================[from CmethDB]==========================*/
    /*==========================================================*/

     //Sql_Get_queryRowsByCat
    //Not sure were it is used???
    public function GET_modProperties_byCat(&$mod,$query,$Col_name,$processResMethod='')
    {
            # va returna un array de genul allRecords[Cat_name][0,1,2...] = array(children array);
            # hmm..daca avem mai multe coloane atunci $allRecords[col][0,,1...]

            $allRecords = array();
            $res = $this->DB->query($query);

            if($res->num_rows > 0)
            {
                while($row = $res->fetch_assoc())
                {

                    $col = $row[$Col_name];

                    if($processResMethod!='' && method_exists($mod,$processResMethod) )
                    {

                        $row = $mod->{$processResMethod}($row);
                    }

                    $allRecords[$col] = array();
                    array_push($allRecords[$col], $row);

                    #var_dump($allRecords[$col]);
                    # am impresia ca asta imi va da peste cap sortarile din query - ramane de vazut


                }

                #var_dump($allRecords);

                return $allRecords;
            }


        }
    //Not sure were it is used???
    public function GETtree_modProperties(
        &$mod, $query, $idC_name,
        $idP_name,$processResMethod=''
    ) {

            # va returna un array de genul allRecords[idP][idC] = array(children array);
            # idC_name / idP_name = numele campurilor pt child / parent

            $allRecords = array();
            $res = $this->DB->query($query);

            if($res->num_rows > 0)
            {
                while($row = $res->fetch_assoc())
                {
                    $parentID = $row[$idP_name];
                    $ID       = $row[$idC_name];
                    if($processResMethod!='') $row = $mod->{$processResMethod}($row);

                    $allRecords[$parentID][$ID] = $row;

                    # am impresia ca asta imi va da peste cap sortarile din query - ramane de vazut


                }

                return $allRecords;
            }

        }




    /*============================================[from CgenTools]=============*/
    //============================================[simple validate POSTS]========

    // i realy dont know....really...
    function check_notempty($val)
    {
        return !empty($val) ? true : false;
    }

    function validate($validType, $val, $fbk, $nomust = false)
    {
        if($this->{'check_'.$validType}($val) ){
            //echo substr($val,0,30)." is true <br>";
            return true;
        }
        //echo substr($val,0,30)." is false <br>";

        // daca are un feedback setat
        if($fbk && is_object($this->feedback)){
            $nomust =  $this->feedback->SetGet_badmess($fbk);

        }
        return $nomust;
    }
    // alias
    function valid_notempty($val, $fbk, $nomust = false)
    {
         return $this->validate('notempty', $val,  $fbk, $nomust);
    }

    /**
     * Based on yml-config
     *
     * updatePrev:
            title:
     *           pstName: 'otherName'
                 validRules: [ string, notempty]
                 fbk_string: {type: "warning", name: "News Title", mess: "Your title should be a string" }
                 fbk_notempty    {type: "error", name: "News Title", mess: "Your news must have a title" }
     *
              newsDate: ""

              extLink: ""

              lead:""

              newsPic: ""
     */
    function Get_postsValidation($psts, $expectedPosts)
    {
        $validation = true;
        foreach($psts AS $prop => $pst){
       // foreach($expectedPosts AS $prop => $det){

            $det = $expectedPosts[$prop];

            // vezi daca are reguli de validare
            if (isset($det['validRules'])) {
                foreach($det['validRules'] AS $validRule ){
                    $fbk = isset($expectedPosts['fbk_'.$validRule])
                           ? $expectedPosts['fbk_'.$validRule]
                           : '';
                    $validation = $this->validate($validRule, $pst, $fbk );
                }
            }
        }
        return $validation;
    }




}