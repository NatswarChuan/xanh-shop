<?php

	$florist_flower_shop_custom_css= "";

	/*---------------------------First highlight color-------------------*/

	$florist_flower_shop_first_color = get_theme_mod('florist_flower_shop_first_color');

	if($florist_flower_shop_first_color != false){
		$florist_flower_shop_custom_css .='#footer-2, #preloader{';
			$florist_flower_shop_custom_css .='background-color: '.esc_attr($florist_flower_shop_first_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	if($florist_flower_shop_first_color != false){
		$florist_flower_shop_custom_css .='#slider .carousel-control-prev-icon, #slider .carousel-control-next-icon, .post-main-box:hover h2 a, .post-main-box:hover .post-info a, .post-info:hover a, .logo .site-title a:hover{';
			$florist_flower_shop_custom_css .='color: '.esc_attr($florist_flower_shop_first_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	/*---------------------------Second highlight color-------------------*/

	$florist_flower_shop_second_color = get_theme_mod('florist_flower_shop_second_color');

	if($florist_flower_shop_second_color != false){
		$florist_flower_shop_custom_css .='#footer{';
			$florist_flower_shop_custom_css .='background-color: '.esc_attr($florist_flower_shop_second_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	if($florist_flower_shop_first_color != false || $florist_flower_shop_second_color != false){
		$florist_flower_shop_custom_css .='.woocommerce span.onsale,#sidebar h3,span.cart-value, .more-btn a,#comments input[type="submit"],#comments a.comment-reply-link,input[type="submit"],.woocommerce #respond input#submit, .woocommerce a.button, .woocommerce button.button, .woocommerce input.button,.woocommerce #respond input#submit.alt, .woocommerce a.button.alt, .woocommerce button.button.alt, .woocommerce input.button.alt,nav.woocommerce-MyAccount-navigation ul li,.pro-button a, .scrollup i, .pagination span, .pagination a, .widget_product_search button, .toggle-nav i{
		background: linear-gradient(to right, '.esc_attr($florist_flower_shop_first_color).', '.esc_attr($florist_flower_shop_second_color).');
		}';
	}

	/*---------------------------Third highlight color-------------------*/

	$florist_flower_shop_third_color = get_theme_mod('florist_flower_shop_third_color');

	if($florist_flower_shop_third_color != false){
		$florist_flower_shop_custom_css .='.main-navigation ul.sub-menu>li>a:before, .slide-search input[type="submit"],.main-inner-box span.entry-date,.more-btn a:hover,input[type="submit"]:hover,#comments input[type="submit"]:hover,#comments a.comment-reply-link:hover,#slider .carousel-control-prev-icon:hover, #slider .carousel-control-next-icon:hover,.pagination .current,.pagination a:hover,#footer .tagcloud a:hover,#sidebar .tagcloud a:hover,.woocommerce #respond input#submit:hover, .woocommerce a.button:hover, .woocommerce button.button:hover, .woocommerce input.button:hover,.woocommerce #respond input#submit.alt:hover, .woocommerce a.button.alt:hover, .woocommerce button.button.alt:hover, .woocommerce input.button.alt:hover,.widget_product_search button:hover{';
			$florist_flower_shop_custom_css .='background-color: '.esc_attr($florist_flower_shop_third_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	if($florist_flower_shop_third_color != false){
		$florist_flower_shop_custom_css .='a, .main-navigation a:hover, #slider .carousel-caption h1 a, .post-main-box h2 a,.post-info span a, .woocommerce div.product p.price, .woocommerce div.product span.price,.woocommerce ul.products li.product .price, h1, h2, h3, h4, h5, h6{';
			$florist_flower_shop_custom_css .='color: '.esc_attr($florist_flower_shop_third_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	/*---------------------------Fourth highlight color-------------------*/

	$florist_flower_shop_fourth_color = get_theme_mod('florist_flower_shop_fourth_color');

	if($florist_flower_shop_fourth_color != false){
		$florist_flower_shop_custom_css .='#footer .textwidget a,#footer li a:hover,.post-main-box:hover h3 a,#sidebar ul li a:hover,.post-navigation a:hover .post-title, .post-navigation a:focus .post-title,.post-navigation a:hover,.post-navigation a:focus{';
			$florist_flower_shop_custom_css .='color: '.esc_attr($florist_flower_shop_fourth_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	/*---------------------------Fifth highlight color-------------------*/

	$florist_flower_shop_fifth_color = get_theme_mod('florist_flower_shop_fifth_color');

	if($florist_flower_shop_fourth_color != false || $florist_flower_shop_fifth_color != false){
		$florist_flower_shop_custom_css .='.get-start-btn{
		background: linear-gradient(to right, '.esc_attr($florist_flower_shop_fourth_color).', '.esc_attr($florist_flower_shop_fifth_color).');
		}';
	}

	/*---------------------------Sixth highlight color-------------------*/

	$florist_flower_shop_sixth_color = get_theme_mod('florist_flower_shop_sixth_color');

	if($florist_flower_shop_sixth_color != false){
		$florist_flower_shop_custom_css .='.top-bar{';
			$florist_flower_shop_custom_css .='background-color: '.esc_attr($florist_flower_shop_sixth_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	/*--------------------------- Slider -------------------*/

	$florist_flower_shop_slider = get_theme_mod('florist_flower_shop_slider_arrows');
	if($florist_flower_shop_slider == false){
		$florist_flower_shop_custom_css .='.page-template-custom-home-page .home-page-header{';
			$florist_flower_shop_custom_css .='position: static;';
		$florist_flower_shop_custom_css .='}';
	}

	/*---------------------------Width Layout -------------------*/

	$florist_flower_shop_theme_lay = get_theme_mod( 'florist_flower_shop_width_option','Full Width');
    if($florist_flower_shop_theme_lay == 'Boxed'){
		$florist_flower_shop_custom_css .='body{';
			$florist_flower_shop_custom_css .='max-width: 1140px; width: 100%; padding-right: 15px; padding-left: 15px; margin-right: auto; margin-left: auto;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-caption h1 a{';
			$florist_flower_shop_custom_css .='font-size: 40px;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='.admin-bar #slider .carousel-caption,#slider .carousel-caption{';
			$florist_flower_shop_custom_css .=' top: 0;right: 30px !important;left: 30px; height: 470px;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='.page-template-custom-home-page .inner-head-box {';
			$florist_flower_shop_custom_css .='padding: 15px 50px;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='.slider-inner-box{';
			$florist_flower_shop_custom_css .='top: 160px;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-next{';
			$florist_flower_shop_custom_css .='right: 83%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-prev{';
			$florist_flower_shop_custom_css .='left: 5%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-prev, #slider .carousel-control-next{';
			$florist_flower_shop_custom_css .='bottom: 20px;';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Wide Width'){
		$florist_flower_shop_custom_css .='body{';
			$florist_flower_shop_custom_css .='width: 100%;padding-right: 15px;padding-left: 15px;margin-right: auto;margin-left: auto;';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Full Width'){
		$florist_flower_shop_custom_css .='body{';
			$florist_flower_shop_custom_css .='max-width: 100%;';
		$florist_flower_shop_custom_css .='}';
	}

	/*--------------------------- Slider Content Layout -------------------*/

	$florist_flower_shop_theme_lay = get_theme_mod( 'florist_flower_shop_slider_content_option','Left');
    if($florist_flower_shop_theme_lay == 'Left'){
		$florist_flower_shop_custom_css .='#slider .carousel-caption{';
			$florist_flower_shop_custom_css .='text-align:left; right: 20%; left:20%';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Center'){
		$florist_flower_shop_custom_css .='#slider .carousel-caption{';
			$florist_flower_shop_custom_css .='text-align:center; right: 20%; left: 20%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-next{';
			$florist_flower_shop_custom_css .='right: 59%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-prev{';
			$florist_flower_shop_custom_css .='left: 34%;';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Right'){
		$florist_flower_shop_custom_css .='#slider .carousel-caption{';
			$florist_flower_shop_custom_css .='text-align:right; right: 20%; left: 20%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-next{';
			$florist_flower_shop_custom_css .='right: 46%;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='#slider .carousel-control-prev{';
			$florist_flower_shop_custom_css .='left: 47%;';
		$florist_flower_shop_custom_css .='}';
	}

	/*---------------------------Blog Layout -------------------*/

	$florist_flower_shop_theme_lay = get_theme_mod( 'florist_flower_shop_blog_layout_option','Default');
    if($florist_flower_shop_theme_lay == 'Default'){
		$florist_flower_shop_custom_css .='.post-main-box{';
			$florist_flower_shop_custom_css .='';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Center'){
		$florist_flower_shop_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p{';
			$florist_flower_shop_custom_css .='text-align:center;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='.post-info{';
			$florist_flower_shop_custom_css .='margin-top:10px;';
		$florist_flower_shop_custom_css .='}';
	}else if($florist_flower_shop_theme_lay == 'Left'){
		$florist_flower_shop_custom_css .='.post-main-box, .post-main-box h2, .post-info, .new-text p, #our-services p{';
			$florist_flower_shop_custom_css .='text-align:Left;';
		$florist_flower_shop_custom_css .='}';
		$florist_flower_shop_custom_css .='.post-main-box h2{';
			$florist_flower_shop_custom_css .='margin-top:10px;';
		$florist_flower_shop_custom_css .='}';
	}

	/*----------------Responsive Media -----------------------*/

	$florist_flower_shop_resp_topbar = get_theme_mod( 'florist_flower_shop_resp_slider_hide_show',false);
	if($florist_flower_shop_resp_topbar == true && get_theme_mod( 'florist_flower_shop_slider_arrows', false) == false){
    	$florist_flower_shop_custom_css .='#topbar{';
			$florist_flower_shop_custom_css .='display:none;';
		$florist_flower_shop_custom_css .='} ';
	}
    if($florist_flower_shop_resp_topbar == true){
    	$florist_flower_shop_custom_css .='@media screen and (max-width:575px) {';
		$florist_flower_shop_custom_css .='#topbar{';
			$florist_flower_shop_custom_css .='display:block;';
		$florist_flower_shop_custom_css .='} }';
	}else if($florist_flower_shop_resp_topbar == false){
		$florist_flower_shop_custom_css .='@media screen and (max-width:575px) {';
		$florist_flower_shop_custom_css .='#topbar{';
			$florist_flower_shop_custom_css .='display:none;';
		$florist_flower_shop_custom_css .='} }';
	}

	$florist_flower_shop_resp_sidebar = get_theme_mod( 'florist_flower_shop_sidebar_hide_show',true);
    if($florist_flower_shop_resp_sidebar == true){
    	$florist_flower_shop_custom_css .='@media screen and (max-width:575px) {';
		$florist_flower_shop_custom_css .='#sidebar{';
			$florist_flower_shop_custom_css .='display:block;';
		$florist_flower_shop_custom_css .='} }';
	}else if($florist_flower_shop_resp_sidebar == false){
		$florist_flower_shop_custom_css .='@media screen and (max-width:575px) {';
		$florist_flower_shop_custom_css .='#sidebar{';
			$florist_flower_shop_custom_css .='display:none;';
		$florist_flower_shop_custom_css .='} }';
	}

	$florist_flower_shop_resp_scroll_top = get_theme_mod( 'florist_flower_shop_resp_scroll_top_hide_show',true);
	if($florist_flower_shop_resp_scroll_top == true && get_theme_mod( 'florist_flower_shop_footer_scroll',true) != true){
    	$florist_flower_shop_custom_css .='.scrollup i{';
			$florist_flower_shop_custom_css .='visibility:hidden !important;';
		$florist_flower_shop_custom_css .='} ';
	}
    if($florist_flower_shop_resp_scroll_top == true){
    	$florist_flower_shop_custom_css .='@media screen and (max-width:575px) {';
		$florist_flower_shop_custom_css .='.scrollup i{';
			$florist_flower_shop_custom_css .='visibility:visible !important;';
		$florist_flower_shop_custom_css .='} }';
	}else if($florist_flower_shop_resp_scroll_top == false){
		$florist_flower_shop_custom_css .='@media screen and (max-width:575px){';
		$florist_flower_shop_custom_css .='.scrollup i{';
			$florist_flower_shop_custom_css .='visibility:hidden !important;';
		$florist_flower_shop_custom_css .='} }';
	}

	/*---------------- Button Settings ------------------*/

	$florist_flower_shop_button_border_radius = get_theme_mod('florist_flower_shop_button_border_radius');
	if($florist_flower_shop_button_border_radius != false){
		$florist_flower_shop_custom_css .='.post-main-box .more-btn a{';
			$florist_flower_shop_custom_css .='border-radius: '.esc_attr($florist_flower_shop_button_border_radius).'px;';
		$florist_flower_shop_custom_css .='}';
	}

	/*-------------- Copyright Alignment ----------------*/

	$florist_flower_shop_copyright_alingment = get_theme_mod('florist_flower_shop_copyright_alingment');
	if($florist_flower_shop_copyright_alingment != false){
		$florist_flower_shop_custom_css .='.copyright p{';
			$florist_flower_shop_custom_css .='text-align: '.esc_attr($florist_flower_shop_copyright_alingment).';';
		$florist_flower_shop_custom_css .='}';
	}

	/*------------------ Preloader Background Color  -------------------*/

	$florist_flower_shop_preloader_bg_color = get_theme_mod('florist_flower_shop_preloader_bg_color');
	if($florist_flower_shop_preloader_bg_color != false){
		$florist_flower_shop_custom_css .='#preloader{';
			$florist_flower_shop_custom_css .='background-color: '.esc_attr($florist_flower_shop_preloader_bg_color).';';
		$florist_flower_shop_custom_css .='}';
	}

	$florist_flower_shop_preloader_border_color = get_theme_mod('florist_flower_shop_preloader_border_color');
	if($florist_flower_shop_preloader_border_color != false){
		$florist_flower_shop_custom_css .='.loader-line{';
			$florist_flower_shop_custom_css .='border-color: '.esc_attr($florist_flower_shop_preloader_border_color).'!important;';
		$florist_flower_shop_custom_css .='}';
	}