<?php
/**
 * Aviso en el escritorio: plugins necesarios y recomendados para el tema.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

add_action(
	'admin_notices',
	function () {
		if ( ! current_user_can( 'install_plugins' ) || get_user_meta( get_current_user_id(), 'gmk_notice_dismissed', true ) ) {
			return;
		}
		$plugins = array(
			// Carpeta => array( nombre, ¿activo?, ¿necesario?, archivo principal ).
			'woocommerce'                 => array( 'WooCommerce', class_exists( 'WooCommerce' ), true, 'woocommerce.php' ),
			'header-footer-elementor'     => array( 'Elementor Header & Footer Builder', defined( 'HFE_VER' ), false, 'header-footer-elementor.php' ),
			'translatepress-multilingual' => array( 'TranslatePress', function_exists( 'trp_custom_language_switcher' ), false, 'index.php' ),
			'yith-woocommerce-wishlist'   => array( 'YITH WooCommerce Wishlist', defined( 'YITH_WCWL' ), false, 'init.php' ),
		);
		$missing = array_filter( $plugins, fn( $p ) => ! $p[1] );
		if ( ! $missing ) {
			return;
		}
		$links = array();
		foreach ( $missing as $slug => $plugin ) {
			$file    = $slug . '/' . $plugin[3];
			$url     = file_exists( WP_PLUGIN_DIR . '/' . $file )
				? wp_nonce_url( self_admin_url( 'plugins.php?action=activate&plugin=' . rawurlencode( $file ) ), 'activate-plugin_' . $file )
				: wp_nonce_url( self_admin_url( 'update.php?action=install-plugin&plugin=' . $slug ), 'install-plugin_' . $slug );
			$links[] = '<a href="' . esc_url( $url ) . '">' . esc_html( $plugin[0] ) . '</a>' . ( $plugin[2] ? ' <strong>(' . esc_html__( 'necesario', 'gmarket' ) . ')</strong>' : ' (' . esc_html__( 'opcional', 'gmarket' ) . ')' );
		}
		$dismiss = wp_nonce_url( add_query_arg( 'gmk_dismiss_notice', 1 ), 'gmk_dismiss_notice' );
		echo '<div class="notice notice-info"><p><strong>GMarket</strong> — ' . esc_html__( 'Para aprovechar todo el tema instala o activa:', 'gmarket' ) . ' ' . implode( ' · ', $links ) . '</p>'; // phpcs:ignore
		echo '<p><a href="' . esc_url( admin_url( 'themes.php?page=gmk-textos' ) ) . '">' . esc_html__( 'Editar los textos de la tienda', 'gmarket' ) . '</a> · <a href="' . esc_url( $dismiss ) . '">' . esc_html__( 'No volver a mostrar', 'gmarket' ) . '</a></p></div>';
	}
);

add_action(
	'admin_init',
	function () {
		if ( isset( $_GET['gmk_dismiss_notice'] ) && check_admin_referer( 'gmk_dismiss_notice' ) ) { // phpcs:ignore
			update_user_meta( get_current_user_id(), 'gmk_notice_dismissed', 1 );
			wp_safe_redirect( remove_query_arg( array( 'gmk_dismiss_notice', '_wpnonce' ) ) );
			exit;
		}
	}
);
