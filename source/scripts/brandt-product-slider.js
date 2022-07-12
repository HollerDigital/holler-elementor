// Avoid `console` errors in browsers that lack a console.
"use strict";

 
jQuery(window).on('elementor/frontend/init', function(){
 	
	elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
		
	jQuery("#brandt-product-slider").hide();
  
  jQuery("#brandt-product-slider").imagesLoaded(function () {
    
    jQuery("#brandt-product-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin:10,
        nav: true,
        lazyLoad: true,
        video: true,
        dots: true,
        autoHeight: true,
        //autoWidth:true,
        responsive:{
            0:{
                items:1
            },
            600:{
                items:1
            },
            1000:{
                items:1
            }
        }
    });
    
    jQuery("#brandt-product-slider").show();
  
  }); 

	
	});
});