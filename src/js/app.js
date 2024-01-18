'use strict';

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