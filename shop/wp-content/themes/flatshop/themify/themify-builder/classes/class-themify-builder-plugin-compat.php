<?php
/**
 * Class Builder Plugin Compatibility
 * @package themify-builder
 */
class Themify_Builder_Plugin_Compat {
	
	/**
	 * Constructor
	 */
	function __construct() {
		global $ThemifyBuilder;

		// Hooks
		add_action( 'admin_enqueue_scripts', array( $this, 'load_admin_scripts' ), 10 );

		// WooCommerce
		if ( $this->is_plugin_active( 'woocommerce/woocommerce.php' ) ) {
			add_action( 'woocommerce_after_single_product_summary', array( $this, 'show_builder_below_tabs'), 12 );
		}

		// WPSEO live preview
		add_action( 'wp_ajax_wpseo_get_html_builder', array( &$this, 'wpseo_get_html_builder_ajaxify' ), 10 );
		add_filter( 'wpseo_pre_analysis_post_content', array( $this, 'wpseo_pre_analysis_post_content' ), 10, 2 );

		// Members
		if( class_exists( 'Members_Load' ) ) {
			remove_filter( 'the_content', array( $ThemifyBuilder, 'builder_show_on_front' ), 11 );
			add_filter( 'the_content', array( &$this, 'members_builder_show_on_front' ), 11 );
		}
	}

	function show_builder_below_tabs() {
		global $post, $ThemifyBuilder;
		if ( ! is_singular( 'product' ) && 'product' != get_post_type() ) return;
		
		$builder_data = get_post_meta( $post->ID, '_themify_builder_settings', true );
		$builder_data = stripslashes_deep( maybe_unserialize( $builder_data ) );

		if ( ! is_array( $builder_data ) ) {
			$builder_data = array();
		}

		$ThemifyBuilder->retrieve_template( 'builder-output.php', array( 'builder_output' => $builder_data, 'builder_id' => $post->ID ), '', '', true );
	}

	function load_admin_scripts( $hook ) {
		global $version, $pagenow, $current_screen;

		if ( in_array( $hook, array( 'post-new.php', 'post.php' ) ) && in_array( get_post_type(), themify_post_types() ) ) {
			wp_enqueue_script( 'themify-builder-plugin-compat', THEMIFY_BUILDER_URI .'/js/themify.builder.plugin.compat.js', array('jquery'), $version, true );
			wp_localize_script( 'themify-builder-plugin-compat', 'TBuilderPluginCompat', apply_filters( 'themify_builder_plugin_compat_vars', array(
				'wpseo_active' => $this->is_plugin_active( 'wordpress-seo/wp-seo.php' ),
				'wpseo_builder_content_text' => __( 'Themify Builder: ', 'themify')
			)) );
		}
	}

	/**
	 * Echo builder on description tab
	 * @return void
	 */
	function echo_builder_on_description_tabs() {
		global $post;
		echo apply_filters( 'the_content', $post->post_content );
	}

	/**
	 * Plugin Active checking
	 * @param string $plugin 
	 * @return bool
	 */
	function is_plugin_active( $plugin ) {
		return in_array( $plugin, apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) );
	}

	/**
	 * Get html data builder
	 */
	function wpseo_get_html_builder_ajaxify(){
		check_ajax_referer( 'tfb_load_nonce', 'nonce' );
		global $ThemifyBuilder;
		$post_id = (int) $_POST['post_id'];
		$meta_key = apply_filters( 'themify_builder_meta_key', '_themify_builder_settings' );

		$builder_data = get_post_meta( $post_id, $meta_key, true );
		$builder_data = stripslashes_deep( maybe_unserialize( $builder_data ) );
		$html = '';

		if ( is_array( $builder_data ) && count( $builder_data ) > 0 ) {
			$html = $ThemifyBuilder->retrieve_template( 'builder-output.php', array( 'builder_output' => $builder_data, 'builder_id' => $post_id ), '', '', false );
			$html = preg_replace('~>\s+<~', '><', $html);
			$html = preg_replace('/\s\s+/', ' ', $html);
			$html = trim( wp_strip_all_tags( $html ) );
			$html = str_replace( '[Edit]', '', $html );
		}

		$response = array(
			'text_str' => $html
		);

		echo json_encode( $response );

		die();
	}

	/**
	 * Builder integration with Members plugin
	 * Display the Builder contents only if user has permission to view the post contents
	 *
	 * @since 1.1.8
	 * @return string $content
	 */
	function members_builder_show_on_front( $content ) {
		global $ThemifyBuilder;

		if( members_can_current_user_view_post( get_the_ID() ) ) {
			$content = $ThemifyBuilder->builder_show_on_front( $content );
		}

		return $content;
	}

	/**
	 * Add filter to wpseo Analysis Post Content
	 * @param string $post_content 
	 * @param object $post 
	 * @return string
	 */
	function wpseo_pre_analysis_post_content( $post_content, $post ) {
		global $ThemifyBuilder;
		$meta_key = apply_filters( 'themify_builder_meta_key', '_themify_builder_settings' );

		$builder_data = get_post_meta( $post->ID, $meta_key, true );
		$builder_data = stripslashes_deep( maybe_unserialize( $builder_data ) );
		$html = '';

		if ( is_array( $builder_data ) && count( $builder_data ) > 0 ) {
			$html = $ThemifyBuilder->retrieve_template( 'builder-output.php', array( 'builder_output' => $builder_data, 'builder_id' => $post->ID ), '', '', false );
			$html = preg_replace('~>\s+<~', '><', $html);
			$html = preg_replace('/\s\s+/', ' ', $html);
			$html = trim( $html );
			$html = str_replace( '[Edit]', '', $html );
		}
		$post_content .= $html;

		return $post_content;
	}
}