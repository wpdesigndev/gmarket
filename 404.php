<?php
/**
 * Página no encontrada.
 */
get_header();
?>
<div class="gmk-container gmk-404">
	<h1><?php echo esc_html( gmk_t( 'e404_title' ) ); ?></h1>
	<p><?php echo esc_html( gmk_t( 'e404_text' ) ); ?></p>
	<p><a class="gmk-btn" href="<?php echo esc_url( function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ) ); ?>"><?php echo esc_html( gmk_t( 'e404_button' ) ); ?></a></p>
</div>
<?php
get_footer();
