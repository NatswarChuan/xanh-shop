<?php
/**
 * @package Florist Flower Shop
 * Setup the WordPress core custom header feature.
 *
 * @uses florist_flower_shop_header_style()
*/
function florist_flower_shop_custom_header_setup() {
	add_theme_support( 'custom-header', apply_filters( 'florist_flower_shop_custom_header_args', array(
		'default-text-color'     => 'fff',
		'header-text' 			 =>	false,
		'width'                  => 1400,
		'height'                 => 80,
		'flex-width'    		 => true,
		'flex-height'    		 => true,
		'wp-head-callback'       => 'florist_flower_shop_header_style',
	) ) );
}
add_action( 'after_setup_theme', 'florist_flower_shop_custom_header_setup' );

if ( ! function_exists( 'florist_flower_shop_header_style' ) ) :
/**
 * Styles the header image and text displayed on the blog
 *
 * @see florist_flower_shop_custom_header_setup().
 */
add_action( 'wp_enqueue_scripts', 'florist_flower_shop_header_style' );

function florist_flower_shop_header_style() {
	//Check if user has defined any header image.
	if ( get_header_image() ) :
	$custom_css = "
        .middle-bar{
			background-image:url('".esc_url(get_header_image())."');
			background-position: center top;
		}";
	   	wp_add_inline_style( 'florist-flower-shop-basic-style', $custom_css );
	endif;
}
endif;