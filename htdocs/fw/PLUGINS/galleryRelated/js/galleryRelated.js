$(document).ready(function()      {

    ivyMods.portfolio ={

      select : {
          mainPic: '.project-details img.mainPic',
          mainPicTitle: '.project-details .mainPicTitle',
          carouselImgs : '.pro-myCarousel ul li img',
          fancyBox: "a[rel=alterPics_group]"
      },
      init:  function(){

        /**
         * WORKING models
         * <div class='pro-myCarousel' >
         *       <ul>
         *          <li >
                        <img src='{~ao->picUrl}' class='alterPic' id='alterPic_{~ao->idPic}' alt='{~ao->picTitle}' width='100' height='100'>
                     </li>
            .....

         <div class='SING project-details'  id='project-details_0_{$o->LG}'>
               <div class='grid pro-mainPic'>
                    ".(isset($o->project->picUrl)
                         ? "<img class='EDpic mainPic' src='{$o->project->picUrl}'>
                            <p class='EDtxt mainPicTitle'>{$o->project->picTitle}</p>
                            "
                         : "<img class='EDpic mainPic' src='http://placehold.it/600x400'>
                            <p class='EDtxt mainPicTitle'></p>"
                      )."

                    ".($this->admin
                        ? "<input type='hidden' name='mainPicId' value='{$o->project->idPic}'>"
                        : ""
                    )."
                </div>

           ..........
         * */

        // starting carousel
        ivyMods.portfolio.startCarousel();

        // binding main image with alter pics

        var mainPic      =  $(this.select.mainPic);
        var mainPicTitle =  $(this.select.mainPicTitle);

        $(this.select.carouselImgs).on('mouseover',function(){

            var imgUrl   = $(this).attr('src');
            var imgTitle =  $(this).attr('alt');

            mainPic.attr('src',imgUrl);
            mainPic.parent('a')
                .attr('href',imgUrl)
                .attr('title', imgTitle);
            mainPicTitle.text(imgTitle);

        });



         ivyMods.portfolio.jqFancyBox();

      },

      startCarousel : function(){
           $(".pro-myCarousel").jCarouselLite({
                     btnNext: ".next",
                     btnPrev: ".prev"
                     , visible: 7
                     , mouseWheel: true
                 });
      },
      jqFancyBox: function(){

          $(this.select.fancyBox).fancybox({
				'transitionIn'		: 'none',
				'transitionOut'		: 'none',
				'titlePosition' 	: 'over',
				'titleFormat'		: function(title, currentArray, currentIndex, currentOpts) {
					return '<span id="fancybox-title-over">Image ' + (currentIndex + 1) + ' / ' + currentArray.length + (title.length ? ' &nbsp; ' + title : '') + '</span>';
				}
			});
      }


 };

  ivyMods.portfolio.init();


});
