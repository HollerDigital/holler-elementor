'use strict';

var active_modal = null;
// When the user clicks on the button, open the modal
// Only attach click handlers to team elements that have data-modal attributes
jQuery(".holler_team[data-modal]").on("click", function(e) {
  var modal_id = jQuery(this).attr("data-modal");
  if (modal_id) { // Only proceed if we have a modal ID
    var modal = document.getElementById("myModal_" + modal_id);
    if (modal) {
      active_modal = modal;
      modal.style.display = "block";
      var span = document.getElementsByClassName("close_" + modal_id)[0];
    }
  }
});

// When the user clicks on <span> (x), close the modal
jQuery(".holler-team-close").on("click", function(e) {
  var modal_id = jQuery(this).attr("data-modal");
  if (modal_id) {
    var modal = document.getElementById("myModal_" + modal_id);
    if (modal) {
      modal.style.display = "none";
    }
  }
});

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (active_modal && event.target == active_modal) {
    active_modal.style.display = "none";
  }
} 