<?php
/**
 * Team Modal Script
 * 
 * This file adds a small script to the footer to handle team modals.
 */

// Don't allow direct access
defined('ABSPATH') || exit;

/**
 * Add team modal script to footer
 */
function holler_add_team_modal_script() {
    ?>
    <script>
    jQuery(document).ready(function($) {
        // When a team member with a bio modal is clicked
        $('.holler-team-bio').on('click', function(e) {
            e.preventDefault();
            var modalId = $(this).attr('data-modal');
            if (modalId) {
                $('#myModal_' + modalId).css('display', 'block');
            }
        });

        // When the close button is clicked
        $('.holler-team-close, .close').on('click', function() {
            var modalId = $(this).attr('data-modal');
            if (modalId) {
                $('#myModal_' + modalId).css('display', 'none');
            }
        });

        // When clicking outside the modal content
        $(window).on('click', function(event) {
            $('.modal').each(function() {
                if (event.target == this) {
                    $(this).css('display', 'none');
                }
            });
        });
    });
    </script>
    <?php
}
add_action('wp_footer', 'holler_add_team_modal_script', 99);
