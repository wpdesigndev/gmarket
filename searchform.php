<?php
/**
 * Buscador de la cabecera: categoría (menú desplegable) + texto, con sugerencias en vivo.
 */
$gmk_cat = isset( $_GET['product_cat'] ) ? sanitize_title( wp_unslash( $_GET['product_cat'] ) ) : ''; // phpcs:ignore
if ( ! $gmk_cat && is_tax( 'product_cat' ) ) {
	$gmk_cat = get_queried_object()->slug;
}
$gmk_cats  = gmk_product_cats();
$gmk_label = gmk_t( 'search_all' );
foreach ( $gmk_cats as $term ) {
	if ( $term->slug === $gmk_cat ) {
		$gmk_label = $term->name;
	}
}
?>
<form role="search" method="get" class="gmk-search" action="<?php echo esc_url( home_url( '/' ) ); ?>" autocomplete="off">
	<label class="screen-reader-text" for="gmk-search-cat"><?php esc_html_e( 'Buscar en', 'gmarket' ); ?></label>
	<select id="gmk-search-cat" name="product_cat" class="gmk-search__cat" tabindex="-1" aria-hidden="true">
		<option value=""><?php echo esc_html( gmk_t( 'search_all' ) ); ?></option>
		<?php foreach ( $gmk_cats as $term ) : ?>
			<option value="<?php echo esc_attr( $term->slug ); ?>" <?php selected( $gmk_cat, $term->slug ); ?>><?php echo esc_html( $term->name ); ?></option>
		<?php endforeach; ?>
	</select>
	<div class="gmk-picker">
		<button type="button" class="gmk-picker__btn" aria-haspopup="listbox" aria-expanded="false" aria-label="<?php esc_attr_e( 'Buscar en', 'gmarket' ); ?>">
			<span class="gmk-picker__label"><?php echo esc_html( $gmk_label ); ?></span>
			<svg class="gmk-picker__arrow" viewBox="0 0 24 24" aria-hidden="true"><path d="M7 10l5 5 5-5" fill="none" stroke="currentColor" stroke-width="2.4" stroke-linecap="round" stroke-linejoin="round"/></svg>
		</button>
		<ul class="gmk-menu-pop gmk-picker__menu" role="listbox" hidden>
			<li role="option" data-value="" aria-selected="<?php echo '' === $gmk_cat ? 'true' : 'false'; ?>"><?php echo esc_html( gmk_t( 'search_all' ) ); ?></li>
			<?php foreach ( $gmk_cats as $term ) : ?>
				<li role="option" data-value="<?php echo esc_attr( $term->slug ); ?>" aria-selected="<?php echo $term->slug === $gmk_cat ? 'true' : 'false'; ?>"><?php echo esc_html( $term->name ); ?></li>
			<?php endforeach; ?>
		</ul>
	</div>
	<label class="screen-reader-text" for="gmk-search-q"><?php esc_html_e( 'Buscar productos', 'gmarket' ); ?></label>
	<input id="gmk-search-q" class="gmk-search__input" type="search" name="s" value="<?php echo esc_attr( get_search_query() ); ?>" placeholder="<?php echo esc_attr( gmk_t( 'search_ph' ) ); ?>" />
	<?php if ( class_exists( 'WooCommerce' ) ) : ?>
		<input type="hidden" name="post_type" value="product" />
	<?php endif; ?>
	<button class="gmk-search__btn" type="submit" aria-label="<?php esc_attr_e( 'Buscar', 'gmarket' ); ?>"><?php echo gmk_icon( 'search' ); // phpcs:ignore ?></button>
	<div class="gmk-suggest" hidden></div>
</form>
