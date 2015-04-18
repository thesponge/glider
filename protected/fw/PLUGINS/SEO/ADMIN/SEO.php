<?php

    # acest script preia SEO pentru un anumit element
    # - este accesat de GEN_edit.js via processScript.php

#==========================================================================================================

$LG      = $_POST['LG'];
$list_id = $_POST['id'];                #id-ul vine de la GEN_edit.js ca list_[id]
$idarr   = explode('_',$list_id);         #extragem id-ul elementului schimbat
$id      = end($idarr);



#==========================================================================================================
#incerc sa vad daca nu exista cumva salvari temporare create de GEN_edit
# change = ['updateITEM', 'seoITEM','addNewITEM', 'deleteITEM' ]

/**
 * set_pathsChange
 *  - RET: true / false  = exista schimbari la adresa  GENERAL/GEN_edit/RES/changes/seoITEM.txt
 *  - SET: ARRchanges    = vector deserializat cu un array asociativ
 *                         SEO = $seo['list_'.$id]['title_tag','title_meta','description_meta','keywords_meta']
 *
 * apoi testez daca exista schimbari la list_id-ul solicitat de mine
 * daca nu voi lua datele de SEO din DB
 */

$ch = new CHANGES();
#$ch->set_pathChanges();
#echo 'changePath-ul '.$changes->pathChanges."<br>";

$changesStat = $ch->set_pathsChange('seoITEM');
#echo 'changePath-ul '.$ch->changePATH;

if($changesStat && isset($ch->ARRchanges[$list_id])){

    #echo ' list_id-ul este '.$list_id."<br>";
    $SEO = $ch->ARRchanges[$list_id];


}
else{

    #var_dump($changes->ARRchanges);

    $DB = new mysqli(DB_HOST,DB_USER,DB_PASS,DB_NAME);
    $query = "SELECT SEO from ITEMS  WHERE id='{$id}' ";
    $res =  $DB->query($query);
    if($res->num_rows > 0)
    {
        $SEO_res = $res->fetch_assoc();
        $SEO_LG = unserialize($SEO_res['SEO']);

        $SEO = &$SEO_LG[$LG];
    }

}
#==========================================================================================================

if(isset($SEO)){
        $title_tag       = $SEO['title_tag'];
        $title_meta      = $SEO['title_meta'];
        $description_meta= $SEO['description_meta'];
        $keywords_meta   = $SEO['keywords_meta'];

}
else{
        $title_tag        = '';
        $title_meta       = '';
        $description_meta = '';
        $keywords_meta    = '';

}



#=======================================================================================================================
$disp =  "    <p id='descr-SEO'>Detalii Seo</p>
               <input type='text' name='title_tag'         value='{$title_tag}'        placeholder='Title Tag'/> Title tag<br />
               <input type='text' name='title_meta'        value='{$title_meta}'       placeholder='Title meta'/> Title <br />
               <input type='text' name='description_meta'  value='{$description_meta}' placeholder='Description meta'/> Description <br />
               <input type='text' name='keywords_meta'     value='{$keywords_meta}'    placeholder='Keywords meta'/>       Keywords<br />
              ";
echo $disp;

