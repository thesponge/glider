function comm_addPic(url){

    var thumbPic = $('form[class^=addComments] ');
    thumbPic.find('*[class^=thumbPic] img')
                .attr('src',url);
    thumbPic.find('input[type=hidden][name=picUrl]')
                .attr('value',url);

}


function openKCFinder_comm_popUp(){
    window.KCFinder = {
           callBack: function(url) {
            //field.value = url;
            alert(url);
            comm_addPic(url);  // carrousel callback function
            popUp_remove();
            window.KCFinder = null;
        }
    };

    var popUpKCF = new popUp_call(
                { content:
                    "<div id='kcfinder_div'>" +
                        '<iframe name="kcfinder_iframe" src="/assets/kcfinder/browse.php?type=images" ' +
                        //'<iframe name="kcfinder_iframe" src="/fw/GENERAL/core/js/kcfinder/browse.php?type=images" ' +
                                'frameborder="0" width="100%" height="100%" marginwidth="0" marginheight="0" scrolling="no" />'+
                    "</div>",

                    widthPop:'900',
                    heightPop : '450'
                });

    // variabila popUPKCF - poate ar trebui sa ii dau unset somehow

}

$(document).ready(function(){

    $('button#callKCFinder_comm').live('click',function(){
        //alert('Am apasat callKCFinder');
        openKCFinder_comm_popUp();
        return false;
    });

});