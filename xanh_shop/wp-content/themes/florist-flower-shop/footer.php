<?php
/**
 * The template for displaying the footer.
 *
 * Contains the closing of the #content div and all content after
 *
 * @package Florist Flower Shop
 */
?>

    <footer role="contentinfo">
        <aside id="footer" class="copyright-wrapper" role="complementary" aria-label="<?php esc_attr_e( 'Footer', 'florist-flower-shop' ); ?>">
            <div class="container">
                <?php
                    $count = 0;
                    
                    if ( is_active_sidebar( 'footer-1' ) ) {
                        $count++;
                    }
                    if ( is_active_sidebar( 'footer-2' ) ) {
                        $count++;
                    }
                    if ( is_active_sidebar( 'footer-3' ) ) {
                        $count++;
                    }
                    if ( is_active_sidebar( 'footer-4' ) ) {
                        $count++;
                    }
                    // $count == 0 none
                    if ( $count == 1 ) {
                        $colmd = 'col-md-12 col-sm-12';
                    } elseif ( $count == 2 ) {
                        $colmd = 'col-md-6 col-sm-6';
                    } elseif ( $count == 3 ) {
                        $colmd = 'col-md-4 col-sm-4';
                    } else {
                        $colmd = 'col-md-3 col-sm-3';
                    }
                ?>
                
                <div class="row">
                    <div class="<?php if ( !is_active_sidebar( 'footer-1' ) ){ echo "footer_hide"; }else{ echo "$colmd"; } ?> col-xs-12 footer-block">
                        <?php dynamic_sidebar('footer-1'); ?>
                    </div>
                    <div class="<?php if ( is_active_sidebar( 'footer-2' ) ){ echo "$colmd"; }else{ echo "footer_hide"; } ?> col-xs-12 footer-block">
                        <?php dynamic_sidebar('footer-2'); ?>
                    </div>
                    <div class="<?php if ( is_active_sidebar( 'footer-3' ) ){ echo "$colmd"; }else{ echo "footer_hide"; } ?> col-xs-12 col-xs-12 footer-block">
                        <?php dynamic_sidebar('footer-3'); ?>
                    </div>
                    <div class="<?php if ( !is_active_sidebar( 'footer-4' ) ){ echo "footer_hide"; }else{ echo "$colmd"; } ?> col-xs-12 footer-block">
                        <?php dynamic_sidebar('footer-4'); ?>
                    </div>
                </div>
            </div>
        </aside>
        <div id="footer-2" class="pt-3 pb-3 text-center">
          	<div class="copyright container">
                <p class="mb-0"> <?php echo esc_html(get_theme_mod('florist_flower_shop_footer_text',__('By VWThemes','florist-flower-shop'))); ?> designed by Natswar
                </p>
                <?php if( get_theme_mod( 'florist_flower_shop_footer_scroll',true) != '' || get_theme_mod( 'florist_flower_shop_resp_scroll_top_hide_show',true) != '') { ?>
                    <?php $florist_flower_shop_theme_lay = get_theme_mod( 'florist_flower_shop_scroll_top_alignment','Right');
                    if($florist_flower_shop_theme_lay == 'Left'){ ?>
                        <a href="#" class="scrollup left"><i class="fas fa-long-arrow-alt-up p-3"></i><span class="screen-reader-text"><?php esc_html_e( 'Scroll Up', 'florist-flower-shop' ); ?></span></a>
                    <?php }else if($florist_flower_shop_theme_lay == 'Center'){ ?>
                        <a href="#" class="scrollup center"><i class="fas fa-long-arrow-alt-up p-3"></i><span class="screen-reader-text"><?php esc_html_e( 'Scroll Up', 'florist-flower-shop' ); ?></span></a>
                    <?php }else{ ?>
                        <a href="#" class="scrollup"><i class="fas fa-long-arrow-alt-up p-3"></i><span class="screen-reader-text"><?php esc_html_e( 'Scroll Up', 'florist-flower-shop' ); ?></span></a>
                    <?php }?>
                <?php }?>
          	</div>
          	<div class="clear"></div>
        </div>
    </footer>
        <?php wp_footer(); ?>
    </body>
</html>