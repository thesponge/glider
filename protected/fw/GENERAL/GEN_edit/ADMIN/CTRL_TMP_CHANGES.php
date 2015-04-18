<?php
class CTRL_TMP_CHANGES extends CHANGES
{
    function parseCHANGE()
    {
        $this->set_pathsChange($_POST['action']);

        //=======================================================================
        //daca schimbarea este defapt updateTREE =>$_POST['but_ol'];
        $lastCHANGES = (isset($_POST['but_ol'])
                     ?   $_POST['but_ol']
                     :    $this->ARRchanges  );
        //procesez schimbarea
        $changes     = $this->{'TMP_'.$this->change}($lastCHANGES);

        //======================================================================
        //serializez si retin toate schimbarile in fisier
        file_put_contents($this->changePATH, serialize($changes));
        file_put_contents($this->TESTchangePATH, $this->TMP_testMes);

    }

    function __construct()
    {
        parent::__construct();

        error_log("[ ivy ] "."CTRL_TMP_CHANGES __construct() cu ".$_POST['action']);
        // actiune trimisa de GEN_edit.js - la  schimbarea listei
        //sau a elementelor din ea
        if (isset($_POST['action'])) {
            $this->parseCHANGE();
        }
        //RESET_changes - prima actiune trmisa de GEN_edit.js  - trimisa la instantierea lui
        if (isset($_POST['deleteCHANGES'])) {
            $this->deleteCHANGES();
        }
    }

}
