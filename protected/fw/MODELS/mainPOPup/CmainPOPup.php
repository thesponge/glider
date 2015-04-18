<?php
class CmainPOPup
{
    /**
     * ATENTIE - css-ul este in GENERAL / layout.css
     */
    var $C;               //main object
    var $LG;
    var $adminSTATUS;
    var $pathIMG;
    var $urlIMG;

    function set_DISPLAY()   {

        $mypost = false;
        if(isset($_POST['save_contPOP']))
        {
            $LG = $this->LG;
            $base_img=FW_PUB_PATH.'GENERAL/css/img/';
            if($_FILES['filename_picPOP_'.$LG]['name'])
            {
                $promo1 = new Resize($_FILES['filename_picPOP_'.$LG], 450, 337, $base_img,'','','jpg','mainPOPup_'.$this->LG);
            }
              $mypost = true;

        }
    #_______________________________________________________________________________________
        if(isset($_POST['delete_contPOP']))
        {
            if(file_exists($this->pathIMG)) unlink($this->pathIMG);
            $mypost = true;
        }
    #_______________________________________________________________________________________

        if($mypost)
        {
            unset($_POST);
            header("Location: ".$_SERVER['REQUEST_URI']);
            exit;
        }


    }
    function DISPLAY()       {
        $disp=''; $LG = $this->LG;

        if(file_exists($this->pathIMG))  $IMGtag ="<img src='".$this->urlIMG."' alt='mainPOPup' />";
        elseif($this->adminSTATUS)       $IMGtag ='No picture ADDED yet';

       #_______________________________________________________________________________________________________________
        if( (!isset($_SESSION['popUP'])  && isset($IMGtag) ) || $this->adminSTATUS  )
        {

            $disp = "
             <div id='mainPOPup'>

                 <div class = 'SING contPOP' id='contPOP_1_{$LG}'>
                     <input type='button' name='close_mainPOPup' value='x' onclick='javascript: $(this).parents(\"#mainPOPup\").remove();' />
                     <div class='EDpic picPOP'> $IMGtag </div>
                 </div>
             </div>
";
           $_SESSION['popUP'] = 1;
        }

        return $disp;
    }
    function __construct($C)
    {
        $this->C = &$C;
        $this->LG = &$C->lang;


       #
        $this->adminSTATUS =($this->C->admin && $this->C->idTree==1 && $this->C->mgrName=='products' ? true : false);

        $this->pathIMG = FW_PUB_PATH.'GENERAL/css/img/mainPOPup_'.$this->LG.'.jpg';
        $this->urlIMG = FW_PUB_URL.'GENERAL/css/img/mainPOPup_'.$this->LG.'.jpg';

        $this->set_DISPLAY();
    }
}