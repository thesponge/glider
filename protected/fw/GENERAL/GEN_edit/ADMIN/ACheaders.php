<?php
class ACheaders{

   /* private function tmpSave($res,$name) {
        $targetPath = PUBLIC_PATH.'GENERAL/css/img/'.$name;
        try {
            if(move_uploaded_file($res, $targetPath) != TRUE)
                throw new Exception("There was an error uploading the file, please try again!");
            else
                return $targetPath;
        }
        catch (Exception $e) {
            echo $e->errorMessage();
        }
    }*/




    function __construct()
    {


        if(isset($_POST['save_pictures']))
        {
           //($sPhotoFileName, $width, $height, $path='.', $prefix='', $suffix='',$extension='jpg')
            $base_img=FW_PUB_PATH.'GENERAL/css/img/';


            if($_FILES['filename_promo1_ro']['name']!='')
               // echo 'promo1 '.($_FILES['filename_promo1_ro']['name'])."<br/>";
                $promo1 = new Resize($_FILES['filename_promo1_ro'], 169, 150, $base_img,'','','png','oferta1');
            if($_FILES['filename_promo2_ro']['name']!='')
              //  echo 'promo2 '.var_dump($_FILES['filename_promo2_ro'])."<br/>";
                 $promo2 = new Resize($_FILES['filename_promo2_ro'], 169, 150, $base_img,'','','png','oferta2');
            if($_FILES['filename_mainPic_ro']['name']!='')
               // echo 'mainPic '.$_FILES['filename_mainPic_ro']['name']."<br/>";
                 $mainPic = new Resize($_FILES['filename_mainPic_ro'], 627, 320, $base_img,'','','jpg','banner');

            unset($_POST);
            header("Location: ".$_SERVER['REQUEST_URI']);
            exit;
        }


    }
}