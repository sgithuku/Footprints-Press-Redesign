<?php
/**
 * The template for displaying the footer
 *
 * Contains the closing of the "off-canvas-wrap" div and all content after.
 *
 * @package WordPress
 * @subpackage FoundationPress
 * @since FoundationPress 1.0
 */

?>
<div class="small-12 break">
	<footer class="footer">
			<?php do_action( 'foundationpress_before_footer' ); ?>
			<?php dynamic_sidebar( 'footer-widgets' ); ?>
			<?php do_action( 'foundationpress_after_footer' ); ?>
	</footer>
</div>
<a class="exit-off-canvas"></a>

	<?php do_action( 'foundationpress_layout_end' ); ?>
	</div>
</div>
<?php wp_footer(); ?>
<?php do_action( 'foundationpress_before_closing_body' ); ?>
<div class="row">
	<p class="text-right">
		Copyright © 2010 — 2015. All Rights Reserved
	</p>
</div>


</body>
</html>
