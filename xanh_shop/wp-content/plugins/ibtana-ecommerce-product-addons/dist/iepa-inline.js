(function($) {
  function get_iepa_activation_status() {
    jQuery.post( ibtana_visual_editor_modal_js.adminAjax, {
      action:							'iepa_activation_status'
    }, function( iepa_response ) {
      console.log( 'iepa_response', iepa_response );
    });
  }

  get_iepa_activation_status();

  // wp.data.subscribe( () => {

    // console.log( 'subscribed' );

    // if ( ibtana_visual_editor_modal_js.post_type == "product" ) {
    //   var edit_post_post_status = $( '.edit-post-post-status' );
    //   if ( edit_post_post_status.length && !jQuery( '#iepa_product_metabox_license_top' ).length ) {
    //     edit_post_post_status.append(
    //       `<div class="components-panel__row">
    //         <p id="iepa_product_metabox_license_top" class="iepa_product_metabox_license">
    // 					Get amazing features with <strong>Ibtana - WooCommerce Product Add-Ons.</strong>
    // 					<a class="button" href="` + iepa_inline_object.admin_url + `admin.php?page=ibtana-visual-editor-addons" target="_blank">Upgrade To Pro!</a>
    // 				</p>
    //       </div>`
    //     );
    //   }
    // }

  // } );

})( jQuery );
