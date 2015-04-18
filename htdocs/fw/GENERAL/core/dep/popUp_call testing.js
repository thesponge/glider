function popUp_call(opt){



       function jsonConcat(json1, json2) {
           for (var key in json2) {
            json1[key] = json2[key];
           }

        return json1;
       }

       this.init = function(){



           // properties
           this.headerName   = opt.headerName;
           this.widthPop     = opt.widthPop;
           this.heightPop    = opt.heightPop;
           this.completeFunc = opt.completeFunc;


           this.content      = opt.content;



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

       this.popUp_load        = function(){

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
       this.popUp_content     = function(){
              $.when($('#popUp #popUp-content').html(this.content))
                        .then(this.popUp_callback());

              // this.popUp_callback();
               //aceaasta procedura ar trebui pusa si ea intr-o metoda

               // callback function?
       }
       this.popUp_set_htmlTml = function(){
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
       this.popUp_remove      = function(){
            //alert('in Remove popUp');
           //alert('Am reusit sa selectez '+$('body #popUP-canvas').attr('id'));
           $('body #popUp-canvas').remove();
       }
       this.popUp_callback    = function(){
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


       this.init();

  }