<?php
/**
 * Ajustes de WooCommerce para la tienda.
 * Los textos salen de Apariencia › Textos de la tienda (gmk_t).
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

// 24 productos por página, en rejilla de 4 columnas (el CSS adapta a la pantalla).
add_filter( 'loop_shop_per_page', fn() => 24 );
add_filter( 'loop_shop_columns', fn() => 4 );

add_filter(
	'woocommerce_breadcrumb_defaults',
	function ( $args ) {
		$args['delimiter']   = '<span class="sep">›</span>';
		$args['home']        = gmk_t( 'crumb_home' );
		$args['wrap_before'] = '<nav class="woocommerce-breadcrumb gmk-breadcrumb" aria-label="' . esc_attr__( 'Ruta de navegación', 'gmarket' ) . '">';
		return $args;
	}
);

add_filter( 'woocommerce_sale_flash', fn() => '<span class="onsale">' . esc_html( gmk_t( 'sale_badge' ) ) . '</span>' );

// Tarjeta del listado: categoría encima del título y línea de entrega.
add_action(
	'woocommerce_shop_loop_item_title',
	function () {
		global $product;
		$terms = get_the_terms( $product->get_id(), 'product_cat' );
		if ( $terms && ! is_wp_error( $terms ) ) {
			$name = is_product_category() ? get_queried_object()->name : $terms[0]->name;
			echo '<span class="gmk-card__cat">' . esc_html( $name ) . '</span>';
		}
	},
	5
);

add_action(
	'woocommerce_after_shop_loop_item_title',
	function () {
		if ( '' !== gmk_t( 'card_ship' ) ) {
			echo '<span class="gmk-card__ship">' . gmk_icon( 'truck' ) . ' ' . esc_html( gmk_t( 'card_ship' ) ) . '</span>'; // phpcs:ignore
		}
	},
	15
);

// Tarjeta del listado: «Leer más / Añadir al carrito» y «Guardar» como dos botones en una fila
// (WooCommerce pinta el botón con prioridad 10 y la lista de deseos con prioridad 15).
add_action( 'woocommerce_after_shop_loop_item', fn() => print( '<div class="gmk-card__actions">' ), 8 );
add_action( 'woocommerce_after_shop_loop_item', fn() => print( '</div>' ), 20 );

// Ficha de producto: aviso de «precio próximamente» (solo si no tiene precio) y ventajas.
add_action(
	'woocommerce_single_product_summary',
	function () {
		global $product;
		if ( '' === $product->get_price() ) {
			echo '<div class="gmk-ask-price"><strong>' . esc_html( gmk_t( 'soon_title' ) ) . '</strong> ' . esc_html( gmk_t( 'soon_before' ) ) . ' <a href="mailto:' . esc_attr( gmk_email() ) . '">' . esc_html( gmk_email() ) . '</a> ' . esc_html( gmk_t( 'soon_after' ) ) . '</div>';
		}
		$items = array(
			array( 'globe', 'box1' ),
			array( 'truck', 'box2' ),
			array( 'shield', 'box3' ),
		);
		echo '<ul class="gmk-buybox">';
		foreach ( $items as $item ) {
			$title = gmk_t( $item[1] . '_title' );
			$text  = gmk_t( $item[1] . '_text' );
			if ( '' === $title && '' === $text ) {
				continue;
			}
			echo '<li>' . gmk_icon( $item[0] ) . '<span><strong>' . esc_html( $title ) . '</strong> ' . esc_html( $text ) . '</span></li>'; // phpcs:ignore
		}
		echo '</ul>';
	},
	35
);

add_filter(
	'woocommerce_output_related_products_args',
	function ( $args ) {
		$args['posts_per_page'] = 5;
		$args['columns']        = 5;
		return $args;
	}
);
add_filter( 'woocommerce_upsell_display_args', fn( $args ) => array_merge( $args, array( 'posts_per_page' => 5, 'columns' => 5 ) ) );

add_filter(
	'woocommerce_product_tabs',
	function ( $tabs ) {
		if ( isset( $tabs['additional_information'] ) ) {
			$tabs['additional_information']['title'] = gmk_t( 'tab_specs' );
		}
		return $tabs;
	}
);

// Contador del carrito en la cabecera, actualizado por AJAX.
add_filter(
	'woocommerce_add_to_cart_fragments',
	function ( $fragments ) {
		$fragments['span.gmk-cart__count'] = '<span class="gmk-cart__count">' . esc_html( gmk_cart_count() ) . '</span>';
		return $fragments;
	}
);

// Título de la tienda y de las búsquedas (lo buscado va aparte para no crear una traducción por búsqueda).
add_filter(
	'woocommerce_page_title',
	function ( $title ) {
		if ( is_search() ) {
			return '<span>' . esc_html( gmk_t( 'search_title' ) ) . '</span> <span data-no-translation>“' . esc_html( get_search_query() ) . '”</span>';
		}
		return is_shop() ? esc_html( gmk_t( 'shop_title' ) ) : $title;
	}
);

// Sin resultados: debajo del aviso, productos que pueden interesar.
add_action(
	'woocommerce_no_products_found',
	function () {
		$count = max( 0, (int) gmk_t( 'noresults_count' ) );
		if ( ! $count ) {
			return;
		}
		$ids = wc_get_featured_product_ids();
		if ( ! $ids ) {
			$ids = wc_get_products( array( 'limit' => $count, 'return' => 'ids', 'orderby' => 'rand' ) );
		}
		shuffle( $ids );
		$ids = array_slice( $ids, 0, $count );
		if ( ! $ids ) {
			return;
		}
		echo '<section class="gmk-suggested"><h2 class="gmk-suggested__title">' . esc_html( gmk_t( 'noresults_title' ) ) . '</h2>';
		echo do_shortcode( '[products ids="' . esc_attr( implode( ',', $ids ) ) . '" limit="' . (int) $count . '" columns="4" orderby="post__in"]' );
		echo '</section>';
	},
	20
);

// Mi cuenta: textos de las secciones.
add_filter(
	'woocommerce_account_menu_items',
	function ( $items ) {
		// «Descargas» solo si la tienda vende productos descargables.
		if ( ! wc_get_products( array( 'downloadable' => true, 'limit' => 1, 'return' => 'ids' ) ) ) {
			unset( $items['downloads'] );
		}
		$labels = array(
			'dashboard'       => 'acc_dashboard',
			'orders'          => 'acc_orders',
			'payment-methods' => 'acc_payment',
			'edit-address'    => 'acc_address',
			'edit-account'    => 'acc_account',
		);
		foreach ( $labels as $item => $key ) {
			if ( isset( $items[ $item ] ) ) {
				$items[ $item ] = gmk_t( $key );
			}
		}
		return $items;
	}
);
