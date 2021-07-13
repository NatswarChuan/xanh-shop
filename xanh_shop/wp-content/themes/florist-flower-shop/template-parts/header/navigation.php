<?php
/**
 * The template part for header
 *
 * @package Florist Flower Shop 
 * @subpackage florist-flower-shop
 * @since florist-flower-shop 1.0
 */
?>

<div id="header">
  <?php if(has_nav_menu('primary')){ ?>
    <div class="toggle-nav mobile-menu">
      <button role="tab" onclick="florist_flower_shop_menu_open_nav()" class="responsivetoggle"><i class="fas fa-bars p-3"></i><span class="screen-reader-text"><?php esc_html_e('Open Button','florist-flower-shop'); ?></span></button>
    </div>
  <?php } ?>
  <div id="mySidenav" class="nav sidenav">
    <nav id="site-navigation" class="main-navigation" role="navigation" aria-label="<?php esc_attr_e( 'Top Menu', 'florist-flower-shop' ); ?>">
      <?php if(has_nav_menu('primary')){
        wp_nav_menu( array( 
          'theme_location' => 'primary',
          'container_class' => 'main-menu my-2 clearfix' ,
          'menu_class' => 'clearfix',
          'items_wrap' => '<ul id="%1$s" class="%2$s mobile_nav">%3$s</ul>',
          'fallback_cb' => 'wp_page_menu',
        ) );
      } ?>
      <a href="javascript:void(0)" class="closebtn mobile-menu" onclick="florist_flower_shop_menu_close_nav()"><i class="fas fa-times"></i><span class="screen-reader-text"><?php esc_html_e('Close Button','florist-flower-shop'); ?></span></a>
    </nav>
  </div>
</div>