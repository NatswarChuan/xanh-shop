<?php
/**
 * Adds and controls pointers for contextual help/tutorials
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * IEPA_Admin_Pointers Class.
 */
class IEPA_Admin_Pointers {

		public static $screen_id;

		public static $is_block_editor = false;

		public static $iepa_products_post_count = 0;

		public static $iepa_tutorial_steps = 0;

		public static $query_params = [];

  	/**
  	 * Constructor.
  	 */
  	public function __construct() {
  		add_action( 'admin_enqueue_scripts', array( $this, 'iepa_setup_pointers_for_screen' ) );
			add_action( 'wp_ajax_iepa_update_tutorial_status', array( $this, 'iepa_update_tutorial_status' ) );
  	}

		public function iepa_update_tutorial_status() {
			$iepa_tutorial_step	=	(float) $_POST['iepa_tutorial_step'];
			$is_updated	=	update_option( 'iepa_tutorial_steps', $iepa_tutorial_step );
			wp_send_json_success( [ 'status' => $is_updated ] );
		}

  	/**
  	 * Setup pointers for screen.
  	 */
  	public function iepa_setup_pointers_for_screen() {

  		$screen = get_current_screen();

  		if ( ! $screen ) {
  			return;
  		}

			// Check the tutorial status
			self::$iepa_tutorial_steps	=	get_option( 'iepa_tutorial_steps' );

			if ( ( gettype( self::$iepa_tutorial_steps ) === 'boolean' ) && ( self::$iepa_tutorial_steps === false ) ) {
				update_option( 'iepa_tutorial_steps', 0 );
				self::$iepa_tutorial_steps	=	0;
			} else {
				self::$iepa_tutorial_steps	=	( float ) self::$iepa_tutorial_steps;
			}


			self::$query_params	=	$_GET;


			self::$screen_id				=	$screen->id;


			self::$is_block_editor	=	$screen->is_block_editor;


			$valid_screens_to_start = array(
				'dashboard',
				'update-core',
				'edit-post',
				'edit-category',
				'edit-post_tag',
				'upload',
				'edit-page',
				'edit-comments',
				'plugins',
				'toplevel_page_ibtana-visual-editor',
				'ibtana-settings_page_ibtana-visual-editor-general-settings',
				'ibtana-settings_page_ibtana-visual-editor-saved-templates',
				'ibtana-settings_page_ibtana-visual-editor-templates',
				'ibtana-settings_page_ibtana-visual-editor-license',
				'ibtana-settings_page_ibtana-visual-editor-addons',
				'woocommerce_page_wc-admin',
				'edit-shop_order',
				'edit-shop_coupon',
				'woocommerce_page_wc-reports',
				'woocommerce_page_wc-settings',
				'woocommerce_page_wc-status',
				'woocommerce_page_wc-addons',
				'edit-product',
				'edit-product_cat',
				'edit-product_tag',
				'product_page_product_attributes',
				'themes',
				'widgets',
				'nav-menus',
				'theme-editor',
				'plugin-install',
				'plugin-editor',
				'users',
				'user',
				'profile',
				'tools',
				'import',
				'export',
				'site-health',
				'export-personal-data',
				'erase-personal-data',
				'tools_page_action-scheduler',
				'options-general',
				'options-writing',
				'options-reading',
				'options-discussion',
				'options-media',
				'options-permalink',
				'options-privacy'
			);


  		if ( in_array( self::$screen_id, $valid_screens_to_start ) && ( self::$iepa_tutorial_steps == 0 ) ) {

				$this->iepa_create_plugin_tutorial();

			} elseif ( self::$screen_id == 'product' ) {

				if ( !self::$is_block_editor ) {
					$post_status	=	get_post_status();
					if ( 'auto-draft' == $post_status ) {
						if ( isset( self::$query_params['tutorial'] ) ) {
							return;
						}
						if ( self::$iepa_tutorial_steps < 1.1 ) {
							$this->iepa_create_product_tutorial();
						}
					} else {
						if ( self::$iepa_tutorial_steps < 1.2 ) {
							$this->iepa_create_product_tutorial_after_save();
						}
					}
				} else {

					if ( self::$iepa_tutorial_steps < 2 ) {
						$this->iepa_create_product_tutorial_for_block_editor();
					}

				}

  		}
  	}

		/**
		 * Pointers according to the different pages.
		 */
    public function iepa_create_plugin_tutorial() {
      $pointers = array(
  			'pointers' => array(
  				'title'          => array(
  					'target'       => '#menu-posts-product',
  					// 'next'         => 'content',
  					'next_trigger' => array(
  						'target' => '#title',
  						'event'  => 'input',
  					),
  					'options'      => array(
  						'content'  => '<h3 class="iepa-tutorial-head">' . esc_html__( 'Create New Product', IEPA_TEXT_DOMAIN ) . '</h3>' .
  										'<p>' . esc_html__( 'To use Ibtana - Ecommerce Product Addons, you need to Add New product first.', IEPA_TEXT_DOMAIN ) . '</p>',
  						'position' => array(
  							'edge'  => 'left',
  							'align' => 'left',
  						),
							'button_dismiss'	=>	true,
							'next_button'	=>	array(
								'text'				=>	esc_html__( 'Add New Product', IEPA_TEXT_DOMAIN ),
								'event_type'	=>	'href',
								'href'				=>	'post-new.php?post_type=product&iepa_tutorial=true',
							),
							'step'				=>	1
  					),
  				)
  			),
  		);

      $this->enqueue_pointers( $pointers );
    }


  	public function iepa_create_product_tutorial() {

  		// These pointers will chain - they will not be shown at once.
  		$pointers = array(
  			'pointers' => array(
  				'title'          => array(
  					'target'       => '#title',
  					'next'         => 'submitdiv',
  					'next_trigger' => array(
  						'target' => '#title',
  						'event'  => 'input',
  					),
  					'options'      => array(
  						'content'  => '<h3 class="iepa-tutorial-head">' . esc_html__( 'Product name', IEPA_TEXT_DOMAIN ) . '</h3>' .
  										'<p>' . esc_html__( 'Give your new product a name here. This is a required field and will be what your customers will see in your store.', IEPA_TEXT_DOMAIN ) . '</p>',
  						'position' => array(
  							'edge'  => 'top',
  							'align' => 'left',
  						),
							'button_dismiss'	=>	true,
							'step'				=>	1.1
  					),
  				),
  				'submitdiv'      => array(
  					'target'  => '#submitdiv',
  					'next'    => '',
  					'options' => array(
  						'content'  => '<h3>' . esc_html__( 'Save your product!', IEPA_TEXT_DOMAIN ) . '</h3>' .
  										'<p>' . esc_html__( 'When you are finished editing your product, hit the "Publish" button to publish your product to your store or you can save as a draft.', IEPA_TEXT_DOMAIN ) . '</p>',
  						'position' => array(
  							'edge'  => 'right',
  							'align' => 'middle',
  						),
  					),
  				),
  			),
  		);

  		$this->enqueue_pointers( $pointers );
  	}


		public function iepa_create_product_tutorial_after_save() {
			$pointers = array(
  			'pointers' => array(
  				'title'          => array(
  					'target'       => '#iepa_product_metabox',
  					// 'next'         => 'content',
  					'next_trigger' => array(
  						'target' => '#iepa_product_metabox',
  						'event'  => 'change',
  					),
  					'options'      => array(
  						'content'  => '<h3 class="iepa-tutorial-head">' . esc_html__( 'Use Block Editor', IEPA_TEXT_DOMAIN ) . '</h3>' .
  										'<p>' . esc_html__( 'Now you are ready to switch to the block editor.', IEPA_TEXT_DOMAIN ) . '</p>',
  						'position' => array(
  							'edge'  => 'right',
  							'align' => 'left',
  						),
							'button_dismiss'	=>	true,
							'next_button'	=>	array(
								'text'				=>	esc_html__( 'Switch Editor', IEPA_TEXT_DOMAIN ),
								'event_type'	=>	'change',
								'selector'		=>	'#iepa_product_metabox',
							),
							'step'				=>	1.2
  					),
  				)
  			),
  		);

      $this->enqueue_pointers( $pointers );
		}


		public function iepa_create_product_tutorial_for_block_editor() {
			$pointers = array(
				'pointers' => array(
					'title'          => array(
						'target'       => '.modal_btn_svg_icon',
						'next'         => '',
						'next_trigger' => array(
							'target' => '.modal_btn_svg_icon',
							'event'  => 'click',
						),
						'options'      => array(
							'content'  => '<h3 class="iepa-tutorial-head">' . esc_html__( 'WooCommerce Product Templates', IEPA_TEXT_DOMAIN ) . '</h3>' .
											'<p>' . esc_html__( 'Check our Ibtana WooCommerce product templates here!', IEPA_TEXT_DOMAIN ) . '</p>',
							'position' => array(
								'edge'  => 'top',
								'align' => 'left',
							),
							'button_dismiss'	=>	true,
							'next_button'	=>	array(
								'text'				=>	esc_html__( 'Check it out now!', IEPA_TEXT_DOMAIN ),
								'event_type'	=>	'click',
								'selector'		=>	'.modal_btn_svg_icon',
							),
							'step'				=>	2
						),
					),
				),
			);

			$this->enqueue_pointers( $pointers );
		}


    /**
  	 * Enqueue pointers and add script to page.
  	 *
  	 * @param array $pointers Pointers data.
  	 */
  	public function enqueue_pointers( $pointers ) {
  		$pointers = rawurlencode( wp_json_encode( $pointers ) );
  		wp_enqueue_style( 'wp-pointer' );
  		wp_enqueue_script( 'wp-pointer' );

			wp_enqueue_style(
				'iepa-admin-pointers-css',
				IEPA_URL . 'dist/iepa-admin-pointers.css',
				[],
				time()
			);

			wp_register_script(
				'iepa-admin-pointers-js',
				IEPA_URL . 'dist/iepa-admin-pointers.js',
				[ 'jquery' ],
				time(),
				true
			);

			// Add pointer options to script.
			$iepa_admin_pointers = array(
				'pointers' 					=>	$pointers,
				'IEPA_TEXT_DOMAIN'	=>	IEPA_TEXT_DOMAIN
			);
    	wp_localize_script( 'iepa-admin-pointers-js', 'iepa_admin_pointers', $iepa_admin_pointers );
			wp_enqueue_script( 'iepa-admin-pointers-js' );

  	}


}


new IEPA_Admin_Pointers();
