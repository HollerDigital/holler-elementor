/**
 * Holler Team Modal Functionality
 * 
 * This script handles the opening and closing of team member bio modals
 * in a clean, efficient way using event delegation and data attributes.
 */
jQuery(document).ready(function($) {
    //console.log("Holler Elementor script initialized");
    
    // When a team member with a modal is clicked
    $(document).on('click', '[data-team-modal="true"]', function(e) {
        //console.log("Team member clicked:", this);
        e.preventDefault();
        var modalId = $(this).attr('data-modal');
        console.log("Modal ID:", modalId);
        
        if (modalId) {
            var modalElement = $('#myModal_' + modalId);
           // console.log("Modal element:", modalElement);
            
            if (modalElement.length) {
                modalElement.css('display', 'block');
              //  console.log("Modal displayed");
            }
        }
    });


    // When the close button is clicked
    $(document).on('click', '.holler-team-close, .close', function() {
        //console.log("Close button clicked");
        var modalId = $(this).attr('data-modal');
        
        if (modalId) {
            var modalElement = $('#myModal_' + modalId);
            if (modalElement.length) {
                modalElement.css('display', 'none');
            }
        }
    });

    // When clicking outside the modal content
    $(window).on('click', function(event) {
        if ($(event.target).hasClass('modal')) {
            $(event.target).css('display', 'none');
        }
    });
});
