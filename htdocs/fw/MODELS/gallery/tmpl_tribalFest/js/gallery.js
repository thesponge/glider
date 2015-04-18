
fmw.ivyGallery = {
    init : function(){

        // alert(window.innerHeight - 138);
           // $('.container-page #mainPage').css('min-height',window.innerHeight - 141 );
        	$('#featured').orbit();

            $('#callKCFinder_gallery').live(
                    'click',
                    function(){
                        //alert('Am apasat callKCFinder');
                        fmw.openKCFinder_popUp('');
                        $('#popUp-close').attr('onclick','window.location.reload()');
                        return false;
                });

           // this.resize();
    },
    resize: function(){
        this.resizeGallery();
        $(window).resize(function() {
         //   alert('resized window');
            fmw.ivyGallery.resizeGallery();
        });
    },
    resizeGallery: function(){
       // alert('resizeGallery');
           var windowHeight = window.innerHeight - 141;

           var featured = $('#featured');
           var minHightImg = featured.find('img').minHeight();

           if(minHightImg > windowHeight) minHightImg = windowHeight;

           featured.css('max-height',minHightImg+'px');
           $('.orbit-wrapper').css('max-height',minHightImg+'px');
           $('.container').css('height',minHightImg+'px');

           $('.container').parent().css('height',minHightImg+'px');

          // alert('minHIght '+minHightImg);
    }
};

$(window).load(function() {

fmw.ivyGallery.init();



});
