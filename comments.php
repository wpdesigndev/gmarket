<?php
/**
 * Comentarios de las entradas del blog.
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

if ( post_password_required() ) {
	return;
}
?>
<section id="comments" class="gmk-comments">
	<?php if ( have_comments() ) : ?>
		<h2 class="gmk-comments__title">
			<?php
			/* translators: %s: número de comentarios. */
			printf( esc_html( _n( '%s comentario', '%s comentarios', get_comments_number(), 'gmarket' ) ), esc_html( number_format_i18n( get_comments_number() ) ) );
			?>
		</h2>
		<ol class="gmk-comments__list">
			<?php wp_list_comments( array( 'style' => 'ol', 'short_ping' => true, 'avatar_size' => 40 ) ); ?>
		</ol>
		<?php the_comments_pagination(); ?>
	<?php endif; ?>
	<?php comment_form(); ?>
</section>
