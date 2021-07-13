<?php
/**
 * Template Name: Custom Home Page
 */

get_header(); ?>

<main id="maincontent" role="main">
  <?php do_action( 'florist_flower_shop_before_slider' ); ?>

  <?php if( get_theme_mod( 'florist_flower_shop_slider_arrows', false) != '' || get_theme_mod( 'florist_flower_shop_resp_slider_hide_show', false) != '') { ?>
    <section id="slider">
      <div id="carouselExampleCaptions" class="carousel slide" data-bs-ride="carousel">
        <?php $florist_flower_shop_pages = array();
          for ( $count = 1; $count <= 3; $count++ ) {
            $mod = intval( get_theme_mod( 'florist_flower_shop_slider_page' . $count ));
            if ( 'page-none-selected' != $mod ) {
              $florist_flower_shop_pages[] = $mod;
            }
          }
          if( !empty($florist_flower_shop_pages) ) :
            $args = array(
              'post_type' => 'page',
              'post__in' => $florist_flower_shop_pages,
              'orderby' => 'post__in'
            );
            $query = new WP_Query( $args );
            if ( $query->have_posts() ) :
              $i = 1;
        ?>
        <div class="carousel-inner" role="listbox">
          <?php while ( $query->have_posts() ) : $query->the_post(); ?>
            <div <?php if($i == 1){echo 'class="carousel-item active"';} else{ echo 'class="carousel-item"';}?>>
              <?php if(has_post_thumbnail()) {?>
                <?php the_post_thumbnail(); ?>
              <?php }?>
              <div class="carousel-caption">
                <div class="row">
                  <div class="slide-outer col-lg-7 col-md-7">
                    <div class="slider-inner-box">
                      <h1 class="mb-0 pt-0"><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h1>
                      <p><?php $excerpt = get_the_excerpt(); echo esc_html( florist_flower_shop_string_limit_words( $excerpt, 25)); ?></p>
                      <div class="more-btn my-5">
                        <a href="<?php the_permalink(); ?>" class="px-4 py-3"><?php esc_html_e('SHOP NOW','florist-flower-shop'); ?><i class="fas fa-long-arrow-alt-right ms-2"></i></a>
                      </div>
                    </div>
                  </div>
                  <?php if( get_theme_mod('florist_flower_shop_product_slider') != ''){ ?>
                  <div class="col-lg-5 col-md-5 ">
                    <section id="product-slide" class="pe-2">
                      <div class="container">
                        <div class="row">
                          <?php if ( class_exists( 'WooCommerce' ) ) {
                            $args = array( 
                              'post_type' => 'product',
                              'posts_per_page' => 1,
                              'product_cat' => get_theme_mod('florist_flower_shop_product_slide'),
                              'order' => 'ASC'
                            );
                            $loop = new WP_Query( $args );
                            while ( $loop->have_posts() ) : $loop->the_post(); global $product; ?>
                              <div class="col-lg-7 col-md-7">
                                <div class="product-image">
                                  <span class="product-sale-tag">
                                    <?php woocommerce_show_product_sale_flash( $post, $product ); ?>
                                  </span>
                                  <?php if (has_post_thumbnail( $loop->post->ID )) echo get_the_post_thumbnail($loop->post->ID, 'shop_catalog'); else echo '<img src="'.esc_url(woocommerce_placeholder_img_src()).'" />'; ?>
                                </div>
                              </div>
                              <div class="col-lg-5 col-md-5 p-0">
                                <h3><a href="<?php echo esc_url(get_permalink( $loop->post->ID )); ?>"><?php the_title(); ?></a></h3>
                                <h4 class="<?php echo esc_attr( apply_filters( 'woocommerce_product_price_class', 'price' ) ); ?>"><?php echo $product->get_price_html(); ?></h4>
                                <p><?php $excerpt = get_the_excerpt(); echo esc_html( florist_flower_shop_string_limit_words( $excerpt, 15)); ?></p>
                                <div class="pro-button my-4">
                                  <?php if( $product->is_type( 'simple' ) ){ woocommerce_template_loop_add_to_cart( $loop->post, $product ); } ?>
                                </div>
                              </div>
                            <?php endwhile; wp_reset_query(); ?>
                          <?php } ?>
                        </div>
                      </div>
                    </section>
                  </div>
                  <?php }?>
                </div>
              </div>
            </div>
            
          <?php $i++; endwhile; 
          wp_reset_postdata();?>
        </div>
        <?php else : ?>
          <div class="no-postfound"></div>
        <?php endif;
        endif;?>
        <a class="carousel-control-prev" data-bs-target="#carouselExampleCaptions" data-bs-slide="prev" role="button">
          <span class="carousel-control-prev-icon py-1 px-3 py-lg-1 px-lg-4 py-md-1 px-md-4 w-auto h-auto" aria-hidden="true"><i class="fas fa-long-arrow-alt-left"></i></span>
          <span class="screen-reader-text"><?php esc_html_e( 'Previous','florist-flower-shop' );?></span>
        </a>
        <a class="carousel-control-next" data-bs-target="#carouselExampleCaptions" data-bs-slide="next" role="button">
          <span class="carousel-control-next-icon py-1 px-3 py-lg-1 px-lg-4 py-md-1 px-md-4 w-auto h-auto" aria-hidden="true"><i class="fas fa-long-arrow-alt-right"></i></span>
          <span class="screen-reader-text"><?php esc_html_e( 'Next','florist-flower-shop' );?></span>
        </a>
      </div>
      <div class="clearfix"></div>
    </section>
  <?php }?>

  <?php do_action( 'florist_flower_shop_after_slider' ); ?>

  <section id="product-sec" class="pt-5 pb-5">
    <div class="container">
      <?php $florist_flower_shop_product_page = array();
        $mod = absint( get_theme_mod( 'florist_flower_shop_product_settings','florist-flower-shop'));
        if ( 'page-none-selected' != $mod ) {
          $florist_flower_shop_product_page[] = $mod;
        }
        if( !empty($florist_flower_shop_product_page) ) :
          $args = array(
            'post_type' => 'page',
            'post__in' => $florist_flower_shop_product_page,
            'orderby' => 'post__in'
          );
          $query = new WP_Query( $args );
          if ( $query->have_posts() ) :
            $count = 0;
            while ( $query->have_posts() ) : $query->the_post(); ?>
              <div class="heading-txt mb-4">
                <h3 class="text-center"><?php the_title(); ?></h3>
              </div>
              <?php the_content(); ?>
            <?php $count++; endwhile; ?>
          <?php else : ?>
            <div class="no-postfound"></div>
          <?php endif;
        endif;
        wp_reset_postdata();
      ?>
    </div>
  </section>

  <?php do_action( 'florist_flower_shop_after_service' ); ?>

  <div id="content-vw">
    <div class="container">
      <?php while ( have_posts() ) : the_post(); ?>
        <?php the_content(); ?>
      <?php endwhile; // end of the loop. ?>
    </div>
  </div>
</main>

<?php get_footer(); ?>