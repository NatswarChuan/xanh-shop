<?php

class IEPA_Pro {

	/** @var IEPA_Pro Instance */
	private static $_instance;
	/** @var string Content from tpl */
	private $matching_tpl;

	/**
	 * Returns instance of current calss
	 * @return IEPA_Pro Instance
	 */
	public static function instance() {
		if ( ! self::$_instance ) {
			self::$_instance = new self();
		}

		return self::$_instance;
	}

	public function __construct() {
		// add_filter( 'init', [ $this, 'init' ] );
		add_filter( 'add_meta_boxes', array( $this, 'add_meta_boxes' ) );
		add_action( 'save_post',      array( $this, 'save_iepa_meta' ) );
		// add_filter( 'woocommerce_taxonomy_objects_product_cat', [ $this, 'add_product_templates' ] );
		// add_filter( 'woocommerce_taxonomy_objects_product_tag', [ $this, 'add_product_templates' ] );
		// add_filter( 'iepa_templates', [ $this, 'templates' ] );
		// add_filter( 'enqueue_block_editor_assets', [ $this, 'iepa_inline_js' ] );
		// add_filter( 'wp_ajax_iepa_save_template', [ $this, 'ajax_save_template' ] );
		add_filter( 'wp_ajax_iepa_use_gt_editor', [ $this, 'iepa_use_gt_editor' ] );
		// add_filter( 'wp_ajax_iepa_get_saved_product_templates', [ $this, 'iepa_get_saved_product_templates' ] );
		// add_filter( 'wp_ajax_iepa_import_saved_single_product_template', [ $this, 'iepa_import_saved_single_product_template' ] );
		// add_filter( 'wp_head', [ $this, 'maybe_apply_template' ] );
		// add_filter( 'manage_iepa_template_posts_columns', [ $this, 'custom_columns' ] );
		// add_action( 'manage_iepa_template_posts_custom_column' , [ $this, 'custom_column_data' ], 10, 2 );
		// add_action( 'admin_print_styles' , [ $this, 'admin_styles' ], 10, 2 );
	}

	public function iepa_import_saved_single_product_template() {
		$single_iepa_builder_template = get_post( $_POST['post_id'] );
		if ( !$single_iepa_builder_template ) {
			wp_send_json( [
				 'status' =>	false,
				 'msg'		=>	'Template Not Found!'
				]
			);
			exit;
		}
		$post_content = $single_iepa_builder_template->post_content;
		wp_update_post( wp_slash( array(
	    'ID' 						=> $_POST['page_id'],
	    'post_content'	=> $post_content
		) ) );
		wp_send_json( [ 'status' => true ] );
	}

	public function iepa_get_saved_product_templates() {
		$template_posts = get_posts( [
			'numberposts'	=>	-1,
			'post_type'		=> 'iepa_template',
		] );
		wp_send_json( [ 'templates' => $template_posts ] );
	}

	public function iepa_use_gt_editor() {
		if ( $_POST['iepa_use_gt_editor'] === 'true' ) {
			update_post_meta( $_POST['post_id'], 'iepa_builder', "1" );
		} else {
			delete_post_meta( $_POST['post_id'], 'iepa_builder' );
		}
		wp_send_json( [ 'status' => true ] );
	}

	/**
	 * Admin styles to hide taxonomies under iepa templates post type.
	 */
	public function admin_styles() {
		?>
		<style>
			a.page-title-action[href*='post-new.php?post_type=iepa_template'],
			#adminmenu .wp-submenu a[href*="post_type=iepa_template"][href*="edit-tags.php?taxonomy=product_"] {
				display: none;
			}
            body.edit-php.post-type-iepa_template a.page-title-action {
                display: none;
            }
        </style>
		<?php
	}

	/**
	 * Adds custom columns on iepa templates post type.
	 * @param array $columns
	 * @return array
	 */
	public function custom_columns( $columns ) {
		$date = $columns['date'];

		unset( $columns['date'] );
		$columns['tpl-cats'] = 'Categories';
		$columns['tpl-tags'] = 'Tags';
		$columns['date'] = $date;
		return $columns;
	}

	/**
	 * Add content to custom columns on iepa templates post type columns.
	 * @param string $column
	 * @param int $post_id
	 */
	public function custom_column_data( $column, $post_id ) {
		$list_terms = null;

		switch ( $column ) {
			case 'tpl-cats' :
				$list_terms = get_the_terms( $post_id, 'product_cat' );
				break;
			case 'tpl-tags' :
				$list_terms = get_the_terms( $post_id, 'product_tag' );
				break;
		}

		if ( $list_terms && count( $list_terms ) ) {
			echo implode( ', ', wp_list_pluck( $list_terms, 'name' ) );
		}
	}

	/**
	 * Adds iepa templates to product cats and tags
	 * @param array $post_types
	 * @return array
	 */
	public function add_product_templates( $post_types ) {
		$post_types[] = 'iepa_template';

		return $post_types;
	}

	/**
	 * Registers IEPA template post type
	 */
	public function init() {
		register_post_type( 'iepa_template', [
			'public'       => false,
			'label'        => 'Product templates',
			'labels' => array(
				'name'               => __( 'Product templates', 'ibtana-ecommerce-product-addons' ),
				'singular_name'      => __( 'Product template', 'ibtana-ecommerce-product-addons' ),
				'menu_name'          => __( 'Product templates', 'ibtana-ecommerce-product-addons' ),
				'name_admin_bar'     => __( 'Product template', 'ibtana-ecommerce-product-addons' ),
				'add_new'            => __( 'Add New', 'ibtana-ecommerce-product-addons' ),
				'add_new_item'       => __( 'Add New Product template', 'ibtana-ecommerce-product-addons' ),
				'new_item'           => __( 'New Product template', 'ibtana-ecommerce-product-addons' ),
				'edit_item'          => __( 'Edit Product template', 'ibtana-ecommerce-product-addons' ),
				'view_item'          => __( 'View Product template', 'ibtana-ecommerce-product-addons' ),
				'all_items'          => __( 'All Templates', 'ibtana-ecommerce-product-addons' ),
				'search_items'       => __( 'Search Product templates', 'ibtana-ecommerce-product-addons' ),
				'parent_item_colon'  => __( 'Parent Product templates:', 'ibtana-ecommerce-product-addons' ),
				'not_found'          => __( 'No product templates found.', 'ibtana-ecommerce-product-addons' ),
				'not_found_in_trash' => __( 'No product templates found in Trash.', 'ibtana-ecommerce-product-addons' ),
			),
			'show_ui'      => true,
			'show_in_menu' => 'edit.php?post_type=product',
			'show_in_admin_bar' => false,
		] );
	}

	/**
	 * Adds metabox for iepa post type help
	 */
	public function add_meta_boxes() {

		if ( defined( 'IBTANA_LICENSE_API_ENDPOINT' ) ) {
			if ( 'product' === get_post_type() ) {
				add_meta_box(
					'iepa_product_template_metabox',
					'Ibtana Product Template',
					[ $this, 'iepa_render_product_meta_box' ],
					null,
					'side',
					'high'
				);
			}

			if( 'iepa_template' === get_post_type() ) {
				add_meta_box(
					'iepa_template_metabox',
					'Product template',
					[ $this, 'render_meta_box' ],
					null,
					'advanced',
					'low'
				);
			}
		}

	}

	function save_iepa_meta( $post_id ) {

		/*
		* We need to verify this came from the our screen and with proper authorization,
		* because save_post can be triggered at other times.
		*/

		// Check if our nonce is set.
		if ( ! isset( $_POST['iepa_inner_custom_box_nonce'] ) ) {
			return $post_id;
		}

		$nonce = $_POST['iepa_inner_custom_box_nonce'];

		// Verify that the nonce is valid.
		if ( ! wp_verify_nonce( $nonce, 'iepa_inner_custom_box' ) ) {
			return $post_id;
		}

		/*
		* If this is an autosave, our form has not been submitted,
		* so we don't want to do anything.
		*/
		if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
			return $post_id;
		}

		// Check the user's permissions.
		if ( 'product' == $_POST['post_type'] ) {
			if ( ! current_user_can( 'edit_post', $post_id ) ) {
				return $post_id;
			}
		}

		/* OK, it's safe for us to save the data now. */

		if ( !isset( $_POST['iepa_product_metabox_field'] ) ) {
			return $post_id;
		}

		// Update the meta field.
		update_post_meta( $post_id, 'iepa_builder', 1 );
	}

	public function iepa_render_product_meta_box( $post ) {

		$post_ID							=	$post->ID;

		$is_gt_editor_enabled = get_post_meta( $post->ID, 'iepa_builder', 'single' );
		$post_status					=	( 'auto-draft' != get_post_status() ) ? 1 : 0;

		$ibtana_ecommerce_product_addons_license_key = get_option( str_replace( '-', '_', get_plugin_data( IEPA_PLUGIN_FILE )['TextDomain'] ) . '_license_key' );
		$ibtana_ecommerce_product_addons_license_key_license_status = false;
		if ( isset( $ibtana_ecommerce_product_addons_license_key['license_status'] ) ) {
			if ( $ibtana_ecommerce_product_addons_license_key['license_status'] == true ) {
				$ibtana_ecommerce_product_addons_license_key_license_status = true;
			}
		}
		$IBTANA_LICENSE_API_ENDPOINT	=	defined( "IBTANA_LICENSE_API_ENDPOINT" ) ? IBTANA_LICENSE_API_ENDPOINT : false;

		$ive_add_ons_admin_url	=	admin_url( 'admin.php?page=ibtana-visual-editor-addons' );

		// Add an nonce field so we can check for it later.
		wp_nonce_field( 'iepa_inner_custom_box', 'iepa_inner_custom_box_nonce' );

		?>
		<input id="iepa_product_metabox" type="checkbox" name="iepa_product_metabox_field" disabled <?php if( $is_gt_editor_enabled == '1' ) { echo 'checked="checked"'; } ?>>
		<?php
		echo __( 'Use Gutenberg Editor', 'ibtana-ecommerce-product-addons' );
		if ( $post_status === 0 ) {
			?>
			<br><br>
			<p>
			<?php
			echo __(
				"You need to save the product first in order to use Gutenberg Editor. You can save as a draft with just a title if you want to make other changes later.",
				'ibtana-ecommerce-product-addons'
			);
			?>
			</p>
			<?php
		}

		if ( !$ibtana_ecommerce_product_addons_license_key_license_status ) {
			?>
				<p id="iepa_product_metabox_license" class="iepa_product_metabox_license">
					Get pre-built premium product page templates using <strong>Ibtana - Ecommerce Product Addons.</strong>
					<a class="button" href="<?php echo $ive_add_ons_admin_url; ?>" target="_blank">Upgrade To Pro!</a>
				</p>
			<?php
		}

		$admin_url = admin_url( 'admin-ajax.php?action=iepa_use_gt_editor' );
		$post_id = get_the_ID();


		?>
		<style media="screen">
			.iepa_is-busy {
				animation: components-button__busy-animation 2.5s linear infinite;
				opacity: 1;
				background-size: 100px 100%;
				background-image: linear-gradient(-45deg,#fafafa 33%,#e0e0e0 0,#e0e0e0 70%,#fafafa 0);
			}
			#iepa_product_metabox_license {
			    background: rgba(229,195,52,0.25);
			    padding: 10px 8px;
			    border-radius: 3px;
			    border: 1px solid #dadada;
					margin-top: 16px;
					text-align: center;
			}
			#iepa_product_metabox_license a {
				background: linear-gradient(#6ccef5, #016194) !important;
				color: #fff;
				text-transform: capitalize;
				font-weight: bold;
				text-align: center;
				margin: 4% auto 0;
				width: 60%;
				border-radius: 4px !important;
			}
		</style>
		<script type="text/javascript">
		(function ($, window, document) {
			'use strict';
			$(document).ready(function () {

				var $post_status = '<?php echo $post_status; ?>';
				var $IBTANA_LICENSE_API_ENDPOINT = '<?php echo $IBTANA_LICENSE_API_ENDPOINT; ?>';


				$( '#iepa_product_metabox' ).prop( 'disabled', false );

		    $( '#iepa_product_metabox' ).on('change', function () {

					if ( !parseInt( $post_status ) ) {
						// jQuery( this ).prop( 'checked', false );

						if ( !jQuery( '#post #title' ).val().trim() ) {
							jQuery( '#post #title' ).val( 'Ibtana Product Template' );
							jQuery( '#post #title' ).trigger( 'input' );
						}
						jQuery( '#post #save-post' ).trigger( 'click' );

						return;
					}

					$( '#iepa_product_template_metabox .inside' ).addClass( 'iepa_is-busy' );
					$( '#iepa_product_metabox' ).prop( 'disabled', true );

	        $.post( '<?php echo $admin_url; ?>' ,
						{
							post_id:	'<?php echo $post_id; ?>',
							iepa_use_gt_editor: document.querySelector('#iepa_product_metabox').checked
						}, function ( data ) {
							if ( data.status === true ) {
								location.reload( true );
							} else {
								$( '#iepa_product_metabox' ).prop( 'checked', !document.querySelector('#iepa_product_metabox').checked );
								$( '#iepa_product_metabox' ).prop( 'disabled', false );
								$( '#iepa_product_template_metabox .inside' ).removeClass( 'iepa_is-busy' );
							}
						}
	        );
		    });


				if ( jQuery( '#iepa_product_metabox_license' ).length && $IBTANA_LICENSE_API_ENDPOINT ) {
					var data_post = {
			      "admin_user_ibtana_license_key": '',
			      "domain": ''
			    };
			    jQuery.ajax({
			      method: "POST",
			      url: $IBTANA_LICENSE_API_ENDPOINT + "get_ibtana_visual_editor_defaults",
			      data: JSON.stringify( data_post ),
			      dataType: 'json',
			      contentType: 'application/json',
			    }).done(function(data) {
			      var get_pro_permalink = data.data.get_pro_permalink;
						jQuery( '.iepa_product_metabox_license a' ).attr( 'href', get_pro_permalink );
			    });
				}

			});
		}(jQuery, window, document));
		</script>
		<?php
	}

	/**
	 * Render post meta box for iepa templates
	 * @param WP_Post $post
	 */
	public function render_meta_box( $post ) {
		?>
		<style>
			#iepa_template_metabox h2.iepa-template-helptext {
				font-size: 18px;
				font-weight: 300;
				padding: 1em 0 0;
			}

			#iepa_template_metabox .iepa-template-links {
				color: inherit;
				text-decoration: none;
				border-bottom: 1px dotted;
			}

			#iepa_template_metabox h3 {
				margin: 1.6em 0 .5em;
			}

			#iepa_template_metabox ul {
				margin-top: 0;
			}
			#product_catdiv,
			#tagsdiv-product_tag {
				transition: all .5s;
			}
			#product_catdiv:target,
			#tagsdiv-product_tag:target {
				transform: scale( 1.06 );
				box-shadow: 1px 2px 5px rgba(0, 0, 0, 0.25);
			}
			.post-type-iepa_template #postdivrich {
				display: none;
			}
		</style>
		<h2 class="iepa-template-helptext">Select the
			<a class="iepa-template-links" href="#product_catdiv">Product Categories</a> and
			<a class="iepa-template-links" href="#tagsdiv-product_tag">Product Tags</a>
			you would like to apply this template to...</h2>

			<?php echo get_the_term_list(
				$post->ID, 'product_cat',
				'<h3>This template will apply to product with any of the following categories:</h3><ul class="ul-disc"><li>',
				'</li><li>',
				'</li></ul>'
			); ?>

			<?php echo get_the_term_list(
				$post->ID, 'product_tag',
				'<h3>This template will apply to product with any of the following tags:</h3><ul class="ul-disc"><li>',
				'</li><li>',
				'</li></ul>'
			); ?>
		<?php
	}

	/**
	 * AJAX handler to save templates
	 */
	public function ajax_save_template() {

		$title = $_POST['title'];
		$post_id = wp_insert_post( [
			'post_title'   => $title,
			'post_content' => $_POST['tpl'],
			'post_type'    => 'iepa_template',
			'post_status'  => 'publish',
		] );

		die( "Successfully saved template '$title'." );
	}

	private $template_weight = [
		'product_cat' => 2,
	];

	/**
	 * Gets templates matching specified taxonomy terms for current post
	 * @param string $taxonomy
	 * @param array $templates
	 * @param array $tpl_html
	 */
	private function get_templates( $taxonomy, &$templates, &$tpl_html ) {
		$terms = get_the_terms( get_the_ID(), $taxonomy );

		if ( $terms ) {
			$terms = wp_list_pluck( $terms, 'term_id' );

			$tpl_matched = get_posts( [
				'post_type' => 'iepa_template',
				'tax_query' => [
					[
						'terms'    => $terms,
						'taxonomy' => $taxonomy,
					],
					'relation' => 'OR',
				],
				'orderby'  => 'ID',
				'order'    => 'desc',
			] );

			if ( $tpl_matched ) {
				foreach ( $tpl_matched as $p ) {
					$tpl_html[ $p->ID ] = $p->post_content;
					if ( ! isset( $templates[ $p->ID ] ) ) {
						$templates[ $p->ID ] = 0;
					}
					$templates[ $p->ID ] += isset( $this->template_weight[ $taxonomy ] ) ? $this->template_weight[$taxonomy] : 1;
				}
			}
		}
	}

	/**
	 * @return mixed|string HTML for matched template
	 */
	public function get_matching_template() {
		$tpl_html = $templates = [];

		$this->get_templates( 'product_cat', $templates, $tpl_html );

		$this->get_templates( 'product_tag', $templates, $tpl_html );
		arsort( $templates, SORT_NUMERIC );

		if ( ! $templates ) {
			return '';
		}
		foreach ( $templates as $tpl_id => $score ) {
			return $tpl_html[ $tpl_id ];
		}
	}

	/**
	 * Applies template when match found.
	 */
	public function maybe_apply_template() {
		if ( is_product() && ! IEPA_Blocks::enabled() ) {
			$this->matching_tpl = $this->get_matching_template();
			if ( $this->matching_tpl ) {
				remove_action( 'iepa_render_product', 'the_content' );
				add_action( 'iepa_render_product', [ $this, 'render_template' ] );
				add_action( 'iepa_render_product', [ wc()->structured_data, 'generate_product_data' ] );

				add_filter( 'wc_get_template_part', [ IEPA_Blocks::instance()->public, 'wc_get_template_part' ], 1001, 3 );
				add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ], 101, 3 );
			}
		}
	}

	public function product_tabs( $tabs ) {
		$tabs['description'] = array(
			'title'    => __( 'Description', 'woocommerce' ),
			'priority' => 10,
			'callback' => 'woocommerce_product_description_tab',
		);

		return $tabs;
	}

	/**
	 * Adds hook to render the template
	 */
	public function render_template() {
		echo apply_filters( 'the_content', apply_filters( 'get_the_content', $this->matching_tpl ) );
	}

	// public function iepa_inline_js( $vars ) {
	// 	$url = admin_url( 'admin-ajax.php?action=iepa_save_template' );
	// 	wp_add_inline_script( 'iepa-blocks-js', <<<JS
	// 		jQuery( function() {
	// 			wp.plugins.registerPlugin( 'iepa-pro', {
	// 				render: function() {
	// 					var el = wp.element.createElement;
	// 					return el(
	// 						wp.editPost.PluginPostStatusInfo,
	// 						{
	// 							className: 'iepa-save-template'
	// 						},
	// 						el(
	// 							'a',
	// 							{
	// 								id: 'iepa-save-template-btn',
	// 								className: 'button-link-delete components-button editor-post-trash is-button is-default is-large',
	// 								style    : {
	// 									color: '#0073aa'
	// 								},
	// 								onClick  : function () {
	// 									var name = prompt( 'What would you like to call this template?' );
	// 									if ( name ) {
	// 										document.getElementById( 'iepa-save-template-btn' ).classList.add( 'is-busy' );
	// 										jQuery.post(
	// 											'$url', {
	// 												title: name,
	// 												tpl: wp.data.select( "core/editor" ).getEditedPostContent(),
	// 											}, function( resp ) {
	// 												alert( resp );
	// 												document.getElementById( 'iepa-save-template-btn' ).classList.remove( 'is-busy' );
	// 											}
	// 										);
	// 									}
	// 								},
	// 							},
	// 							'Save as template'
	// 						),
	// 					)
	// 				},
	// 			} );
	// 		} );
	// 		JS
	// 	 );
	// }

	public function templates( $templates ) {

		$template_posts = get_posts( [
			'post_type' => 'iepa_template',
		] );

		/** @var WP_Post $tpl_post */
		foreach ( $template_posts as $tpl_post ) {
			$templates[ $tpl_post->ID ] = [
				'title' => $tpl_post->post_title,
				'tpl' => $tpl_post->post_content,
			];
		}

		return $templates;
	}

}

IEPA_Pro::instance();
