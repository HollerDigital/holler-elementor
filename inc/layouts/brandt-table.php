<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

function _table_template( $settings) { 		
	 ob_start();
?>
  <aside>
		<?php
			 if ( $settings['list'] ) {
				  echo '<table class="tbl-nopad">';
						foreach (  $settings['list'] as $item ) {
  						echo "<tr>";
							echo '<td class="table-cell-dark item-' . $item['_id'] . '">' . $item['list_title'] . '</td>';
							echo '<td>' . $item['list_value'] . '</td>';
							echo "</tr>";
						}
          echo '</table>';
		    }
		?>
</aside>
<?php
  return ob_get_clean();
  
  } 