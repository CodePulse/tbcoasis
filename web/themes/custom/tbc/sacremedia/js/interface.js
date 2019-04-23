( function($) {
  'use strict';

	/*-------------------------------------------------------------------------------
		  Sticky-Header
		-------------------------------------------------------------------------------*/
	$(window).scroll(function(){
	  var sticky = $('#header'),
	      scroll = $(window).scrollTop();

	  if (scroll >= 100) sticky.addClass('sticky');
	  else sticky.removeClass('sticky');
	});

	/* -------------------------------------------------------------
	   gallery isotope
	------------------------------------------------------------- */
    // init Isotope after all images have loaded code
    var isotopeContainer = $('.gallery-mesonary');
    isotopeContainer.isotope({
      itemSelector: '.gallery-item',
      masonry: {
        columnWidth: isotopeContainer.width() / 4,
      }
    });

    /* -------------------------------------------------------------
	   gallery isotope
	------------------------------------------------------------- */
	$('.gallery-isotope').imagesLoaded( function() {
	    // init Isotope after all images have loaded code
	    var isotopeContainer = $('.gallery-mesonary-nonprofit');
	    isotopeContainer.isotope({
	      itemSelector: '.gallery-item',
	      masonry: {
	        columnWidth: isotopeContainer.width() / 3,
	      }
	    });
    });

	/////////// sa-gallery-mesonary ////////////
	$('.sa-gallery-mesonary').imagesLoaded( function() {
		var isotopeContainer = $('.sa-gallery-mesonary');
		isotopeContainer.isotope({
		  itemSelector: '.sa-gallery-item',
		  masonry: {
		    columnWidth: isotopeContainer.width() / 5,
		  }
		});
	});


	/*-------------------------------------------------------------------------------
		  Video
		-------------------------------------------------------------------------------*/
	$('.popup-youtube').magnificPopup({
		type: 'iframe'
	});
	$('.popup-vimeo').magnificPopup({
		type: 'iframe'
	});
	$('.popup-video').magnificPopup({
		type: 'iframe'
	});
		  
	/*------------------------------------------------------------------
		Intro-Slider
	-------------------------------------------------------------------*/
	$('.sa-main-banner').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:true,
	    items:1,
	    dots:false,
	})

	// style-two //
	$('.style-two-slider').owlCarousel({
	    loop:true,
	    margin:10,
	    nav:true,
	    items:1,
	    dots:false,
	    navText: ['<i class="fa fa-arrow-left"></i>', '<i class="fa fa-arrow-right"></i>']
	})
	
		
	/*------------------------------------------------------------------
		Causes-Slider
	-------------------------------------------------------------------*/
	$('#causes .owl-carousel').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:true,
		dots:false,
		autoplay:true,
	    autoplayTimeout:4000,
	    responsive:{
	        0:{items:1},
	        600:{items:1},
	        1000:{items:1}
	    }
	});
		
	/*------------------------------------------------------------------
		Testimonials-Slider
	-------------------------------------------------------------------*/
	$('#testimonials .owl-carousel').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:false,
		dots:true,
		autoplay:false,
		items: 1,
	});

	// sa-fund-carousel //
	$('.sa-fund-carousel').owlCarousel({
	    loop:true,
	    margin:10,
	    nav:true,
	    items:1,
	    dots:false,
	    navText: ['<i class="fa fa-angle-left"></i>', '<i class="fa fa-angle-right"></i>']
	})
		
	/*------------------------------------------------------------------
		back to top
	-------------------------------------------------------------------*/
	var top = $('#back-top');
		top .hide();
	$(window).scroll(function () {
		if ($(this).scrollTop() > 100) {
			top .fadeIn();
		} else {
			top .fadeOut();
		}
	});
	$('#back-top a').on('click', function(e) {
		$('body,html').animate({
			scrollTop: 0
		}, 800);
		return false;
	});
			
	/*------------------------------------------------------------------
		Countdown
	-------------------------------------------------------------------*/
	// 2019[year] - 8[month] - 20[day]

	$('#countdown').countdown('2019/8/20', function(event) { 
		$(this).html(event.strftime('<span class="countdown-period">%-D <span>Day%!D</span></span> <span class="countdown-period">%H <span>Hours</span></span> <span class="countdown-period">%M <span>Minutes</span></span> <span class="countdown-period">%S <span>Seconds</span></span>'));
	});

	/*------------------------------------------------------------------
		Countdown
	-------------------------------------------------------------------*/
	// 2019[year] - 8[month] - 20[day]
	if($('#countdown-intro').length > 0){
		$('#countdown-intro').countdown('2019/3/20', function(event) { 
		$(this).html(event.strftime(
			'<span class="countdown-period"> <span class="c-title">%-D</span><span>Day%!D</span></span> <span class="countdown-period"><span class="c-title">%H</span> <span>Hours</span></span> <span class="countdown-period"><span class="c-title">%M</span><span>Minutes</span></span> <span class="countdown-period"><span class="c-title">%S</span> <span>Seconds</span></span>'));
		});
	}

	// bannercoundown //
	if($('.bannercoundown').length > 0){
		$('.bannercoundown').countdown('2019/3/20', function(event) { 
		$(this).html(event.strftime(
			'<span class="countdown-period"> <span class="c-title">%-D</span><span>Day%!D</span></span> <span class="countdown-period"><span class="c-title">%H</span> <span>Hours</span></span> <span class="countdown-period"><span class="c-title">%M</span><span>Minutes</span></span> <span class="countdown-period countdown-period-last"><span class="c-title">%S</span> <span>Seconds</span></span>'));
		});
	}
 

    /*------------------------------------------------------------------
	    sa-banner-slider
	-------------------------------------------------------------------*/
	$('.sa-banner-slider').owlCarousel({
	    loop:true,
	    margin:0,
	    nav:false,
	    items:1,
	    dots:true,
	})

	/*------------------------------------------------------------------
	    progress bar funtion
	-------------------------------------------------------------------*/
    function progressBars() {
        var progressBar = $('.cause-progress .progress-bar');
        progressBar.css({width: 0});
        progressBar.each(function () {
            $(this).animate({width: $(this).attr("aria-valuenow") + "%"}, 1000)
        });
    }
    // progress bar funtion call
    progressBars();


    /* -------------------------------------------------------------
	    Fact Counter
	------------------------------------------------------------- */
	if ( $('.counters').length){
	    $('.counters').counterUp({
	        delay: 10,
	        time: 1000
	    });
	}


    /* -------------------------------------------------------------
      RoundCircle Progress js
    ------------------------------------------------------------- */
    function roundCircleProgress () {
      var rounderContainer = $('.piechart');
      if (rounderContainer.length) {
        rounderContainer.each(function () {
          var Self = $(this);
          var value = Self.data('value');
          var size = Self.parent().width();
          var color = Self.data('border-color');

          Self.find('.count').each(function () {
            var expertCount = $(this);
            expertCount.appear(function () {
              expertCount.countTo({
                from: 1,
                to: value*100,
                speed: 2000
              });
            });

          });
          Self.appear(function () {         
            Self.circleProgress({
              value: value,
              size: 130,
              thickness: 15,
              emptyFill: '#fff',
              animation: {
                duration: 2000
              },
              fill: {
                color: color
              }
            });
          });
        });
      };
    }
    roundCircleProgress ();
    // Window load function
    jQuery(window).on('load', function () {
       (function ($) {
          roundCircleProgress ();
      })(jQuery);
    });


    /* -------------------------------------------------------------
    	minus btn js
    ------------------------------------------------------------- */
    $('.minus-btn').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('input');
        var value = parseInt($input.val());

        if (value > 1) {
            value = value - 1;
        } else {
            value = 0;
        }

    $input.val(value);

    });

    $('.plus-btn').on('click', function(e) {
        e.preventDefault();
        var $this = $(this);
        var $input = $this.closest('div').find('input');
        var value = parseInt($input.val());

        if (value < 100) {
        value = value + 1;
        } else {
            value =100;
        }

        $input.val(value);
    });

  $('.like-btn').on('click', function() {
    $(this).toggleClass('is-active');
  });

/*
    |----------------------------------------------------------------------------
    | Google Map
    |----------------------------------------------------------------------------
    */
    if($('#map-canvas').length > 0){
      function popup_listing_map(){
         var map;        
          var myCenter=new google.maps.LatLng(53, -1.33);
          var marker=new google.maps.Marker({
              position:myCenter
          });
          function initialize() {
              var mapProp = {
                center:myCenter,
                zoom: 14,
                draggable: false,
                scrollwheel: false,
                mapTypeId:google.maps.MapTypeId.ROADMAP
              };

              map=new google.maps.Map(document.getElementById("map-canvas"),mapProp);

               //Map Marker
              var marker = new google.maps.Marker({
                  position:myCenter,
                  map: map,
                  icon: 'assets/images/marker.png'
              });

              google.maps.event.addListener(marker, 'click', function() {
                
              infowindow.setContent(contentString);
              infowindow.open(map, marker);

              }); 
          };

          google.maps.event.addDomListener(window, 'load', initialize);

          google.maps.event.addDomListener(window, "resize", resizingMap());

          $('#popupmodal').on('show.bs.modal', function() {
             //Must wait until the render of the modal appear, thats why we use the resizeMap and NOT resizingMap!! ;-)
             resizeMap();
          })

          function resizeMap() {
             if(typeof map =="undefined") return;
             setTimeout( function(){resizingMap();} , 400);
          }

          function resizingMap() {
             if(typeof map =="undefined") return;
             var center = map.getCenter();
             google.maps.event.trigger(map, "resize");
             map.setCenter(center); 
          } 
      }
      popup_listing_map();

    }
    
  

})(jQuery);