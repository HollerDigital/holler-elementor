// Avoid `console` errors in browsers that lack a console.
"use strict";
 
 
 
 
 jQuery(window).on('elementor/frontend/init', function(){
 	
	elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
		
		jQuery("#brandt-feature-slider").imagesLoaded( function () {
	    
		    jQuery('#brandt-feature-slider').owlCarousel({
		      loop: true,
		      margin: 10,
		      nav: true,
		      dots: true,
		      responsive: {
		          0:{
		              items:1
		          },
		          768:{
		              items:3
		          },
		          1160:{
		              items:5
		          }
		      }
		    });
			
			jQuery("#brandt-feature-slider").show();
			
	 	});
	});
});