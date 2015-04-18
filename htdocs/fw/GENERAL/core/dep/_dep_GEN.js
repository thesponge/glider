
//PLEASE ENCAPSULATE
function hideShow(){


    $('button[class^=showHidden]').on(

        'click',
        function(){
            $(this).siblings('*[class^=hidden]')
                    .toggle()
                        .css({visibility: "visible", display: "block"});;
            $(this).hide();
            $(this).siblings('*[class^=hiddeHidden]').show();

            return false;
        }
    );

    $('button[class^=hiddeHidden]').on(

        'click',
        function(){
            $(this).siblings('*[class^=hidden]')
                        .toggle()
                            .css({visibility: "hidden", display: ""});;
            $(this).hide();
            $(this).siblings('*[class^=showHidden]').show();

            return false;
        }
    );
}




/*_____________________[labels]________________________________________*/

/**
 * un element care contine taguri este de forma
 * <* class="[EDtags] labels"> label1, label2 , labe3...etc </*>
 *
 * LOGISTICA:
 *  - functia creaza array-ul cu taguri/ labeluri
 *  - daca elemntul cu taguri este editabil atunci la noile splited-labels se va adauga clasa noATmpl
 *          - clasa noATmpl - nu se va afisa acest element in cadrul from#EDITform
 *
 *  - dupa labels => after(  .splited-labels  )
 */
//******************ENCAPSULATE
function convertLabels(){

    //
    $(".labels").map(function(){

        var text_arr = $(this).text().split(', ');

        if(text_arr.length > 0 && text_arr[0]!=' ')
        {

            var noATmpl = $(this).hasClass('EDtags') ? 'noATmpl' : '';

            var splited_labels = '';
            for(var i in text_arr)
                splited_labels +="<span class='label ptb0 r5 label-inverse'>" +
                                    "<small>" +
                                        text_arr[i]+
                                     "</small>"+
                                  "</span>";

            $(this).after
                ("<span class='pull-left splited-labels "+noATmpl+"'>" + splited_labels +"</span> ")
                .hide() ;

        }
    });

}

//todo: extend for multimensional JSON
function jsonConcat(json1, json2) {
    for (var key in json2) {
     json1[key] = json2[key];
    }

 return json1;
}


/*___________________________________________[popUp]________________________________________*/
// si aici e de lucru...
/**
 * WORK popUP - tmpl
 * <div id='popUp-canvas'>
      <div id='popUp'>
         <div id='popUp-header'>
              <span>
                   [ header ]
               </span>
              <button id='popUp-close' onclick='popUp_remove();' class='close'>&times;</button>

         </div>
         <div id='popUp-content'>
                [ loading - container]
         </div>
      </div>
   </div>
 */
function popUp_remove(){
    //alert('in Remove popUp');
    //alert('Am reusit sa selectez '+$('body #popUP-canvas').attr('id'));
    $('body #popUp-canvas').remove();
}
/**
 * SET popUp- HTML template + centralizare in window (1)
 *
 *
 * @param header -  (title) pentru popup
 * @param width  -  optional pt #popUp
 * @param height
 * @return {*}   - returneaza pointer la selectia #popUp-content
 */
function popUp_set_htmlTml(){

    var popUp =
    $('body').prepend
             ("<div id='popUp-canvas'>" +
                 "<div id='popUp'>" +
                    "<div id='popUp-header'>" +
                        "<span>"+this.headerName+"</span>" +
                         "<button id='popUp-close' onclick='popUp_remove();' class='close'>&times;</button>" +

                    "</div>" +
                    "<div id='popUp-content'></div>" +
                 "</div>" +
              "</div>")
             .find('#popUp');
    var popUpContent = popUp.find('#popUp-content');
  //________________________________________________________________________________________________



    if(this.widthPop)
        popUp.css('width',this.widthPop+'px');
    if(this.heightPop)
        popUp.css('height',this.heightPop+'px');

    // 1
    var popupContent_height = popUpContent.height() /2;
    var topPopup = ($(window).height() - popUp.height())/2 -50;
    var margin_left =  popUp.width()/2;

    popUp.css('top',topPopup+'px');
    popUp.css('margin-left','-'+margin_left+'px');




  //________________________________________________________________________________________________

    //alert(topPopup);
    popUpContent.append(
        "<img alt='preloader' src='fw/GENERAL/core/css/img/ajax-loader.gif' " +
               "style='display: block; margin: 0px auto; padding-top:"+popupContent_height+"px;'>");


    popUp.draggable();


    //return popUpContent;


}
/**
 * SET popUp - call setTMPL + .load() settup
 *
 * @param pathLoad      - scriptul chemat de load via  procesSCRIPT_file = procesSCRIPT.php
 * @param dataSend      - JSON - date trimise la script + parsePOSTfile = pathLoad (pt procesSCRIPT.php)
 * @param completeFunc  - numele functiei apelate dupa ce s-a efectuat loadul
 *
 * Parametrii utilizati pentru crearea templateului - vezi popUP_set_htmlTmpl
 *      @param header
 *      @param width
 *      @param height
 */
function popUp_ini(pathLoad, dataSend, completeFunc, header, width, height){


    //______________________________________[ set html TMPL]_________________________________________________

    popUpContent = popUp_set_htmlTml( header, width, height);

    //______________________________________[ set dataSend ]_________________________________________________
    //alert('In popUp_ini');
    // alert(typeof  dataSend);
    if(dataSend instanceof object) {
        //alert('Este object');
        dataSend = jsonConcat(dataSend,{parsePOSTfile : pathLoad});
    }
    else {
        //alert('nu este object');
        dataSend = {parsePOSTfile : pathLoad};
    }

    //_____________________________________[ set load ]__________________________________________________

    setTimeout(function(){
        popUpContent
            .load(
                procesSCRIPT_file,
                dataSend,
                function(){

                    if(completeFunc.length > 0)
                    {
                        if (typeof completeFunc == 'string' &&
                            eval('typeof ' + completeFunc) == 'function')
                        {
                            //alert('Considera ca s-a gasit o functie');
                            eval(completeFunc+'()');

                        }

                        else
                            alert('there is no function named '+completeFunc);
                    }
                    else  alert('there was no function name sent');
                }

             );
    },250);

}

function popUp_load(){

  //_____________________________________[ set load ]__________________________________________________
  /*  alert('procesSCRIPT '+this.procesSCRIPT
        + '\n\n dataSend '+this.dataSend
        + '\n\n pathLoad '+this.pathLoad
        + '\n\n completeFunc '+this.completeFunc + ' length'+this.completeFunc.length
        + '\n\n type '+(typeof this.completeFunc));*/

    var mod      = this;
   // pentru ca this is not in the scope inside setTimeout function

    setTimeout(function(){

        $('#popUp #popUp-content')
              .load(
                  mod.procesSCRIPT,
                  mod.dataSend,
                  function(){
                        mod.popUp_callback();
                  }

               );
    },250);
   // alert( this.completeFunc);
}


function popUp_content(){

    $.when($('#popUp #popUp-content').html(this.content))
        .then(this.popUp_callback());

   // this.popUp_callback();
    //aceaasta procedura ar trebui pusa si ea intr-o metoda

    // callback function?
}
function popUp_callback(){
   // alert('this is the callBack '+ this.completeFunc);
    if(typeof this.completeFunc !='undefined' && this.completeFunc.length > 0)
          {
              if (typeof this.completeFunc == 'string' &&
                  eval('typeof ' + this.completeFunc) == 'function')
              {
                  //alert('Considera ca s-a gasit o functie');
                  eval(this.completeFunc+'()');

              }

              else
                  alert('there is no function named '+this.completeFunc);
          }

}
function popUp_call(opt){

    /*MAN's
    *
    * opt.pathLoad
    * opt.dataSend
    * opt.procesSCRIPT
    *
    * */

    // properties

    this.headerName   = opt.headerName;
    this.widthPop     = opt.widthPop;
    this.heightPop    = opt.heightPop;
    this.completeFunc = opt.completeFunc;


    this.content      = opt.content;


    //methods
    this.popUp_load        = popUp_load;
    this.popUp_content     = popUp_content;
    this.popUp_set_htmlTml = popUp_set_htmlTml;
    this.popUp_remove      = popUp_remove;
    this.popUp_callback    = popUp_callback;
    //this.popUp_loadContent ;//???

    //______________________________________[ set html TMPL]_________________________________________________

    this.popUp_set_htmlTml();
    //alert(this.popUpContent);

    // ini stuf
    if(typeof this.content !='undefined')
    {
        this.popUp_content();
        //alert(this.content);
    }
    else
    {
       /**
        * Daca scriptul meu de process este acelasi cu cel default atunci facem aranjamentele necesare*/
        this.procesSCRIPT = (typeof opt.procesSCRIPT != 'undefined' || typeof opt.procesSCRIPT == 'null' )
                            ? opt.procesSCRIPT
                            : procesSCRIPT_file;

        this.dataSend     = opt.dataSend instanceof object
                            ? opt.dataSend
                            : {};

        this.dataSend     = (this.procesSCRIPT == procesSCRIPT_file && typeof opt.pathLoad != 'undefined')
                            ? jsonConcat(this.dataSend,{parsePOSTfile : opt.pathLoad})
                            : this.dataSend;


       this.popUp_load();
    }





}


/*=====================[ KCFinder ]=================================*/
function openKCFinder() {
    window.KCFinder = {
        callBack: function(url) {
            //field.value = url;
            alert(url);
            window.KCFinder = null;
        }
    };
    window.open('/fw/GENERAL/core/js/kcfinder/browse.php?type=images', 'kcfinder_textbox',
        'status=0, toolbar=0, location=0, menubar=0, directories=0, ' +
        'resizable=1, scrollbars=0, width=800, height=600'
    );
}

function openKCFinder_IFR() {
    alert('in _IFR');
    var div = document.getElementById('kcfinder_div');
    if (div.style.display == "block") {
        div.style.display = 'none';
        div.innerHTML = '';
        return;
    }
    window.KCFinder = {
        callBack: function(url) {
            window.KCFinder = null;
            //field.value = url;
            div.style.display = 'none';
            div.innerHTML = '';
        }
    };
    div.innerHTML = '<iframe name="kcfinder_iframe" src="/fw/GENERAL/core/js/kcfinder/browse.php?type=images" ' +
        'frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />';
    div.style.display = 'block';

}


/*_____________________[SETING CURRENT BUTTONs]________________________________________*/

// not sure if this is needed anymore
    current_idT = $('input[name=current_idT]').attr('value');
    current_idC = $('input[name=current_idC]').attr('value');
    $('ul.MENUhorizontal1>li a[id$='+current_idT+']').addClass('current');
    $('div#children_display > ul > li > a#'+current_idC).addClass('current');
/*_____________________________________________________________________________________*/


$(document).ready(function(){

    hideShow();
    convertLabels();


});