"use strict";

// Tab Hover Style
jQuery(document).ready(function($) {
	jQuery( "#brandt-tabs" ).hide();
	jQuery( function() {
    	jQuery( "#brandt-tabs" ).tabs({ active: 0 });
    	jQuery( "#brandt-tabs" ).show();
  	});

});
 

jQuery(window).on('elementor/frontend/init', function(){
  console.log(elementorFrontend.hooks); // ok
	  elementorFrontend.hooks.addAction( 'frontend/element_ready/global', function( $scope ) {
			jQuery( "#brandt-tabs" ).tabs({ active: 0 });
	});

});