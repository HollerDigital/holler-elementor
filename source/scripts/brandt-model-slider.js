// Avoid `console` errors in browsers that lack a console.
"use strict";

/*
// Tab Hover Style
jQuery(document).ready(function($) {
       
  jQuery("#brandt-model-slider").hide();
  
  jQuery("#brandt-model-slider").imagesLoaded(function () {
    
    jQuery("#brandt-model-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin:10,
        nav: false,
        lazyLoad: true,
        video: true,
        dots: false,
        autoHeight: true,
        URLhashListener:true,
        autoplayHoverPause:true,
        startPosition: 'URLHash',
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
    
    jQuery("#brandt-model-slider").show();
  
  }); 
  
 });
 
*/
 
 
jQuery(window).on('elementor/frontend/init', function(){
 	
	elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
		
	jQuery("#brandt-model-slider").hide();
  
  jQuery("#brandt-model-slider").imagesLoaded(function () {
    
    jQuery("#brandt-model-slider").owlCarousel({
        loop: true,
        center: true,
        items: 1,
        margin:10,
        nav: false,
        lazyLoad: true,
        video: true,
        dots: false,
        autoHeight: true,
        URLhashListener:true,
        autoplayHoverPause:true,
        startPosition: 'URLHash',
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
    
    jQuery("#brandt-model-slider").show();
  
  }); 

	
	});
});