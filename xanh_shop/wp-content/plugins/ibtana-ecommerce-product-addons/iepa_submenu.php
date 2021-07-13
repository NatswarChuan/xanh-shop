<?php
/**
* Ibtana submenu for woocommerce editor settings
*/
class IEPA_Submenu extends Ibtana_Visual_Editor_Menu_Creator {
  public $iepa_submenu_defaults = array(
    'page_type' => 'menu_page',
    'page_title' => '',
    'menu_title' => '',
    'capability' => '',
    'menu_slug' => '',
    'icon_url' => '',
    'position' => '',
    'parent_slug' => '',
    'priority' => 10,
    'network_page' => false,
    'page_functions' => ''
  );
	public $iepa_args;
	public $iepa_ivehook;

  function __construct( $iepa_args ) {
		$this->active_plugins = apply_filters( 'active_plugins', get_option( 'active_plugins' ) );

    /* Global that will hold all the arguments for all the menu pages */
    global $ibtana_visual_editor_pages;

    /* Merge the input arguments and the iepa_submenu_defaults. */
    $this->iepa_args = wp_parse_args( $iepa_args, $this->iepa_submenu_defaults );

    /* Add the settings for this page to the global object */
    $ibtana_visual_editor_pages[$this->iepa_args['page_title']] = $this->iepa_args;

    if( !$this->iepa_args['network_page'] ) {
      /* Hook the page function to 'admin_menu'. */
      // add_action( 'admin_menu', array( &$this, 'ibtana_visual_editor_page_init' ), $this->iepa_args['priority'] );
    } else {
      /* Hook the page function to 'admin_menu'. */
      // add_action( 'network_admin_menu', array( &$this, 'ibtana_visual_editor_page_init' ), $this->iepa_args['priority'] );
    }

    // License
    include_once( IEPA_ABSPATH . 'class-ive-iepa.php' );
    $iepa_admin  = new IEPA_Backend();
    add_filter( 'ive_is_add_on_installed', array( $iepa_admin, 'iepa_is_add_on_installed' ) );
    add_action( 'ive_addon_license_area', array( $iepa_admin, 'iepa_addon_license_area' ) );
    add_action( 'wp_ajax_activate_iepa_license', array( $iepa_admin, 'iepa_license_activate' ) );
    add_action( 'wp_ajax_iepa_activation_status', array( $iepa_admin, 'iepa_activation_status' ) );
  }


  /**
	 * Function that creates the admin page
	 */
	function ibtana_visual_editor_page_init() {
		global $ibtana_visual_editor_pages_ivehooks;

    /* don't add the page at all if the user doesn't meet the capabilities */
    if( !empty( $this->iepa_args['capability'] ) ){
        if( !current_user_can( $this->iepa_args['capability'] ) )
            return;
    }

		/* Create the page using either add_menu_page or add_submenu_page functions depending on the 'page_type' parameter. */
	 if( $this->iepa_args['page_type'] == 'submenu_page' ){
			$this->iepa_ivehook = add_submenu_page( $this->iepa_args['parent_slug'], $this->iepa_args['page_title'], $this->iepa_args['menu_title'], $this->iepa_args['capability'], $this->iepa_args['menu_slug'], array($this,$this->iepa_args['page_functions']) );

			$ibtana_visual_editor_pages_ivehooks[$this->iepa_args['menu_slug']] = $this->iepa_ivehook;
		}

	}

	/**
	 * Do action 'add_meta_boxes'. This hook isn't executed by default on a admin page so we have to add it.
	 */
	function ibtana_visual_editor_settings_page_add_meta_boxes() {
    do_action( 'ibtana_visual_editor_page_creator_before_meta_boxes', $this->iepa_ivehook );
		do_action( 'add_meta_boxes', $this->iepa_ivehook, 0 );
    do_action( 'ibtana_visual_editor_page_creator_after_meta_boxes', $this->iepa_ivehook );
	}
  /**
 * Editor setting submenu
 */
  public function iepa_save_page() {
  }

  /**
  * End : create sub main menu page editor  of ibtana plugin
  */
}

$iepa_editor_settings = array(
  'page_type'       =>  'submenu_page',
  'page_title'      =>  'Saved Templates',
  'menu_title'      =>  'Saved Templates',
  'capability'      =>  'edit_theme_options',
  'menu_slug'       =>  'ibtana-visual-editor-editor',
  'icon_url'        =>  '',
  'parent_slug'     =>  'ibtana-visual-editor',
  'page_functions'  =>  'iepa_save_page'
);

new IEPA_Submenu( $iepa_editor_settings );

 ?>
