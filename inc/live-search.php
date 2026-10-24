<?php
/**
 * Sugerencias de búsqueda en vivo (nombre, SKU y etiquetas del producto).
 * Con TranslatePress, los resultados salen traducidos y con la URL del idioma de la página.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action( 'wp_ajax_gmk_search', 'gmk_live_search' );
add_action( 'wp_ajax_nopriv_gmk_search', 'gmk_live_search' );

/**
 * Responde con productos y categorías que coinciden con el texto.
 */
function gmk_live_search() {
	check_ajax_referer( 'gmk_search', 'nonce' );
	global $wpdb;

	$q    = isset( $_GET['q'] ) ? sanitize_text_field( wp_unslash( $_GET['q'] ) ) : '';
	$lang = isset( $_GET['lang'] ) ? sanitize_text_field( wp_unslash( $_GET['lang'] ) ) : '';
	$tr   = fn( $s ) => gmk_trp_translate( $s, $lang );
	$url  = fn( $link ) => gmk_trp_url( $link, $lang );
	$cat  = isset( $_GET['cat'] ) ? sanitize_title( wp_unslash( $_GET['cat'] ) ) : '';
	if ( mb_strlen( $q ) < 2 ) {
		wp_send_json_success( array( 'products' => array(), 'cats' => array() ) );
	}

	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'posts_per_page' => 8,
		's'              => $q,
		'fields'         => 'ids',
	);
	if ( $cat ) {
		$args['tax_query'] = array( array( 'taxonomy' => 'product_cat', 'field' => 'slug', 'terms' => $cat ) ); // phpcs:ignore
	}
	$ids = get_posts( $args );

	// Coincidencias por SKU.
	$like = '%' . $wpdb->esc_like( $q ) . '%';
	$sku  = $wpdb->get_col( $wpdb->prepare( "SELECT product_id FROM {$wpdb->wc_product_meta_lookup} WHERE sku LIKE %s LIMIT 8", $like ) );
	// Coincidencias por etiqueta (sirve para sinónimos, marcas o nombres en otros idiomas).
	$tags = get_terms( array( 'taxonomy' => 'product_tag', 'name__like' => $q, 'fields' => 'ids', 'hide_empty' => true ) );
	$by_t = ( $tags && ! is_wp_error( $tags ) ) ? get_posts( array( 'post_type' => 'product', 'post_status' => 'publish', 'fields' => 'ids', 'posts_per_page' => 8, 'tax_query' => array( array( 'taxonomy' => 'product_tag', 'terms' => $tags ) ) ) ) : array(); // phpcs:ignore
	$ids  = array_slice( array_unique( array_merge( $ids, array_map( 'intval', $sku ), $by_t ) ), 0, 8 );

	$products = array();
	foreach ( $ids as $id ) {
		$product = wc_get_product( $id );
		if ( ! $product || ! $product->is_visible() ) {
			continue;
		}
		$terms      = get_the_terms( $id, 'product_cat' );
		$products[] = array(
			'title' => $tr( $product->get_name() ),
			'url'   => $url( get_permalink( $id ) ),
			'price' => '' === $product->get_price() ? '' : html_entity_decode( wp_strip_all_tags( wc_price( wc_get_price_to_display( $product ) ) ), ENT_QUOTES, 'UTF-8' ),
			'img'   => wp_get_attachment_image_url( $product->get_image_id(), 'woocommerce_gallery_thumbnail' ),
			'cat'   => $terms && ! is_wp_error( $terms ) ? $tr( $terms[0]->name ) : '',
		);
	}

	$cats  = array();
	$found = get_terms( array( 'taxonomy' => 'product_cat', 'name__like' => $q, 'hide_empty' => true, 'number' => 3 ) );
	foreach ( is_wp_error( $found ) ? array() : $found as $term ) {
		$cats[] = array( 'name' => $tr( $term->name ), 'url' => $url( get_term_link( $term ) ) );
	}

	wp_send_json_success( array( 'products' => $products, 'cats' => $cats ) );
}
