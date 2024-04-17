var  active_modal = null;
// When the user clicks on the button, open the modal
jQuery(".holler_team").on( "click", function(e) {
  var modal_id = jQuery(this).attr("data-modal")
  var modal = document.getElementById("myModal_" +  modal_id);
  active_modal = modal
  modal.style.display = "block";
  var span = document.getElementsByClassName("close_" +  modal_id)[0];
});

// When the user clicks on <span> (x), close the modal
jQuery(".holler-team-close").on( "click", function(e) {
  var modal_id = jQuery(this).attr("data-modal")
  console.log("hit ", modal_id)
  var modal = document.getElementById("myModal_" +  modal_id);
  modal.style.display = "none"; 
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target ==  active_modal) {
    active_modal.style.display = "none";
  }
}



jQuery(document).ready(function($) {
  // Listen for Elementor editor initialization
  $(window).on('elementor:init', function() {
      // Add action to run when a widget is edited
      elementor.hooks.addAction('panel/open_editor/widget', function(panel, model, view) {
          var elementType = model.attributes.elType;
          // Check if the edited widget is a 'container'
          if (elementType === 'container') {
              // Here, you should define how to find and apply your custom class.
              // This example assumes you have a way to access your custom setting
              // For demonstration, let's assume we're applying a static class for simplicity
              var customClass = 'my-custom-class'; // This should be dynamic based on your actual logic

              // Apply the custom class to the widget's editor element
              // Note: Direct DOM manipulation may not reflect immediately in the editor's preview
              // and could be overridden by Elementor's reactive data system.
              view.$el.addClass(customClass);

              // For a more integrated approach, you might need to extend Elementor widgets
              // to include your custom settings and classes in a way that's reactive within Elementor's Vue.js components.
          }
      });
  });
});

jQuery(document).ready(function($) {
  console.log("Custom script loaded");
  $(window).on('elementor:init', function() {
      console.log("Elementor editor initialized");
      // Your code here
  });
});