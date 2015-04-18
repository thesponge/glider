//var procesSCRIPT_file = 'procesSCRIPT.php';

var parsePOSTfile     = 'GENERAL/GEN_edit/parsePOST.php';    // scriptul dorit pt $.post()
var parsePOSTfile4    = 'PLUGINS/SEO/ADMIN/SEO.php';



function INIadmin_GEN_edit()  {
    $('ol.sortable').nestedSortable({
  			disableNesting: 'no-nest',
  			forcePlaceholderSize: true,
  			handle: 'div',
  			helper:	'clone',
  			items: 'li',
  			maxLevels: 5,
  			opacity: .6,
  			placeholder: 'placeholder',
  			revert: 250,
  			tabSize: 25,
  			tolerance: 'pointer',
  			toleranceElement: '> div',

             update: function(event,ui)
             {

                   but_ol =    $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0})  ;
                   $.post(procesSCRIPT_file,
                       {   parsePOSTfile : parsePOSTfile ,
                           restoreCore: 0,
                           action:'updateTREE' ,
                           but_ol : but_ol
                       } );

                   setFOLDING('false');


             }

  		});

   /***********************************************************[preparare pt editare]**********************************/
       $.post(procesSCRIPT_file,
           {   parsePOSTfile : parsePOSTfile ,
               restoreCore: 0,
               deleteCHANGES:'deleteCHANGES'
           } );


       $('ol.sortable > li > ol li>div').map(function(){ setTOOLS_GE($(this));  });
       $('ol.sortable  li>div').prepend('<span class="li-liniuta"></span>');


       setFOLDING('true');

   //TODO: trimit prin post la sortOL.php -> deleteChanges (pt resetarea lor)
   //TODO: creare folder in RES / changes
}


/*********************************************************************************************************************/

function setFOLDING(fold)     {
   //if the sublist should be by default folded or unfolded
    FOLDstyle = '';UNFOLDstyle =  '';

    if(fold=='true')  FOLDstyle   = 'style=" display: none;"';
    else              UNFOLDstyle = 'style=" display: none;"';
    //_____________________________________________________________________


    $('ol.sortable li>ol li>ol').map(function()
    {

         id = $(this).parent().attr('id');

         FOLDbut ='';
         FOLDbut =  $(this).siblings('div').children('input').attr('class');
         if(!FOLDbut)                                                               //if a FOLDbutton is not set
         {
             $(this).siblings('div')
                    .children('span.li-liniuta')
                    .after('<input type="button" class="unfold" onclick="unfold(\''+id+'\')"  value="+" '+UNFOLDstyle+'>' +
                           '<input type="button" class="fold"   onclick="fold(\''+id+'\')"    value="-" '+FOLDstyle+'>');

           if(fold=='true')    $(this).hide();
         }
    });

    check_statusFolding();
   $('ol.sortable>li>ol>li>ol').map(function(){id = $(this).parent().attr('id'); unfold(id); });


}
/**
 *
 * daca un li este extras si lista nu mai are copii => the folding buttons should be removed
 */
function check_statusFolding(){

    $('ol.sortable li>ol li').map(function()
      {

           id_this = $(this).attr('id');
           id_ch = $(this).find('li:first-child').attr('id');

           if(id_ch==undefined)  removeFolding(id_this);

      });
}
function removeFolding(id)    {

     $('li#'+id+'>div>input').remove();

}
function fold(id)             {

    $('li#'+id+' >div>input.fold').hide();
    $('li#'+id+' >div>input.unfold').show();


    $('li#'+id).children('ol').hide('fast');
}
function unfold(id)           {

    $('li#'+id+' >div>input.fold').show();
    $('li#'+id+' >div>input.unfold').hide();

    $('li#'+id).children('ol').show('fast');
}

function setTOOLS_GE(liDIV)   {

        id = liDIV.parents('li').attr('id');
        liDIV .wrapInner('<span class="li-cont" />')
                .children('.li-cont')
                    .wrapInner('<span class="cont" onclick="click_cont_li(\''+id+'\')" />')
                    .append
                    (
                     '<span class="TOOLS" style="display:none;">' +
                         '<input type="button" class="editITEM" onclick="editITEM(\''+id+'\')"   value="edit">' +
                     '</span>'
                    );


    /**
     *  HTML - start:
     *  <li id='list_[id]'>    Item  ....</li> || <ol>
     *  ___________________________________________________________________________________________________
     *  HTML - RET:
     *
     *  <li id='list_[id]'>
     *      <div>
     *          <span class="li-liniuta"></span>
     *
     *          <span class="li-cont">
     *              <span class='cont'  onclick="click_cont_li('[id]')"> Item </span>
     *              <span class="TOOLS">
     *                  <input type="button" class="deleteITEM" onclick="deleteITEM('[id]')"   value="x" />
     *                  <input type="button" class="editITEM" onclick="editITEM('[id]')"   value="e" />
     *              </span>
     *
     *           </span>
     *      </div>
     *
     */
}

function editITEM(id)         {
    /**
       *  HTML - start:
       *  <li id='list_[id]'>    Item  ....</li> || <ol>
       *  ___________________________________________________________________________________________________
       *  HTML - start:
       *
       *  <li id='list_[id]'>
       *     <div>
       *         <span class="li-liniuta"></span>
       *
       *          <span class="li-cont">
       *                <span class='cont'  onclick="click_cont_li('[id]')"> Item </span>
       *              <span class="TOOLS">
       *                  <input type="button" class="deleteITEM" onclick="deleteITEM('[id]')"   value="x" />
       *                  <input type="button" class="editITEM" onclick="editITEM('[id]')"   value="e" />
       *              </span>
       *            </span>
       *      </div>
       *
       *  ___________________________________________________________________________________________________
       *  HTML - RET:
       *
       *
       *  <li id='list_[id]'>
       *     <div>
       *          <span class="li-liniuta"></span>
       *
       *          <span class="li-cont" style='display:none;'>
       *                <span class='cont'  onclick="click_cont_li('[id]')"> Item </span>
       *              <span class="TOOLS">
       *                  <input type="button" class="deleteITEM" onclick="deleteITEM('[id]')"   value="x" />
       *                  <input type="button" class="editITEM" onclick="editITEM('[id]')"   value="e" />
       *              </span>
       *          </span>
       *
     *            <span class='editMode li-cont'>
                         <input type='text' name='item_name' value='"+content+"'>

                         <span class='TOOLS'>
                                 <input type='button' name='save_item' value='s' />
                                 <input type="button" onclick="ExitEditITEM()"   value="x" />
                         </span>
                   </span>
     *
       *      </div>
       *
       */
    ExitEditITEM();
//$('.hello').clone().appendTo('.goodbye');

    type = $('li#'+id+'>div').attr('class');
    if(type=='no-edit')alert('not Editable!!! - the item doesnt have a model to be associated with');
    else
    {

         content = $('li#'+id+'>div>span>span.cont').text();
        // $('li#'+id+'>div>span[class^=li-cont]').hide();                //ascundem spanul cu continut

    //=================================================================================================================
         selectTYPE_html='';

         selectTYPE = $('span.types').clone();

         if(type)   selectTYPE.find('option.'+type).attr('selected','selected');
         selectTYPE_html = selectTYPE.html();

    //=================================================================================================================


         GET_pageCONF_GEN(id, content,type, selectTYPE_html);


    //====================================== [editMode tools & content]================================================
         /*$('li#'+id+'>div').append
         (
             "<span class='editMode li-cont'>" +

                 "   <input type='text' name='item_name' value='"+content+"'  >" +
                     selectTYPE_html +


                 "<span class='TOOLS' >" +
                 '   <input type="button"  class="save_editITEM" name="save_item" onclick="SaveEditITEM(\''+id+'\')" value="save" />' +
                 '   <input type="button"  class="seo_editITEM" name="seo_item" onclick="SEOEditITEM(\''+id+'\')" value="SEO" />' +
                 '   <input type="button"  class="deleteITEM" onclick="deleteITEM(\''+id+'\')"   value="delete">' +
                 '   <input type="button"  class="exit_editITEM" name="exit_editITEM" onclick="ExitEditITEM()"   value="exit" />' +
                 "</span>" +
             "</span>"
         );*/

    }//else

}

function ExitEditITEM()       {

    $('li >div>span[class^=editMode]').remove()
    $('li >div>span.li-cont').show();

    /*
       ------ GOLIM URMATORUL COD HTML ----------

       <div id='GE_pageCONF'>
            <div id='GE_ctrlDET'>                   #aici vor sta taburi si butoane [save si delete]
                <div id='GE_tabs'></div>
                <div id='GE_buts'></div>
            </div>
            <div id='GE_contDET'></div>            #continutul taburilor

       </div>*/

    $('#GE_tabs, #GE_buts, #GE_contDET').empty();

}
function deleteITEM(id)       {

            alert('itemul cu id-ul a fost deletat '+id);
            type = $('li#'+id+'>div').attr('class');
            if(type=='no-edit')alert('not Editable!!! - the item doesnt have a model to be associated with');
            else
            {

                 ID_onlyChild = $('li#'+id+':only-child').attr('id')
                 if(ID_onlyChild!=undefined) $('li#'+id).parent().remove();
                 else  $('li#'+id).remove();

                 check_statusFolding();
            }


    //_________________________________________________________________________________________________________________

        $.post( procesSCRIPT_file,
            {   parsePOSTfile : parsePOSTfile ,
                restoreCore: 0,
                action : 'deleteITEM',
                id : id
            } );

    //______________________________________[necesar sa se updateze tree-urile  la delete ]____________________________
        but_ol =    $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0})  ;
        $.post(procesSCRIPT_file,
            {   parsePOSTfile : parsePOSTfile ,
                restoreCore: 0,
                action:'updateTREE' ,
                but_ol : but_ol
            });


}

function SEOEditITEM(id)      {
    LG = $('input[name=lang]').val();
    $('span.editMode span.TOOLS input.seo_editITEM').remove();
    $('span.editMode')
        .append("<div class='itemSEO'>")
            .find(".itemSEO")
                .load( procesSCRIPT_file, {parsePOSTfile:parsePOSTfile4, id:id, LG :LG});


}
function Save_SEOEdit(id)     {

    //trebuie testata vizibilitatea butonului de seo, existeanta lui.
    mes = '';
    SEObut = $('span.editMode span.TOOLS input.seo_editITEM');
    if(!SEObut.attr('name'))   //daca nu mai exista butonul de SEO inseamna ca s-a activat SEO
    {
         //alert(SEObut.attr('name') );

         title_tag        = $('#GE_pageCONF  input[name=title_tag]').val();
         title_meta       = $('#GE_pageCONF  input[name=title_meta]').val();
         description_meta = $('#GE_pageCONF  input[name=description_meta]').val();
         keywords_meta    = $('#GE_pageCONF  input[name=keywords_meta]').val();


         $.post( procesSCRIPT_file,
                   {
                       parsePOSTfile : parsePOSTfile ,
                       restoreCore: 0,
                       action : 'seoITEM',
                       id : id,
                       title_tag       : title_tag,
                       title_meta      : title_meta      ,
                       description_meta: description_meta,
                       keywords_meta   : keywords_meta
                   });



         mes=
         "\n" +
         "\n id= "+id+
         "\n title_tag       " + title_tag       +
         "\n title_meta      " + title_meta      +
         "\n description_meta" + description_meta+
         "\n keywords_meta   " + keywords_meta ;


    }
    return mes;
}

function SaveEditITEM(id)     {

         var val      = $('#GE_pageCONF  input[name=item_name]').val();
         var newType = $('#GE_pageCONF  input[name=typeNew]').val();
         var itemTYPE =newType ? newType :  $('#GE_pageCONF  select[name=type]').val();

          $('li#'+id+' >div>span.li-cont > span.cont').text(val);
          if(itemTYPE)
              $('li#'+id+' >div').attr('class',itemTYPE);


          mesSEO = Save_SEOEdit(id);
          ExitEditITEM();


    //__________________________________________________________________________________________________________________

    alert('itemul '+id+' are val '+val + mesSEO);
    $.post( procesSCRIPT_file,
           {
               parsePOSTfile : parsePOSTfile ,
               restoreCore: 0,
               action : 'updateITEM',
               id : id,
               val : val,
               type:itemTYPE
           });

    //______________________________________[necesar sa se updateze tree-urile  la editare ]____________________________
    but_ol =    $('ol.sortable').nestedSortable('toHierarchy', {startDepthCount: 0})  ;
    $.post(procesSCRIPT_file,
           {
               parsePOSTfile : parsePOSTfile ,
               restoreCore: 0,
               action:'updateTREE' ,
               but_ol : but_ol
           });




}
function addNewITEM()         {

           lastID   = $('input[name=lastID]').val();     $('input[name=lastID]').val(parseInt(lastID)+1);
           itemName = $('input[name=itemName]').val();
           itemTYPE = $('.block_addNew select').val();

     //=================================================================================================================
                     if(itemTYPE!='none') itemClassTYPE = " class='"+itemTYPE+"' ";
                     else itemClassTYPE ='';


                     $('li#list_MenuFREE > ol#children_new').append
                     (
                         "<li id='list_"+lastID+"'>" +
                             "<div"+itemClassTYPE+">"+itemName +"</div>"+
                         "</li>"
                     );
                    //  $('li#list_MenuFREE').show();
                      newITEM =  $('li#list_'+lastID+' >div');

                      setTOOLS_GE( newITEM);
                      newITEM.prepend('<span class="li-liniuta"></span>');


    //================================= [ trimit action = add new ITEM ] ===============================================

    $.post( procesSCRIPT_file,
        { parsePOSTfile : parsePOSTfile ,
          restoreCore: 0,
          action : 'addNewITEM',
          id : lastID,
          val : itemName, type:itemTYPE
        } );


}

/*========================================================================================*/

function GET_pageCONF_GEN(id, content,type, selectTYPE_html)      {

    /*
       ------ POPULAM URMATORUL COD HTML ----------

       <div id='GE_pageCONF'>
            <div id='GE_ctrlDET'>                   #aici vor sta taburi si butoane [save si delete]
                <div id='GE_tabs'></div>
                <div id='GE_buts'></div>
            </div>
            <div id='GE_contDET'></div>            #continutul taburilor

       </div>
    */

    //este important sa pastram acelasi format al formurilor create pentru ca codul sa poata fii procesat identic



    $("#GE_tabs").append("<span>General</span>");
    $("#GE_buts").append(
        "<span class='TOOLS' >" +
                 '   <input type="button"  class="save_editITEM" name="save_item" onclick="SaveEditITEM(\''+id+'\')" value="save" />' +
                 '   <input type="button"  class="deleteITEM" onclick="deleteITEM(\''+id+'\')"   value="delete">' +
        "</span>"

    );

    $('#GE_contDET')
        .append(
             "   <input type='text' name='item_name' value='"+content+"'  >" +
              selectTYPE_html+
             "   <input type='text' name='typeNew' value='' placeholder='new Type'  >" +
             "<div class='itemSEO'></div>"
        )
         .find(".itemSEO")
         .load( procesSCRIPT_file, {parsePOSTfile:parsePOSTfile4, id:id, LG :LG});


}

/*CE se intampla cand se da click pe un element din lista
*  - safety - se face ExitEditITEM() - in cazul in care un alt element a fost editat
*  - se creaza noile stileuri
*  - se arata tool-urile
*
*/
  function click_cont_li(id)  {

      ExitEditITEM();
      $('span.li-cont-blue').removeClass('li-cont-blue');
      $('li > div > span > span.TOOLS').hide();

      $('li#'+id+'>div>span.li-cont').addClass('li-cont-blue');
      $('li#'+id+'>div>span>span.TOOLS').show();

    /**
     *  HTML - REQ:
     *
     *  <li id='list_[id]'>
     *      <div>
     *          <span class="li-liniuta"></span>
     *
     *          <span class="li-cont">
     *              <span class='cont'  onclick="click_cont_li('[id]')"> Item </span>
     *              <span class="TOOLS">
     *                  <input type="button" class="deleteITEM" onclick="deleteITEM('[id]')"   value="x" />
     *                  <input type="button" class="editITEM" onclick="editITEM('[id]')"   value="e" />
     *              </span>
     *
     *           </span>
     *      </div>
     *
     */
  }



// this function disables text select on a perticular dom object
function disableSelection(target) {

    if (typeof target.onselectstart!="undefined") //IE route
        target.onselectstart=function(){return false}
    else if (typeof target.style.MozUserSelect!="undefined") //Firefox route
        target.style.MozUserSelect="none"
    else //All other route (ie: Opera)
        target.onmousedown=function(){return false}
    target.style.cursor = "default"
}