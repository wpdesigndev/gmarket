<?php
/**
 * Compatibilidad con TranslatePress (opcional): idioma actual, traducciones de la búsqueda en vivo
 * y textos de YITH Wishlist que TranslatePress no alcanza.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Ajustes de TranslatePress (o vacío si no está activo).
 */
function gmk_trp_settings() {
	if ( ! function_exists( 'trp_custom_language_switcher' ) ) {
		return array();
	}
	return (array) get_option( 'trp_settings', array() );
}

/**
 * Código del idioma por defecto (p. ej. «es_ES»).
 */
function gmk_default_language() {
	$settings = gmk_trp_settings();
	return ! empty( $settings['default-language'] ) ? $settings['default-language'] : get_locale();
}

/**
 * Código del idioma en que se muestra la página.
 */
function gmk_current_language() {
	global $TRP_LANGUAGE; // phpcs:ignore
	return ( gmk_trp_settings() && ! empty( $TRP_LANGUAGE ) ) ? $TRP_LANGUAGE : gmk_default_language(); // phpcs:ignore
}

/**
 * 'main' si la página está en el idioma por defecto; 'alt' si está en otro idioma de TranslatePress.
 */
function gmk_lang() {
	return gmk_current_language() === gmk_default_language() ? 'main' : 'alt';
}

/**
 * ¿Es un idioma configurado en TranslatePress?
 */
function gmk_is_language( $code ) {
	$settings = gmk_trp_settings();
	return ! empty( $settings['translation-languages'] ) && in_array( $code, (array) $settings['translation-languages'], true );
}

/**
 * Traduce un texto con el diccionario de TranslatePress (nombres de productos y categorías).
 */
function gmk_trp_translate( $text, $language ) {
	static $cache = array();
	$text = trim( $text );
	if ( '' === $text || $language === gmk_default_language() || ! gmk_is_language( $language ) ) {
		return $text;
	}
	$key = $language . '|' . $text;
	if ( ! array_key_exists( $key, $cache ) ) {
		global $wpdb;
		$table = $wpdb->prefix . 'trp_dictionary_' . strtolower( gmk_default_language() ) . '_' . strtolower( $language );
		$found = $wpdb->get_var( $wpdb->prepare( "SELECT translated FROM {$table} WHERE original = %s AND status <> 0 LIMIT 1", $text ) ); // phpcs:ignore
		$cache[ $key ] = $found ? $found : $text;
	}
	return $cache[ $key ];
}

/**
 * URL equivalente en otro idioma de TranslatePress.
 */
function gmk_trp_url( $url, $language ) {
	if ( $language === gmk_default_language() || ! gmk_is_language( $language ) || ! class_exists( 'TRP_Translate_Press' ) ) {
		return $url;
	}
	return TRP_Translate_Press::get_trp_instance()->get_component( 'url_converter' )->get_url_for_language( $language, $url, '' );
}

// Textos de YITH Wishlist en el segundo idioma (YITH los pinta con JavaScript, fuera del alcance de TranslatePress).
foreach (
	array(
		'yith_wcwl_add_to_wishlist_text'     => 'alt_wish_add',
		'yith_wcwl_browse_wishlist_text'     => 'alt_wish_view',
		'yith_wcwl_product_added_text'       => 'alt_wish_added',
		'yith_wcwl_already_in_wishlist_text' => 'alt_wish_already',
		'yith_wcwl_wishlist_title'           => 'alt_wish_title',
	) as $gmk_option => $gmk_key
) {
	add_filter(
		'option_' . $gmk_option,
		function ( $value ) use ( $gmk_key ) {
			if ( is_admin() || 'alt' !== gmk_lang() ) {
				return $value;
			}
			$text = gmk_t( $gmk_key );
			return '' !== $text ? $text : $value;
		}
	);
}
