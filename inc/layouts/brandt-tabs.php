<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

 
function _tabs_heading( $settings) {
	 ob_start();
?>
	 <?php
			        if ( $settings['list'] ) {
						echo '<ul>';
						foreach (  $settings['list'] as $item ) {
							echo '<li><a href="#tabs-' . $item['_id'] . '">' . $item['list_title'] . '</a></li>';
						}
						//echo '<li><a href="#tabs-specs">Specifications</a></li>';
						echo '<li><a href="#tabs-dl">Downloads</a></li>';
						echo '</ul>';
					}?>
		       

<?php
  return ob_get_clean();
}


function _tabs_specs( $settings) {
		 		
 

	 ob_start();
?>
 
		        <?php
			        if ( $settings['Specifications'] ) {
						echo '<div id="tabs-specs">';
						echo '<div id="spec-table">';
						foreach (  $settings['Specifications'] as $item ) {
							
							echo '<dl>';
							echo '<dt class="elementor-repeater-item-' . $item['_id'] . '">' . $item['spec_title'] . '</dt>';
							echo '<dd>' . $item['spec_value'] . '</dd>';
							echo '</dl>';
						}
						echo '</div>';
						echo '</div>';
					}
		        ?>
	        <?php
  return ob_get_clean();
}

function _tabs_body( $settings) {
		 		
 


	 ob_start();
?>
 
		        <?php
			        if ( $settings['list'] ) {
						
						foreach (  $settings['list'] as $item ) {
							echo '<div id="tabs-' . $item['_id'] . '">';
						 
							echo  $item['list_value'] ;
							echo '</div>';
						}
						
					}
		        ?>
	        <?php
  return ob_get_clean();
}

function _tabs_downloads( $settings) {
		 		
 


	 ob_start();
?>
 <div id="tabs-dl"> 
		        <?php
			        if ( $settings['dl_list'] ) {
						
						foreach (  $settings['dl_list'] as $item ) {
							
						 echo '<a href="' . $item['dl_link']['url'] . '"  target="_blank" class="btn brandt-download">' . $item['dl_title'] . '</a>';
						 
							 
						}
						
					}
		        ?>
		       </div>
	        <?php
  return ob_get_clean();
}


 