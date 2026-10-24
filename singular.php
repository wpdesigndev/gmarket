<?php
/**
 * Páginas y entradas.
 */
get_header();
while ( have_posts() ) :
	the_post();
	$gmk_wc = function_exists( 'is_woocommerce' ) && ( is_cart() || is_checkout() || is_account_page() );
	?>
	<div class="gmk-container gmk-page <?php echo $gmk_wc ? 'gmk-page--wc' : 'gmk-page--text'; ?>">
		<?php if ( function_exists( 'woocommerce_breadcrumb' ) && ! is_front_page() ) : ?>
			<?php woocommerce_breadcrumb(); ?>
		<?php endif; ?>
		<header class="gmk-page__head">
			<h1><?php the_title(); ?></h1>
			<?php if ( is_singular( 'post' ) ) : ?>
				<p class="gmk-page__meta"><time datetime="<?php echo esc_attr( get_the_date( 'c' ) ); ?>"><?php echo esc_html( get_the_date() ); ?></time></p>
			<?php endif; ?>
		</header>
		<?php if ( is_singular( 'post' ) && has_post_thumbnail() ) : ?>
			<div class="gmk-page__thumb"><?php the_post_thumbnail( 'large' ); ?></div>
		<?php endif; ?>
		<div class="gmk-content">
			<?php the_content(); ?>
			<?php wp_link_pages(); ?>
		</div>
		<?php
		if ( is_singular( 'post' ) && ( comments_open() || get_comments_number() ) ) {
			comments_template();
		}
		?>
	</div>
	<?php
endwhile;
get_footer();
