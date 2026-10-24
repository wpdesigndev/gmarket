<?php
/**
 * Piezas de la cabecera y del pie como shortcodes, para colocarlas con Elementor
 * (plantillas de «Elementor Header & Footer Builder») o usarlas en las plantillas del tema.
 *
 * Cabecera: [gmk_burger] [gmk_deliver] [gmk_search] [gmk_lang] [gmk_account] [gmk_orders] [gmk_cart] [gmk_nav]
 * Pie:      [gmk_menu location="footer_1"] [gmk_footer_cats] [gmk_pay_icons] [gmk_year] [gmk_breadcrumb]
 *
 * Los textos por defecto salen de Apariencia › Textos de la tienda; en los shortcodes se pueden cambiar con atributos.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Botón que abre el menú lateral.
 */
function gmk_part_burger() {
	return '<button class="gmk-header__burger" type="button" data-gmk-drawer="open" aria-label="' . esc_attr__( 'Abrir menú', 'gmarket' ) . '">' . gmk_icon( 'menu' ) . '</button>';
}

/**
 * Zona de entrega («Enviamos a / Todo el país»), con enlace opcional.
 */
function gmk_part_deliver( $small = null, $text = null ) {
	$small = null === $small ? gmk_t( 'h_deliver_small' ) : $small;
	$text  = null === $text ? gmk_t( 'h_deliver' ) : $text;
	if ( '' === $text ) {
		return '';
	}
	$url   = gmk_t( 'h_deliver_url' );
	$inner = gmk_icon( 'pin' ) . '<span><small>' . esc_html( $small ) . '</small><strong>' . esc_html( $text ) . '</strong></span>';
	return $url ? '<a class="gmk-deliver" href="' . esc_url( $url ) . '">' . $inner . '</a>' : '<span class="gmk-deliver">' . $inner . '</span>';
}

/**
 * Buscador con categorías y sugerencias.
 */
function gmk_part_search() {
	ob_start();
	echo '<div class="gmk-header__search">';
	get_search_form();
	echo '</div>';
	return ob_get_clean();
}

/**
 * Selector de idioma.
 */
function gmk_part_lang() {
	ob_start();
	gmk_lang_switch( 'gmk-lang--header' );
	return ob_get_clean();
}

/**
 * «Hola, identifícate / Cuenta y listas».
 */
function gmk_part_account( $text = null ) {
	$text = null === $text ? gmk_t( 'h_account' ) : $text;
	$user = wp_get_current_user();
	$acc  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
	$name = $user->first_name ? $user->first_name : $user->display_name;
	$hi   = is_user_logged_in() ? '<span>' . esc_html( gmk_t( 'hello_user' ) ) . '</span> <span data-no-translation>' . esc_html( $name ) . '</span>' : esc_html( gmk_t( 'hello_guest' ) );
	return '<a class="gmk-hlink gmk-hlink--account" href="' . esc_url( $acc ) . '">' . gmk_icon( 'user' ) . '<span><small>' . $hi . '</small><strong>' . esc_html( $text ) . '</strong></span></a>';
}

/**
 * «Devoluciones y pedidos».
 */
function gmk_part_orders( $small = null, $text = null ) {
	$small = null === $small ? gmk_t( 'h_orders_small' ) : $small;
	$text  = null === $text ? gmk_t( 'h_orders' ) : $text;
	if ( '' === $small && '' === $text ) {
		return '';
	}
	$url = function_exists( 'wc_get_account_endpoint_url' ) ? wc_get_account_endpoint_url( 'orders' ) : home_url( '/' );
	return '<a class="gmk-hlink gmk-hlink--orders" href="' . esc_url( $url ) . '"><span><small>' . esc_html( $small ) . '</small><strong>' . esc_html( $text ) . '</strong></span></a>';
}

/**
 * Carrito con contador.
 */
function gmk_part_cart( $text = null ) {
	$text = null === $text ? gmk_t( 'h_cart' ) : $text;
	$url  = function_exists( 'wc_get_cart_url' ) ? wc_get_cart_url() : home_url( '/' );
	return '<a class="gmk-cart" href="' . esc_url( $url ) . '" aria-label="' . esc_attr( $text ? $text : __( 'Carrito', 'gmarket' ) ) . '"><span class="gmk-cart__icon">' . gmk_icon( 'cart' ) . '<span class="gmk-cart__count">' . esc_html( gmk_cart_count() ) . '</span></span><strong>' . esc_html( $text ) . '</strong></a>';
}

/**
 * Barra de categorías: botón «Todas», menú principal y «Más» para lo que no cabe.
 */
function gmk_part_nav( $all = null, $more = null ) {
	$all  = null === $all ? gmk_t( 'h_nav_all' ) : $all;
	$more = null === $more ? gmk_t( 'h_nav_more' ) : $more;
	$menu = wp_nav_menu(
		array(
			'theme_location' => 'primary',
			'container'      => false,
			'menu_class'     => 'gmk-nav__list',
			'depth'          => 1,
			'fallback_cb'    => 'gmk_menu_fallback',
			'echo'           => false,
		)
	);
	if ( ! $menu ) {
		ob_start();
		gmk_menu_fallback( array( 'menu_class' => 'gmk-nav__list' ) );
		$menu = ob_get_clean();
	}
	return '<div class="gmk-nav__inner">'
		. '<button class="gmk-nav__all" type="button" data-gmk-drawer="open">' . gmk_icon( 'menu' ) . ' ' . esc_html( $all ) . '</button>'
		. $menu
		. '<div class="gmk-nav__more" hidden><button type="button" class="gmk-nav__more-btn" aria-haspopup="true" aria-expanded="false">' . esc_html( $more ) . ' ' . gmk_icon( 'chevron' ) . '</button><ul class="gmk-menu-pop gmk-nav__more-list" hidden></ul></div>'
		. '</div>';
}

/**
 * Menú de una ubicación (pie).
 */
function gmk_part_menu( $location ) {
	$menu = wp_nav_menu(
		array(
			'theme_location' => $location,
			'container'      => false,
			'depth'          => 1,
			'menu_class'     => 'gmk-fmenu',
			'fallback_cb'    => '__return_empty_string',
			'echo'           => false,
		)
	);
	return $menu ? $menu : '';
}

/**
 * Lista de categorías para el pie.
 */
function gmk_part_footer_cats( $limit = 8 ) {
	$out = '<ul class="gmk-fmenu">';
	foreach ( array_slice( gmk_product_cats(), 0, (int) $limit ) as $term ) {
		$out .= '<li><a href="' . esc_url( get_term_link( $term ) ) . '">' . esc_html( $term->name ) . '</a></li>';
	}
	return $out . '</ul>';
}

/**
 * Atributos de un shortcode: los que no se indican quedan en null (= texto de Apariencia › Textos de la tienda).
 */
function gmk_part_atts( $defaults, $atts ) {
	return array_values( shortcode_atts( $defaults, $atts ) );
}

add_shortcode( 'gmk_burger', 'gmk_part_burger' );
add_shortcode( 'gmk_search', 'gmk_part_search' );
add_shortcode( 'gmk_lang', 'gmk_part_lang' );
add_shortcode( 'gmk_deliver', fn( $a ) => gmk_part_deliver( ...gmk_part_atts( array( 'small' => null, 'text' => null ), $a ) ) );
add_shortcode( 'gmk_account', fn( $a ) => gmk_part_account( ...gmk_part_atts( array( 'text' => null ), $a ) ) );
add_shortcode( 'gmk_orders', fn( $a ) => gmk_part_orders( ...gmk_part_atts( array( 'small' => null, 'text' => null ), $a ) ) );
add_shortcode( 'gmk_cart', fn( $a ) => gmk_part_cart( ...gmk_part_atts( array( 'text' => null ), $a ) ) );
add_shortcode( 'gmk_nav', fn( $a ) => gmk_part_nav( ...gmk_part_atts( array( 'all' => null, 'more' => null ), $a ) ) );
add_shortcode( 'gmk_menu', fn( $a ) => gmk_part_menu( shortcode_atts( array( 'location' => 'footer_1' ), $a )['location'] ) );
add_shortcode( 'gmk_footer_cats', fn( $a ) => gmk_part_footer_cats( shortcode_atts( array( 'limit' => 8 ), $a )['limit'] ) );
add_shortcode( 'gmk_pay_icons', 'gmk_pay_icons' );
add_shortcode( 'gmk_year', fn() => '<span data-no-translation>' . gmdate( 'Y' ) . '</span>' ); // El año no cambia la frase que se traduce.
add_shortcode(
	'gmk_breadcrumb',
	function () {
		if ( ! function_exists( 'woocommerce_breadcrumb' ) ) {
			return '';
		}
		ob_start();
		woocommerce_breadcrumb();
		return ob_get_clean();
	}
);

/**
 * Menú lateral (se imprime al final de la página, valga la cabecera que valga).
 */
function gmk_drawer() {
	$user = wp_get_current_user();
	$acc  = function_exists( 'wc_get_page_permalink' ) ? wc_get_page_permalink( 'myaccount' ) : wp_login_url();
	?>
	<div class="gmk-drawer" data-gmk-drawer-panel hidden>
		<div class="gmk-drawer__overlay" data-gmk-drawer="close"></div>
		<aside class="gmk-drawer__panel" aria-label="<?php esc_attr_e( 'Menú', 'gmarket' ); ?>">
			<div class="gmk-drawer__head">
				<?php echo gmk_icon( 'user' ); // phpcs:ignore ?>
				<a href="<?php echo esc_url( $acc ); ?>"><?php if ( is_user_logged_in() ) : ?><span><?php echo esc_html( gmk_t( 'hello_user' ) ); ?></span> <span data-no-translation><?php echo esc_html( $user->display_name ); ?></span><?php else : ?><?php echo esc_html( gmk_t( 'hello_guest' ) ); ?><?php endif; ?></a>
				<button type="button" class="gmk-drawer__close" data-gmk-drawer="close" aria-label="<?php esc_attr_e( 'Cerrar', 'gmarket' ); ?>"><?php echo gmk_icon( 'close' ); // phpcs:ignore ?></button>
			</div>
			<div class="gmk-drawer__body">
				<?php $gmk_cats = gmk_product_cats(); ?>
				<?php if ( $gmk_cats || function_exists( 'wc_get_page_permalink' ) ) : ?>
					<h4><?php echo esc_html( gmk_t( 'drawer_cats' ) ); ?></h4>
					<ul>
						<?php foreach ( $gmk_cats as $term ) : ?>
							<li><a href="<?php echo esc_url( get_term_link( $term ) ); ?>"><?php echo esc_html( $term->name ); ?> <?php echo gmk_icon( 'chevron' ); // phpcs:ignore ?></a></li>
						<?php endforeach; ?>
						<?php if ( function_exists( 'wc_get_page_permalink' ) ) : ?>
							<li><a href="<?php echo esc_url( wc_get_page_permalink( 'shop' ) ); ?>"><strong><?php echo esc_html( gmk_t( 'drawer_all' ) ); ?></strong></a></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
				<?php
				// Enlaces de ayuda: menú «Menú lateral – Ayuda» (Apariencia › Menús).
				$gmk_help = wp_nav_menu(
					array(
						'theme_location' => 'drawer_help',
						'container'      => false,
						'depth'          => 1,
						'fallback_cb'    => false,
						'echo'           => false,
						'items_wrap'     => '%3$s',
					)
				);
				?>
				<?php if ( $gmk_help || is_user_logged_in() ) : ?>
					<h4><?php echo esc_html( gmk_t( 'drawer_help' ) ); ?></h4>
					<ul>
						<?php echo $gmk_help; // phpcs:ignore ?>
						<?php if ( is_user_logged_in() ) : ?>
							<li><a href="<?php echo esc_url( wp_logout_url( home_url( '/' ) ) ); ?>"><?php echo esc_html( gmk_t( 'drawer_logout' ) ); ?></a></li>
						<?php endif; ?>
					</ul>
				<?php endif; ?>
			</div>
		</aside>
	</div>
	<?php
}
add_action( 'wp_footer', 'gmk_drawer', 5 );

/**
 * ¿Hay plantilla de Elementor para la cabecera / el pie?
 */
function gmk_hfe( $part ) {
	return 'header' === $part
		? function_exists( 'hfe_header_enabled' ) && hfe_header_enabled()
		: function_exists( 'hfe_footer_enabled' ) && hfe_footer_enabled();
}
