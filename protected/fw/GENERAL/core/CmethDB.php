<?php

/**
 * CmethDB
 *
 * @package Core
 * @version 1.0
 * @copyright Copyright (c) 2012 Serenity Media
 * @author  Ioana Cristea
 * @license  http://www.gnu.org/licenses/agpl-3.0.txt AGPLv3
 */
class CmethDB extends CrenderTmpl {

    #=============================[ select ]====================================

    public function Db_Get_queryRes($query)
    {
        // daca query este obiect atunci probrabil este o resursa
        // daca nu atunci probabil ca este un sql
        $queryRes = is_object($query)
                    ?  $query : $this->DB->query($query);

        return $queryRes;
    }

    public function Db_Set_procModProps(&$mod,$processResMethod, $query)
    {
        $queryRes = $this->Db_Get_queryRes($query);

        $allRecords = array();
        $row = $queryRes->fetch_assoc();

        $row = $mod->{$processResMethod}($row);

        if ($row) {
            if (is_array($row) && count($row) > 0) {
                # atribuim valori direct in prop obiectului
                foreach($row AS $recordName => $recordValue) {
                    $mod->$recordName = $recordValue;
                }
            }
            $allRecords[0] = $row;
        }

        return $allRecords;

    }
    public function Db_Set_modProps(&$mod, $query)
    {
        $queryRes = $this->Db_Get_queryRes($query);

        $allRecords = array();
        $row = $queryRes->fetch_assoc();

        # atribuim valori direct in prop obiectului
        foreach($row AS $recordName => $recordValue) {
            $mod->$recordName = $recordValue;
        }

        $allRecords[0] = $row;

        return $allRecords;
    }
    public function Db_Get_procRows(&$mod,$processResMethod, $query)
    {
        $queryRes = $this->Db_Get_queryRes($query);

        $allRecords = array();

        if ($queryRes->num_rows > 0) {
            while ($row = $queryRes->fetch_assoc()) {
                $row = $mod->{$processResMethod}($row);
                if ($row) {
                    array_push($allRecords, $row);
                }
            }
        }
        return $allRecords;

    }
    public function Db_Get_procRow(&$mod,$processResMethod, $query)
    {
        $row = $this->Db_Get_queryRes($query)->fetch_assoc();

        if($row) {
            $row = $mod->{$processResMethod}($row);
            if($row) {
                return $row;
            }
        }
        return false;
    }

    public function Db_Get_rows($query, $method = 'fetch_assoc')
    {
        $queryRes = $this->Db_Get_queryRes($query);

        $allRecords = array();
        while ($row = $queryRes->{$method}()){
            array_push($allRecords, $row);
        }
        return $allRecords;
    }

    public function Handle_Db_fetch(&$mod,$query,$processResMethod='', $onlyArr = false)
    {
        $queryRes = $this->DB->query($query);
        $numRows  = $queryRes->num_rows;
        /**
         * Daca avem un singur rand returnat && pentru aceasta varianta atribuim
         * rezultatele modulului pasat + un array[0]- cu randul returnat
         *  - avem si o metoda de procesare / sau nu
         *
         * Daca avem mai multe randuri returnate => rezultatul va fi returnat
         * doar sub forma de array multidimensional procesat sau nu
         */
        if ($numRows) {

            if ($queryRes->num_rows == 1 && !$onlyArr) {
                if ($processResMethod && method_exists($mod,$processResMethod)) {
                    return $this->Db_Set_procModProps($mod, $processResMethod,  $queryRes);
                } else {
                    return $this->Db_Set_modProps($mod, $queryRes);
                }

            } else {
                if ($processResMethod && method_exists($mod,$processResMethod)) {
                    return $this->Db_Get_procRows($mod, $processResMethod, $queryRes);
                } else {
                    return $this->Db_Get_rows( $queryRes);
                }
            }
        }
    }

   #=============================[ update / Insert ]============================


    //relocare remote ...sunt situatii cand e nevoie
    public function reLocate($location='', $ANCORA='',$paramAdd='')
    {
        Toolbox::relocate($location, $ANCORA, $paramAdd);
    }

    //sql_query
    public function Db_query($query, $reset = false, $ANCORA='', $location='',
        $paramAdd='', $errorMessage=''
    ) {

        $this->DB->query($query);

        //echo $query."<br>";
        // daca se cere reset
        if (!$reset) {
            return $errorMessage;
        } else {
            $this->reLocate($location,$ANCORA,$paramAdd);
        }
    }

    //Db_queryBulk
    public function Db_queryBulk($queries, $reset=false, $ANCORA='', $location='',
        $paramAdd='', $errorMessage=''
    ) {

        foreach($queries AS $query){
            $res =   $this->DB->query($query);
            // daca nu reuseste sa faca un query va iesii din loop
            if(!$res) {
              break;
            }
        }

        if (!$reset) {
            return $errorMessage;
        } else {
            $this->reLocate($location,$ANCORA,$paramAdd);
        }

    }


    /**
     *  Seteaza stringul pt sql UPDATE / INSERT de genul
     *  varName1 = 'varValue1', varName2 = 'varValue2', ...
     *
     * dintr-un vector asociativ trimis
     *
     * @param $values - vectorul asociativ de genul varName => varValue
     *
     * @return string - 'varValue1', varName2 = 'varValue2', ...
     */
    public function Db_setFromAssoc($values)
    {
        $sets = array();
        foreach($values AS $varName=>$varValue) {
            $varValue = $this->DB->real_escape_string($varValue);
            array_push($sets, "$varName = '".$varValue."'");
        }

        $set = implode(', ', $sets);
        return $set;
    }

    #==============================[ query strings ]============================
    public function Db_Get_filters($obj, $filters)
    {
        //filterList
        $filtersStrs = array();

        // check for requested filter
        if (isset($_REQUEST['filterName']) && isset($_REQUEST['filterValue'])) {
           $filters[$_REQUEST['filterName']] =  $_REQUEST['filterValue'];
        }

        // pentru mai multe filtre ( idee neimplementata inca)
        /*if(isset($_REQUEST['filters'])) {
            $filters = array_merge($filters, $_REQUEST['filters']);
        }*/

        if (count($filters)) {

            foreach ($filters AS $filterName => $filterValue) {
                //test if method exists
                if (!method_exists($obj, 'Get_Filter_'.$filterName)) {
                    error_log("[ ivy ] CmethDb - Db_handled_filters :"
                              ." Sorry the filter $filterName has no method handler "
                    );
                } else {
                    $filter = $obj->{'Get_Filter_'.$filterName}($filterValue);
                    array_push($filtersStrs, $filter);
                }
            }
        }

        return $filtersStrs;


    }

    /**
     * @param $obj
     * @param $queryAdds = array('funcName' = >array(parameters for function))
     *
     * @return array
     *
     */
    public function Db_Get_queryAdds($obj, $queryAdds)
    {
         $queryJoins = array();
         if (count($queryAdds)) {

            foreach ($queryAdds AS $queryAddFn => $queryFnArgs) {

                if(!method_exists($obj, $queryAddFn)) {
                    error_log("[ ivy ] CmethDb - Db_handled_filters :"
                              ." Sorry the method $queryAddFn does not exists "
                    );
                } else {
                    $join = call_user_func(array($obj, $queryAddFn), $queryFnArgs);
                    array($queryJoins, $join);
                }

            }
         }

        return $queryJoins;
    }

    /**
     * based on Get_Filter_[filterName]
     *
     * @param $obj
     * @param $queryBase
     *      - poate sa fie numele undei functii sau chiar queryul in sine
     *      - daca sa zicem dimensiunea stringului este < 30 este posibil sa fie o metoda
     *        a obiectului deci poate fi testata intai caatare si apoi considerata
     *        fix un query
     * @param array $queryAdds = array('fnNames');
     * @param array $filters
     *      - filtrele default
     *      = array('filterName' = > 'filterValue')
     *
     * @return stdClass
     *      ->parts[query, join, joins = array(), where, wheres = array()]
     *      -> fullQuery
     */
    public function Db_Get_queryParts($obj, $queryBase,  $filters = array(), $queryAdds = array())
    {
        $sql = new stdClass();

        $queryBaseLn = strlen($queryBase);

        $sql->parts['query'] = $queryBaseLn < 30
                             ? method_exists($obj, $queryBase)
                                    ? $obj->{$queryBase}()
                                    : $queryBase
                             : $queryBase;

        $sql->parts['joins'] = $this->Db_Get_queryAdds($obj, $queryAdds);
        $sql->parts['join']  = implode(' ', $sql->parts['joins']);

        $sql->parts['wheres'] = $this->Db_Get_filters($obj, $filters);
        $sql->parts['where']  = (count($sql->parts['wheres']) == 0 ? ''
                                  : ' WHERE '.implode(' AND ', $sql->parts['wheres'])
                                );


        $sql->query       = $sql->parts['query']
                               .$sql->parts['join']
                               .$sql->parts['where'];

        error_log("[ ivy ] CMethDb - Db_Get_queryParts : "
                         .preg_replace('/\s+/', ' ', $sql->query));

        return $sql;
    }

}
