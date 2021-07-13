<?php
/**
 * Related posts based on categories and tags.
 * 
 */

$florist_flower_shop_related_posts_taxonomy = get_theme_mod( 'florist_flower_shop_related_posts_taxonomy', 'category' );

$florist_flower_shop_post_args = array(
    'posts_per_page'    => absint( get_theme_mod( 'florist_flower_shop_related_posts_count', '3' ) ),
    'orderby'           => 'rand',
    'post__not_in'      => array( get_the_ID() ),
);

$florist_flower_shop_tax_terms = wp_get_post_terms( get_the_ID(), 'category' );
$florist_flower_shop_terms_ids = array();
foreach( $florist_flower_shop_tax_terms as $tax_term ) {
	$florist_flower_shop_terms_ids[] = $tax_term->term_id;
}

$florist_flower_shop_post_args['category__in'] = $florist_flower_shop_terms_ids; 

if(get_theme_mod('florist_flower_shop_related_post',true)==1){

$florist_flower_shop_related_posts = new WP_Query( $florist_flower_shop_post_args );

if ( $florist_flower_shop_related_posts->have_posts() ) : ?>
    <div class="related-post">
        <h3 class="py-3"><?php echo esc_html(get_theme_mod('florist_flower_shop_related_post_title','Related Post'));?></h3>
        <div class="row">
            <?php while ( $florist_flower_shop_related_posts->have_posts() ) : $florist_flower_shop_related_posts->the_post(); ?>
                <?php get_template_part('template-parts/grid-layout'); ?>
            <?php endwhile; ?>
        </div>
    </div>
<?php endif;
wp_reset_postdata();

}