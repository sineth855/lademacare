<script>

$(document).ready(function() {

  $(".slider ul").owlCarousel({

	  'autoPlay':true,

	  'pagination':true,

	  'itemsDesktop':[1199,1],

	  'itemsDesktopSmall':[979,1],

	  'itemsTablet':[768,1],

	  'items':1

	  });


  $(".new ul").owlCarousel({

	  'autoPlay':true,

	  'pagination':true,

	  'itemsDesktop':[1199,4],

	  'itemsDesktopSmall':[979,4],

	  'itemsTablet':[768,2],

	  'itemsMobile':[479,2],

	  'items':4

	  });



var owll = $(".slider ul").data('owlCarousel');

$(".slider .next").click(function(){

	owll.next();

	});

$(".slider .prev").click(function(){

	owll.prev();

	});

var owlll = $(".new ul").data('owlCarousel');

$(".new .next").click(function(){

	owlll.next();

	});

    $(".new .prev").click(function(){
	    owlll.prev();
	});

    $(window).scroll(function(){
	   if($(document).scrollTop() >= 100) {
		$('.header').removeClass("header1");
	  }else{
		$('.header').addClass("header1");
	}
});





});

</script>