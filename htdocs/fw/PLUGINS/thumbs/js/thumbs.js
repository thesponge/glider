var procesSCRIPT_file        = 'procesSCRIPT.php';                   // intermediaza requesturile scripurilor .js
var parsePOSTfile_thumbs     = 'PLUGINS/thumbs/thumbsProcDB.php';    // scriptul dorit pt $.post()

var thumbsVoted = new Array();
function bindThumbs(){

    $('.thumbs i[class^=icon-thumbs]').bind('mouseover', function(){

        $(this).addClass('icon-blue');
    });
    $('.thumbs i[class^=icon-thumbs]').bind('mouseout', function(){

            $(this).removeClass('icon-blue');
    });


    $('.thumbs i[class^=icon-thumbs]').bind('click', function(){


        // ar putea fii Up sau Down
        var dataThumbs  = {  parsePOSTfile :parsePOSTfile_thumbs   };
        var thumbs      = $(this).parents('*[class^=thumbs]');
        var uTitle_arr  =  thumbs.attr('id').split('_');

        dataThumbs['DB_table_prefix']   =  uTitle_arr[0];
        dataThumbs['DB_extKey_name']     =  uTitle_arr[1];
        dataThumbs['DB_extKey_value']    =  uTitle_arr[2];

    //=================================================================================================================


        if(typeof thumbsVoted[dataThumbs['DB_extKey_value']] == 'undefined'){



            //==================== Updatarea Scorului pe thumbul care s-a afisat========================================
                var thumbScore_Span = $(this).siblings('*[class^=thumbs]');
                var thumbScore      = parseInt(thumbScore_Span.text()) + 1;

                thumbScore_Span.text(thumbScore);

            //==========================================================================================================


                 dataThumbs['thumbsUp']   =  thumbs.find('.thumbsUp').text();
                 dataThumbs['thumbsDown'] =  thumbs.find('.thumbsDown').text();


             //=========================================================================================================
            $.post(procesSCRIPT_file,dataThumbs, function(data){
                        thumbs.append("<br>"+data);
                    });

            thumbsVoted[dataThumbs['DB_extKey_value']] = true;


        }


        else{
            alert('you have already voted this feedback');
        }


    });
}

$(document).ready(function()      {

  bindThumbs();

});