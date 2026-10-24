<?php
/**
 * Shortcodes para las páginas hechas con Elementor.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * [gmk_email] Enlace al correo de la tienda.
 */
add_shortcode(
	'gmk_email',
	function () {
		return '<a class="gmk-email" href="mailto:' . esc_attr( gmk_email() ) . '">' . esc_html( gmk_email() ) . '</a>';
	}
);

/**
 * [gmk_link page="slug" text="..."] Enlace a una página o a la tienda/cuenta.
 */
add_shortcode(
	'gmk_link',
	function ( $atts ) {
		$atts = shortcode_atts( array( 'page' => 'shop', 'text' => gmk_t( 'home_more' ), 'class' => 'gmk-btn' ), $atts );
		if ( in_array( $atts['page'], array( 'shop', 'myaccount', 'cart' ), true ) && function_exists( 'wc_get_page_permalink' ) ) {
			$url = wc_get_page_permalink( $atts['page'] );
		} elseif ( 0 === strpos( $atts['page'], 'cat:' ) ) {
			$term = get_term_by( 'slug', substr( $atts['page'], 4 ), 'product_cat' );
			$url  = $term ? get_term_link( $term ) : home_url( '/' );
		} else {
			$url = gmk_page_url( $atts['page'] );
		}
		return '<a class="' . esc_attr( $atts['class'] ) . '" href="' . esc_url( $url ) . '">' . esc_html( $atts['text'] ) . '</a>';
	}
);

/**
 * [gmk_categories] Rejilla de categorías con imagen.
 */
add_shortcode(
	'gmk_categories',
	function () {
		$out = '<div class="gmk-cats">';
		foreach ( gmk_product_cats() as $term ) {
			$img  = (int) get_term_meta( $term->term_id, 'thumbnail_id', true );
			$out .= '<a class="gmk-cats__item" href="' . esc_url( get_term_link( $term ) ) . '">';
			$out .= '<span class="gmk-cats__img">' . ( $img ? wp_get_attachment_image( $img, 'woocommerce_thumbnail' ) : '' ) . '</span>';
			$out .= '<span class="gmk-cats__name">' . esc_html( $term->name ) . '</span>';
			$out .= '<span class="gmk-cats__count">' . str_replace( '%d', '<span data-no-translation>' . (int) $term->count . '</span>', esc_html( 1 === (int) $term->count ? gmk_t( 'home_count_one' ) : gmk_t( 'home_count_many' ) ) ) . '</span></a>'; // phpcs:ignore
		}
		return $out . '</div>';
	}
);

/**
 * [gmk_tiles cats="slug1,slug2,slug3,slug4"] Tarjetas estilo marketplace: 4 productos por categoría.
 */
add_shortcode(
	'gmk_tiles',
	function ( $atts ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return '';
		}
		$atts = shortcode_atts( array( 'cats' => '' ), $atts );
		$out  = '<div class="gmk-tiles">';
		foreach ( array_filter( array_map( 'trim', explode( ',', $atts['cats'] ) ) ) as $slug ) {
			$term = get_term_by( 'slug', $slug, 'product_cat' );
			if ( ! $term ) {
				continue;
			}
			$products = wc_get_products( array( 'category' => array( $slug ), 'limit' => 4, 'status' => 'publish', 'orderby' => 'rand' ) );
			$out     .= '<div class="gmk-tile"><h3>' . esc_html( $term->name ) . '</h3><div class="gmk-tile__grid">';
			foreach ( $products as $product ) {
				$out .= '<a href="' . esc_url( $product->get_permalink() ) . '">' . $product->get_image( 'woocommerce_thumbnail' ) . '<span>' . esc_html( wp_trim_words( $product->get_name(), 4, '…' ) ) . '</span></a>';
			}
			$out .= '</div><a class="gmk-tile__more" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( gmk_t( 'home_tile_more' ) . ' ' . $term->name ) . '</a></div>';
		}
		return $out . '</div>';
	}
);

/**
 * [gmk_row title="..." cat="slug" type="featured|sale|new" limit="10" link="url opcional"] Fila de productos con desplazamiento horizontal.
 */
add_shortcode(
	'gmk_row',
	function ( $atts ) {
		if ( ! function_exists( 'wc_get_products' ) ) {
			return '';
		}
		$atts = shortcode_atts( array( 'title' => '', 'cat' => '', 'type' => 'new', 'limit' => 10, 'link' => '' ), $atts );
		$args = array( 'limit' => (int) $atts['limit'], 'status' => 'publish', 'visibility' => 'catalog' );
		if ( $atts['cat'] ) {
			$args['category'] = array( $atts['cat'] );
		}
		if ( 'featured' === $atts['type'] ) {
			$args['featured'] = true;
		} elseif ( 'sale' === $atts['type'] ) {
			$args['include'] = wc_get_product_ids_on_sale() ? wc_get_product_ids_on_sale() : array( 0 );
		} else {
			$args['orderby'] = 'date';
		}
		$products = wc_get_products( $args );
		if ( ! $products ) {
			return '';
		}
		$more = '';
		if ( $atts['cat'] ) {
			$term = get_term_by( 'slug', $atts['cat'], 'product_cat' );
			$more = $term ? get_term_link( $term ) : '';
		} elseif ( 'sale' === $atts['type'] ) {
			$more = add_query_arg( 'on_sale', 1, wc_get_page_permalink( 'shop' ) );
		} else {
			$more = wc_get_page_permalink( 'shop' );
		}
		if ( $atts['link'] ) {
			$more = $atts['link'];
		}
		$out = '<section class="gmk-row"><div class="gmk-row__head"><h2>' . esc_html( $atts['title'] ) . '</h2><a href="' . esc_url( $more ) . '">' . esc_html( gmk_t( 'home_more' ) ) . '</a></div><div class="gmk-row__track">';
		foreach ( $products as $product ) {
			$out .= '<a class="gmk-row__item" href="' . esc_url( $product->get_permalink() ) . '">';
			$out .= '<span class="gmk-row__img">' . $product->get_image( 'woocommerce_thumbnail' ) . ( $product->is_on_sale() ? '<span class="onsale">' . esc_html( gmk_t( 'sale_badge' ) ) . '</span>' : '' ) . '</span>';
			$out .= '<span class="gmk-row__title">' . esc_html( $product->get_name() ) . '</span>';
			$out .= '<span class="gmk-row__price">' . $product->get_price_html() . '</span></a>';
		}
		return $out . '</div></section>';
	}
);
