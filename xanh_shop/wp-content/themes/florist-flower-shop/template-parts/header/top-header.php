<?php
/**
 * The template part for top header
 *
 * @package Florist Flower Shop
 * @subpackage florist-flower-shop
 * @since florist-flower-shop 1.0
 */
?>

<div class="top-bar">
  	<div class="container">
	  	<div class="row">
	  		<div class="col-lg-7 col-md-4">
	  			<div class="marquee-text">
			    	<?php if( get_theme_mod('florist_flower_shop_top_bar_text') != ''){ ?>
		      			<marquee class="py-2 py-lg-3 py-md-3 mb-0 text-center text-md-left text-lg-left"><?php echo esc_html(get_theme_mod('florist_flower_shop_top_bar_text',''));?></marquee>
			    	<?php } ?>
			    </div>
			</div>
			<div class="col-lg-3 col-md-5">
				<?php dynamic_sidebar('social-links'); ?>
			</div>
			<div class="col-lg-2 col-md-3">
		        <?php if( get_theme_mod('florist_flower_shop_contact_us_link') != '' || get_theme_mod('florist_flower_shop_contact_us_text') != ''){ ?>
		          	<div class="get-start-btn py-2 px-3 text-center my-2">
		            	<a href="<?php echo esc_url(get_theme_mod('florist_flower_shop_contact_us_link',''));?>"><?php echo esc_html(get_theme_mod('florist_flower_shop_contact_us_text',''));?></a>
		          	</div>
		        <?php } ?>
			</div>
		</div>
  	</div>
</div>