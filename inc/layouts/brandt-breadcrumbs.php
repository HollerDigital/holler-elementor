<?php  

defined( 'ABSPATH' ) || die( "Can't access directly" );

 
function _breadcrumbs_template( $settings) {
  ob_start(); ?>
  <ol itemscope itemtype="https://schema.org/BreadcrumbList" id="brtt-breadcrumbs">
    <li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">
      <a itemprop="item" href="https://camex.com">
        <span itemprop="name">Home</span></a>
        <meta itemprop="position" content="1" />
         <span class="seperator">›</span>
    </li>
  <?php
		if( $settings['list'] ):
			foreach (  $settings['list'] as $key=>$item ): $index = $key+2 ?>
			<?php if(!empty($item['list_link']['url']) && $item['list_link']['url'] != '#'):?>
			 <li itemprop="itemListElement" itemscope
          itemtype="https://schema.org/ListItem">
        <a itemscope itemtype="https://schema.org/WebPage"
           itemprop="item" itemid="<?php echo $item['list_link']['url']; ?>"
           href="<?php echo $item['list_link']['url']; ?>">
          <span itemprop="name"><?php echo $item['list_title']; ?></span></a>
          <meta itemprop="position" content="<?php echo  $index; ?>" />
          <span class="seperator">›</span>
      </li>
      <?php else: ?>
      <li itemprop="itemListElement" itemscope
          itemtype="https://schema.org/ListItem">
        <span itemprop="name"><?php echo $item['list_title']; ?></span>
        <meta itemprop="position" content="<?php echo  $index; ?>" />
        <span class="seperator">›</span>
      </li>
    <?php endif; endforeach;?>
    
      </ol>
      <?php endif; 
  return ob_get_clean();
  
  	//echo '<li><a href="#tabs-' . $item['_id'] . '">' . $item['list_title'] . '</a></li>';
}

 



 
 
 


 