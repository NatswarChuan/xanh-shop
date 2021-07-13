<style media="screen">
  .submitdelete {
    cursor: pointer;
  }
</style>

<?php self::ibtana_visual_editor_banner_head(); ?>


<div class="wrap">

  <?php $_wpnonce = wp_create_nonce( '_wpnonce' ); ?>

  <?php
  $args = array(
    'post_type'   =>  'ibtana_template'
  );

  // if ( !isset( $_GET['post_status'] ) ) {
  //   $args['post_status'] =  array( 'publish', 'draft' );
  // } else {
  //   $args['post_status'] =  $_GET['post_status'];
  // }
  //
  // echo "<pre>";
  // print_r( $args );
  // echo "</pre>";

  $query = new WP_Query( $args );

  $admin_ajax = admin_url( 'admin-ajax.php' );

  $iepa_key = get_option( str_replace( '-', '_', 'ibtana-ecommerce-product-addons' ) . '_license_key' );
  $is_iepa_activated = false;
  if ( $iepa_key ) {
    if ( isset( $iepa_key['license_key'] ) && isset( $iepa_key['license_status'] ) ) {
      if ( ( $iepa_key['license_key'] != '' ) && ( $iepa_key['license_status'] == 1 ) ) {
        $is_iepa_activated = true;
      }
    }
  }
  ?>

  <h1 class="wp-heading-inline">Ibtana templates</h1>

  <hr class="wp-header-end">

  <h2 class="screen-reader-text">Filter posts list</h2>

  <ul class="subsubsub" style="display:none;">

    <li class="all">
      <a class="<?php if( !isset( $_GET['post_status'] ) ) { echo 'current'; } ?>" href="admin.php?page=ibtana-visual-editor-saved-templates">All
        <span class="count">(1)</span>
      </a> |
    </li>

    <li class="publish">
      <a class="<?php if( isset($_GET['post_status']) && $_GET['post_status'] == 'publish' ) { echo 'current'; } ?>" href="admin.php?post_status=publish&amp;page=ibtana-visual-editor-saved-templates">Published
        <span class="count">(1)</span>
      </a> |
    </li>

    <li class="draft">
      <a href="admin.php?post_status=draft&amp;page=ibtana-visual-editor-saved-templates">Draft
        <span class="count">(1)</span>
      </a> |
    </li>

    <li class="trash">
      <a href="admin.php?post_status=trash&amp;page=ibtana-visual-editor-saved-templates">Trash
        <span class="count">(1)</span>
      </a>
    </li>

  </ul>

  <form id="posts-filter" method="get">

    <input type="hidden" name="post_status" class="post_status_page" value="all">
    <input type="hidden" name="post_type" class="post_type_page" value="ibtana_template">



    <input type="hidden" id="_wpnonce" name="_wpnonce" value="<?php echo $_wpnonce; ?>">
    <input type="hidden" name="_wp_http_referer" value="edit.php?post_type=ibtana_template">


    <div class="tablenav top">

      <div class="tablenav-pages one-page">

        <span class="displaying-num"><?php echo count( $query->posts ); ?> item</span>

      </div>

      <br class="clear">

    </div>

    <h2 class="screen-reader-text">Posts list</h2>

    <table class="wp-list-table widefat fixed striped table-view-list posts">
      <thead>
        <tr>
          <!-- <td id="cb" class="manage-column column-cb check-column">
            <label class="screen-reader-text" for="cb-select-all-1">Select All</label>
            <input id="cb-select-all-1" type="checkbox">
          </td> -->
          <th scope="col" id="title" class="manage-column column-title column-primary sortable desc">
            <a>
              <span>Title</span>
            </a>
          </th>
          <th scope="col" id="taxonomy-ibtana_template_type" class="manage-column column-taxonomy-ibtana_template_type">Ibtana Template Type</th>
          <!-- <th scope="col" id="date" class="manage-column column-date sortable asc">
            <a>
              <span>Date</span>
            </a>
          </th> -->
        </tr>
      </thead>

      <tbody id="the-list">

        <?php if ( $query->posts ): ?>

          <?php foreach ( $query->posts as $ibtana_template_post ): ?>

            <tr id="post-<?php echo $ibtana_template_post->ID; ?>" class="iedit author-self level-0 post-<?php echo $ibtana_template_post->ID; ?> type-ibtana_template status-publish hentry entry">

            <th scope="row" class="check-column" style="display:none;">
              <label class="screen-reader-text" for="cb-select-<?php echo $ibtana_template_post->ID; ?>">Select <?php echo $ibtana_template_post->post_title; ?></label>
              <input id="cb-select-<?php echo $ibtana_template_post->ID; ?>" type="checkbox" name="post[]" value="<?php echo $ibtana_template_post->ID; ?>">
              <div class="locked-indicator">
                <span class="locked-indicator-icon" aria-hidden="true"></span>
                <span class="screen-reader-text">“<?php echo $ibtana_template_post->post_title; ?>” is locked</span>
              </div>
            </th>

            <td class="title column-title has-row-actions column-primary page-title" data-colname="Title">

              <div class="locked-info">
                <span class="locked-avatar"></span>
                <span class="locked-text"></span>
              </div>

              <strong>
                <a class="row-title" href="post.php?post=<?php echo $ibtana_template_post->ID; ?>&amp;action=edit" aria-label="“<?php echo $ibtana_template_post->post_title; ?>” (Edit)"><?php echo $ibtana_template_post->post_title; ?></a>
              </strong>

              <div class="hidden" id="inline_<?php echo $ibtana_template_post->ID; ?>">
                <div class="post_title"><?php echo $ibtana_template_post->post_title; ?></div>
                <div class="post_name"><?php echo $ibtana_template_post->post_name; ?></div>
                <div class="post_author">1</div>
                <div class="comment_status">closed</div>
                <div class="ping_status">closed</div>
                <div class="_status">publish</div>
                <div class="jj">24</div>
                <div class="mm">05</div>
                <div class="aa">2021</div>
                <div class="hh">08</div>
                <div class="mn">31</div>
                <div class="ss">26</div>
                <div class="post_password"></div>
                <div class="page_template">default</div>
                <div class="post_category" id="ibtana_template_type_<?php echo $ibtana_template_post->ID; ?>">40</div>
                <div class="sticky"></div>
              </div>

              <div class="row-actions">
                <span class="edit">
                  <a href="post.php?post=<?php echo $ibtana_template_post->ID; ?>&amp;action=edit" aria-label="Edit “<?php echo $ibtana_template_post->post_title; ?>”">Edit</a> |
                </span>



                <?php if ( $is_iepa_activated == true ): ?>
                <span class="trash">
                  <a post-id="<?php echo $ibtana_template_post->ID; ?>" class="submitdelete" aria-label="Delete “<?php echo $ibtana_template_post->post_title; ?>”">Delete</a>
                </span>
                <?php endif; ?>


                <?php foreach ( wp_get_post_terms( $ibtana_template_post->ID, 'ibtana_template_type' ) as $ibtana_template_type ): ?>
                  <?php if ( $ibtana_template_type->name == 'Ibtana Page Template' ): ?>
                    <span class="view">
                      <a target="_blank" href="<?php echo get_permalink( $ibtana_template_post->ID ); ?>" rel="bookmark" aria-label="Preview “Untitled”">Preview</a>
                    </span>
                    <?php break; ?>
                  <?php endif; ?>
                <?php endforeach; ?>


              </div>

              <button type="button" class="toggle-row">
                <span class="screen-reader-text">Show more details</span>
              </button>

            </td>

            <td class="taxonomy-ibtana_template_type column-taxonomy-ibtana_template_type" data-colname="Ibtana Template Type">
              <?php foreach ( wp_get_post_terms( $ibtana_template_post->ID, 'ibtana_template_type' ) as $ibtana_template_type ): ?>
                <a><?php echo $ibtana_template_type->name; ?></a>
              <?php endforeach; ?>
            </td>

            <!-- <td class="date column-date" data-colname="Date">Published<br>2021/05/24 at 8:31 am</td> -->

          </tr>

          <?php endforeach; ?>

        <?php else: ?>

          <tr class="no-items"><td class="colspanchange" colspan="4">No ibtana templates found.</td></tr>

        <?php endif; ?>

      </tbody>

      <tfoot>
        <tr>
          <!-- <td class="manage-column column-cb check-column">
            <label class="screen-reader-text" for="cb-select-all-2">Select All</label>
            <input id="cb-select-all-2" type="checkbox">
          </td> -->
          <th scope="col" class="manage-column column-title column-primary sortable desc">
            <a>
              <span>Title</span>
            </a>
          </th>
          <th scope="col" class="manage-column column-taxonomy-ibtana_template_type">Ibtana Template Type</th>
          <!-- <th scope="col" class="manage-column column-date sortable asc">
            <a>
              <span>Date</span>
            </a>
          </th> -->
        </tr>
      </tfoot>

    </table>

    <div class="tablenav bottom">

      <div class="tablenav-pages one-page">

        <span class="displaying-num"><?php echo count( $query->posts ); ?> item</span>

      </div>

      <br class="clear">

    </div>

  </form>

  <div id="ajax-response"></div>
  <div class="clear"></div>
</div>


<script type="text/javascript">
  (function($) {

    $( '#posts-filter table' ).on( 'click', '.submitdelete', function() {
      var $this_card  = $(this);
      var post_id = $( this ).attr('post-id');
      // $('.ibtana--modal--loader').show();
      jQuery( $this_card ).css('opacity', 0.5)
      jQuery.post(
        '<?php echo $admin_ajax; ?>', {
          action:   'ive_delete_saved_single_ibtana_template',
          post_id:  post_id
        }, function( ive_saved_ibtana_template ) {
          if ( ive_saved_ibtana_template.status === false ) {
            // IbtanaModal.Box.setup( 'Notice', ive_saved_ibtana_template.msg, false, false, function( name ) {});
          } else {
            // IbtanaModal.Box.setup( 'Success', ive_saved_ibtana_template.msg, false, false, function( name ) {});
            $this_card.closest( 'tr[id*="post"]' ).remove();
          }
          // $('.ibtana--modal--loader').hide();
        }
      );
    });

  })(jQuery);
</script>
