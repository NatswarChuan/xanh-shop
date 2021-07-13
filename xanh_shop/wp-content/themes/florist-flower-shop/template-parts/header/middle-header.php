<?php
/**
 * The template part for top header
 *
 * @package Florist Flower Shop
 * @subpackage florist-flower-shop
 * @since florist-flower-shop 1.0
 */
?>

<div class="middle-bar text-center text-lg-start text-md-start">
  <div class="container">
    <div class="inner-head-box">
      <div class="row">
        <div class="col-lg-3 col-md-5 col-9 align-self-lg-center">
          <div class="logo text-md-left text-lg-left py-md-2 py-lg-0">
            <?php if ( has_custom_logo() ) : ?>
              <div class="site-logo"><?php the_custom_logo(); ?></div>
            <?php endif; ?>
            <?php $blog_info = get_bloginfo( 'name' ); ?>
              <?php if ( ! empty( $blog_info ) ) : ?>
                <?php if ( is_front_page() && is_home() ) : ?>
                  <?php if( get_theme_mod('florist_flower_shop_logo_title_hide_show',true) != ''){ ?>
                    <h1 class="site-title py-1"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></h1>
                  <?php } ?>
                <?php else : ?>
                  <?php if( get_theme_mod('florist_flower_shop_logo_title_hide_show',true) != ''){ ?>
                    <p class="site-title py-1 mb-0"><a href="<?php echo esc_url( home_url( '/' ) ); ?>" rel="home"><?php bloginfo( 'name' ); ?></a></p>
                  <?php } ?>
                <?php endif; ?>
              <?php endif; ?>
              <?php
                $description = get_bloginfo( 'description', 'display' );
                if ( $description || is_customize_preview() ) :
              ?>
              <?php if( get_theme_mod('florist_flower_shop_tagline_hide_show',true) != ''){ ?>
                <p class="site-description mb-0">
                  <?php echo esc_html($description); ?>
                </p>
              <?php } ?>
            <?php endif; ?>
          </div>
        </div>
        <div class="col-lg-5 col-md-1 col-3 p-0 py-2 align-self-lg-center">
          <?php get_template_part('template-parts/header/navigation'); ?>
        </div>
        <div class="col-lg-3 col-md-4 col-9 align-self-lg-center">
          <div class="search-box py-2 py-md-3 py-lg-2">
            <?php if(class_exists('woocommerce')){ ?>
              <?php get_product_search_form(); ?>
            <?php } ?>
          </div>
        </div>
        <div class="col-lg-1 col-md-2 col-3 align-self-lg-center">
          <?php if(class_exists('woocommerce')){ ?>
            <span class="cart_no my-3 d-block text-right">
              <a href="<?php if(function_exists('wc_get_cart_url')){ echo esc_url(wc_get_cart_url()); } ?>" title="<?php esc_attr_e( 'shopping cart','florist-flower-shop' ); ?>"><i class="fas fa-shopping-basket"></i><span class="screen-reader-text"><?php esc_html_e( 'shopping cart','florist-flower-shop' );?></span></a>
              <span class="cart-value"><?php echo esc_html( wp_kses_data( WC()->cart->get_cart_contents_count()));?></span>
            </span>
          <?php } ?>
        </div>
      </div>
    </div>
  </div>
</div>