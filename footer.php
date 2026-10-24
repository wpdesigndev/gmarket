<?php
/**
 * Pie del sitio. Si existe un pie hecho con Elementor (Header & Footer Builder), se usa ese.
 * Textos: Apariencia › Textos de la tienda › Pie y contacto.
 */
$gmk_trust = array(
	array( 'globe', gmk_t( 'ft1_title' ), esc_html( gmk_t( 'ft1_text' ) ) ),
	array( 'truck', gmk_t( 'ft2_title' ), esc_html( gmk_t( 'ft2_text' ) ) ),
	array( 'shield', gmk_t( 'ft3_title' ), esc_html( gmk_t( 'ft3_text' ) ) ),
	array( 'mail', gmk_t( 'ft4_title' ), '<a href="mailto:' . esc_attr( gmk_email() ) . '">' . esc_html( gmk_email() ) . '</a>' ),
);
$gmk_trust = array_filter( $gmk_trust, fn( $item ) => '' !== $item[1] );
$gmk_menus = array();
foreach ( array( 'footer_1' => 'ft_col1', 'footer_2' => 'ft_col2', 'footer_3' => 'ft_col3' ) as $gmk_loc => $gmk_key ) {
	if ( has_nav_menu( $gmk_loc ) ) {
		$gmk_menus[ $gmk_loc ] = gmk_t( $gmk_key );
	}
}
$gmk_cats = array_slice( gmk_product_cats(), 0, 8 );
?>
</main>

<?php if ( gmk_hfe( 'footer' ) ) : ?>
	<?php hfe_render_footer(); ?>
<?php else : ?>
<footer class="gmk-footer">
	<?php if ( '' !== gmk_t( 'ft_top' ) ) : ?>
		<a class="gmk-footer__top" href="#arriba"><?php echo esc_html( gmk_t( 'ft_top' ) ); ?></a>
	<?php endif; ?>

	<?php if ( $gmk_trust ) : ?>
		<div class="gmk-footer__trust" style="--gmk-n: <?php echo (int) count( $gmk_trust ); ?>">
			<?php foreach ( $gmk_trust as $gmk_item ) : ?>
				<div><?php echo gmk_icon( $gmk_item[0] ); // phpcs:ignore ?><span><strong><?php echo esc_html( $gmk_item[1] ); ?></strong><?php echo $gmk_item[2]; // phpcs:ignore ?></span></div>
			<?php endforeach; ?>
		</div>
	<?php endif; ?>

	<div class="gmk-footer__cols" style="--gmk-n: <?php echo (int) max( 1, count( $gmk_menus ) + ( $gmk_cats ? 1 : 0 ) ); ?>">
		<div class="gmk-footer__col gmk-footer__col--brand">
			<?php gmk_footer_logo(); ?>
			<?php if ( '' !== gmk_t( 'ft_about' ) ) : ?>
				<p><?php echo esc_html( gmk_t( 'ft_about' ) ); ?></p>
			<?php endif; ?>
			<a class="gmk-footer__mail" href="mailto:<?php echo esc_attr( gmk_email() ); ?>"><?php echo gmk_icon( 'mail' ); // phpcs:ignore ?> <?php echo esc_html( gmk_email() ); ?></a>
		</div>
		<?php foreach ( $gmk_menus as $gmk_loc => $gmk_title ) : ?>
			<div class="gmk-footer__col">
				<?php if ( '' !== $gmk_title ) : ?>
					<h4><?php echo esc_html( $gmk_title ); ?></h4>
				<?php endif; ?>
				<?php
				wp_nav_menu(
					array(
						'theme_location' => $gmk_loc,
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => '__return_empty_string',
					)
				);
				?>
			</div>
		<?php endforeach; ?>
		<?php if ( $gmk_cats ) : ?>
			<div class="gmk-footer__col">
				<?php if ( '' !== gmk_t( 'ft_cats' ) ) : ?>
					<h4><?php echo esc_html( gmk_t( 'ft_cats' ) ); ?></h4>
				<?php endif; ?>
				<ul>
					<?php foreach ( $gmk_cats as $term ) : ?>
						<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?></a></li>
					<?php endforeach; ?>
				</ul>
			</div>
		<?php endif; ?>
	</div>

	<?php if ( '' !== gmk_t( 'ft_notice' ) ) : ?>
		<div class="gmk-footer__notice">
			<p>
				<?php echo esc_html( gmk_t( 'ft_notice' ) ); ?>
				<?php if ( gmk_t( 'ft_notice_url' ) && gmk_t( 'ft_notice_link' ) ) : ?>
					<a href="<?php echo esc_url( gmk_t( 'ft_notice_url' ) ); ?>"><?php echo esc_html( gmk_t( 'ft_notice_link' ) ); ?></a>
				<?php endif; ?>
			</p>
		</div>
	<?php endif; ?>

	<div class="gmk-footer__bottom">
		<?php echo gmk_pay_icons(); // phpcs:ignore ?>
		<p>&copy; <?php echo esc_html( gmdate( 'Y' ) ); ?> <?php bloginfo( 'name' ); ?>. <?php echo esc_html( gmk_t( 'ft_copy' ) ); ?></p>
	</div>
</footer>
<?php endif; ?>

<?php wp_footer(); ?>
</body>
</html>
