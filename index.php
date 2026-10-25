<?php
/**
 * Plantilla general (blog, archivos y búsquedas).
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

get_header();
?>
<div class="gmk-container gmk-page">
	<?php if ( is_archive() || is_search() ) : ?>
		<header class="gmk-page__head">
			<h1>
				<?php
				if ( is_search() ) {
					echo '<span>' . esc_html( gmk_t( 'search_title' ) ) . '</span> <span data-no-translation>“' . esc_html( get_search_query() ) . '”</span>';
				} else {
					echo wp_kses_post( get_the_archive_title() );
				}
				?>
			</h1>
		</header>
	<?php elseif ( is_home() && ! is_front_page() ) : ?>
		<header class="gmk-page__head"><h1><?php single_post_title(); ?></h1></header>
	<?php endif; ?>

	<?php if ( have_posts() ) : ?>
		<div class="gmk-posts">
			<?php
			while ( have_posts() ) :
				the_post();
				?>
				<article <?php post_class( 'gmk-post-card' ); ?>>
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium_large' ); ?></a>
					<?php endif; ?>
					<h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
					<?php the_excerpt(); ?>
				</article>
			<?php endwhile; ?>
		</div>
		<?php the_posts_pagination(); ?>
	<?php else : ?>
		<p><?php echo esc_html( gmk_t( 'no_content' ) ); ?></p>
	<?php endif; ?>
</div>
<?php
get_footer();
