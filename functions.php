<?php
/**
 * GMarket: funciones del tema.
 *
 * Desarrollado por GMETI.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GMK_VERSION', '1.0.1' );
define( 'GMK_DIR', get_template_directory() );
define( 'GMK_URI', get_template_directory_uri() );

add_action(
	'after_setup_theme',
	function () {
		load_theme_textdomain( 'gmarket', GMK_DIR . '/languages' );
		add_theme_support( 'title-tag' );
		add_theme_support( 'automatic-feed-links' );
		add_theme_support( 'responsive-embeds' );
		add_theme_support( 'header-footer-elementor' );
		add_theme_support( 'post-thumbnails' );
		add_theme_support( 'html5', array( 'search-form', 'comment-form', 'comment-list', 'gallery', 'caption', 'style', 'script' ) );
		add_theme_support( 'custom-logo', array( 'height' => 120, 'width' => 200, 'flex-height' => true, 'flex-width' => true ) );
		add_theme_support(
			'woocommerce',
			array(
				'thumbnail_image_width' => 400,
				'single_image_width'    => 800,
				'product_grid'          => array( 'default_columns' => 4, 'default_rows' => 6 ),
			)
		);
		add_theme_support( 'wc-product-gallery-zoom' );
		add_theme_support( 'wc-product-gallery-lightbox' );
		add_theme_support( 'wc-product-gallery-slider' );
		register_nav_menus(
			array(
				'primary'     => __( 'Barra de navegación', 'gmarket' ),
				'footer_1'    => __( 'Pie: columna 1', 'gmarket' ),
				'footer_2'    => __( 'Pie: columna 2', 'gmarket' ),
				'footer_3'    => __( 'Pie: columna 3', 'gmarket' ),
				'drawer_help' => __( 'Menú lateral – Ayuda', 'gmarket' ),
			)
		);
	}
);

add_action(
	'wp_enqueue_scripts',
	function () {
		wp_enqueue_style( 'gmk-fonts', 'https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap', array(), null );
		wp_enqueue_style( 'gmk-main', GMK_URI . '/assets/css/main.css', array(), GMK_VERSION );
		wp_add_inline_style( 'gmk-main', gmk_colors_css() );
		wp_enqueue_script( 'gmk-main', GMK_URI . '/assets/js/main.js', array(), GMK_VERSION, true );
		$alt = 'alt' === gmk_lang();
		wp_localize_script(
			'gmk-main',
			'GMK',
			array(
				'ajax'  => admin_url( 'admin-ajax.php' ),
				'nonce' => wp_create_nonce( 'gmk_search' ),
				'shop'  => function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'shop' ) : home_url( '/' ),
				'lang'  => gmk_current_language(),
				'i18n'  => $alt
					? array( 'none' => gmk_t( 'alt_search_none' ), 'all' => gmk_t( 'alt_search_all' ), 'cat' => gmk_t( 'alt_search_cat' ), 'short' => gmk_t( 'alt_search_short' ) )
					: array( 'none' => gmk_t( 'search_none' ), 'all' => gmk_t( 'search_see_all' ), 'cat' => gmk_t( 'search_cat' ), 'short' => gmk_t( 'search_ph_short' ) ),
			)
		);
		if ( is_singular() && comments_open() && get_option( 'thread_comments' ) ) {
			wp_enqueue_script( 'comment-reply' );
		}
	}
);

add_action(
	'widgets_init',
	function () {
		register_sidebar(
			array(
				'name'          => __( 'Tienda: filtros extra', 'gmarket' ),
				'id'            => 'shop-sidebar',
				'description'   => __( 'Widgets que aparecen debajo de los filtros de la tienda.', 'gmarket' ),
				'before_widget' => '<section class="gmk-filter %2$s">',
				'after_widget'  => '</section>',
				'before_title'  => '<h3 class="gmk-filter__title">',
				'after_title'   => '</h3>',
			)
		);
	}
);

/**
 * Correo de contacto de la tienda (Apariencia › Textos de la tienda; si está vacío, el del administrador).
 */
function gmk_email() {
	$email = gmk_t( 'email' );
	return $email ? $email : get_option( 'admin_email' );
}

/**
 * Icono SVG en línea.
 */
function gmk_icon( $name ) {
	$icons = array(
		'search'  => '<path d="M21 21l-4.35-4.35M11 18a7 7 0 1 1 0-14 7 7 0 0 1 0 14z"/>',
		'cart'    => '<circle cx="9" cy="20" r="1.5"/><circle cx="18" cy="20" r="1.5"/><path d="M2 3h3l2.4 11.2a2 2 0 0 0 2 1.6h8.4a2 2 0 0 0 2-1.5L22 7H6"/>',
		'user'    => '<circle cx="12" cy="8" r="4"/><path d="M4 21c0-4 4-6 8-6s8 2 8 6"/>',
		'menu'    => '<path d="M3 6h18M3 12h18M3 18h18"/>',
		'pin'     => '<path d="M12 22s7-6.2 7-12a7 7 0 0 0-14 0c0 5.8 7 12 7 12z"/><circle cx="12" cy="10" r="2.5"/>',
		'close'   => '<path d="M6 6l12 12M18 6L6 18"/>',
		'heart'   => '<path d="M20.8 4.6a5.5 5.5 0 0 0-7.8 0L12 5.7l-1-1.1a5.5 5.5 0 0 0-7.8 7.8L12 21l8.8-8.6a5.5 5.5 0 0 0 0-7.8z"/>',
		'mail'    => '<rect x="3" y="5" width="18" height="14" rx="2"/><path d="M3 7l9 6 9-6"/>',
		'truck'   => '<path d="M3 6h11v10H3zM14 9h4l3 3v4h-7"/><circle cx="7" cy="18" r="1.8"/><circle cx="17" cy="18" r="1.8"/>',
		'shield'  => '<path d="M12 3l8 3v6c0 5-3.5 8-8 9-4.5-1-8-4-8-9V6z"/><path d="M9 12l2 2 4-4"/>',
		'globe'   => '<circle cx="12" cy="12" r="9"/><path d="M3 12h18M12 3c3 3 3 15 0 18M12 3c-3 3-3 15 0 18"/>',
		'card'    => '<rect x="2" y="5" width="20" height="14" rx="2"/><path d="M2 10h20M6 15h4"/>',
		'filter'  => '<path d="M3 5h18l-7 8v6l-4 2v-8z"/>',
		'box'     => '<path d="M21 8l-9-5-9 5 9 5 9-5zM3 8v8l9 5 9-5V8M12 13v8"/>',
		'chevron' => '<path d="M9 6l6 6-6 6"/>',
	);
	$path = isset( $icons[ $name ] ) ? $icons[ $name ] : '';
	return '<svg class="gmk-icon gmk-icon--' . esc_attr( $name ) . '" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">' . $path . '</svg>';
}

require GMK_DIR . '/inc/settings.php';
require GMK_DIR . '/inc/customizer.php';
require GMK_DIR . '/inc/translatepress.php';
require GMK_DIR . '/inc/template-tags.php';
require GMK_DIR . '/inc/shortcodes.php';
require GMK_DIR . '/inc/header-parts.php';
require GMK_DIR . '/inc/admin-notice.php';
require GMK_DIR . '/inc/updater.php';
if ( class_exists( 'WooCommerce' ) ) {
	require GMK_DIR . '/inc/woocommerce.php';
	require GMK_DIR . '/inc/filters.php';
	require GMK_DIR . '/inc/live-search.php';
}
