<?php

class IEPA_Blocks
{
    private static  $iepa_blocks_instance = null ;
    /** @var string Token */
    public static  $iepa_token ;
    /** @var string Version */
    public static  $iepa_version ;
    /** @var string Plugin main __FILE__ */
    public static  $iepa_file ;
    /** @var string Plugin directory url */
    public static  $iepa_url ;
    /** @var string Plugin directory path */
    public static  $iepa_path ;
    public  $iepa_admin ;
    public  $iepa_public ;
    private  $iepa_templates = array() ;
  	protected $iepa_gallery_image_size;
    private function __construct( $iepa_file ) {
        self::$iepa_token = 'ibtanaecommerceproductaddons-blocks';
        self::$iepa_file = $iepa_file;
        self::$iepa_url = plugin_dir_url( $iepa_file );
        self::$iepa_path = plugin_dir_path( $iepa_file );
        self::$iepa_version = '3.6.0';
        add_action( 'plugins_loaded', [ $this, 'iepa_init' ] );
    }

    public static function iepa_blocks_instance( $iepa_file = '' ) {
      if ( null == self::$iepa_blocks_instance ) {
          self::$iepa_blocks_instance = new self( $iepa_file );
      }
      return self::$iepa_blocks_instance;
    }

    public function iepa_init() {
      $this->iepa_admin();
      $this->iepa_public();
    }

    private function iepa_admin() {
      //Instantiating admin class
      $this->iepa_admin = IEPA_Blocks_Admin::instance();
      add_filter(
        'gutenberg_can_edit_post_type',
        [ $this->iepa_admin, 'enable_gutenberg_products' ],
        11,
        2
      );
      add_filter(
        'use_block_editor_for_post_type',
        [ $this->iepa_admin, 'enable_gutenberg_products' ],
        11,
        2
      );
      add_action( 'enqueue_block_editor_assets', array( $this->iepa_admin, 'enqueue' ), 7 );
    }

    private function iepa_public() {
      if ( ! has_action( 'woocommerce_simple_add_to_cart' ) ) {
        add_action( 'woocommerce_single_product_summary', 'woocommerce_template_single_add_to_cart', 30 );
        add_action( 'woocommerce_simple_add_to_cart', 'woocommerce_simple_add_to_cart', 30 );
        add_action( 'woocommerce_grouped_add_to_cart', 'woocommerce_grouped_add_to_cart', 30 );
        add_action( 'woocommerce_variable_add_to_cart', 'woocommerce_variable_add_to_cart', 30 );
        add_action( 'woocommerce_external_add_to_cart', 'woocommerce_external_add_to_cart', 30 );
        add_action( 'woocommerce_single_variation', 'woocommerce_single_variation', 10 );
        add_action( 'woocommerce_single_variation', 'woocommerce_single_variation_add_to_cart_button', 20 );
      }

      add_action( 'init', array( $this, 'iepa_setup_product_render' ) );
      add_action( 'init', array( $this, 'iepa_register_blocks' ) );
      add_action( 'wp_head', array( $this, 'maybe_setup_iepa_product' ) );
    }

    public static function template_id( $product_id = 0 ) {
      if ( !$product_id ) {
        $product_id = get_the_ID();
      }
      return get_post_meta( $product_id, 'iepa_builder', 'single' );
    }

    public static function enabled( $product_id = 0 ) {
        return !!self::template_id( $product_id );
    }

    public function iepa_setup_product_render() {
        add_action( 'iepa_render_product', 'the_content' );
        if ( class_exists( 'WooCommerce' ) ) {
          add_action( 'iepa_render_product', [ wc()->structured_data, 'generate_product_data' ] );
        }
    }

    public function iepa_maybe_apply_template() {
      $this->iepa_matching_tpl = $this->iepa_get_matching_template();
      remove_action( 'iepa_render_product', 'the_content' );
      add_action( 'iepa_render_product', [ $this, 'iepa_render_template' ] );
      add_action( 'iepa_render_product', [ wc()->structured_data, 'generate_product_data' ] );
      add_filter( 'wc_get_template_part', [ $this->iepa_public, 'wc_get_template_part' ], 1001, 3 );
      //add_filter( 'woocommerce_product_tabs', [ $this, 'product_tabs' ], 101, 3 );
    }

    public function iepa_render_template() {
      echo apply_filters( 'the_content', apply_filters( 'get_the_content', $this->iepa_matching_tpl ) );
    }

  public function iepa_get_matching_template() {
		$iepa_tpl_html = $iepa_templates = [];

		$this->iepa_get_templates( 'product_cat', $iepa_templates, $iepa_tpl_html );

		$this->iepa_get_templates( 'product_tag', $iepa_templates, $iepa_tpl_html );
		arsort( $iepa_templates, SORT_NUMERIC );

		if ( ! $iepa_templates ) {
			return '';
		}
		foreach ( $iepa_templates as $tpl_id => $score ) {
			return $iepa_tpl_html[ $tpl_id ];
		}
	}

  public function iepa_get_templates( $iepa_taxonomy, &$iepa_templates, &$iepa_tpl_html ) {
    $iepa_terms = get_the_terms( get_the_ID(), $iepa_taxonomy );

    if ( $iepa_terms ) {
      $iepa_terms = wp_list_pluck( $iepa_terms, 'term_id' );

      $iepa_tpl_matched = get_posts( [
        'post_type' => 'iepa_template',
        'tax_query' => [
          [
            'terms'    => $iepa_terms,
            'taxonomy' => $iepa_taxonomy,
          ],
          'relation' => 'OR',
        ],
        'orderby'  => 'ID',
        'order'    => 'desc',
      ] );

      if ( $iepa_tpl_matched ) {
        foreach ( $iepa_tpl_matched as $p ) {
          $iepa_tpl_html[ $p->ID ] = $p->post_content;
          if ( ! isset( $iepa_templates[ $p->ID ] ) ) {
            $iepa_templates[ $p->ID ] = 0;
          }
          $iepa_templates[ $p->ID ] += isset( $this->template_weight[ $iepa_taxonomy ] ) ? $this->template_weight[$iepa_taxonomy] : 1;
        }
      }
    }
  }

  public function maybe_setup_iepa_product() {
    if ( IEPA_Blocks::enabled() ) {
      if ( function_exists( 'gencwooc_single_product_loop' ) && has_action( 'genesis_loop', 'gencwooc_single_product_loop' ) ) {
        remove_action( 'genesis_loop', 'gencwooc_single_product_loop' );
        add_action( 'genesis_loop', [ $this, 'iepa_gencwooc_single_product_template' ] );
      }
      // Priority more than storefront pro 999
      add_filter( 'wc_get_template_part', array( $this, 'wc_get_template_part' ), 1001, 3 );
    }
  }

  public function iepa_gencwooc_single_product_template() {
    /**
     * woocommerce_before_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper - 10 (outputs opening divs for the content)
     * @hooked woocommerce_breadcrumb - 20
     */
    do_action( 'woocommerce_before_main_content' );
    ?>

    <?php while ( have_posts() ) : ?>
      <?php the_post(); ?>

      <?php wc_get_template_part( 'content', 'single-product' ); ?>

    <?php endwhile; // end of the loop. ?>

    <?php
    /**
     * woocommerce_after_main_content hook.
     *
     * @hooked woocommerce_output_content_wrapper_end - 10 (outputs closing divs for the content)
     */
    do_action( 'woocommerce_after_main_content' );
  }

  /**
   * Adds front end stylesheet and js
   * @action wp_enqueue_scripts
   * @since 1.0.0
   */
  public function wc_get_template_part( $template, $slug, $name ) {

    if (
      'content' == $slug &&
      'single-product' == $name
    ) {
      return dirname( __FILE__ ) . '/iepa-single-product.php';
    }

    return $template;
  }

  public function iepa_blocks() {
    return [
      'iepa-add-to-cart'            =>  [
        'attributes'=>[
          'uniqueID' => [
            'default' => '',
            'type'    => 'string',
          ],
          'fontSizeType' => [
            'type'    =>  'string',
            'default' =>  'px',
          ],
          'fontSize' => [
            'type'    => 'array',
            'default' => [ 12, 12, 12 ],
          ],
          'fontFamily' => [
            'type'    => 'string',
            'default' => '',
          ],
          'googleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'loadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'fontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'fontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'fontStyle'  => [
            'type'    =>  'string',
            'default' =>  'normal'
          ],
          'fontSubset'  =>  [
            'type'    =>  'string',
            'default' =>  ''
          ],
          'textTransform' =>  [
            'type'    =>  'string',
            'default' =>  ''
          ],
          'quantityTextColor'   =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ],
          'quantityBackgroundColor' =>  [
            'type'    =>  'string',
            'default' =>  '#ffffff'
          ],
          'quantityBorderSize'  =>  [
            'type'    =>  'number',
            'default' =>  0
          ],
          'quantityBorderRadius'  =>  [
            'type'    =>  'number',
            'default' =>  0
          ],
          'quantityBorderColor' =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ],
          'buttonBorderRadius' => [
            'type' => 'number',
            'default' => 5,
          ],
          'buttonBorderSize' => [
            'type' => 'number',
            'default' => 0,
          ],
          'cartBorderColor' => [
            'type' => 'string',
            'default' => '#000000',
          ],
          'cartTextColor'=>[
            'type' => 'string',
            'default' => '#000000'
          ],
          'btnBgColor'      =>  [
            'type'    =>  'string',
            'default' =>  '#28303d'
          ],
          'btnBgHoverColor' =>  [
            'type'    =>  'string',
            'default' =>  '#28303d'
          ],
          'gradientEnable'  =>  [
            'type'    =>  'boolean',
            'default' =>  false
          ],
          'bgfirstcolorr' => [
            'type' => 'string',
            'default' => '#00B5E2'
          ],
          'bgGradLoc' => [
            'type' => 'number',
            'default' => 0
          ],
          'bgSecondColr' => [
            'type' => 'string',
            'default' => '#00B5E2'
          ],
          'bgGradLocSecond' => [
            'type' => 'number',
            'default' => 100
          ],
          'bgGradType' =>  [
            'type' => 'string',
            'default' => 'linear'
          ],
          'bgGradAngle' =>  [
            'type' => 'number',
            'default' => 180
          ],
          'vBgImgPosition' =>  [
            'type' => 'string',
            'default' => 'center center'
          ],
          'hovGradFirstColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'hovGradSecondColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'buttonBorderRadius' => [
            'type' => 'number',
            'default' => 5,
          ],
        ]
      ],
      'iepa-product-price'          =>  [
        'attributes'  =>  [
          'uniqueID' => [
            'default' => '',
            'type'    => 'string',
          ],
          "hideRegularPrice"  =>  [
            'type'    =>    'boolean',
            'default' =>  false
          ],
          'fontSizeType' => [
            'type'    =>  'string',
            'default' =>  'px',
          ],
          'fontSize' => [
            'type'    => 'array',
            'default' => [ 12, 12, 12 ],
          ],
          'fontFamily' => [
            'type'    => 'string',
            'default' => '',
          ],
          'googleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'loadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'fontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'fontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'fontStyle'  => [
            'type'    =>  'string',
            'default' =>  'normal'
          ],
          'fontSubset'  =>  [
            'type'    =>  'string',
            'default' =>  ''
          ],
          'regularPriceFontColor' =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ],
          'regularPriceHoverColor'  =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ],
          'regularPriceBtnRightPadding' =>  [
            'type'    =>  'array',
            'default' =>  [ 0, 0, 0 ]
          ],
          'salePriceFontColor'  =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ],
          'salePriceHoverColor' =>  [
            'type'    =>  'string',
            'default' =>  '#000000'
          ]
        ]
      ],
      'iepa-product-images'         =>  [
        'attributes'  =>  [
          'uniqueID' => [
            'default' => '',
            'type'    => 'string',
          ],
          'galleryPosition' =>  [
            'type'    =>  'string',
            'default' =>  'horizontal'
          ],
          'sliderArrow' =>  [
            'type'    =>  'boolean',
            'default' =>  true
          ],
          'sliderGallery' =>  [
            'type'    =>  'boolean',
            'default' =>  false
          ],
          'lightbox' =>  [
            'type'    =>  'boolean',
            'default' =>  true
          ],
          'autoplay' =>  [
            'type'    =>  'boolean',
            'default' =>  false
          ],
          'loop' =>  [
            'type'    =>  'boolean',
            'default' =>  true
          ],
          'zoom' =>  [
            'type'    =>  'boolean',
            'default' =>  true
          ],
          'arrowColor' =>  [
            'type'    =>  'string',
            'default' =>  ''
          ],
          'arrowBgColor' =>  [
            'type'    =>  'string',
            'default' =>  ''
          ]
        ]
      ],
      'iepa-product-review'         =>  [
        'attributes'=>[
          'uniqueID' => [
            'type'    => 'string',
            'default' => '',
          ],
          'fontSize' => [
            'type'    => 'number',
            'default' => 16,
          ],
          'reviewFontSize' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'textTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'letterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'typography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'googleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'loadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'fontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'fontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'fontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'fontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'gradientDisable' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'bgfirstcolorr' => [
            'type' => 'string',
            'default' => '#00B5E2'
          ],
          'bgGradLoc' => [
            'type' => 'number',
            'default' => 0
          ],
          'bgSecondColr' => [
            'type' => 'string',
            'default' => '#00B5E2'
          ],
          'bgGradLocSecond' => [
            'type' => 'number',
            'default' => 100
          ],
          'bgGradType' =>  [
            'type' => 'string',
            'default' => 'linear'
          ],
          'bgGradAngle' =>  [
            'type' => 'number',
            'default' => 180
          ],
          'vBgImgPosition' =>  [
            'type' => 'string',
            'default' => 'center center'
          ],
          'hovGradFirstColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'hovGradSecondColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'colorReview' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'colorReviewHov' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'colorReviewUnfilled' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'colorHovUnfilled' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'colorTextReview' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'activeGradColor1' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'bggradColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'hoverbggradColor' =>  [
            'type' => 'string',
            'default' => ''
          ],
          'activeGradColor2' =>  [
            'type' => 'string',
            'default' => ''
          ],
        ]
      ],
      'iepa-product-reviews'        =>  [
        'attributes'=>[
          'uniqueID' => [
            'type'    => 'string',
            'default' => '',
          ],
          'descFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'desctextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'descletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'desctypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'descgoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'descloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'descfontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'descfontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'descfontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'descfontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'authFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'authtextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'authletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'authtypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'authgoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'authloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'authfontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'authfontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'authfontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'authfontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'dateFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'datetextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'dateletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'datetypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'dategoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'dateloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'datefontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'datefontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'datefontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'datefontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'descColor' => [
            'type'    => 'string',
            'default' => '#000'
          ],
          'authColor' => [
            'type'    => 'string',
            'default' => '#000'
          ],
          'dateColor' => [
            'type'    => 'string',
            'default' => '#000'
          ],
          'descVisibility' => [
            'type'    => 'array',
            'default' => ['true','true','true']
          ],
          'authVisibility' => [
            'type'    => 'array',
            'default' => ['true','true','true']
          ],
          'dateVisibility' => [
            'type'    => 'array',
            'default' => ['true','true','true']
          ],
          'imgVisibility' => [
            'type'    => 'array',
            'default' => ['true','true','true']
          ]
        ]
      ],
      'iepa-product-meta'           =>  [
        'attributes'=>[
          'uniqueID' => [
            'type'    => 'string',
            'default' => ''
          ],
          'skuvisible' => [
            'type'    => 'boolean',
            'default' => true
          ],
          'tagsvisible' => [
            'type'    => 'boolean',
            'default' => true
          ],
          'catvisible' => [
            'type'    => 'boolean',
            'default' => true
          ],
          'sharevisible' => [
            'type'    => 'boolean',
            'default' => true
          ],
          'metaAlignment' => [
            'type'    => 'string',
            'default' => 'left'
          ],
          'skuFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'skuColor' => [
            'type'    => 'string',
            'default' => '',
          ],
          'skutextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'skuletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'skutypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'skugoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'skuloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'skufontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'skufontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'skufontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'skufontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'tagFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'tagColor' => [
            'type'    => 'string',
            'default' => '',
          ],
          'tagtextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'tagletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'tagtypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'taggoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'tagloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'tagfontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'tagfontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'tagfontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'tagfontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'catFontSize' => [
            'type'    => 'array',
            'default' => [ 18, 16, 14 ],
          ],
          'catColor' => [
            'type'    => 'string',
            'default' => '',
          ],
          'cattextTransform' => [
            'type'    => 'string',
            'default' => '',
          ],
          'catletterSpacing' => [
            'type'    => 'number',
            'default' => 1,
          ],
          'cattypography' => [
            'type'    => 'string',
            'default' => '',
          ],
          'catgoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'catloadGoogleFont' => [
            'type'    => 'boolean',
            'default' => false,
          ],
          'catfontSubset' => [
            'type'    => 'string',
            'default' => '',
          ],
          'catfontVariant' => [
            'type'    => 'string',
            'default' => '',
          ],
          'catfontWeight' => [
            'type'    => 'string',
            'default' => '400',
          ],
          'catfontStyle' => [
            'type'    => 'string',
            'default' => 'normal',
          ],
          'sharearr' => [
            'type'    => 'array',
            'default' => array(
              array(
                'icon' => 'fab fa-facebook',
                'name' => 'Facebook',
                'visible' => true,
                'link' => 'https://www.facebook.com/sharer.php?u=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-twitter-square',
                'name' => 'Twitter',
                'visible' => true,
                'link' => 'https://twitter.com/share?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-google-plus-square',
                'name' => 'Google Plus',
                'visible' => false,
                'link' => 'https://plus.google.com/share?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-linkedin',
                'name' => 'Linkedin',
                'visible' => true,
                'link' => 'https://www.linkedin.com/shareArticle?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-digg',
                'name' => 'Digg',
                'visible' => false,
                'link' => 'http://digg.com/submit?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-blogger',
                'name' => 'Blogger',
                'visible' => false,
                'link' => 'https://www.blogger.com/blog_this.pyra?t&amp;u=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-reddit-square',
                'name' => 'Reddit',
                'visible' => false,
                'link' => 'https://reddit.com/submit?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-stumbleupon-circle',
                'name' => 'Stumbleupon',
                'visible' => false,
                'link' => 'https://www.stumbleupon.com/submit?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-tumblr-square',
                'name' => 'Tumblr',
                'visible' => false,
                'link' => 'https://www.tumblr.com/widgets/share/tool?canonicalUrl=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fas fa-envelope',
                'name' => 'Mail',
                'visible' => true,
                'link' => 'mailto:?body',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-pinterest-square',
                'name' => 'Pinterest',
                'visible' => false,
                'link' => 'https://pinterest.com/pin/create/link/?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-vk',
                'name' => 'VK',
                'visible' => false,
                'link' => 'https://vkontakte.ru/share.php?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-odnoklassniki-square',
                'name' => 'Odnoklassniki',
                'visible' => false,
                'link' => 'https://connect.ok.ru/offer?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-get-pocket',
                'name' => 'Pocket',
                'visible' => false,
                'link' => 'https://getpocket.com/edit?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-whatsapp-square',
                'name' => 'Whatsapp',
                'visible' => false,
                'link' => 'https://api.whatsapp.com/send?text=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-xing-square',
                'name' => 'Xing',
                'visible' => false,
                'link' => 'https://www.xing.com/app/user?op=share&url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-telegram',
                'name' => 'Telegram',
                'visible' => false,
                'link' => 'https://telegram.me/share/url?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-skype',
                'name' => 'Skype',
                'visible' => false,
                'link' => 'https://web.skype.com/share?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              ),
              array(
                'icon' => 'fab fa-buffer',
                'name' => 'Buffer',
                'visible' => false,
                'link' => 'https://buffer.com/add?url=',
                'target'=> '_self',
                'desksize'=> 50,
                'tabsize'=> 35,
                'mobsize'=> 20,
                'deskwidth'=> 'auto',
                'deskheight'=> 'auto',
                'tabwidth'=> 'auto',
                'tabheight'=> 'auto',
                'mobwidth'=> 'auto',
                'mobheight'=> 'auto',
                'color'=> '#444444',
                'hoverColor'=> '#eeeeee',
                'background'=> '#ffffff',
                'hoverBackground'=> '#000000',
                'border'=> '#444444',
                'hoverBorder'=> '#FF0000',
                'borderRadius'=> 0,
                'borderWidth'=> 2,
                'borderStyle'=> 'none',
                'deskpadding'=> 20,
                'tabpadding'=> 16,
                'mobpadding'=> 12,
                'deskpadding2'=> 20,
                'tabpadding2'=> 16,
                'mobpadding2'=> 12,
                'style'=> 'default',
                'iconGrad'=> false,
                'gradFirstColor'=> '',
                'gradFirstLoc'=> 0,
                'gradSecondColor'=> '#00B5E2',
                'gradSecondLoc'=> '100',
                'gradType'=> 'linear',
                'gradAngle'=> '180',
                'gradRadPos'=> 'center center',
                'hovGradFirstColor'=> '',
                'hovGradSecondColor'=> '',
              )
            )
          ]
        ]
      ],
      'iepa-product-sale-countdown' =>  [
        'attributes'  =>  [
          'uniqueID'  =>  [
            'type'    =>  'string',
            'default' =>  ''
          ],
        ]
      ]
    ];
  }

  public function iepa_register_blocks() {
    $iepa_blocks  = $this->iepa_blocks();

    foreach ( $iepa_blocks as $key => $block ) {

       register_block_type(
        str_replace( '_', '-', 'iepa/' . $key ),
        array(
        'category'        =>  esc_html__( 'Ibtana Blocks', 'ibtana-ecommerce-product-addons' ),
        'attributes'      =>  $block['attributes'],
        'apiVersion'      =>  2,
        'render_callback' =>  array( $this, 'render_' . str_replace( '-', '_', $key ) ),
        )
      );
    }

  }

  public function render_iepa_add_to_cart( $prop ) {
    $attributes = $prop;

    $className = isset( $attributes['className'] ) ? $attributes['className'] : '';


    // $fontSizeType   = isset( $attributes['fontSizeType'] ) ? $attributes['fontSizeType'] : 'px';
    // $fontSizeDesk   = isset( $attributes['fontSize'] ) ? $attributes['fontSize'][0] . $fontSizeType : '12px';
    // $fontSizeTab    = isset( $attributes['fontSize'] ) ? $attributes['fontSize'][1] . $fontSizeType : '12px';
    // $fontSizeMob    = isset( $attributes['fontSize'] ) ? $attributes['fontSize'][2] . $fontSizeType : '12px';

    // $radialBtnGrad = 'radial-gradient(at '.$attributes['vBgImgPosition'].' }, '.$attributes['bgfirstcolorr'].' '.$attributes['bgGradLoc'].'%, '.$attributes['bgSecondColr'].' '.$attributes['bgGradLocSecond'].'%) !important;';
    // $linearBtnGrad = 'linear-gradient('.$attributes['bgGradAngle'].'deg, '.$attributes['bgfirstcolorr'].' '.$attributes['bgGradLoc'].'%, '.$attributes['bgSecondColr'].' '.$attributes['bgGradLocSecond'].'%) !important;';
    // $gradientColor = $attributes['bgGradType'] === 'radial' ? $radialBtnGrad : $linearBtnGrad;

    global $product;
    if ( ! $product ) return '';

    ob_start();
    echo '<div class="iepa_add_to_cart'.$prop['uniqueID'].' '.$className.'">';
    woocommerce_template_single_add_to_cart();
    echo '</div>';
    return ob_get_clean();
  }

	public function iepa_enable_rest_taxonomy( $args ) {
		$args['show_in_rest'] = true;

		return $args;
	}

	private function iepa_openWrap( $props, $class, $tag = 'div', $style = '' ) {

		if ( ! empty( $props['className'] ) ) {
			$class .= " $props[className]";
		}

		if ( ! empty( $props['text_align'] ) ) {
			$style .= "text-align:{$props['text_align']};";
		}

		if ( ! empty( $props['font_size'] ) ) {
			$style .= "font-size:{$props['font_size']}px;";
		}
		if ( ! empty( $props['font'] ) ) {
			$props['font'] = stripslashes( $props['font'] );
			$style         .= "font-family:{$props['font']};";
		}
		if ( ! empty( $props['text_color'] ) ) {
			$style .= "color:{$props['text_color']};";
		}
		if ( ! empty( $props['iepa_style'] ) ) {
			$class .= " ibtanaecommerceproductaddons-style-$props[iepa_style]";
		}

		if ( $style ) {
			$style = 'style="' . $style . '"';
		}

		return "<$tag class='ibtanaecommerceproductaddons-block ibtanaecommerceproductaddons-$class' $style>";
	}

	public function render_iepa_title( $props ) {
		ob_start();

		return $this->iepa_openWrap( $props, 'title entry-title', 'h1' ) . get_the_title() . '</h1>';
	}

	public function render_iepa_product_review( $props ) {

		global $product;

		if ( ! $product ) return '';

    $className            = isset($props['className']) ? $props['className'] : '';
    $fontSize             = isset($props['fontSize']) ? $props['fontSize'].'px' : '16px';
    $reviewFontSize       = isset($props['reviewFontSize']) ? $props['reviewFontSize'].'em' : '1em';
    $letterSpacing        = isset($props['letterSpacing']) ? $props['letterSpacing'].'px' : '0px';
    $textTransform        = isset($props['textTransform']) ? $props['textTransform'] : '';
    $textColor            = isset($props['colorTextReview']) ? $props['colorTextReview'] : '';

    $typography           = (isset($props['typography']) && $props['typography'] !== '') ? $props['typography'] : 'Open+Sans';
    $tyochange            = str_replace(" ","+",$typography);
    $fontWeight           = isset($props['fontWeight']) ? $props['fontWeight'] : 400;
    $fontStyle            = isset($props['fontStyle']) ? $props['fontStyle'] : 'normal';

    $colorReview          = isset($props['colorReview']) ? $props['colorReview'] : '';
    $colorReviewHov       = isset($props['colorReviewHov']) ? $props['colorReviewHov'] : '';
    $colorReviewUnfilled  = isset($props['colorReviewUnfilled']) ? $props['colorReviewUnfilled'] : '';
    $colorHovUnfilled     = isset($props['colorHovUnfilled']) ? $props['colorHovUnfilled'] : '';
    $bgfirstcolorr        = isset($props['bgfirstcolorr']) ? $props['bgfirstcolorr'] : '';
    $bgSecondColr         = isset($props['bgSecondColr']) ? $props['bgSecondColr'] : '';
    $vBgImgPosition       = isset($props['vBgImgPosition']) ? $props['vBgImgPosition'] : '';
    $bgGradType           = isset($props['bgGradType']) ? $props['bgGradType'] : '';
    $bgGradAngle          = isset($props['bgGradAngle']) ? $props['bgGradAngle'] : '';
    $bgGradLocSecond      = isset($props['bgGradLocSecond']) ? $props['bgGradLocSecond'] : '';
    $bgGradLoc            = isset($props['bgGradLoc']) ? $props['bgGradLoc'] : '';
    $hovGradSecondColor   = isset($props['hovGradSecondColor']) ? $props['hovGradSecondColor'] : '';
    $hovGradFirstColor    = isset($props['hovGradFirstColor']) ? $props['hovGradFirstColor'] : '';
    $activeGradColor1     = isset($props['activeGradColor1']) ? $props['activeGradColor1'] : '';
    $activeGradColor2     = isset($props['activeGradColor2']) ? $props['activeGradColor2'] : '';
    $bggradColor          = isset($props['bggradColor']) ? $props['bggradColor'] : '';
    $hoverbggradColor     = isset($props['hoverbggradColor']) ? $props['hoverbggradColor'] : '';

    $radialFilledGrad = 'radial-gradient(at '.$vBgImgPosition.' }, '.$bgfirstcolorr.' '.$bgGradLoc.'%, '.$bgSecondColr.' '.$bgGradLocSecond.'%) !important';
    $linearFilledGrad = 'linear-gradient('.$bgGradAngle.'deg, '.$bgfirstcolorr.' '.$bgGradLoc.'%, '.$bgSecondColr.' '.$bgGradLocSecond.'%) !important';
    $gradFilledColor = $bgGradType === 'radial' ? $radialFilledGrad : $linearFilledGrad;
    $filledGradColor = $props['gradientDisable'] ? $gradFilledColor : 'unset !important';

    $radialUnfilledGrad = 'radial-gradient(at '.$vBgImgPosition.' }, '.$activeGradColor1.' '.$bgGradLoc.'%, '.$activeGradColor2.' '.$bgGradLocSecond.'%) !important';
    $linearUnfilledGrad = 'linear-gradient('.$bgGradAngle.'deg, '.$activeGradColor1.' '.$bgGradLoc.'%, '.$activeGradColor2.' '.$bgGradLocSecond.'%) !important';
    $gradUnfilledColor = $bgGradType === 'radial' ? $radialUnfilledGrad : $linearUnfilledGrad;
    $unfilledGradColor = $props['gradientDisable'] ? $gradUnfilledColor : 'unset !important';

    $transparent = '';
    if ($props['gradientDisable']) {
      $transparent = '-webkit-text-fill-color: transparent;-webkit-background-clip: text;';
    }

    $radialFilledGradHov = 'radial-gradient(at '.$vBgImgPosition.' }, '.$hovGradFirstColor.' '.$bgGradLoc.'%, '.$hovGradSecondColor.' '.$bgGradLocSecond.'%) !important';
    $linearFilledGradHov = 'linear-gradient('.$bgGradAngle.'deg, '.$hovGradFirstColor.' '.$bgGradLoc.'%, '.$hovGradSecondColor.' '.$bgGradLocSecond.'%) !important';
    $gradFilledColorHov = $props['bgGradType'] === 'radial' ? $radialFilledGradHov : $linearFilledGradHov;
    $filledGradHovColor = $props['gradientDisable'] ? $gradFilledColorHov : 'unset !important';

    $radunfilledGradHov = 'radial-gradient(at '.$vBgImgPosition.' }, '.$activeGradColor1.' '.$bgGradLoc.'%, '.$activeGradColor2.' '.$bgGradLocSecond.'%) !important';
    $linearunfilledGradHov = 'linear-gradient('.$bgGradAngle.'deg, '.$activeGradColor1.' '.$bgGradLoc.'%, '.$activeGradColor2.' '.$bgGradLocSecond.'%) !important';
    $gradUnfilledColorHov = $props['bgGradType'] === 'radial' ? $radunfilledGradHov : $linearunfilledGradHov;
    $unfilledGradHovColor = $props['gradientDisable'] ? $gradUnfilledColorHov : 'unset !important';

		ob_start();
		echo $this->iepa_openWrap( $props, 'rating' );
		$rating_count = $product->get_rating_count();
		$review_count = $product->get_review_count();
		$average      = $product->get_average_rating();
    // echo "<style>
    // @import url(https://fonts.googleapis.com/css2?family=$tyochange:wght@$fontWeight&display=swap);
    // </style>";
		?>
    <div class=" iepa_product_review<?php echo $props['uniqueID']; ?><?php echo $className;?>">
			<?php echo wc_get_rating_html( $average, $rating_count ); ?>
			<?php if ( $rating_count > 0 && comments_open() ) : ?>
        <a href="#reviews" class="iepa-review-link" rel="nofollow">
          (
            <?php printf(
              _n( '%s customer review', '%s customer reviews', $review_count, 'woocommerce' ),
              '<span class="count">' . esc_html( $review_count ) . '</span>'
            ); ?>
          )
        </a>
      <?php endif; ?>
    </div>
		<?php
		echo '</div>';

		return ob_get_clean();
	}

	public function iepa_render_add_to_cart( $props ) {
		global $product;

		if ( ! $product ) return '';

		ob_start();

    echo '<div class="iepa_add_to_cart'.$props['uniqueID'].'">';
		//echo $this->iepa_openWrap( $props, 'add-to-cart' );
		woocommerce_template_single_add_to_cart();
		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_cover( $props, $content = '' ) {
		global $product;

		if ( ! $product ) return '';

		ob_start();

		$parallax = empty( $props['Parallax'] ) ? '' : 'background-attachment:fixed;';
		$full = empty( $props['Full width'] ) ? '' : ' vw-100';

		echo $this->iepa_openWrap(
			$props, "cover-wrap bg-center ph4 cover flex flex-column justify-center relative $full $props[BlockAlignment]", 'div',
			'min-height:' . $props['Min height'] . 'px;' . $parallax .
			'background-image:url(' . get_the_post_thumbnail_url( $product->get_id(), 'large' ) . '")'
		);

		echo $content;

		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_stock_countdown( $props ) {
		global $product;

		if ( ! $product ) return '';

		$stock = $product->get_stock_quantity();

		if ( ! $stock ) return '';

		$max = max( $stock, $props['max'] );
		$percent = $stock / $max * 100;

		ob_start();
		echo $this->iepa_openWrap( $props, 'stock-countdown' );
		echo "<style>.ibtanaecommerceproductaddons.product p.stock{display:none}</style>";

		echo "<div class='woobk-stock-countdown-bar' style='background:$props[track_color];'>" .
				 "<div class='woobk-stock-countdown-bar-left' style='background:$props[active_color];width:$percent%;'></div>" .
				 "</div>";
		if ( $stock > 1 ) {
			printf( $props['message'], '' . $stock );
		} else {
			echo $props['message1'];
		}
		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_product_sale_countdown( $props ) {
		global $product;

		if ( ! $product ) return '';

		/** @var WC_Product $product */
		// Declare and define two dates
		$date1 = strtotime( $product->get_date_on_sale_to() );
		$diff  = $date1 - time();

		if ( ! $diff || $diff < 5 ) {
			return '<div></div>';
		}

		$props = wp_parse_args( $props, [
			'active_color' => '#555',
			'track_color' => '#ddd',
			'track_width' => '2',
		] );

		ob_start();

		echo $this->iepa_openWrap( $props, 'sale_counter_wrap' );
		echo "<div class='ibtanaecommerceproductaddons-sale_counter' data-date-end='$date1'>";

		$days = floor( $diff / ( 60 * 60 * 24 ) );

		$hours = floor( $diff % (60 * 60 * 24) / ( 60 * 60 ) );

		$minutes = floor( $diff % (60 * 60) / 60 );

		$seconds = floor( $diff % 60 );

		$r = 15.9154; // 100/2PI
		$center = $r + $props['track_width'] / 2;

		$width = 2 * $center;


		$circle_attrs = "cx=$center cy=$center r='{$r}' stroke-width='{$props['track_width']}' " .
										"style='transform-origin:50%% 50%%;transform:rotate(-90deg);' fill='none'";

		$format =
			'<div class="woob-timr woob-timr-%1$s">' .
			"<svg viewBox='0 0 $width $width'>" .
			"<circle $circle_attrs stroke='{$props['track_color']}' />" .
			"<circle $circle_attrs stroke='{$props['active_color']}' class='woob-timr-arc-%1\$s' />" .
			'</svg>' .
			'<div class="woob-timr-number-%1$s woob-timr-number">%3$s</div>' .
			'<div class="woob-timr-label">%4$s</div>' .
			'</div>';

		echo $days ? sprintf( $format, 'days', $days * 100 / 31, $days, _n( 'day', 'days', $days ) ) : '';

		echo sprintf( $format, 'hours', $hours * 100 / 24, $hours, _n( 'hour', 'hours', $hours ) );

		echo sprintf( $format, 'minutes', $minutes * 100 / 60, $minutes, _n( 'minute', 'minutes', $minutes ) );

		echo sprintf( $format, 'seconds', $seconds * 100 / 60, $seconds, _n( 'second', 'seconds', $seconds ) );

		echo '</div></div>';

		return ob_get_clean();
	}

	public function render_iepa_related_products( $props ) {
		global $product;

		if ( ! $product ) return '';

		ob_start();
		echo $this->iepa_openWrap( $props, 'related_products' );
		woocommerce_related_products();
		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_product_price( $props ) {

    $attributes=$prop=$props;
    $className = isset($attributes['className']) ? $attributes['className'] : '';

		global $product;

		if ( ! $product ) return '';

		return '<div class="iepa_product_price'.$prop['uniqueID'].' '.$className.'">' .
              $product->get_price_html() .
              // $product->get_price() .
              // get_woocommerce_currency_symbol() . $product->get_regular_price() .
              // $product->get_sale_price() .
              // woocommerce_template_single_price() .
           '</div>';
	}

	public function render_iepa_excerpt( $props ) {
		global $product, $post;

		if ( ! $product ) return '';

		$short_description = apply_filters( 'woocommerce_short_description', $post->post_excerpt );

		return $this->iepa_openWrap( $props, 'excerpt' ) . $short_description . '</div>';
	}

	public function render_iepa_product_meta( $props ) {
		global $product;

		if ( ! $product ) return '';

		ob_start();
		echo $this->iepa_openWrap( $props, 'meta' );
		$metadata = '';
    $metadata .=  "<div class='iepa_product_meta".$props['uniqueID']."'>";

		$sku      = $product->get_sku();
		if ( $sku ) {
			$metadata .= "<span class='ibtanaecommerceproductaddons-sku'>SKU: $sku</span> ";
		}
		$metadata .= wc_get_product_category_list( $product->get_id(), ', ', '<span class="posted_in">' . _n( 'Category:', 'Categories:', count( $product->get_category_ids() ), 'woocommerce' ) . ' ', '</span> ' );
		$metadata .= wc_get_product_tag_list( $product->get_id(), ', ', '<span class="tagged_as">' . _n( 'Tag:', 'Tags:', count( $product->get_tag_ids() ), 'woocommerce' ) . ' ', '</span> ' );

    $icons = $props['sharearr'];

    $metadata .= "<span class='ipea_icon_share_parent'>Share:";
    foreach ($icons as $key => $icon) {

      $metadata .= "<a class='iepa_icon_parent_".$key."' target='".$icon['target']."' rel='noopener' data-href='".$icon['link']."'><i class='".$icon['icon']."'></i></a>";
    }
    $metadata .= "</span>";
    $metadata .= "</div>";

		echo apply_filters( 'iepa_product_meta', $metadata );

		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_product_reviews( $props ) {
    global $product;
		if ( ! $product ) return '';

    $isIepaGutenberg = ( isset( $_GET['isIepaGutenberg'] ) && ( $_GET['isIepaGutenberg'] == true ) ) ? true : false;

    $className = isset( $props['className'] ) ? $props['className'] : '';

		ob_start();
		// echo $this->iepa_openWrap( $props, 'reviews' );
    if ( !$isIepaGutenberg ) {
      echo '<div class="iepa_product_reviews' . $props['uniqueID'] . ' ' . $className . '">';
    }
		comments_template();
    if ( !$isIepaGutenberg ) {
      echo '</div>';
    }

		return ob_get_clean();
	}

	public function iepa_woocommerce_gallery_image_size( $size ) {
		if ( $this->iepa_gallery_image_size ) {
			return $this->iepa_gallery_image_size;
		}
		return $size;
	}

	public function render_iepa_product_images( $props ) {
		global $post, $product;

    $className = isset($props['className']) ? $props['className'] : '';

		if ( ! empty( $props['img_size'] ) ) {
			$this->iepa_gallery_image_size = $props['img_size'];
		}

		if ( ! $product ) return '';

    $attachment_ids = $product->get_gallery_image_ids();

    $sliderArrow      = $props['sliderArrow'] ? 'true' : 'false';
    $lightbox         = $props['lightbox'] ? 'true' : 'false';
    $sliderGallery    = $props['sliderGallery'] ? $props['sliderGallery'] : false;
    $autoplay         = $props['autoplay'] ? 'true' : 'false';
    $loop             = $props['loop'] ? 'true' : 'false';
    $zoom             = $props['zoom'] ? 'true' : 'false';
    $arrowColor       = $props['arrowColor'] ? $props['arrowColor'] : '#ffffff';
    $arrowBgColor     = $props['arrowBgColor'] ? $props['arrowBgColor'] : '#000000';
    $galleryPosition  = $props['galleryPosition'] ? $props['galleryPosition'] : 'horizontal';

    if ( has_post_thumbnail() ) {
      $thumbanil_id   = array(get_post_thumbnail_id());
      $attachment_ids = array_merge($thumbanil_id,$attachment_ids);
    }
    ob_start();

    if($sliderGallery){
      echo '<div class="iepa_product_images' . $props['uniqueID'] . ' ' . $className . '">';
      //Image With Slider 2 Zoom
      if ( has_post_thumbnail() ) {

        $attachment_count = count( $attachment_ids);

        $gallery          = $attachment_count > 0 ? '[product-gallery]' : '';
        $image_link       = wp_get_attachment_url( get_post_thumbnail_id() );
        $imgProps2        = wc_get_product_attachment_props( get_post_thumbnail_id(), $post );
        $image            = get_the_post_thumbnail( $post->ID, apply_filters( 'single_product_large_thumbnail_size', 'shop_single' ), array(
          'title'	 => $imgProps2['title'],
          'alt'    => $imgProps2['alt'],
        ) );

        $fullimage        = get_the_post_thumbnail( $post->ID, 'full', array(
          'title'	 => $imgProps2['title'],
          'alt'    => $imgProps2['alt'],
        ) );

        // IEPA FOR SLIDER vertical-img-right
        $html  = '<div class="slider iepa-slider-for" data-arrow='.$sliderArrow.' data-lightbox='.$lightbox.' data-autoplay='.$autoplay.' data-loop='.$loop.' data-zoom='.$zoom.'
        data-arrow-color='.$arrowColor.' data-arrow-bg-color='.$arrowBgColor.' data-arrow-position='.$galleryPosition.'>';

        // $html .= sprintf(
        //   '<div class="zoom">%s%s<a href="%s" class="iepa-popup fa fa-expand" data-fancybox="product-gallery"></a></div>',
        //   $fullimage,
        //   $image,
        //   $image_link
        // );

        foreach( $attachment_ids as $attachment_id ) {
          $imgfull_src = wp_get_attachment_image_src( $attachment_id,'full');
          $image_src   = wp_get_attachment_image_src( $attachment_id,'shop_single');
          $html .= '<div class="zoom"><img src="'.$imgfull_src[0].'" /><img src="'.$image_src[0].'" /><a href="'.$imgfull_src[0].'" class="iepa-popup fa fa-expand" data-fancybox="product-gallery"></a></div>';
        }

        $html .= '</div>';

        echo apply_filters(
          'woocommerce_single_product_image_html',
          $html,
          $post->ID
        );
      } else {
        echo apply_filters( 'woocommerce_single_product_image_html', sprintf( '<img src="%s" alt="%s" />', wc_placeholder_img_src(), __( 'Placeholder', 'woocommerce' ) ), $post->ID );
      }

      //Image With Slider 1
      if ( $attachment_ids ) {
        //vertical-thumb-right

        echo '<div id="iepa-gallery" class="slider iepa-slider-nav"><?php';

          foreach ( $attachment_ids as $attachment_id ) {

            $imgProps = wc_get_product_attachment_props( $attachment_id, $post );

            $thumbnails_catlog = '';

            if ( ! $imgProps['url'] ) {
              continue;
            }

            echo apply_filters(
              'woocommerce_single_product_image_thumbnail_html',
              sprintf(
                '<li title="%s">%s</li>',
                esc_attr( $imgProps['caption'] ),
                wp_get_attachment_image( $attachment_id, apply_filters( 'single_product_large_thumbnail_size', 'thumbnail' ), 0, $thumbnails_catlog )
              ),
              $attachment_id,
              $post->ID
            );
          }

        echo '</div>';
      }
      echo '</div>';
    }else{
      echo '<div class="iepa_product_images' . $props['uniqueID'] . ' ' . $className . '">';
      add_action( 'woocommerce_gallery_image_size', [ $this, 'iepa_woocommerce_gallery_image_size' ], 999 );

      woocommerce_show_product_images();
      remove_action( 'woocommerce_gallery_image_size', [ $this, 'iepa_woocommerce_gallery_image_size' ], 999 );
      echo '</div>';
    }

		return ob_get_clean();
	}

	public function render_iepa_images_carousel( $props ) {
		global $product;

		if ( ! $product ) return '';

		if ( ! function_exists( 'wc_get_gallery_image_html' ) ) {
			return '';
		}

		ob_start();
		echo $this->iepa_openWrap( $props, 'images_carousel flexslider o-0' );
		$slide_attachments = $product->get_gallery_image_ids();
		array_splice( $slide_attachments, 0, 0, + $product->get_image_id() );
		?>
		<ul class="slides">
			<?php
			if ( $slide_attachments ) {
				foreach ( $slide_attachments as $attachment ) {
					echo '<li>';
					echo wp_get_attachment_image( $attachment, 'large' );
					echo '</li>';
				}
			}
			?>
		</ul>
		<div class="ibtanaecommerceproductaddons-images_carousel-navigation">
			<a href="#" class="flex-prev"></a>
			<a href="#" class="flex-next"></a>
		</div>
		<?php
		echo '</div>';

		return ob_get_clean();
	}

	public function render_iepa_request_quote( $props ) {
		global $product;

		if ( ! $product ) return '';

		$pid = $product->get_id();

		ob_start();
		echo $this->iepa_openWrap( $props, 'request_quote' );

		echo '<form action="#" method="post" class="cart flex items-center">';

		if ( method_exists( $product, 'get_available_variations' ) ) {
			$variations = $product->get_available_variations();
			if ( $variations ) {
				echo '<select name="sfpbk-pt-variations[' . $product->get_id() . ']">';
				foreach ( $variations as $var ) {
					$label = array_map( function( $itm ) { return str_replace( '-', ' ', $itm ); }, $var['attributes'] );
					$label = ucfirst( implode( ', ', $label ) );
					echo "<option value='$var[variation_id]'>$label</option>";
				}
				echo '</select>';
			}
		}
		echo
			wp_nonce_field( 'woobk-action', 'woobk-nonce', 0, 0 ) .
			"<div class='quantity'><input required class='qty' type='number' name='sfpbk-pt-prods[$pid]' value='1'></div>" .
			"<a href='#woobk-quote-dialog' class='button'>" . __( 'Request quote', 'woocommerce' ) . '</a>';
		$this->iepa_request_quote_dialog();
		echo '</form></div>';
		return ob_get_clean();
	}

	public function iepa_request_quote_dialog() {
		?>
		<div class="absolute--fill" id="woobk-quote-dialog">
			<a class="absolute--fill" href="#_">&nbsp;</a>
			<div class="woobk-fields relative">
				<input required name="requester_name" type="text" placeholder="Full name">
				<input required name="requester_email" type="email" placeholder="Email">
				<textarea name="requester_message" placeholder="Message"></textarea>
				<button name='action' value='quote'><?php _e( 'Send request for quote', 'woocommerce' ) ?></button>
			</div>
		</div>
		<?php
	}

	public function render_iepa_tabs( $props ) {
		global $product;
		if ( ! $product ) return '';

		if ( ! has_action( 'woocommerce_product_tabs', array( $this, 'product_tabs' ) ) ) {
			add_filter( 'woocommerce_product_tabs', array( $this, 'product_tabs' ), 99, 3 );
		}
		$this->iepa_product_description = nl2br( $props['desc'] );
		ob_start();
		echo $this->iepa_openWrap( $props, 'tabs' );
		woocommerce_output_product_data_tabs();
		echo '</div>';
		return ob_get_clean();
	}
}
IEPA_Blocks::iepa_blocks_instance( __FILE__ );
