<?php
/**
 * Apariencia › Textos de la tienda: todos los textos fijos del tema, editables desde el panel.
 *
 * Los textos se escriben en el idioma principal de la web. Si usas TranslatePress, el segundo idioma
 * se traduce con «Traducir página», salvo la sección «Segundo idioma», que TranslatePress no alcanza.
 * Un campo vacío oculta ese elemento.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Campos: clave => array( sección, etiqueta, valor por defecto, tipo ).
 */
function gmk_text_fields() {
	static $fields = null;
	if ( null !== $fields ) {
		return $fields;
	}
	$fields = array(
		// Cabecera y menú lateral.
		'hello_guest'      => array( 'header', __( 'Saludo sin sesión', 'gmarket' ), __( 'Hola, identifícate', 'gmarket' ) ),
		'hello_user'       => array( 'header', __( 'Saludo con sesión (va delante del nombre)', 'gmarket' ), __( 'Hola,', 'gmarket' ) ),
		'h_account'        => array( 'header', __( 'Enlace de la cuenta', 'gmarket' ), __( 'Cuenta y listas', 'gmarket' ) ),
		'h_orders_small'   => array( 'header', __( 'Enlace de pedidos: línea pequeña', 'gmarket' ), __( 'Devoluciones', 'gmarket' ) ),
		'h_orders'         => array( 'header', __( 'Enlace de pedidos: línea grande', 'gmarket' ), __( 'y pedidos', 'gmarket' ) ),
		'h_cart'           => array( 'header', __( 'Carrito', 'gmarket' ), __( 'Carrito', 'gmarket' ) ),
		'h_deliver_small'  => array( 'header', __( 'Zona de entrega: línea pequeña', 'gmarket' ), __( 'Enviamos a', 'gmarket' ) ),
		'h_deliver'        => array( 'header', __( 'Zona de entrega: línea grande (vacío = se oculta)', 'gmarket' ), __( 'Todo el país', 'gmarket' ) ),
		'h_deliver_url'    => array( 'header', __( 'Zona de entrega: enlace (opcional)', 'gmarket' ), '', 'url' ),
		'h_nav_all'        => array( 'header', __( 'Barra de categorías: botón «Todas»', 'gmarket' ), __( 'Todas', 'gmarket' ) ),
		'h_nav_more'       => array( 'header', __( 'Barra de categorías: botón «Más»', 'gmarket' ), __( 'Más', 'gmarket' ) ),
		'h_promo'          => array( 'header', __( 'Barra de categorías: mensaje a la derecha (vacío = se oculta)', 'gmarket' ), __( 'Envío rápido · Pago seguro', 'gmarket' ) ),
		'drawer_cats'      => array( 'header', __( 'Menú lateral: título de categorías', 'gmarket' ), __( 'Comprar por categoría', 'gmarket' ) ),
		'drawer_all'       => array( 'header', __( 'Menú lateral: enlace a toda la tienda', 'gmarket' ), __( 'Ver toda la tienda', 'gmarket' ) ),
		'drawer_help'      => array( 'header', __( 'Menú lateral: título de ayuda (los enlaces se editan en Apariencia › Menús › «Menú lateral – Ayuda»)', 'gmarket' ), __( 'Ayuda y configuración', 'gmarket' ) ),
		'drawer_logout'    => array( 'header', __( 'Menú lateral: cerrar sesión', 'gmarket' ), __( 'Cerrar sesión', 'gmarket' ) ),

		// Buscador.
		'search_ph'        => array( 'search', __( 'Texto de ayuda del buscador', 'gmarket' ), __( 'Busca productos, marcas y más…', 'gmarket' ) ),
		'search_ph_short'  => array( 'search', __( 'Texto de ayuda en el móvil', 'gmarket' ), __( 'Buscar…', 'gmarket' ) ),
		'search_all'       => array( 'search', __( 'Opción «todas las categorías»', 'gmarket' ), __( 'Todo', 'gmarket' ) ),
		'search_none'      => array( 'search', __( 'Búsqueda en vivo: sin resultados', 'gmarket' ), __( 'No encontramos productos con esa búsqueda.', 'gmarket' ) ),
		'search_see_all'   => array( 'search', __( 'Búsqueda en vivo: ver todos', 'gmarket' ), __( 'Ver todos los resultados', 'gmarket' ) ),
		'search_cat'       => array( 'search', __( 'Búsqueda en vivo: etiqueta de categoría', 'gmarket' ), __( 'Categoría:', 'gmarket' ) ),

		// Tienda y tarjetas.
		'shop_title'       => array( 'shop', __( 'Título de la tienda', 'gmarket' ), __( 'Todos los productos', 'gmarket' ) ),
		'search_title'     => array( 'shop', __( 'Título de resultados de búsqueda', 'gmarket' ), __( 'Resultados para', 'gmarket' ) ),
		'crumb_home'       => array( 'shop', __( 'Ruta de navegación: inicio', 'gmarket' ), __( 'Inicio', 'gmarket' ) ),
		'sale_badge'       => array( 'shop', __( 'Etiqueta de oferta', 'gmarket' ), __( 'Oferta', 'gmarket' ) ),
		'card_ship'        => array( 'shop', __( 'Tarjeta de producto: línea de entrega (vacío = se oculta)', 'gmarket' ), __( 'Envío a domicilio', 'gmarket' ) ),
		'noresults_title'  => array( 'shop', __( 'Sin resultados: título de sugerencias', 'gmarket' ), __( 'Productos que te pueden interesar', 'gmarket' ) ),
		'noresults_count'  => array( 'shop', __( 'Sin resultados: cuántos productos sugerir (0 = ninguno)', 'gmarket' ), '8', 'number' ),
		'no_content'       => array( 'shop', __( 'Blog y archivos: sin contenido', 'gmarket' ), __( 'No hay contenido para mostrar.', 'gmarket' ) ),

		// Filtros.
		'f_title'          => array( 'filters', __( 'Panel de filtros: título', 'gmarket' ), __( 'Filtrar resultados', 'gmarket' ) ),
		'f_button'         => array( 'filters', __( 'Botón de filtros (móvil)', 'gmarket' ), __( 'Filtros', 'gmarket' ) ),
		'f_cat'            => array( 'filters', __( 'Título: categoría', 'gmarket' ), __( 'Categoría', 'gmarket' ) ),
		'f_all_cats'       => array( 'filters', __( 'Todas las categorías', 'gmarket' ), __( 'Todas las categorías', 'gmarket' ) ),
		'f_price'          => array( 'filters', __( 'Título: precio', 'gmarket' ), __( 'Precio', 'gmarket' ) ),
		'f_ranges'         => array( 'filters', __( 'Tramos de precio (importes separados por comas)', 'gmarket' ), '10, 20, 50' ),
		'f_under'          => array( 'filters', __( 'Tramo inferior (%s = importe)', 'gmarket' ), __( 'Menos de %s', 'gmarket' ) ),
		'f_between'        => array( 'filters', __( 'Tramo intermedio (%1$s y %2$s = importes)', 'gmarket' ), __( '%1$s a %2$s', 'gmarket' ) ),
		'f_over'           => array( 'filters', __( 'Tramo superior (%s = importe)', 'gmarket' ), __( 'Más de %s', 'gmarket' ) ),
		'f_min'            => array( 'filters', __( 'Precio mínimo (campo)', 'gmarket' ), __( 'Mín', 'gmarket' ) ),
		'f_max'            => array( 'filters', __( 'Precio máximo (campo)', 'gmarket' ), __( 'Máx', 'gmarket' ) ),
		'f_go'             => array( 'filters', __( 'Botón de precio', 'gmarket' ), __( 'Ir', 'gmarket' ) ),
		'f_chip_price'     => array( 'filters', __( 'Etiqueta activa: precio', 'gmarket' ), __( 'Precio:', 'gmarket' ) ),
		'f_chip_from'      => array( 'filters', __( 'Etiqueta activa: precio desde', 'gmarket' ), __( 'Precio desde', 'gmarket' ) ),
		'f_attrs'          => array( 'filters', __( 'Características que se pueden filtrar (sus nombres se cambian en Productos › Atributos)', 'gmarket' ), '*', 'attributes' ),
		'f_rating'         => array( 'filters', __( 'Título: valoración', 'gmarket' ), __( 'Opinión media de clientes', 'gmarket' ) ),
		'f_rating_opt'     => array( 'filters', __( 'Opción de valoración', 'gmarket' ), __( '★★★★ y más', 'gmarket' ) ),
		'f_chip_rating'    => array( 'filters', __( 'Etiqueta activa: valoración', 'gmarket' ), __( '4 estrellas o más', 'gmarket' ) ),
		'f_avail'          => array( 'filters', __( 'Título: disponibilidad', 'gmarket' ), __( 'Disponibilidad y ofertas', 'gmarket' ) ),
		'f_instock'        => array( 'filters', __( 'Solo disponibles', 'gmarket' ), __( 'Solo productos disponibles', 'gmarket' ) ),
		'f_onsale'         => array( 'filters', __( 'En oferta', 'gmarket' ), __( 'En oferta', 'gmarket' ) ),
		'f_chip_stock'     => array( 'filters', __( 'Etiqueta activa: disponibles', 'gmarket' ), __( 'Disponibles', 'gmarket' ) ),
		'f_clear'          => array( 'filters', __( 'Borrar filtros', 'gmarket' ), __( 'Borrar todos los filtros', 'gmarket' ) ),

		// Ficha de producto.
		'soon_title'       => array( 'product', __( 'Sin precio: título del aviso', 'gmarket' ), __( 'Precio disponible próximamente.', 'gmarket' ) ),
		'soon_before'      => array( 'product', __( 'Sin precio: texto antes del correo', 'gmarket' ), __( 'Si necesitas este producto, escríbenos a', 'gmarket' ) ),
		'soon_after'       => array( 'product', __( 'Sin precio: texto después del correo', 'gmarket' ), __( 'y te informamos.', 'gmarket' ) ),
		'box1_title'       => array( 'product', __( 'Ventaja 1: título', 'gmarket' ), __( 'Paga como prefieras', 'gmarket' ) ),
		'box1_text'        => array( 'product', __( 'Ventaja 1: texto', 'gmarket' ), __( 'con tarjeta de débito o crédito, PayPal, Apple Pay o Google Pay.', 'gmarket' ) ),
		'box2_title'       => array( 'product', __( 'Ventaja 2: título', 'gmarket' ), __( 'Entrega a domicilio.', 'gmarket' ) ),
		'box2_text'        => array( 'product', __( 'Ventaja 2: texto', 'gmarket' ), __( 'El costo de envío se calcula en el pago.', 'gmarket' ) ),
		'box3_title'       => array( 'product', __( 'Ventaja 3: título', 'gmarket' ), __( 'Compra protegida.', 'gmarket' ) ),
		'box3_text'        => array( 'product', __( 'Ventaja 3: texto', 'gmarket' ), __( 'Recibes la confirmación del pedido en tu correo.', 'gmarket' ) ),
		'tab_specs'        => array( 'product', __( 'Pestaña de características', 'gmarket' ), __( 'Características', 'gmarket' ) ),

		// Mi cuenta.
		'acc_dashboard'    => array( 'account', __( 'Resumen', 'gmarket' ), __( 'Resumen', 'gmarket' ) ),
		'acc_orders'       => array( 'account', __( 'Pedidos', 'gmarket' ), __( 'Mis pedidos', 'gmarket' ) ),
		'acc_payment'      => array( 'account', __( 'Métodos de pago', 'gmarket' ), __( 'Métodos de pago', 'gmarket' ) ),
		'acc_address'      => array( 'account', __( 'Direcciones', 'gmarket' ), __( 'Direcciones', 'gmarket' ) ),
		'acc_account'      => array( 'account', __( 'Datos de la cuenta', 'gmarket' ), __( 'Datos de la cuenta', 'gmarket' ) ),

		// Bloques de la portada.
		'home_more'        => array( 'home', __( 'Filas: enlace «ver más»', 'gmarket' ), __( 'Ver más', 'gmarket' ) ),
		'home_tile_more'   => array( 'home', __( 'Tarjetas: «ver todo en» (va delante del nombre de la categoría)', 'gmarket' ), __( 'Ver todo en', 'gmarket' ) ),
		'home_count_one'   => array( 'home', __( 'Categorías: 1 producto (%d = número)', 'gmarket' ), __( '%d producto', 'gmarket' ) ),
		'home_count_many'  => array( 'home', __( 'Categorías: varios productos (%d = número)', 'gmarket' ), __( '%d productos', 'gmarket' ) ),

		// Pie y contacto.
		'email'            => array( 'footer', __( 'Correo de contacto de la tienda (vacío = el del administrador)', 'gmarket' ), '', 'email' ),
		'ft_top'           => array( 'footer', __( 'Botón «volver arriba»', 'gmarket' ), __( 'Volver arriba', 'gmarket' ) ),
		'ft1_title'        => array( 'footer', __( 'Franja de confianza 1: título', 'gmarket' ), __( 'Compra desde donde estés', 'gmarket' ) ),
		'ft1_text'         => array( 'footer', __( 'Franja de confianza 1: texto', 'gmarket' ), __( 'Tienda abierta las 24 horas.', 'gmarket' ) ),
		'ft2_title'        => array( 'footer', __( 'Franja de confianza 2: título', 'gmarket' ), __( 'Envío a domicilio', 'gmarket' ) ),
		'ft2_text'         => array( 'footer', __( 'Franja de confianza 2: texto', 'gmarket' ), __( 'Seguimiento de tu pedido por correo.', 'gmarket' ) ),
		'ft3_title'        => array( 'footer', __( 'Franja de confianza 3: título', 'gmarket' ), __( 'Pago 100% seguro', 'gmarket' ) ),
		'ft3_text'         => array( 'footer', __( 'Franja de confianza 3: texto', 'gmarket' ), __( 'Tarjeta, PayPal, Apple Pay y Google Pay.', 'gmarket' ) ),
		'ft4_title'        => array( 'footer', __( 'Franja de confianza 4: título (el texto es el correo)', 'gmarket' ), __( 'Atención por correo', 'gmarket' ) ),
		'ft_about'         => array( 'footer', __( 'Texto junto al logo del pie', 'gmarket' ), __( 'Tu tienda online de confianza. Compra fácil, paga seguro y recibe en casa.', 'gmarket' ), 'textarea' ),
		'ft_col1'          => array( 'footer', __( 'Título de la columna 1 (menú «Pie: columna 1»)', 'gmarket' ), __( 'Conócenos', 'gmarket' ) ),
		'ft_col2'          => array( 'footer', __( 'Título de la columna 2 (menú «Pie: columna 2»)', 'gmarket' ), __( 'Te ayudamos', 'gmarket' ) ),
		'ft_col3'          => array( 'footer', __( 'Título de la columna 3 (menú «Pie: columna 3»)', 'gmarket' ), __( 'Información legal', 'gmarket' ) ),
		'ft_cats'          => array( 'footer', __( 'Título de la columna de categorías', 'gmarket' ), __( 'Categorías', 'gmarket' ) ),
		'ft_notice'        => array( 'footer', __( 'Aviso sobre el pie (vacío = se oculta)', 'gmarket' ), '', 'textarea' ),
		'ft_notice_url'    => array( 'footer', __( 'Aviso: enlace «Más información» (opcional)', 'gmarket' ), '', 'url' ),
		'ft_notice_link'   => array( 'footer', __( 'Aviso: texto del enlace', 'gmarket' ), __( 'Más información', 'gmarket' ) ),
		'ft_copy'          => array( 'footer', __( 'Derechos (va detrás de «© año Nombre del sitio.»)', 'gmarket' ), __( 'Todos los derechos reservados.', 'gmarket' ) ),
		'pay_icons'        => array( 'footer', __( 'Iconos de pago que se muestran', 'gmarket' ), 'cc-visa,cc-mastercard,cc-amex,cc-discover,cc-paypal,cc-stripe,cc-apple-pay,google-pay', 'payicons' ),

		// Página 404.
		'e404_title'       => array( '404', __( 'Título', 'gmarket' ), __( 'Lo sentimos, no encontramos esta página', 'gmarket' ) ),
		'e404_text'        => array( '404', __( 'Texto', 'gmarket' ), __( 'Puede que el enlace haya cambiado. Busca lo que necesitas o vuelve a la tienda.', 'gmarket' ) ),
		'e404_button'      => array( '404', __( 'Botón', 'gmarket' ), __( 'Ir a la tienda', 'gmarket' ) ),

		// Segundo idioma: textos que TranslatePress no alcanza (se pintan con JavaScript).
		'alt_search_none'  => array( 'alt', __( 'Búsqueda en vivo: sin resultados', 'gmarket' ), 'No products match your search.' ),
		'alt_search_all'   => array( 'alt', __( 'Búsqueda en vivo: ver todos', 'gmarket' ), 'See all results for' ),
		'alt_search_cat'   => array( 'alt', __( 'Búsqueda en vivo: etiqueta de categoría', 'gmarket' ), 'Category:' ),
		'alt_search_short' => array( 'alt', __( 'Texto de ayuda del buscador en el móvil', 'gmarket' ), 'Search…' ),
		'alt_wish_add'     => array( 'alt', __( 'Lista de deseos (YITH): guardar', 'gmarket' ), 'Save' ),
		'alt_wish_added'   => array( 'alt', __( 'Lista de deseos (YITH): guardado', 'gmarket' ), 'Saved' ),
		'alt_wish_view'    => array( 'alt', __( 'Lista de deseos (YITH): ver lista', 'gmarket' ), 'View list' ),
		'alt_wish_already' => array( 'alt', __( 'Lista de deseos (YITH): ya está', 'gmarket' ), 'Already in your list' ),
		'alt_wish_title'   => array( 'alt', __( 'Lista de deseos (YITH): título', 'gmarket' ), 'My wish list' ),
	);
	return $fields;
}

/**
 * Secciones de la página de ajustes.
 */
function gmk_text_sections() {
	return array(
		'header'  => __( 'Cabecera y menú lateral', 'gmarket' ),
		'search'  => __( 'Buscador', 'gmarket' ),
		'shop'    => __( 'Tienda', 'gmarket' ),
		'filters' => __( 'Filtros de la tienda', 'gmarket' ),
		'product' => __( 'Ficha de producto', 'gmarket' ),
		'account' => __( 'Mi cuenta', 'gmarket' ),
		'home'    => __( 'Bloques de la portada', 'gmarket' ),
		'footer'  => __( 'Pie y contacto', 'gmarket' ),
		'404'     => __( 'Página no encontrada (404)', 'gmarket' ),
		'alt'     => __( 'Segundo idioma (solo con TranslatePress: lo que no se puede traducir con «Traducir página»)', 'gmarket' ),
	);
}

/**
 * Texto configurable: el guardado (aunque esté vacío) o, si nunca se guardó, el valor por defecto.
 */
function gmk_t( $key ) {
	static $saved = null;
	if ( null === $saved ) {
		$saved = (array) get_option( 'gmk_texts', array() );
	}
	if ( array_key_exists( $key, $saved ) ) {
		return (string) $saved[ $key ];
	}
	$fields = gmk_text_fields();
	return isset( $fields[ $key ] ) ? $fields[ $key ][2] : '';
}

add_action(
	'admin_menu',
	function () {
		add_theme_page( __( 'Textos de la tienda', 'gmarket' ), __( 'Textos de la tienda', 'gmarket' ), 'manage_options', 'gmk-textos', 'gmk_texts_page' );
	}
);

add_action(
	'admin_init',
	function () {
		register_setting(
			'gmk_texts',
			'gmk_texts',
			array(
				'type'              => 'array',
				'sanitize_callback' => 'gmk_texts_sanitize',
			)
		);
	}
);

/**
 * Limpia los valores guardados.
 */
function gmk_texts_sanitize( $input ) {
	$out = array();
	foreach ( gmk_text_fields() as $key => $field ) {
		$type = isset( $field[3] ) ? $field[3] : 'text';
		if ( in_array( $type, array( 'attributes', 'payicons' ), true ) ) {
			// «-» = ninguno marcado (distinto de «nunca guardado»).
			$out[ $key ] = ! empty( $input[ $key ] ) ? implode( ',', array_map( 'sanitize_key', (array) $input[ $key ] ) ) : '-';
			continue;
		}
		if ( ! isset( $input[ $key ] ) ) {
			continue;
		}
		$value = wp_unslash( $input[ $key ] );
		switch ( $type ) {
			case 'textarea':
				$out[ $key ] = sanitize_textarea_field( $value );
				break;
			case 'email':
				$out[ $key ] = sanitize_email( $value );
				break;
			case 'url':
				$out[ $key ] = esc_url_raw( $value );
				break;
			case 'number':
				$out[ $key ] = (string) absint( $value );
				break;
			default:
				$out[ $key ] = sanitize_text_field( $value );
		}
	}
	gmk_flush_caches();
	return $out;
}

/**
 * Vacía las cachés habituales para que los cambios se vean al momento.
 */
function gmk_flush_caches() {
	if ( function_exists( 'wp_cache_flush' ) ) {
		wp_cache_flush();
	}
	if ( isset( $GLOBALS['wp_fastest_cache'] ) && method_exists( $GLOBALS['wp_fastest_cache'], 'deleteCache' ) ) {
		$GLOBALS['wp_fastest_cache']->deleteCache( true );
	}
	if ( function_exists( 'rocket_clean_domain' ) ) {
		rocket_clean_domain();
	}
	do_action( 'litespeed_purge_all' );
}

/**
 * Página de ajustes.
 */
function gmk_texts_page() {
	$fields   = gmk_text_fields();
	$saved    = (array) get_option( 'gmk_texts', array() );
	$sections = gmk_text_sections();
	?>
	<div class="wrap">
		<h1><?php esc_html_e( 'Textos de la tienda', 'gmarket' ); ?></h1>
		<p><?php esc_html_e( 'Aquí cambias los textos fijos del tema. Deja un campo vacío para ocultar ese elemento. Si usas TranslatePress, traduce el resto con «Traducir página»; la sección «Segundo idioma» es para lo que TranslatePress no puede traducir.', 'gmarket' ); ?></p>
		<p>
			<?php
			printf(
				/* translators: 1: enlace al personalizador, 2: enlace a los menús. */
				esc_html__( 'Colores y logo del pie: %1$s. Menús: %2$s. Logo e icono del sitio: Apariencia › Personalizar › Identidad del sitio.', 'gmarket' ),
				'<a href="' . esc_url( admin_url( 'customize.php?autofocus[section]=gmk_colors' ) ) . '">' . esc_html__( 'Apariencia › Personalizar › GMarket', 'gmarket' ) . '</a>', // phpcs:ignore
				'<a href="' . esc_url( admin_url( 'nav-menus.php' ) ) . '">' . esc_html__( 'Apariencia › Menús', 'gmarket' ) . '</a>' // phpcs:ignore
			);
			?>
		</p>
		<form method="post" action="options.php">
			<?php settings_fields( 'gmk_texts' ); ?>
			<?php foreach ( $sections as $sid => $stitle ) : ?>
				<?php
				if ( 'alt' === $sid && ! function_exists( 'trp_custom_language_switcher' ) ) {
					continue;
				}
				?>
				<h2 class="title" style="margin-top:2em"><?php echo esc_html( $stitle ); ?></h2>
				<table class="form-table" role="presentation">
					<?php
					foreach ( $fields as $key => $field ) :
						if ( $field[0] !== $sid ) {
							continue;
						}
						$type  = isset( $field[3] ) ? $field[3] : 'text';
						$value = gmk_t( $key );
						$name  = 'gmk_texts[' . $key . ']';
						?>
						<tr>
							<th scope="row"><label for="gmk-<?php echo esc_attr( $key ); ?>"><?php echo esc_html( $field[1] ); ?></label></th>
							<td>
								<?php if ( 'textarea' === $type ) : ?>
									<textarea id="gmk-<?php echo esc_attr( $key ); ?>" name="<?php echo esc_attr( $name ); ?>" rows="3" class="large-text"><?php echo esc_textarea( $value ); ?></textarea>
								<?php elseif ( 'attributes' === $type ) : ?>
									<?php
									$attrs = function_exists( 'wc_get_attribute_taxonomies' ) ? wc_get_attribute_taxonomies() : array();
									if ( ! $attrs ) {
										echo '<p class="description">' . esc_html__( 'Aún no hay atributos. Créalos en Productos › Atributos.', 'gmarket' ) . '</p>';
									}
									foreach ( $attrs as $attr ) :
										?>
										<label style="display:block;margin:2px 0"><input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $attr->attribute_name ); ?>" <?php checked( '*' === $value || in_array( $attr->attribute_name, explode( ',', $value ), true ) ); ?>> <?php echo esc_html( $attr->attribute_label ); ?></label>
									<?php endforeach; ?>
								<?php elseif ( 'payicons' === $type ) : ?>
									<?php foreach ( require GMK_DIR . '/inc/pay-icons.php' as $icon => $data ) : ?>
										<label style="display:inline-block;margin:2px 14px 2px 0"><input type="checkbox" name="<?php echo esc_attr( $name ); ?>[]" value="<?php echo esc_attr( $icon ); ?>" <?php checked( in_array( $icon, explode( ',', $value ), true ) ); ?>> <?php echo esc_html( $data[0] ); ?></label>
									<?php endforeach; ?>
								<?php else : ?>
									<?php
									$input_type = in_array( $type, array( 'number', 'email', 'url' ), true ) ? $type : 'text';
									$extra      = 'number' === $type ? ' min="0" max="24"' : '';
									$extra     .= 'email' === $type ? ' placeholder="' . esc_attr( get_option( 'admin_email' ) ) . '"' : '';
									$extra     .= 'url' === $type ? ' placeholder="https://"' : '';
									?>
									<input id="gmk-<?php echo esc_attr( $key ); ?>" type="<?php echo esc_attr( $input_type ); ?>" name="<?php echo esc_attr( $name ); ?>" value="<?php echo esc_attr( $value ); ?>" class="<?php echo 'number' === $type ? 'small-text' : 'regular-text'; ?>"<?php echo $extra; // phpcs:ignore ?>>
								<?php endif; ?>
							</td>
						</tr>
					<?php endforeach; ?>
				</table>
			<?php endforeach; ?>
			<?php submit_button( __( 'Guardar textos', 'gmarket' ) ); ?>
		</form>
		<p style="color:#646970"><?php esc_html_e( 'GMarket · Tema desarrollado por GMETI.', 'gmarket' ); ?></p>
	</div>
	<?php
}
