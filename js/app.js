// Foundation JavaScript
// Documentation can be found at: http://foundation.zurb.com/docs
$(document).foundation();

// smooth div animations
$(function() {
  $('a[href*=#]:not([href=#])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
      var target = $(this.hash);
      target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
      if (target.length) {
        $('html,body').animate({
          scrollTop: target.offset().top
        }, 1000);
        return false;
      }
    }
  });
});


// responsive grid stuff
// $(function() {

// 	$( '#ri-grid' ).gridrotator( {
// 		animSpeed : 300,
// 		animType : 'rotate3d',
// 		rows:3,
// 		columns:3,
// 		w320 : {
// 			rows : 3,
// 			columns : 3
// 		},
// 		w240 : {
// 			rows : 3,
// 			columns : 3
// 		},
// 		onhover : true,
// 		preventClick: false
// 	} );

// });



//resize all the windows!

// $(document).ready(function(){
//   var height = $('.height').height();
//   function resize(){$('.brick').height(height);} 
//   resize();

//   $(window).resize(function() { resize(); });
// });