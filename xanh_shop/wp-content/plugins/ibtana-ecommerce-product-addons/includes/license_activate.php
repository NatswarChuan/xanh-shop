<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}

if (!isset($_POST['add_on_key'])) {
  wp_send_json( array( 'status' => false, 'msg' => 'Please Provide The KEY!' ) );
  exit;
}


$iepa_post_add_on_key  = sanitize_text_field( $_POST['add_on_key'] );
$iepa_response = wp_remote_post( IBTANA_LICENSE_API_ENDPOINT . 'ibtana_license_activate_premium_addon' , array(
  'method'      => 'POST',
  'body'        => wp_json_encode(array(
      'add_on_key'          =>  $iepa_post_add_on_key,
      'site_url'            =>  site_url(),
      'add_on_text_domain'  =>  get_plugin_data( IEPA_PLUGIN_FILE )['TextDomain']
  )),
  'headers'     => [
      'Content-Type' => 'application/json',
  ],
  'data_format' => 'body'
));

if ( is_wp_error( $iepa_response ) ) {
  wp_send_json(array('status' => false, 'msg' => 'Something Went Wrong!'));
  exit;
} else {
  $iepa_response     = wp_remote_retrieve_body( $iepa_response );
  $iepa_api_response = json_decode( $iepa_response, true );

	$iepa_key = str_replace( '-', '_', get_plugin_data( IEPA_PLUGIN_FILE )['TextDomain'] ) . '_license_key';
  if ( $iepa_api_response['status'] == true ) {

    update_option( $iepa_key, [
      'license_key'     			=>	$iepa_post_add_on_key,
      'license_status'  			=>	true,
			'plan_expiration_date'	=>	$iepa_api_response['dates_with_diff_info']['plan_expiration_date'],
			// 'save_templates_limit'	=>	5
    ]);
    wp_send_json( array( 'status' => true, 'msg' => $iepa_api_response['msg'] ) );
    exit;
  } else {
    update_option( $iepa_key, [
      'license_key'     => '',
      'license_status'  => false
    ]);
    wp_send_json( array( 'status' => false, 'msg' => $iepa_api_response['msg'] ) );
    exit;
  }
}
