<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <?php
    echo defined("FAV_ICON")
               ? '<link rel="icon" type="image/png" href="' . FAV_ICON . '">'
               : '<link rel="icon" type="image/png" href="' . TMPL_URL . 'favicon.ico">';
    ?>

    <script type="text/javascript"  src="http://cdnjs.cloudflare.com/ajax/libs/headjs/0.99/head.min.js"></script>
    <!--<script type="text/javascript"  src="/timisoara/assets/jquery/jquery-1.8.3.min.js"></script>-->

    <?php
        if(isset($core->admin) && $core->admin) {
            $core->cssIncPaths[] = PUBLIC_URL . "assets/jquery-ui-1.10.3.custom/css/ui-lightness/jquery-ui-1.10.3.custom.min.css";
        }
        /*echo
           (!isset($core->admin) ? '' :
            ' <link rel="stylesheet" href="/timisoara/assets/jquery-ui-1.8.19.custom/development-bundle/themes/base/jquery.ui.all.css">'
           ).
          $core->cssInc;*/

        $header_TMPL = TMPL_INC . 'header.php';
        if(is_file($header_TMPL)) {
            require_once($header_TMPL);
        }
    ?>
    <script type="text/javascript">
        head.load( "<?php echo implode('", "', $core->cssIncPaths);  ?> " );
    </script>

</head>
<body>
