

    idT = $('input[name=current_idT]').val();
    idC = $('input[name=current_idC]').val();

    $('a#lang_en').attr('href','./index.php?lang=en&idT='+idT+'&idC='+idC);
    $('a#lang_ro').attr('href','./index.php?lang=ro&idT='+idT+'&idC='+idC);
