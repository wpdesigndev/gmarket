<?php
/**
 * Piezas reutilizables de las plantillas.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Categorías de producto de primer nivel, ordenadas por nombre.
 */
function gmk_product_cats() {
	if ( ! taxonomy_exists( 'product_cat' ) ) {
		return array();
	}
	$terms = get_terms(
		array(
			'taxonomy'   => 'product_cat',
			'hide_empty' => true,
			'parent'     => 0,
			'exclude'    => array( (int) get_option( 'default_product_cat' ) ),
		)
	);
	return is_wp_error( $terms ) ? array() : $terms;
}

/**
 * Logo de la tienda (personalizado o nombre del sitio).
 */
function gmk_logo() {
	if ( has_custom_logo() ) {
		the_custom_logo();
		return;
	}
	echo '<a class="gmk-logo__text" href="' . esc_url( home_url( '/' ) ) . '">' . esc_html( get_bloginfo( 'name' ) ) . '</a>';
}

/**
 * Isotipo del logo (icono del sitio) para la cabecera del móvil.
 */
function gmk_logo_icon() {
	$id = (int) get_option( 'site_icon' );
	if ( ! $id ) {
		return;
	}
	echo '<a class="gmk-logo__icon" href="' . esc_url( home_url( '/' ) ) . '" rel="home" aria-label="' . esc_attr( get_bloginfo( 'name' ) ) . '">' . wp_get_attachment_image( $id, array( 96, 96 ), false, array( 'alt' => get_bloginfo( 'name' ) ) ) . '</a>';
}

/**
 * URL de una página por su slug.
 */
function gmk_page_url( $slug ) {
	$page = get_page_by_path( $slug );
	return $page ? get_permalink( $page ) : home_url( '/' . $slug . '/' );
}

/**
 * Menú de reserva cuando no hay un menú asignado.
 */
function gmk_menu_fallback( $args ) {
	$items = '';
	foreach ( array_slice( gmk_product_cats(), 0, 9 ) as $term ) {
		$items .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
	}
	printf( '<ul class="%s">%s</ul>', esc_attr( $args['menu_class'] ), $items ); // phpcs:ignore
}

/**
 * Selector de idioma ES | EN (TranslatePress).
 */
function gmk_lang_switch( $class = '' ) {
	if ( ! function_exists( 'trp_custom_language_switcher' ) ) {
		return;
	}
	global $TRP_LANGUAGE; // phpcs:ignore
	$langs = trp_custom_language_switcher();
	if ( count( $langs ) < 2 ) {
		return;
	}
	echo '<nav class="gmk-lang ' . esc_attr( $class ) . '" aria-label="' . esc_attr__( 'Idioma', 'gmarket' ) . '" data-no-translation>';
	foreach ( $langs as $code => $lang ) {
		$short = strtoupper( substr( $code, 0, 2 ) );
		$on    = $code === $TRP_LANGUAGE; // phpcs:ignore
		printf(
			'<a href="%s" hreflang="%s" lang="%s" title="%s"%s data-no-translation>%s</a>',
			esc_url( $lang['current_page_url'] ),
			esc_attr( substr( $code, 0, 2 ) ),
			esc_attr( substr( $code, 0, 2 ) ),
			esc_attr( $lang['language_name'] ),
			$on ? ' class="on" aria-current="true"' : '',
			esc_html( $short )
		);
	}
	echo '</nav>';
}

/**
 * Iconos de los métodos de pago.
 */
function gmk_pay_icons() {
	$icons = require GMK_DIR . '/inc/pay-icons.php';
	$out   = '<ul class="gmk-pay" aria-label="' . esc_attr__( 'Métodos de pago aceptados', 'gmarket' ) . '">';
	$show  = array_filter( explode( ',', gmk_t( 'pay_icons' ) ) ); // Apariencia › Textos de la tienda.
	foreach ( $icons as $key => $icon ) {
		if ( $show && ! in_array( $key, $show, true ) ) {
			continue;
		}
		list( $label, $w, $h, $path ) = $icon;
		$out .= '<li class="gmk-pay__' . esc_attr( $key ) . '" title="' . esc_attr( $label ) . '"><svg viewBox="0 0 ' . (int) $w . ' ' . (int) $h . '" role="img" aria-label="' . esc_attr( $label ) . '"><path fill="currentColor" d="' . esc_attr( $path ) . '"/></svg></li>';
	}
	return $out . '</ul>';
}

/**
 * Logo del pie (versión blanca, sin fondo).
 */
function gmk_footer_logo() {
	$id = (int) get_theme_mod( 'gmk_footer_logo' );
	if ( ! $id ) {
		gmk_logo();
		return;
	}
	echo '<a href="' . esc_url( home_url( '/' ) ) . '" class="gmk-footer__logo" rel="home">' . wp_get_attachment_image( $id, 'medium', false, array( 'alt' => get_bloginfo( 'name' ), 'loading' => 'lazy' ) ) . '</a>';
}

/**
 * Número de productos en el carrito.
 */
function gmk_cart_count() {
	return ( function_exists( 'WC' ) && WC()->cart ) ? WC()->cart->get_cart_contents_count() : 0;
}
