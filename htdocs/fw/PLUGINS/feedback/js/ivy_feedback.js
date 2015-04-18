ivyMods.feedback_init = {

	fbReadMore: function(jqMsg){
		var fb_mess = jqMsg.prev();
	   var overflowY = fb_mess.css('overflow-y');

	   // alert(overflowY);
	   if(overflowY == 'hidden') {
		   jqMsg.text('show less');
	        fb_mess
	            .css('overflow-y','visible')
	            .css('height', '100%');
	   }
	   else{
		   jqMsg.text('show more');
	       fb_mess
	           .css('overflow-y','hidden')
	           .css('height', '50px');

	   }

	},
	toggleFb: function(){
		var fbMess = $('.ivy-feedback-mess');
      if(fbMess.is(':visible')){
          fbMess.prev().attr('value','show feedback');
          fbMess.slideUp();
          //alert('is visible');
      }
      else {
          fbMess.prev().attr('value','hide feedback');
          fbMess.slideDown();

          //alert('pare sa fie invisible');
      }
	},
	init: function(){

		$('.toggleFb').on('click', function(){
			ivyMods.feedback_init.toggleFb();
		});
		$('.fb_readMore').on('click', function(){
			ivyMods.feedback_init.fbReadMore($(this));
		});

		setTimeout(function(){
			ivyMods.feedback_init.toggleFb();
		},1000);
	}
};



$(document).ready(function(){

    ivyMods.feedback_init.init();

});