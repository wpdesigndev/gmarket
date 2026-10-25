<?php
/**
 * Contenedor de WooCommerce: tienda y categorías con filtros, y ficha de producto.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();

if ( is_singular( 'product' ) ) :
	?>
	<div class="gmk-container gmk-product-page">
		<?php woocommerce_breadcrumb(); ?>
		<?php woocommerce_content(); ?>
	</div>
	<?php
else :
	?>
	<div class="gmk-container gmk-shop">
		<?php woocommerce_breadcrumb(); ?>
		<div class="gmk-shop__layout">
			<div class="gmk-filters-overlay" data-gmk-filters="close" aria-hidden="true"></div>
			<aside class="gmk-shop__filters" id="gmk-filters" aria-label="<?php esc_attr_e( 'Filtros', 'gmarket' ); ?>">
				<div class="gmk-shop__filters-head">
					<strong><?php echo gmk_icon( 'filter' ); // phpcs:ignore ?> <?php echo esc_html( gmk_t( 'f_title' ) ); ?></strong>
					<button type="button" class="gmk-shop__filters-close" data-gmk-filters="close" aria-label="<?php esc_attr_e( 'Cerrar filtros', 'gmarket' ); ?>"><?php echo gmk_icon( 'close' ); // phpcs:ignore ?></button>
				</div>
				<?php gmk_filters_sidebar(); ?>
				<?php if ( is_active_sidebar( 'shop-sidebar' ) ) : ?>
					<?php dynamic_sidebar( 'shop-sidebar' ); ?>
				<?php endif; ?>
			</aside>
			<div class="gmk-shop__results">
				<button type="button" class="gmk-btn gmk-btn--ghost gmk-shop__filters-open" data-gmk-filters="open"><?php echo gmk_icon( 'filter' ); // phpcs:ignore ?> <?php echo esc_html( gmk_t( 'f_button' ) ); ?></button>
				<?php gmk_active_filters(); ?>
				<?php woocommerce_content(); ?>
			</div>
		</div>
	</div>
	<?php
endif;

get_footer();
