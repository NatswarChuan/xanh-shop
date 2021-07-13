<?php
//about theme info
add_action( 'admin_menu', 'florist_flower_shop_gettingstarted' );
function florist_flower_shop_gettingstarted() {
	add_theme_page( esc_html__('About Florist Flower Shop', 'florist-flower-shop'), esc_html__('About Florist Flower Shop', 'florist-flower-shop'), 'edit_theme_options', 'florist_flower_shop_guide', 'florist_flower_shop_mostrar_guide');
}

// Add a Custom CSS file to WP Admin Area
function florist_flower_shop_admin_theme_style() {
	wp_enqueue_style('florist-flower-shop-custom-admin-style', esc_url(get_template_directory_uri()) . '/inc/getstart/getstart.css');
	wp_enqueue_script('florist-flower-shop-tabs', esc_url(get_template_directory_uri()) . '/inc/getstart/js/tab.js');
	wp_enqueue_style( 'font-awesome-css', esc_url(get_template_directory_uri()).'/assets/css/fontawesome-all.css' );
}
add_action('admin_enqueue_scripts', 'florist_flower_shop_admin_theme_style');

//guidline for about theme
function florist_flower_shop_mostrar_guide() { 
	//custom function about theme customizer
	$florist_flower_shop_return = add_query_arg( array()) ;
	$florist_flower_shop_theme = wp_get_theme( 'florist-flower-shop' );
?>

<div class="wrapper-info">
    <div class="col-left">
    	<h2><?php esc_html_e( 'Welcome to Florist Flower Shop', 'florist-flower-shop' ); ?> <span class="version"><?php esc_html_e( 'Version', 'florist-flower-shop' ); ?>: <?php echo esc_html($florist_flower_shop_theme['Version']);?></span></h2>
    	<p><?php esc_html_e('All our WordPress themes are modern, minimalist, 100% responsive, seo-friendly,feature-rich, and multipurpose that best suit designers, bloggers and other professionals who are working in the creative fields.','florist-flower-shop'); ?></p>
    </div>
    <div class="col-right">
    	<div class="logo">
			<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/final-logo.png" alt="" />
		</div>
		<div class="update-now">
			<h4><?php esc_html_e('Buy Florist Flower Shop at 20% Discount','florist-flower-shop'); ?></h4>
			<h4><?php esc_html_e('Use Coupon','florist-flower-shop'); ?> ( <span><?php esc_html_e('vwpro20','florist-flower-shop'); ?></span> ) </h4> 
			<div class="info-link">
				<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_BUY_NOW ); ?>" target="_blank"> <?php esc_html_e( 'Upgrade to Pro', 'florist-flower-shop' ); ?></a>
			</div>
		</div>
    </div>

    <div class="tab-sec">
    	<div class="tab">
    		<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'lite_theme')"><?php esc_html_e( 'Setup With Customizer', 'florist-flower-shop' ); ?></button>
    		<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'block_pattern')"><?php esc_html_e( 'Setup With Block Pattern', 'florist-flower-shop' ); ?></button>
			<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'gutenberg_editor')"><?php esc_html_e( 'Setup With Gutunberg Block', 'florist-flower-shop' ); ?></button>
			<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'product_addons_editor')"><?php esc_html_e( 'Woocommerce Product Addons', 'florist-flower-shop' ); ?></button>
			<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'pro_theme')"><?php esc_html_e( 'Get Premium', 'florist-flower-shop' ); ?></button>
			<button class="tablinks" onclick="florist_flower_shop_open_tab(event, 'lite_pro')"><?php esc_html_e( 'Support', 'florist-flower-shop' ); ?></button>
		</div>

		<?php
			$florist_flower_shop_plugin_custom_css = '';
			if(class_exists('Ibtana_Visual_Editor_Menu_Class')){
				$florist_flower_shop_plugin_custom_css ='display: block';
			}
		?>
		<div id="lite_theme" class="tabcontent open">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
				$plugin_ins = Florist_Flower_Shop_Plugin_Activation_Settings::get_instance();
				$florist_flower_shop_actions = $plugin_ins->recommended_actions;
				?>
				<div class="florist-flower-shop-recommended-plugins">
				    <div class="florist-flower-shop-action-list">
				        <?php if ($florist_flower_shop_actions): foreach ($florist_flower_shop_actions as $key => $florist_flower_shop_actionValue): ?>
				                <div class="florist-flower-shop-action" id="<?php echo esc_attr($florist_flower_shop_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($florist_flower_shop_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($florist_flower_shop_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($florist_flower_shop_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" get-start-tab-id="lite-theme-tab" href="javascript:void(0);"><?php esc_html_e('Skip','florist-flower-shop'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="lite-theme-tab" style="<?php echo esc_attr($florist_flower_shop_plugin_custom_css); ?>">
				<h3><?php esc_html_e( 'Lite Theme Information', 'florist-flower-shop' ); ?></h3>
				<hr class="h3hr">	
				<p><?php esc_html_e('Free flower shop WordPress theme is modern and alluring with the potential to make a good website for online flower sale. It is also a good choice for contemporary flower studio or the flower shop agency. It has the multiple inner page templates apart from advance social media feature making it more business oriented. It is a theme of multipurpose level and you can open up a flower boutique business soon. Things become easier with WooCommerce plugin because you can open an online flower shop in a short span of time. It has enable disable options on all sections apart from gallery, banner as well as post type plugin functionality. It has theme options and because of the responsive design, you can open the website easily on the Android or Apple iPhones. It has advanced colour option apart from title and tagline customization. Since it came online, it did well both in quality and fame.','florist-flower-shop'); ?></p>		  	
			  	<div class="col-left-inner">
			  		<h4><?php esc_html_e( 'Theme Documentation', 'florist-flower-shop' ); ?></h4>
					<p><?php esc_html_e( 'If you need any assistance regarding setting up and configuring the Theme, our documentation is there.', 'florist-flower-shop' ); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_FREE_THEME_DOC ); ?>" target="_blank"> <?php esc_html_e( 'Documentation', 'florist-flower-shop' ); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Theme Customizer', 'florist-flower-shop'); ?></h4>
					<p> <?php esc_html_e('To begin customizing your website, start by clicking "Customize".', 'florist-flower-shop'); ?></p>
					<div class="info-link">
						<a target="_blank" href="<?php echo esc_url( admin_url('customize.php') ); ?>"><?php esc_html_e('Customizing', 'florist-flower-shop'); ?></a>
					</div>
					<hr>				
					<h4><?php esc_html_e('Having Trouble, Need Support?', 'florist-flower-shop'); ?></h4>
					<p> <?php esc_html_e('Our dedicated team is well prepared to help you out in case of queries and doubts regarding our theme.', 'florist-flower-shop'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Support Forum', 'florist-flower-shop'); ?></a>
					</div>
					<hr>
					<h4><?php esc_html_e('Reviews & Testimonials', 'florist-flower-shop'); ?></h4>
					<p> <?php esc_html_e('All the features and aspects of this WordPress Theme are phenomenal. I\'d recommend this theme to all.', 'florist-flower-shop'); ?></p>
					<div class="info-link">
						<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_REVIEW ); ?>" target="_blank"><?php esc_html_e('Reviews', 'florist-flower-shop'); ?></a>
					</div>
					<div class="link-customizer">
						<h3><?php esc_html_e( 'Link to customizer', 'florist-flower-shop' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-slides"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_slidersettings') ); ?>" target="_blank"><?php esc_html_e('Slider Settings','florist-flower-shop'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-welcome-write-blog"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_top_header') ); ?>" target="_blank"><?php esc_html_e('Top Header Section','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-category"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_product_slide_section') ); ?>" target="_blank"><?php esc_html_e('Popular Product Section','florist-flower-shop'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','florist-flower-shop'); ?></a>
								</div>
							</div>

							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','florist-flower-shop'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-editor-table"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_product_section') ); ?>" target="_blank"><?php esc_html_e('BestSelling Product','florist-flower-shop'); ?></a>
								</div> 
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','florist-flower-shop'); ?></a>
								</div>
							</div>
						</div>
					</div>
			  	</div>
				<div class="col-right-inner">
					<h3 class="page-template"><?php esc_html_e('How to set up Home Page Template','florist-flower-shop'); ?></h3>
				  	<hr class="h3hr">
					<p><?php esc_html_e('Follow these instructions to setup Home page.','florist-flower-shop'); ?></p>
                  	<p><span class="strong"><?php esc_html_e('1. Create a new page :','florist-flower-shop'); ?></span><?php esc_html_e(' Go to ','florist-flower-shop'); ?>
					  	<b><?php esc_html_e(' Dashboard >> Pages >> Add New Page','florist-flower-shop'); ?></b></p>
                  	<p><?php esc_html_e('Name it as "Home" then select the template "Custom Home Page".','florist-flower-shop'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/home-page-template.png" alt="" />
                  	<p><span class="strong"><?php esc_html_e('2. Set the front page:','florist-flower-shop'); ?></span><?php esc_html_e(' Go to ','florist-flower-shop'); ?>
					  	<b><?php esc_html_e(' Settings >> Reading ','florist-flower-shop'); ?></b></p>
				  	<p><?php esc_html_e('Select the option of Static Page, now select the page you created to be the homepage, while another page to be your default page.','florist-flower-shop'); ?></p>
                  	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/set-front-page.png" alt="" />
                  	<p><?php esc_html_e(' Once you are done with setup, then follow the','florist-flower-shop'); ?> <a class="doc-links" href="<?php echo esc_url( FLORIST_FLOWER_SHOP_FREE_THEME_DOC ); ?>" target="_blank"><?php esc_html_e('Documentation','florist-flower-shop'); ?></a></p>
			  	</div>
			</div>
		</div>	

		<div id="block_pattern" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = Florist_Flower_Shop_Plugin_Activation_Settings::get_instance();
			$florist_flower_shop_actions = $plugin_ins->recommended_actions;
			?>
				<div class="florist-flower-shop-recommended-plugins">
				    <div class="florist-flower-shop-action-list">
				        <?php if ($florist_flower_shop_actions): foreach ($florist_flower_shop_actions as $key => $florist_flower_shop_actionValue): ?>
				                <div class="florist-flower-shop-action" id="<?php echo esc_attr($florist_flower_shop_actionValue['id']);?>">
			                        <div class="action-inner">
			                            <h3 class="action-title"><?php echo esc_html($florist_flower_shop_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($florist_flower_shop_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($florist_flower_shop_actionValue['link']); ?>
			                            <a class="ibtana-skip-btn" href="javascript:void(0);" get-start-tab-id="gutenberg-editor-tab"><?php esc_html_e('Skip','florist-flower-shop'); ?></a>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php } ?>
			<div class="gutenberg-editor-tab" style="<?php echo esc_attr($florist_flower_shop_plugin_custom_css); ?>">
				<div class="block-pattern-img">
				  	<h3><?php esc_html_e( 'Block Patterns', 'florist-flower-shop' ); ?></h3>
					<hr class="h3hr">
					<p><?php esc_html_e('Follow the below instructions to setup Home page with Block Patterns.','florist-flower-shop'); ?></p>
	              	<p><b><?php esc_html_e('Click on Below Add new page button >> Click on "+" Icon >> Click Pattern Tab >> Click on homepage sections >> Publish.','florist-flower-shop'); ?></span></b></p>
	              	<div class="florist-flower-shop-pattern-page">
				    	<a href="javascript:void(0)" class="vw-pattern-page-btn button-primary button"><?php esc_html_e('Add New Page','florist-flower-shop'); ?></a>
				    </div>
	              	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/block-pattern.png" alt="" />	
	            </div>

	            <div class="block-pattern-link-customizer">
	              	<div class="link-customizer-with-block-pattern">
						<h3><?php esc_html_e( 'Link to customizer', 'florist-flower-shop' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','florist-flower-shop'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','florist-flower-shop'); ?></a>
								</div>
								
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','florist-flower-shop'); ?></a>
								</div>
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','florist-flower-shop'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','florist-flower-shop'); ?></a>
								</div> 
							</div>
						</div>
					</div>	
				</div>
	        </div>
		</div>

		<div id="gutenberg_editor" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = Florist_Flower_Shop_Plugin_Activation_Settings::get_instance();
			$florist_flower_shop_actions = $plugin_ins->recommended_actions;
			?>
				<div class="florist-flower-shop-recommended-plugins">
				    <div class="florist-flower-shop-action-list">
				        <?php if ($florist_flower_shop_actions): foreach ($florist_flower_shop_actions as $key => $florist_flower_shop_actionValue): ?>
				                <div class="florist-flower-shop-action" id="<?php echo esc_attr($florist_flower_shop_actionValue['id']);?>">
			                        <div class="action-inner plugin-activation-redirect">
			                            <h3 class="action-title"><?php echo esc_html($florist_flower_shop_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($florist_flower_shop_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($florist_flower_shop_actionValue['link']); ?>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Gutunberg Blocks', 'florist-flower-shop' ); ?></h3>
				<hr class="h3hr">
				<div class="florist-flower-shop-pattern-page">
			    	<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-templates' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Ibtana Settings','florist-flower-shop'); ?></a>
			    </div>

			    <div class="link-customizer-with-guternberg-ibtana">
	              	<div class="link-customizer-with-block-pattern">
						<h3><?php esc_html_e( 'Link to customizer', 'florist-flower-shop' ); ?></h3>
						<hr class="h3hr">
						<div class="first-row">
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-buddicons-buddypress-logo"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[control]=custom_logo') ); ?>" target="_blank"><?php esc_html_e('Upload your logo','florist-flower-shop'); ?></a>
								</div>
								<div class="row-box2">
									<span class="dashicons dashicons-format-gallery"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_post_settings') ); ?>" target="_blank"><?php esc_html_e('Post settings','florist-flower-shop'); ?></a>
								</div>
							</div>
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-menu"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=nav_menus') ); ?>" target="_blank"><?php esc_html_e('Menus','florist-flower-shop'); ?></a>
								</div>
								
								<div class="row-box2">
									<span class="dashicons dashicons-text-page"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_footer') ); ?>" target="_blank"><?php esc_html_e('Footer Text','florist-flower-shop'); ?></a>
								</div>
							</div>
							
							<div class="row-box">
								<div class="row-box1">
									<span class="dashicons dashicons-admin-generic"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[section]=florist_flower_shop_left_right') ); ?>" target="_blank"><?php esc_html_e('General Settings','florist-flower-shop'); ?></a>
								</div>
								 <div class="row-box2">
									<span class="dashicons dashicons-screenoptions"></span><a href="<?php echo esc_url( admin_url('customize.php?autofocus[panel]=widgets') ); ?>" target="_blank"><?php esc_html_e('Footer Widget','florist-flower-shop'); ?></a>
								</div> 
							</div>
						</div>
					</div>	
				</div>
			<?php } ?>
		</div>

		<div id="product_addons_editor" class="tabcontent">
			<?php if(!class_exists('Ibtana_Visual_Editor_Menu_Class')){ 
			$plugin_ins = Florist_Flower_Shop_Plugin_Activation_Settings::get_instance();
			$florist_flower_shop_actions = $plugin_ins->recommended_actions;
			?>
				<div class="florist-flower-shop-recommended-plugins">
				    <div class="florist-flower-shop-action-list">
				        <?php if ($florist_flower_shop_actions): foreach ($florist_flower_shop_actions as $key => $florist_flower_shop_actionValue): ?>
				                <div class="florist-flower-shop-action" id="<?php echo esc_attr($florist_flower_shop_actionValue['id']);?>">
			                        <div class="action-inner plugin-activation-redirect">
			                            <h3 class="action-title"><?php echo esc_html($florist_flower_shop_actionValue['title']); ?></h3>
			                            <div class="action-desc"><?php echo esc_html($florist_flower_shop_actionValue['desc']); ?></div>
			                            <?php echo wp_kses_post($florist_flower_shop_actionValue['link']); ?>
			                        </div>
				                </div>
				            <?php endforeach;
				        endif; ?>
				    </div>
				</div>
			<?php }else{ ?>
				<h3><?php esc_html_e( 'Woocommerce Products Blocks', 'florist-flower-shop' ); ?></h3>
				<hr class="h3hr">
				<div class="florist-flower-shop-pattern-page">
					<p><?php esc_html_e('Follow the below instructions to setup Products Templates.','florist-flower-shop'); ?></p>
					<p><b><?php esc_html_e('1. First you need to activate these plugins','florist-flower-shop'); ?></b></p>
						<p><?php esc_html_e('1. Ibtana - WordPress Website Builder ','florist-flower-shop'); ?></p>
						<p><?php esc_html_e('2. Ibtana - Ecommerce Product Addons.','florist-flower-shop'); ?></p>
						<p><?php esc_html_e('3. Woocommerce','florist-flower-shop'); ?></p>

	              	<div class="florist-flower-shop-pattern-page">
			    		<a href="<?php echo esc_url( admin_url( 'admin.php?page=ibtana-visual-editor-templates&ive_wizard_view=parent' ) ); ?>" class="vw-pattern-page-btn ibtana-dashboard-page-btn button-primary button"><?php esc_html_e('Woocommerce Templates','florist-flower-shop'); ?></a>
			    	</div>
			    </div>
			<?php } ?>
		</div>

		<div id="pro_theme" class="tabcontent">
		  	<h3><?php esc_html_e( 'Premium Theme Information', 'florist-flower-shop' ); ?></h3>
			<hr class="h3hr">
		    <div class="col-left-pro">
		    	<p><?php esc_html_e('Flower shop WordPress theme has prime focus on floral business and you can accomplish this through a good floral website. It is clean, modern and alluring and you can also open a floral shop agency. It is a nice choice for the floristic studio and also has the blog post section as well as contact page template. You can make a website for horticulture or for the flowers that you grow in your apartment. WordPress theme for flower shop has testimonial section apart from enable disable options on all sections. It has the footer widgets and editor style and the prime focus of this WP theme is on the florist services. It has the social media feature apart from being retina ready, translation ready and with WooCommerce. With WooCommerce compatibility, you can open an online shop for sale of fresh or synthetic flowers. It is translation ready, SEO ready with more than 100 font family options.','florist-flower-shop'); ?></p>
		    	<div class="pro-links">
			    	<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_LIVE_DEMO ); ?>" target="_blank"><?php esc_html_e('Live Demo', 'florist-flower-shop'); ?></a>
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_BUY_NOW ); ?>"><?php esc_html_e('Buy Pro', 'florist-flower-shop'); ?></a>
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_PRO_DOC ); ?>" target="_blank"><?php esc_html_e('Pro Documentation', 'florist-flower-shop'); ?></a>
				</div>
		    </div>
		    <div class="col-right-pro">
		    	<img src="<?php echo esc_url(get_template_directory_uri()); ?>/inc/getstart/images/responsive.png" alt="" />
		    </div>
		    <div class="featurebox">
			    <h3><?php esc_html_e( 'Theme Features', 'florist-flower-shop' ); ?></h3>
				<hr class="h3hr">
				<div class="table-image">
					<table class="tablebox">
						<thead>
							<tr>
								<th></th>
								<th><?php esc_html_e('Free Themes', 'florist-flower-shop'); ?></th>
								<th><?php esc_html_e('Premium Themes', 'florist-flower-shop'); ?></th>
							</tr>
						</thead>
						<tbody>
							<tr>
								<td><?php esc_html_e('Theme Customization', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Responsive Design', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Logo Upload', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Social Media Links', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Slider Settings', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Number of Slides', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('4', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('Unlimited', 'florist-flower-shop'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Template Pages', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('6', 'florist-flower-shop'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Home Page Template', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('1', 'florist-flower-shop'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Theme sections', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('3', 'florist-flower-shop'); ?></td>
								<td class="table-img"><?php esc_html_e('10', 'florist-flower-shop'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Contact us Page Template', 'florist-flower-shop'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('1', 'florist-flower-shop'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Blog Templates & Layout', 'florist-flower-shop'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('3(Full width/Left/Right Sidebar)', 'florist-flower-shop'); ?></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Page Templates & Layout', 'florist-flower-shop'); ?></td>
								<td class="table-img">0</td>
								<td class="table-img"><?php esc_html_e('2(Left/Right Sidebar)', 'florist-flower-shop'); ?></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Color Pallete For Particular Sections', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Global Color Option', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Reordering', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Demo Importer', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Allow To Set Site Title, Tagline, Logo', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Enable Disable Options On All Sections, Logo', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Full Documentation', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Latest WordPress Compatibility', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Woo-Commerce Compatibility', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Support 3rd Party Plugins', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Secure and Optimized Code', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Exclusive Functionalities', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Section Enable / Disable', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Section Google Font Choices', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Gallery', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Simple & Mega Menu Option', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Support to add custom CSS / JS ', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Shortcodes', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Background, Colors, Header, Logo & Menu', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Premium Membership', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Budget Friendly Value', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('Priority Error Fixing', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Custom Feature Addition', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr class="odd">
								<td><?php esc_html_e('All Access Theme Pass', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td><?php esc_html_e('Seamless Customer Support', 'florist-flower-shop'); ?></td>
								<td class="table-img"><i class="fas fa-times"></i></td>
								<td class="table-img"><i class="fas fa-check"></i></td>
							</tr>
							<tr>
								<td></td>
								<td class="table-img"></td>
								<td class="update-link"><a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Upgrade to Pro', 'florist-flower-shop'); ?></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
		</div>

		<div id="lite_pro" class="tabcontent">
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-star-filled"></span><?php esc_html_e('Pro Version', 'florist-flower-shop'); ?></h4>
				<p> <?php esc_html_e('To gain access to extra theme options and more interesting features, upgrade to pro version.', 'florist-flower-shop'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_BUY_NOW ); ?>" target="_blank"><?php esc_html_e('Get Pro', 'florist-flower-shop'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-cart"></span><?php esc_html_e('Pre-purchase Queries', 'florist-flower-shop'); ?></h4>
				<p> <?php esc_html_e('If you have any pre-sale query, we are prepared to resolve it.', 'florist-flower-shop'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_CONTACT ); ?>" target="_blank"><?php esc_html_e('Question', 'florist-flower-shop'); ?></a>
				</div>
		  	</div>
		  	<div class="col-3">		  		
		  		<h4><span class="dashicons dashicons-admin-customizer"></span><?php esc_html_e('Child Theme', 'florist-flower-shop'); ?></h4>
				<p> <?php esc_html_e('For theme file customizations, make modifications in the child theme and not in the main theme file.', 'florist-flower-shop'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_CHILD_THEME ); ?>" target="_blank"><?php esc_html_e('About Child Theme', 'florist-flower-shop'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-admin-comments"></span><?php esc_html_e('Frequently Asked Questions', 'florist-flower-shop'); ?></h4>
				<p> <?php esc_html_e('We have gathered top most, frequently asked questions and answered them for your easy understanding. We will list down more as we get new challenging queries. Check back often.', 'florist-flower-shop'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_FAQ ); ?>" target="_blank"><?php esc_html_e('View FAQ','florist-flower-shop'); ?></a>
				</div>
		  	</div>

		  	<div class="col-3">
		  		<h4><span class="dashicons dashicons-sos"></span><?php esc_html_e('Support Queries', 'florist-flower-shop'); ?></h4>
				<p> <?php esc_html_e('If you have any queries after purchase, you can contact us. We are eveready to help you out.', 'florist-flower-shop'); ?></p>
				<div class="info-link">
					<a href="<?php echo esc_url( FLORIST_FLOWER_SHOP_SUPPORT ); ?>" target="_blank"><?php esc_html_e('Contact Us', 'florist-flower-shop'); ?></a>
				</div>
		  	</div>
		</div>
		
	</div>
</div>

<?php } ?>