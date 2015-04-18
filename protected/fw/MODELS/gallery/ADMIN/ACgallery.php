<?php

class ACgallery extends Cgallery{


    function set_manageCaptions_res(){
        file_put_contents(RES_PATH."{$this->modType}/gallery/managePics.html",$this->manageCaptions);

    }
    function get_manageCaptions(){

        $this->titles = '';
        $this->links  = '';
        foreach($this->captions AS $caption){

            $this->titles .= $caption['title_'.$this->lang]."\n";
            $this->links .= $caption['link']."\n";


        }
        $this->titles = trim($this->titles);
        $this->links  = trim($this->links);
        $this->manageCaptions = $this->C->renderDisplay_frommod($this, '',"/{$this->modType}/gallery/tmpl_pro/ADMIN/tmpl/manageCaptions.html");
        //echo $this->manageCaptions;
        $this->set_manageCaptions_res();
    }

    function saveCaptions(){
        #var_dump($_POST);
        /**
         * POST
         *
         * title
         * link

         * mod
         * captions
         */

        /*foreach($this->urlImages AS $key=> $urlPic)
        {
            $this->captions[$key]["title_{$this->lang}"] =  $_POST['title'][$key];
            $this->captions[$key]['link'] = $_POST['link'][$key];
            array_push($cap, $this->captions[$key]) ;


        }*/

        $cap = array();


        $titles = $_POST['titles'];
        $arrTitles = explode("\n",$titles);

        $links = $_POST['links'];
        $arrlinks = explode("\n",$links);


        foreach($arrTitles AS $key => $title){

            $title = trim($title);
            if($title){

                $this->captions[$key]["title_{$this->lang}"] = $title;
                $this->captions[$key]['link'] = trim($arrlinks[$key]);
                array_push($cap, $this->captions[$key]) ;
            }
        }

        $captions = array ('captions' => $cap);
        $ymlDump = Spyc::YAMLDump($captions);
        file_put_contents(ETC_PATH."{$this->modType}/gallery/captions.yml", $ymlDump);
        //echo $ymlDump;

        //var_dump($this->captions);

    }
    function _init_(){

        parent::_init_();
        if(isset($this->captions))
            $this->get_manageCaptions();

    }
}
