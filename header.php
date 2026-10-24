<?php
/**
 * Cabecera del sitio. Si existe una cabecera hecha con Elementor (Header & Footer Builder), se usa esa.
 */
?><!doctype html>
<html <?php language_attributes(); ?>>
<head>
	<meta charset="<?php bloginfo( 'charset' ); ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
<?php wp_body_open(); ?>
<a class="screen-reader-text skip-link" href="#contenido"><?php esc_html_e( 'Saltar al contenido', 'gmarket' ); ?></a>
<span id="arriba"></span>

<?php if ( gmk_hfe( 'header' ) ) : ?>
	<?php hfe_render_header(); ?>
<?php else : ?>
	<header class="gmk-header">
		<div class="gmk-header__main">
			<div class="gmk-logo"><?php gmk_logo(); ?><?php gmk_logo_icon(); ?></div>
			<?php echo gmk_part_deliver() . gmk_part_search() . gmk_part_lang() . gmk_part_account() . gmk_part_orders() . gmk_part_cart(); // phpcs:ignore ?>
		</div>
		<nav class="gmk-nav" aria-label="<?php esc_attr_e( 'Categorías', 'gmarket' ); ?>">
			<?php echo gmk_part_nav(); // phpcs:ignore ?>
			<?php if ( '' !== gmk_t( 'h_promo' ) ) : ?>
				<span class="gmk-nav__promo"><?php echo gmk_icon( 'globe' ); // phpcs:ignore ?> <?php echo esc_html( gmk_t( 'h_promo' ) ); ?></span>
			<?php endif; ?>
		</nav>
	</header>
<?php endif; ?>

<main id="contenido" class="gmk-main">
