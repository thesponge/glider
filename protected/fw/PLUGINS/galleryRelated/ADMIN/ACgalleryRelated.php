<?php
class ACgalleryRelated extends Cgallery
{
 //ADD pics
  public function _hook_add_pic_portofProject(){


      $lg = $this->lang;
      $alterPicUrl   = $_POST["alterPic_{$lg}"];
      $alterPicTitle = $_POST["altPic-title_{$lg}"];


      // procesarea datelor
      $_POST["altPic-title_{$lg}"] = $alterPicTitle = trim($alterPicTitle);
      $_POST["alterPic_{$lg}"]     =  '/'.str_replace(baseURL,'',$alterPicUrl );


      // returnare feedback
      if($alterPicTitle && $alterPicUrl )
      {
          return true;
      }
      else{
          if(!$alterPicTitle){
              $this->C->feedback->setFb('error','Picture title', "You must insert a title for you picture ");
              //echo "You should insert a name for your project";
          }
          if(!$alterPicUrl){
              $this->C->feedback->setFb('error','Project alternate picture',
                  "You must insert a picture for your project");
              //echo "You should insert a main Picture for your project";

          }

          return false;
      }
  }
  public  function add_pic_portofProject(){

      /**
       * USE
       * alterPic_ro
       * altPic-title_ro
      */

      $lg = $this->lang;
      $idPr = $_GET['idPr'];
      $alterPicUrl  = $_POST["alterPic_{$lg}"];
      $alterPicTitle = $_POST["altPic-title_{$lg}"];



      $queries = array();

      $query_insert = "INSERT into portfolio_pics
                                (idPr, picUrl)
                         values ($idPr, '{$alterPicUrl}')";

      $this->DB->query($query_insert);

      $idPic = $this->DB->insert_id;

      foreach($this->C->langs AS $lang){

          array_push($queries, "INSERT into  portfolio_pics_i18n
                                            (idPic, idLg, picTitle)
                                     values ($idPic, '{$lang}', '{$alterPicTitle}')");
      }

      $this->C->DMLsql_bulk($queries, false);


  }

  // UPDATE alternate Pics
  public function _hook_update_pic_portofProject(){



      $lg = $this->lang;
      $alterPicUrl   = $_POST["alterPic_{$lg}"];
      $alterPicTitle = $_POST["altPic-title_{$lg}"];


      // procesarea datelor
      $_POST["altPic-title_{$lg}"] = $alterPicTitle = trim($alterPicTitle);
      if($alterPicUrl)
        $_POST["alterPic_{$lg}"]     =  '/'.str_replace(baseURL,'',$alterPicUrl );


      // returnare feedback
      if($alterPicTitle)
      {
          return true;
      }
      else{
          if(!$alterPicTitle){
              $this->C->feedback->setFb('error','Picture title', "You must insert a title for you picture ");
              //echo "You should insert a name for your project";
          }

          return false;
      }
  }
  public  function update_pic_portofProject(){

      $lg   = $this->lang;
      $idPic = $_POST['BLOCK_id'];
      $alterPicUrl   = $_POST["alterPic_{$lg}"];
      $alterPicTitle = $_POST["altPic-title_{$lg}"];

      $queries = array();

      if( $alterPicUrl)
      {
          $query_update_url = "UPDATE portfolio_pics
                                  SET picUrl  = '{$alterPicUrl}'
                                WHERE idPic = $idPic ";
          array_push($queries, $query_update_url);
      }

      $query_update_title = "UPDATE portfolio_pics_i18n
                                SET picTitle = '{$alterPicTitle}'
                                WHERE idPic = $idPic AND idLg = '{$lg}' ";
      array_push($queries, $query_update_title);


       $this->C->DMLsql_bulk($queries, false);


  }

  // delete Alternate Pics
  public  function delete_pic_portofProject(){

      $idPic = $_POST['BLOCK_id'];
      $query = "DELETE from portfolio_pics WHERE idPic = $idPic ";

      $this->DB->query($query);
  }

  // UPDATE project
  public function _hook_update_portofProject(){

   /**
    * USE
    * pro-Title_ro       LG
    * proYear_ro         -
    * proLocation_ro     LG
    * proStatus_ro       LG
    * proTeam_ro         -
    * proDescription_ro  LG
    *
    *
    * mainPic_ro         -
    * mainPicTitle_ro    LG
    *
    *
   */
      // ATENTIE ar trebuii sa dau niste warninguri spunand ce campuri au fost lasate necompletate


      $lg = $this->lang;
      $_POST["pro-Title_{$lg}"]      = trim($_POST["pro-Title_{$lg}"]);
      $_POST["proYear_{$lg}"]        = trim($_POST["proYear_{$lg}"]);
      $_POST["proLocation_{$lg}"]    = trim($_POST["proLocation_{$lg}"]);
      $_POST["proStatus_{$lg}"]      = trim($_POST["proStatus_{$lg}"]);
      $_POST["proTeam_{$lg}"]        = htmlentities( trim($_POST["proTeam_{$lg}"]) );
      $_POST["proDescription_{$lg}"] = trim($_POST["proDescription_{$lg}"]);
      $_POST["mainPicTitle_{$lg}"]   = trim($_POST["mainPicTitle_{$lg}"]);


      if($_POST["pro-Title_{$lg}"])
      {
          if( $_POST["mainPic_{$lg}"])
              $_POST["mainPic_{$lg}"] =  '/'.str_replace(baseURL,'',$_POST["mainPic_{$lg}"]);

          return true;
      }
      else{
          if(!$_POST["pro-Title_{$lg}"]){
              $this->C->feedback->setFb('error','Project title', "You should insert a name for your project");
              //echo "You should insert a name for your project";
          }

          return false;
      }
  }
  public  function update_portofProject(){

      /**
       * USE
       * pro-Title_ro       LG
       * proYear_ro         -
       * proLocation_ro     LG
       * proStatus_ro       LG
       * proTeam_ro         -
       * proDescription_ro  LG
       *
       *
       * mainPic_ro         -
       * mainPicTitle_ro    LG
       *
       *
      */

      $idPr = $_GET['idPr'];
      $lg = $this->lang;            ;
      $proTitle         = $_POST["pro-Title_{$lg}"]     ;
      $proYear          = $_POST["proYear_{$lg}"]       ;
      $proLocation      = $_POST["proLocation_{$lg}"]   ;
      $proStatus        = $_POST["proStatus_{$lg}"]     ;
      $proTeam          = $_POST["proTeam_{$lg}"]       ;
      $proDescription   = $_POST["proDescription_{$lg}"];

      $mainPicTitle     = $_POST["mainPicTitle_{$lg}"]  ;
      $mainPicUrl       = $_POST["mainPic_{$lg}"];
      $mainPicId        = $_POST['mainPicId'];

      $queries = array();

       // update queries

      // update campuri netranslatabile
       array_push($queries, "UPDATE portfolio_projects
                                SET year = '{$proYear}',
                                    team = '{$proTeam}'
                                WHERE idPr = $idPr " );

     // update translatable fields
      array_push($queries, "UPDATE portfolio_projects_i18n
                               SET title    = '{$proTitle}',
                                   location = '{$proLocation}',
                                   status   = '{$proStatus}',
                                   description = '{$proDescription}'
                               WHERE idPr = $idPr AND idLg = '{$lg}' " );


      // update mainPicture for project
      // upda url
       if($mainPicUrl)
        array_push($queries, "UPDATE portfolio_pics  SET picUrl = '{$mainPicUrl}' WHERE idPr= $idPr AND picPoz = 0 ");

      if($mainPicTitle)
      // update title
       array_push($queries, "REPLACE into  portfolio_pics_i18n
                                     SET picTitle = '{$mainPicTitle}',
                                         idPic= $mainPicId,
                                         idLg = '{$lg}'
                                     ");

      $this->C->DMLsql_bulk($queries, false);

  }


  //========================[ project - preview ]=============================


  // ADD Project
  public  function _hook_add_portofProjectPrev(){

          $lg = $this->lang;
          $_POST["proTitle_{$lg}"] = trim($_POST["proTitle_{$lg}"]);

          if($_POST["proTitle_{$lg}"] && $_POST["proPic_{$lg}"])
          {
              $_POST["proPic_{$lg}"] =  '/'.str_replace(baseURL,'',$_POST["proPic_{$lg}"]);
              return true;
          }
          else{
              if(!$_POST["proTitle_{$lg}"]){
                  $this->C->feedback->setFb('error','Project title', "You should insert a name for your project");
                  //echo "You should insert a name for your project";
              }
              if(!$_POST["proPic_{$lg}"]){
                  $this->C->feedback->setFb('error','Project picture',
                      "You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project You should insert a main Picture for your project
                  You should insert a main Picture for your project
                  You should insert a main Picture for your project You should insert a main Picture for your project
                  You should insert a main Picture for your project You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  You should insert a main Picture for your project  You should insert a main Picture for your project
                  ");
                  //echo "You should insert a main Picture for your project";

              }

              return false;
          }

  }
  public  function add_portofProjectPrev(){

      /**
       * USE
       * proTitle_ro
       * proPic_ro
       *
       *
      */

      // get data
      $lg = $this->lang;
      $proTitle = $_POST["proTitle_{$lg}"];
      $proPic   = $_POST["proPic_{$lg}"];


      // insert project
      $query_project = "INSERT into portfolio_projects (idCat) values ({$this->idC}) ";
      $this->DB->query($query_project);

      $idPr = $this->DB->insert_id;


      // manages langs for projects
      $queries = array();
      foreach($this->C->langs AS $lang){
          array_push($queries, "INSERT into portfolio_projects_i18n (idPr, idLg, title) values ( $idPr, '{$lang}', '{$proTitle}' )");
      }

      // pictures for project
      $query_mainPic = "INSERT into portfolio_pics (idPr,picUrl, picPoz) values ( $idPr, '{$proPic}', 0 )";
      $this->DB->query($query_mainPic);
      $idPic = $this->DB->insert_id;

      foreach($this->C->langs AS $lang){

            array_push($queries, "INSERT into portfolio_pics_i18n (idPic,idLg ) values ( $idPic, '{$lang}' )");
      }
      $this->C->DMLsql_bulk($queries, false);



  }

  // UPDATE Project previw
  public  function _hook_update_portofProjectPrev(){

           $lg = $this->lang;
           $_POST["proTitle_{$lg}"] = trim($_POST["proTitle_{$lg}"]);


           if($_POST["proTitle_{$lg}"])
           {
               if( $_POST["proPic_{$lg}"])
                   $_POST["proPic_{$lg}"] =  '/'.str_replace(baseURL,'',$_POST["proPic_{$lg}"]);

               return true;
           }
           else{
               if(!$_POST["proTitle_{$lg}"]){
                   $this->C->feedback->setFb('error','Project title', "You should insert a name for your project");
                   //echo "You should insert a name for your project";
               }

               return false;
           }

     }
  public  function update_portofProjectPrev(){

       // get data
      $lg       = $this->lang;
      $idPr     = $_POST["BLOCK_id"];
      $proTitle = $_POST["proTitle_{$lg}"];
      $proPic   = $_POST["proPic_{$lg}"];

      $queries = array();

      // update queries

      array_push($queries, "UPDATE portfolio_projects_i18n SET title = '{$proTitle}' WHERE idPr = $idPr AND idLg = '{$lg}' " );

       // pictures for project
      if($proPic)
      array_push($queries, "UPDATE portfolio_pics  SET picUrl = '{$proPic}' WHERE idPr= $idPr AND picPoz = 0 ");

      //echo $queries[0];
      //echo $queries[1];
      $this->C->DMLsql_bulk($queries, false);


  }

  // DELETE Project
  public  function delete_portofProjectPrev(){

      $idPr     = $_POST["BLOCK_id"];
      $query_delete = "DELETE from portfolio_projects WHERE idPr = $idPr ";
      $this->DB->query($query_delete);
  }


 //========================[ categories ]=============================

  /**
   * Not a clean implementation so..
   * for this reason deleting a category shoul be made form GEN_edit
  */
  public  function delete_portofCat(){

       $lg = $this->lang;
       $id = $_POST['BLOCK_id'];

      $queries = array();
      $query = "DELETE FROM ITEMS where id = $id "; array_push($queries,$query);
      $query = "DELETE FROM TREE where Cid = $id "; array_push($queries,$query);

      $this->C->DMLsql_bulk($queries, false);

      $this->C->reset_affected($this->affected_mods, 'reset_currentTree' );

      //echo "<b>delete_portofCat</b> $query";


  }
  /**
   * Not a clean implementation so..
   * for this reason adding a cat shoul be made form GEN_edit
  */
  public  function add_portofCat(){ echo "ACportfolio add_portofCat";}

  public  function update_portofCat(){

      /**
       * USE:
       *
       * catTitle_ro
       * catPic_ro          // replace  baseUrl = 'http://'.$_SERVER['HTTP_HOST'].'/'
       * BLOCK_id = idC
       *
       * UPDATE IN:
       *
       * ITEMS - id , name_lg
       * portfolio_categories idC, urlPic doar daca catPic_ro exista
       *
      */
      $lg       = $this->lang;
      $catTitle = $_POST["catTitle_{$lg}"];
      $catPic   = $_POST["catPic_{$lg}"];
      $id       = $_POST['BLOCK_id'];

      $queries = array();

      if($catPic){
          $catPic = '/'.str_replace(baseURL,'',$catPic);

          $query = "REPLACE into portfolio_categories set urlPic = '{$catPic}' , idCat = $id ";
          array_push($queries,$query);

      }
          $query = "UPDATE ITEMS set name_{$lg} = '{$catTitle}' where id = $id   ";
        array_push($queries,$query);

      //var_dump($queries);
      $this->C->DMLsql_bulk($queries, false);

      $this->C->reset_affected($this->affected_mods, 'reset_currentTree' );



  }
}