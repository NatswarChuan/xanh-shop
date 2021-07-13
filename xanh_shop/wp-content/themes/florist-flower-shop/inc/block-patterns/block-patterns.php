<?php
/**
 * Florist Flower Shop: Block Patterns
 *
 * @package Florist Flower Shop
 * @since   1.0.0
 */

/**
 * Register Block Pattern Category.
 */
if ( function_exists( 'register_block_pattern_category' ) ) {
	register_block_pattern_category( 'florist-flower-shop',
		array( 'label' => __( 'Florist Flower Shop', 'florist-flower-shop' ) )
	);
}

/**
 * Register Block Patterns.
 */
if ( function_exists( 'register_block_pattern' ) ) {
	register_block_pattern(
		'florist-flower-shop/banner-section',
		array(
			'title'      => __( 'Banner Section', 'florist-flower-shop' ),
			'categories' => array( 'florist-flower-shop' ),
			'content'    => "<!-- wp:cover {\"url\":\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\",\"id\":881,\"dimRatio\":90,\"overlayColor\":\"white\",\"minHeight\":600,\"contentPosition\":\"center center\",\"align\":\"full\",\"className\":\"banner-section\"} -->\n<div class=\"wp-block-cover alignfull has-background-dim-90 has-white-background-color has-background-dim banner-section\" style=\"min-height:600px\"><img class=\"wp-block-cover__image-background wp-image-881\" alt=\"\" src=\"" . esc_url(get_template_directory_uri()) . "/inc/block-patterns/images/banner.png\" data-object-fit=\"cover\"/><div class=\"wp-block-cover__inner-container\"><!-- wp:columns {\"className\":\"mx-md-5 px-xl-5 px-lg-4 px-md-0 px-3 my-0\"} -->\n<div class=\"wp-block-columns mx-md-5 px-xl-5 px-lg-4 px-md-0 px-3 my-0\"><!-- wp:column {\"width\":\"60%\"} -->\n<div class=\"wp-block-column\" style=\"flex-basis:60%\"><!-- wp:heading {\"level\":1,\"style\":{\"typography\":{\"fontSize\":50},\"color\":{\"text\":\"#234ea4\"}}} -->\n<h1 class=\"has-text-color\" style=\"color:#234ea4;font-size:50px\">Lorem ipsum dolor sit amet consectetur</h1>\n<!-- /wp:heading -->\n\n<!-- wp:paragraph {\"style\":{\"typography\":{\"fontSize\":15}},\"textColor\":\"black\"} -->\n<p class=\"has-black-color has-text-color\" style=\"font-size:15px\">Lorem ipsum dolor sit amet consectetur</p>\n<!-- /wp:paragraph -->\n\n<!-- wp:buttons {\"className\":\"mb-lg-0 mb-4\"} -->\n<div class=\"wp-block-buttons mb-lg-0 mb-4\"><!-- wp:button {\"borderRadius\":15,\"style\":{\"color\":{\"gradient\":\"linear-gradient(90deg,rgb(154,69,173) 0%,rgb(56,77,166) 100%)\"}},\"className\":\"banner-button\"} -->\n<div class=\"wp-block-button banner-button\"><a class=\"wp-block-button__link has-background\" style=\"border-radius:15px;background:linear-gradient(90deg,rgb(154,69,173) 0%,rgb(56,77,166) 100%)\">SHOP NOW</a></div>\n<!-- /wp:button --></div>\n<!-- /wp:buttons --></div>\n<!-- /wp:column -->\n\n<!-- wp:column -->\n<div class=\"wp-block-column\"><!-- wp:woocommerce/product-category {\"columns\":1,\"rows\":1,\"categories\":[17],\"className\":\"banner-product\"} /--></div>\n<!-- /wp:column --></div>\n<!-- /wp:columns --></div></div>\n<!-- /wp:cover -->",
		)
	);

	register_block_pattern(
		'florist-flower-shop/product-section',
		array(
			'title'      => __( 'Product Section', 'florist-flower-shop' ),
			'categories' => array( 'florist-flower-shop' ),
			'content'    => "<!-- wp:group {\"className\":\"product-section py-5\"} -->\n<div class=\"wp-block-group product-section py-5\"><div class=\"wp-block-group__inner-container\"><!-- wp:heading {\"textAlign\":\"center\",\"level\":3,\"style\":{\"typography\":{\"fontSize\":50},\"color\":{\"text\":\"#234ea4\"}},\"className\":\"mb-5\"} -->\n<h3 class=\"has-text-align-center mb-5 has-text-color\" style=\"color:#234ea4;font-size:50px\">Shop Best Sellers</h3>\n<!-- /wp:heading -->\n\n<!-- wp:woocommerce/product-category {\"columns\":5,\"rows\":1,\"categories\":[17],\"contentVisibility\":{\"title\":true,\"price\":true,\"rating\":true,\"button\":false},\"align\":\"wide\"} /--></div></div>\n<!-- /wp:group -->",
		)
	);
}