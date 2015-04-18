var procesSCRIPT_file        = 'procesSCRIPT.php';                   // intermediaza requesturile scripurilor .js
var parsePOSTfile_rating     = 'PLUGINS/rating/ratingProcess.php';    // scriptul dorit pt $.post()

var starNo='';
var uStars=0;     //numarul de stele deja acordate de user;

function bindStars(){


    uStars = $('*[id^=uRating] i[class$=icon-star]').length;
    //alert('uStars '+uStars);


    $('*[id^=uRating] i').live('mouseover', function(){

         starNo = parseInt( $(this).attr('title').replace('star','') );

        var uRating = $(this).parent();

        uRating.children('i').attr('class','icon-star-empty icon-blue');
        uRating.children('i:lt('+starNo+')').attr('class','icon-star icon-blue');

    });


    $('*[id^=uRating] i').live('mouseout', function(){

        var uRating = $(this).parent();

        uRating.children('i').attr('class','icon-star-empty icon-blue');
        uRating.children('i:lt('+uStars+')').attr('class','icon-star icon-blue');
    });



    /**
     * Ce anume trebuie trimis in post?
     *
     * - starsNo
     * - uStars     # votul userului in prealabil
     *
     *  span#votes
     *      -votes
     *
     *   span#totalRating
     *      - totalRating
     *
     *   span
     *      id:
     *       - uid
     *
     *
     *       title:
     *       - postFix_table
     *       - extKey_name
     *       - extKey_value
     *
     */

    $('*[id^=uRating] i').live('click', function(){


        var dataRating = { starNo: starNo,
                           uStars: uStars,
                           parsePOSTfile :parsePOSTfile_rating
                        };


        var uRating        =  $(this).parent();
        var uRating_parent =  uRating.parents('#ratings');

        var Rating         = uRating_parent.children('#Rating');

        //===============================================================
        dataRating['totalRating']  = Rating.find('#totalRating').text();

        dataRating['nrRates']      = Rating.find('#nrRates').text();



        dataRating['uid']          = uRating.attr('id').split('_')[1];

        //===============================================================

        var uTitle_arr   =  uRating.attr('title').split('_');

        dataRating['postFix_table']   =  uTitle_arr[0];
        dataRating['extKey_name']     =  uTitle_arr[1];
        dataRating['extKey_value']    =  uTitle_arr[2];

    /*    dataRatingStr='';
       for(var key in dataRating)
           dataRatingStr+=key+' '+dataRating[key]+"\n";

        alert(dataRatingStr);*/

        uRating_parent.parent('#ratingRecord').
                            load(procesSCRIPT_file,
                                 dataRating,
                                  function(){

                                //alert(uStars);

                                if(uStars==0) alert('thanks for voting');
                                else alert('your vote has been updated');
                                bindStars();

                            });



    });
}
$(document).ready(function()      {


  bindStars();



});
