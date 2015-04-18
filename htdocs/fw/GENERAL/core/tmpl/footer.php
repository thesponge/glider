<?php
     $add_jsIncPaths[] = PUBLIC_URL . "assets/jquery/jquery-1.8.3.min.js";
     $add_jsIncPaths[] = PUBLIC_URL . "assets/onVisible/onvisible.js";
     $add_jsIncPaths[] = PUBLIC_URL . "assets/jquery-cookie-master/jquery.cookie.js";
     if(isset($core->admin) && $core->admin) {

         $add_jsIncPaths[] =  PUBLIC_URL . "assets/jquery-ui-1.10.3.custom/js/jquery-ui-1.10.3.custom.min.js" ;
         $add_jsIncPaths[] =  PUBLIC_URL . "assets/nestedSortable/jquery.ui.nestedSortable.js";
         $add_jsIncPaths[] =  PUBLIC_URL . "assets/ckeditor-4.2/ckeditor.js";
     }
    //var_dump($core->jsIncPaths);
    // deoarecele add.jsIncPaths trebuie sa fie la inceput
    $core->jsIncPaths = array_merge($add_jsIncPaths, $core->jsIncPaths);

?>
<script type="text/javascript">

   head.js(
   "<?php echo implode('", "', $core->jsIncPaths);  ?> "
   , function(){
       <?php echo $core->jsTalk; ?>
   });

   head.ready( "GEN.js",
   function(){
       ivyMods = {    set_iEdit:{ /*modName : function(){}*/       } };
       <?php echo  (!$core->admin ? ''
             : "fmw.admin  = 1;").
               "fmw.idC    = ".($core->idNode ?: '""' ).";
                fmw.idT    = ".($core->idTree ?: '""').";
                fmw.pubPath = '".FW_PUB_PATH."';
               ";
       ?>
   });

</script>
<!-- ======================================================================= -->

<?php
  //echo $core->jsInc;
  $footer_TMPL = TMPL_INC.'footer.php';
  if (is_file($footer_TMPL)) {
      include_once $footer_TMPL;
  }
?>

</body>
</html>
