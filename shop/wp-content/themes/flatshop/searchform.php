<?php
/**
 * Template for search form that separates blog posts search from products search
 * @package themify
 * @since 1.0.0
 */

$product_slug = 'product';
$post_slug = 'post';

if ( class_exists( 'WPML_Slug_Translation' ) && method_exists( 'WPML_Slug_Translation', 'get_translated_slug' ) ) {
	global $sitepress, $wp_post_types;
	if ( $translated_post_slug = WPML_Slug_Translation::get_translated_slug( $wp_post_types['post']->rewrite['slug'], $sitepress->get_current_language() ) ) {
		$product_slug = $translated_post_slug;
	}
	if ( $translated_product_slug = WPML_Slug_Translation::get_translated_slug( $wp_post_types['product']->rewrite['slug'], $sitepress->get_current_language() ) ) {
		$product_slug = $translated_product_slug;
	}
}

?>
<form method="get" id="searchform" action="<?php echo home_url(); ?>/">

	<i class="fa fa-search icon-search"></i>

	<input type="hidden" class="search-type" name="post_type" value="<?php echo esc_attr( $post_slug ); ?>" />

	<input type="text" name="s" id="s"  placeholder="<?php esc_attr_e( 'Search', 'themify' ); ?>" />

	<div class="search-option">

		<input id="search-blog" class="search-blog" type="radio" name="search-option" value="<?php echo esc_attr( $post_slug ); ?>" /> <label for="search-blog"><?php _e( 'Blog', 'themify' ); ?></label>

		<input id="search-shop" class="search-shop" type="radio" name="search-option" value="<?php echo esc_attr( $product_slug ); ?>" /> <label for="search-shop"><?php _e( 'Shop', 'themify' ); ?></label>

	</div>

</form>