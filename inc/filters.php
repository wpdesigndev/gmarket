<?php
/**
 * Filtros de la tienda estilo marketplace.
 *
 * Usa los parámetros nativos de WooCommerce (min_price, max_price, filter_{atributo},
 * rating_filter) y añade «on_sale» (ofertas) e «in_stock» (disponibles). Los recuentos se calculan sobre los
 * productos del contexto actual (categoría o búsqueda), así solo se ofrecen opciones con resultados.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Atributos que se pueden filtrar: taxonomía => título.
 */
function gmk_filter_attributes() {
	$chosen = gmk_t( 'f_attrs' ); // «*» = todos (por defecto), «-» = ninguno.
	$slugs  = '*' === $chosen
		? wp_list_pluck( wc_get_attribute_taxonomies(), 'attribute_name' )
		: array_filter( array_map( 'trim', explode( ',', $chosen ) ), fn( $s ) => '' !== $s && '-' !== $s );
	$out    = array();
	foreach ( $slugs as $slug ) {
		$out[ 'pa_' . $slug ] = wc_attribute_label( 'pa_' . $slug );
	}
	return $out;
}

/**
 * Importe con el símbolo de la moneda de la tienda, sin decimales si es entero.
 */
function gmk_money( $amount ) {
	$amount = (float) $amount;
	$html   = wc_price( $amount, array( 'decimals' => floor( $amount ) === $amount ? 0 : wc_get_price_decimals() ) );
	return html_entity_decode( wp_strip_all_tags( $html ), ENT_QUOTES, 'UTF-8' );
}

/**
 * Tramos de precio: [mínimo, máximo|null, etiqueta].
 */
function gmk_price_ranges() {
	$points = array_values( array_filter( array_map( 'floatval', explode( ',', gmk_t( 'f_ranges' ) ) ) ) );
	sort( $points );
	$fmt    = 'gmk_money';
	$ranges = array();
	$prev   = 0;
	foreach ( $points as $i => $p ) {
		$ranges[] = array( $prev, $p, 0 === $i ? sprintf( gmk_t( 'f_under' ), $fmt( $p ) ) : sprintf( gmk_t( 'f_between' ), $fmt( $prev ), $fmt( $p ) ) );
		$prev     = $p;
	}
	if ( $points ) {
		$ranges[] = array( $prev, null, sprintf( gmk_t( 'f_over' ), $fmt( $prev ) ) );
	}
	return $ranges;
}

/**
 * Parámetros GET que pertenecen a los filtros.
 */
function gmk_filter_keys() {
	$keys = array( 'min_price', 'max_price', 'rating_filter', 'on_sale', 'in_stock', 'paged' );
	foreach ( array_keys( gmk_filter_attributes() ) as $tax ) {
		$name   = wc_attribute_taxonomy_slug( $tax );
		$keys[] = 'filter_' . $name;
		$keys[] = 'query_type_' . $name;
	}
	return $keys;
}

/**
 * URL base de la página actual (sin paginación), conservando búsqueda y orden.
 */
function gmk_filter_base_url() {
	if ( is_product_taxonomy() ) {
		$url = get_term_link( get_queried_object() );
	} else {
		$url = wc_get_page_permalink( 'shop' );
	}
	$keep = array( 's', 'post_type', 'orderby', 'product_cat' );
	foreach ( gmk_filter_keys() as $key ) {
		$keep[] = $key;
	}
	$args = array();
	foreach ( $keep as $key ) {
		if ( isset( $_GET[ $key ] ) && '' !== $_GET[ $key ] && 'paged' !== $key ) { // phpcs:ignore
			$args[ $key ] = rawurlencode( sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) ); // phpcs:ignore
		}
	}
	return add_query_arg( $args, $url );
}

/**
 * IDs de productos visibles del contexto actual, sin aplicar los filtros.
 */
function gmk_context_product_ids() {
	static $ids = null;
	if ( null !== $ids ) {
		return $ids;
	}
	$args = array(
		'post_type'      => 'product',
		'post_status'    => 'publish',
		'fields'         => 'ids',
		'posts_per_page' => -1,
		'no_found_rows'  => true,
		'tax_query'      => array( // phpcs:ignore
			array(
				'taxonomy' => 'product_visibility',
				'field'    => 'name',
				'terms'    => array( 'exclude-from-catalog' ),
				'operator' => 'NOT IN',
			),
		),
	);
	if ( is_product_taxonomy() ) {
		$term                = get_queried_object();
		$args['tax_query'][] = array(
			'taxonomy' => $term->taxonomy,
			'field'    => 'term_id',
			'terms'    => array( $term->term_id ),
		);
	} elseif ( ! empty( $_GET['product_cat'] ) ) { // phpcs:ignore
		$args['tax_query'][] = array(
			'taxonomy' => 'product_cat',
			'field'    => 'slug',
			'terms'    => sanitize_title( wp_unslash( $_GET['product_cat'] ) ), // phpcs:ignore
		);
	}
	if ( is_search() ) {
		$args['s'] = get_search_query();
	}
	$ids = get_posts( $args );
	return $ids;
}

/**
 * Valores seleccionados de un parámetro separado por comas.
 */
function gmk_selected( $key ) {
	if ( empty( $_GET[ $key ] ) ) { // phpcs:ignore
		return array();
	}
	return array_filter( array_map( 'sanitize_title', explode( ',', wp_unslash( $_GET[ $key ] ) ) ) ); // phpcs:ignore
}

/**
 * Enlace de opción de filtro con casilla.
 */
function gmk_filter_link( $url, $label, $active, $count = null ) {
	printf(
		'<li><a class="gmk-check%s" href="%s" rel="nofollow"><span class="gmk-check__box" aria-hidden="true"></span><span class="gmk-check__label">%s</span>%s</a></li>',
		$active ? ' is-active' : '',
		esc_url( $url ),
		esc_html( $label ),
		null === $count ? '' : '<span class="gmk-check__count">' . (int) $count . '</span>'
	);
}

/**
 * Barra lateral de filtros.
 */
function gmk_filters_sidebar() {
	global $wpdb;
	$ids  = gmk_context_product_ids();
	$base = gmk_filter_base_url();

	// Categorías.
	$current = is_product_taxonomy() ? get_queried_object() : null;
	$cats    = gmk_product_cats();
	if ( $cats ) {
		echo '<section class="gmk-filter"><h3 class="gmk-filter__title">' . esc_html( gmk_t( 'f_cat' ) ) . '</h3><ul class="gmk-filter__cats">';
		echo '<li><a class="' . ( is_shop() ? 'is-active' : '' ) . '" href="' . esc_url( wc_get_page_permalink( 'shop' ) ) . '">' . esc_html( gmk_t( 'f_all_cats' ) ) . '</a></li>';
		foreach ( $cats as $term ) {
			$active = $current && $current->term_id === $term->term_id;
			echo '<li><a class="' . ( $active ? 'is-active' : '' ) . '" href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . ' <span>(' . (int) $term->count . ')</span></a></li>';
		}
		echo '</ul></section>';
	}

	if ( ! $ids ) {
		return;
	}
	$in = implode( ',', array_map( 'intval', $ids ) );

	// Precio (solo si hay productos con precio).
	$priced  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN ($in) AND max_price > 0" ); // phpcs:ignore
	$min_sel = isset( $_GET['min_price'] ) ? (float) $_GET['min_price'] : null; // phpcs:ignore
	$max_sel = isset( $_GET['max_price'] ) ? (float) $_GET['max_price'] : null; // phpcs:ignore
	if ( $priced ) :
	echo '<section class="gmk-filter"><h3 class="gmk-filter__title">' . esc_html( gmk_t( 'f_price' ) ) . '</h3><ul>';
	foreach ( gmk_price_ranges() as $range ) {
		list( $min, $max, $label ) = $range;
		$where = $wpdb->prepare( 'min_price >= %f', $min ) . ( null === $max ? '' : $wpdb->prepare( ' AND min_price < %f', $max ) );
		$count = (int) $wpdb->get_var( "SELECT COUNT(DISTINCT product_id) FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN ($in) AND $where" ); // phpcs:ignore
		if ( ! $count ) {
			continue;
		}
		$active = null !== $min_sel && (float) $min === $min_sel && ( null === $max ? null === $max_sel : (float) $max === $max_sel );
		$url    = $active ? remove_query_arg( array( 'min_price', 'max_price' ), $base ) : add_query_arg( array( 'min_price' => $min, 'max_price' => $max ), remove_query_arg( 'max_price', $base ) );
		gmk_filter_link( $url, $label, $active, $count );
	}
	echo '</ul>';
	?>
	<form class="gmk-price-form" method="get" action="<?php echo esc_url( strtok( $base, '?' ) ); ?>">
		<?php
		foreach ( array( 's', 'post_type', 'orderby', 'product_cat', 'on_sale', 'in_stock', 'rating_filter' ) as $key ) {
			if ( ! empty( $_GET[ $key ] ) ) { // phpcs:ignore
				echo '<input type="hidden" name="' . esc_attr( $key ) . '" value="' . esc_attr( sanitize_text_field( wp_unslash( $_GET[ $key ] ) ) ) . '">'; // phpcs:ignore
			}
		}
		foreach ( array_keys( gmk_filter_attributes() ) as $tax ) {
			$name = wc_attribute_taxonomy_slug( $tax );
			if ( gmk_selected( 'filter_' . $name ) ) {
				echo '<input type="hidden" name="filter_' . esc_attr( $name ) . '" value="' . esc_attr( implode( ',', gmk_selected( 'filter_' . $name ) ) ) . '"><input type="hidden" name="query_type_' . esc_attr( $name ) . '" value="or">';
			}
		}
		?>
		<label><span class="screen-reader-text"><?php esc_html_e( 'Mínimo', 'gmarket' ); ?></span><input type="number" name="min_price" min="0" step="1" placeholder="<?php echo esc_attr( gmk_t( 'f_min' ) ); ?>" value="<?php echo null === $min_sel ? '' : esc_attr( $min_sel ); ?>"></label>
		<label><span class="screen-reader-text"><?php esc_html_e( 'Máximo', 'gmarket' ); ?></span><input type="number" name="max_price" min="0" step="1" placeholder="<?php echo esc_attr( gmk_t( 'f_max' ) ); ?>" value="<?php echo null === $max_sel ? '' : esc_attr( $max_sel ); ?>"></label>
		<button type="submit" class="gmk-btn gmk-btn--small"><?php echo esc_html( gmk_t( 'f_go' ) ); ?></button>
	</form>
	</section>
	<?php
	endif;

	// Atributos.
	foreach ( gmk_filter_attributes() as $tax => $title ) {
		if ( ! taxonomy_exists( $tax ) ) {
			continue;
		}
		$name     = wc_attribute_taxonomy_slug( $tax );
		$key      = 'filter_' . $name;
		$selected = gmk_selected( $key );
		$rows     = $wpdb->get_results( // phpcs:ignore
			$wpdb->prepare(
				"SELECT t.slug, t.name, COUNT(DISTINCT tr.object_id) AS c FROM {$wpdb->term_relationships} tr
				INNER JOIN {$wpdb->term_taxonomy} tt ON tt.term_taxonomy_id = tr.term_taxonomy_id
				INNER JOIN {$wpdb->terms} t ON t.term_id = tt.term_id
				WHERE tt.taxonomy = %s AND tr.object_id IN ($in) GROUP BY t.term_id ORDER BY t.name", // phpcs:ignore
				$tax
			)
		);
		if ( ! $rows ) {
			continue;
		}
		echo '<section class="gmk-filter"><h3 class="gmk-filter__title">' . esc_html( $title ) . '</h3><ul>';
		foreach ( $rows as $row ) {
			$active = in_array( $row->slug, $selected, true );
			$next   = $active ? array_diff( $selected, array( $row->slug ) ) : array_merge( $selected, array( $row->slug ) );
			$url    = $next
				? add_query_arg( array( $key => implode( ',', $next ), 'query_type_' . $name => 'or' ), $base )
				: remove_query_arg( array( $key, 'query_type_' . $name ), $base );
			gmk_filter_link( $url, $row->name, $active, $row->c );
		}
		echo '</ul></section>';
	}

	// Valoración (solo si hay productos valorados).
	$rated = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN ($in) AND average_rating >= 4" ); // phpcs:ignore
	if ( $rated ) {
		$active = '4,5' === ( isset( $_GET['rating_filter'] ) ? sanitize_text_field( wp_unslash( $_GET['rating_filter'] ) ) : '' ); // phpcs:ignore
		echo '<section class="gmk-filter"><h3 class="gmk-filter__title">' . esc_html( gmk_t( 'f_rating' ) ) . '</h3><ul>';
		gmk_filter_link( $active ? remove_query_arg( 'rating_filter', $base ) : add_query_arg( 'rating_filter', '4,5', $base ), gmk_t( 'f_rating_opt' ), $active, $rated );
		echo '</ul></section>';
	}

	// Disponibilidad y ofertas.
	$on_sale   = array_intersect( $ids, wc_get_product_ids_on_sale() );
	$in_stock  = (int) $wpdb->get_var( "SELECT COUNT(*) FROM {$wpdb->wc_product_meta_lookup} WHERE product_id IN ($in) AND stock_status = 'instock'" ); // phpcs:ignore
	$is_sale   = ! empty( $_GET['on_sale'] ); // phpcs:ignore
	$is_stock  = ! empty( $_GET['in_stock'] ); // phpcs:ignore
	echo '<section class="gmk-filter"><h3 class="gmk-filter__title">' . esc_html( gmk_t( 'f_avail' ) ) . '</h3><ul>';
	gmk_filter_link( $is_stock ? remove_query_arg( 'in_stock', $base ) : add_query_arg( 'in_stock', 1, $base ), gmk_t( 'f_instock' ), $is_stock, $in_stock );
	if ( $on_sale ) {
		gmk_filter_link( $is_sale ? remove_query_arg( 'on_sale', $base ) : add_query_arg( 'on_sale', 1, $base ), gmk_t( 'f_onsale' ), $is_sale, count( $on_sale ) );
	}
	echo '</ul></section>';
}

/**
 * Etiquetas de los filtros activos, cada una con su botón para quitarla.
 */
function gmk_active_filters() {
	$base  = gmk_filter_base_url();
	$chips = array();

	if ( isset( $_GET['min_price'] ) || isset( $_GET['max_price'] ) ) { // phpcs:ignore
		$min     = gmk_money( isset( $_GET['min_price'] ) ? (float) $_GET['min_price'] : 0 ); // phpcs:ignore
		$max     = isset( $_GET['max_price'] ) ? gmk_money( (float) $_GET['max_price'] ) : ''; // phpcs:ignore
		// Los importes van aparte para que «Precio:» y «Precio desde» se traduzcan una sola vez.
		$label   = isset( $_GET['max_price'] ) // phpcs:ignore
			? '<span>' . esc_html( gmk_t( 'f_chip_price' ) ) . '</span> <span data-no-translation>' . esc_html( $min . ' – ' . $max ) . '</span>'
			: '<span>' . esc_html( gmk_t( 'f_chip_from' ) ) . '</span> <span data-no-translation>' . esc_html( $min ) . '</span>';
		$chips[] = array( $label, remove_query_arg( array( 'min_price', 'max_price' ), $base ), true );
	}
	foreach ( gmk_filter_attributes() as $tax => $title ) {
		$name = wc_attribute_taxonomy_slug( $tax );
		$sel  = gmk_selected( 'filter_' . $name );
		foreach ( $sel as $slug ) {
			$term    = get_term_by( 'slug', $slug, $tax );
			$rest    = array_diff( $sel, array( $slug ) );
			$url     = $rest ? add_query_arg( 'filter_' . $name, implode( ',', $rest ), $base ) : remove_query_arg( array( 'filter_' . $name, 'query_type_' . $name ), $base );
			$chips[] = array( $term ? $term->name : $slug, $url );
		}
	}
	if ( ! empty( $_GET['rating_filter'] ) ) { // phpcs:ignore
		$chips[] = array( gmk_t( 'f_chip_rating' ), remove_query_arg( 'rating_filter', $base ) );
	}
	if ( ! empty( $_GET['on_sale'] ) ) { // phpcs:ignore
		$chips[] = array( gmk_t( 'f_onsale' ), remove_query_arg( 'on_sale', $base ) );
	}
	if ( ! empty( $_GET['in_stock'] ) ) { // phpcs:ignore
		$chips[] = array( gmk_t( 'f_chip_stock' ), remove_query_arg( 'in_stock', $base ) );
	}
	if ( ! $chips ) {
		return;
	}
	echo '<div class="gmk-chips">';
	foreach ( $chips as $chip ) {
		echo '<a class="gmk-chip" href="' . esc_url( $chip[1] ) . '" rel="nofollow">' . ( empty( $chip[2] ) ? esc_html( $chip[0] ) : $chip[0] ) . ' ' . gmk_icon( 'close' ) . '</a>'; // phpcs:ignore
	}
	echo '<a class="gmk-chips__clear" href="' . esc_url( remove_query_arg( gmk_filter_keys(), $base ) ) . '" rel="nofollow">' . esc_html( gmk_t( 'f_clear' ) ) . '</a></div>';
}

/**
 * Aplica «on_sale» (ofertas) e «in_stock» (disponibles) a la consulta principal de productos.
 */
add_action(
	'woocommerce_product_query',
	function ( $q ) {
		if ( ! empty( $_GET['on_sale'] ) ) { // phpcs:ignore
			$ids = wc_get_product_ids_on_sale();
			$q->set( 'post__in', $ids ? $ids : array( 0 ) );
		}
		if ( ! empty( $_GET['in_stock'] ) ) { // phpcs:ignore
			$meta   = (array) $q->get( 'meta_query' );
			$meta[] = array(
				'key'   => '_stock_status',
				'value' => 'instock',
			);
			$q->set( 'meta_query', $meta );
		}
	}
);

// Las búsquedas de productos usan la plantilla de la tienda (con filtros).
add_action(
	'pre_get_posts',
	function ( $q ) {
		if ( ! is_admin() && $q->is_main_query() && $q->is_search() && empty( $q->get( 'post_type' ) ) ) {
			$q->set( 'post_type', 'product' );
		}
	}
);
