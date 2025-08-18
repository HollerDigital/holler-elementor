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

/**
 * Holler Conveyor init
 * Initializes jConveyorTicker on widgets rendered on the page and Elementor editor.
 */
(function($){
    function initHollerConveyors(scope){
        if (typeof $.fn.jConveyorTicker !== 'function') return; // library not loaded
        var $scope = scope ? $(scope) : $(document);

        $scope.find('.holler-conveyor').each(function(){
            var $wrap = $(this);
            var $ul = $wrap.children('ul.jcticker'); // UL must be direct child now
            if (!$ul.length || !$ul.children('li').length) return; // nothing to animate

            // Avoid double init
            if ($wrap.data('hollerConveyorInit')) return;

            var animDuration = parseInt($wrap.attr('data-anim-duration'), 10) || 200; // ms per 10px
            var reverseElm   = $wrap.attr('data-reverse-elm') === '1';
            var forceLoop    = $wrap.attr('data-force-loop') === '1';
            var startPaused  = $wrap.attr('data-start-paused') === '1';
            var pauseHover   = $wrap.attr('data-pause-hover') === '1';
            var itemGap      = parseInt($wrap.attr('data-item-gap'), 10) || 24;
            var trackHeight  = parseInt($wrap.attr('data-track-height'), 10) || 0;

            // Ensure no CSS/JS transform-based mirroring remains
            $wrap.removeClass('is-reversed').css({ transform: '', 'transform-origin': '' });
            $ul.css({ transform: '', 'transform-origin': '' });
            $ul.children('li').children().css({ transform: '' });

            // Apply per-item spacing if provided (pre-init for original items)
            if (itemGap >= 0) {
                // Expose as CSS variable for CSS-enforced fallback
                try { $wrap.css('--holler-conveyor-item-gap', itemGap + 'px'); } catch(e){}
                $ul.children('li').css({
                    'margin-right': itemGap + 'px',
                    'margin-left': itemGap + 'px'
                });
            }
            if (trackHeight > 0) {
                $ul.css('height', trackHeight + 'px');
                $ul.css('line-height', trackHeight + 'px');
            }

            // Initialize on the wrapper so it sees label + UL as children
            // Use the plugin's native reverse. Pass the UI flag directly.
            var instance = $wrap.jConveyorTicker({
                anim_duration: animDuration,
                reverse_elm: reverseElm,
                reverse: reverseElm,
                force_loop: forceLoop,
                start_paused: startPaused
            });

            // After init, the plugin may wrap/duplicate items inside .jctkr-wrapper
            // Apply spacing to those as well
            if (itemGap >= 0) {
                var applyGap = function(){
                    $wrap.find('.jctkr-wrapper ul li').css({
                        'margin-right': itemGap + 'px',
                        'margin-left': itemGap + 'px'
                    });
                };
                applyGap();
                // Attempt again shortly in case of async cloning
                setTimeout(applyGap, 50);
                setTimeout(applyGap, 200);
            }

            // Hover behavior
            if (instance && instance.pauseAnim && instance.playAnim) {
                if (pauseHover) {
                    // Respect pause on hover
                    $wrap.on('mouseenter', function(){ instance.pauseAnim(); });
                    $wrap.on('mouseleave', function(){ instance.playAnim(); });
                } else {
                    // Some builds pause by default on hover; force play to keep it running
                    $wrap.on('mouseenter', function(){ instance.playAnim(); });
                    $wrap.on('mouseleave', function(){ instance.playAnim(); });
                }
            }

            // No need to mirror left->right; wrapper mirroring handles perceived direction while
            // allowing the plugin to manage its own left-based animation.

            // No DOM mutation observers needed when using native reverse
            var moChildren = null;

            $wrap.data('hollerConveyorInit', true)
                 .data('hollerConveyorInstance', instance)
                 .data('hollerConveyorObserverChildren', moChildren || null);
        });
    }

    // Frontend
    jQuery(function(){ initHollerConveyors(document); });

    // Elementor Editor hook
    if (window.elementorFrontend && window.elementorFrontend.hooks) {
        window.elementorFrontend.hooks.addAction('frontend/element_ready/widget', function($scope){
            initHollerConveyors($scope);
        });
        window.elementorFrontend.hooks.addAction('frontend/element_ready/holler-conveyor.default', function($scope){
            initHollerConveyors($scope);
        });
    }
})(jQuery);

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
