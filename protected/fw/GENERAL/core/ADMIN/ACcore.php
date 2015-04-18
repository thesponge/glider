<?php
class ACcore extends ACunstable
{
    /**
     * rescrisa in core pt adminFolder se arata mesajul iar pentru guest - no mesage
     * @param $mess
     * @return string
     */
    static function debugMess($mess){ return $mess;}

    # functie cu denumire ambigua
    public function mergeArray(){

        $this->default_PLUGINS = array_merge($this->default_PLUGINS,$this->defaultAdmin_PLUGINS);
        $this->default_MODELS  = array_merge($this->default_MODELS,$this->defaultAdmin_MODELS);
        $this->default_LOCALS  = array_merge($this->default_LOCALS,$this->defaultAdmin_LOCALS);
        $this->default_GENERAL = array_merge($this->default_GENERAL,$this->defaultAdmin_GENERAL);


    }

    protected function Set_modules() {

       // $this->adminFolder = true;

        $this->mergeArray();
        parent::Set_modules();

        // not sure for what is this used
        //$this->display = '';
        # $this->TOOLbar->ADDbuttons("<a href='".PUBLIC_URL."assets/XOS-IDE/XOSIDE/index_EN.php' target='_blank'> IDE </a>");
    }

}
