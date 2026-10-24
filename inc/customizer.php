<?php
/**
 * Apariencia › Personalizar › GMarket: colores de la marca y logo del pie.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Colores editables: ajuste => array( variable CSS, etiqueta, valor por defecto ).
 */
function gmk_color_settings() {
	return array(
		'gmk_color_primary'      => array( '--gmk-blue', __( 'Color principal (enlaces, botones secundarios)', 'gmarket' ), '#0a4c9a' ),
		'gmk_color_primary_dark' => array( '--gmk-blue-dark', __( 'Color principal oscuro (al pasar el ratón)', 'gmarket' ), '#062f63' ),
		'gmk_color_deep'         => array( '--gmk-blue-deep', __( 'Fondo de la cabecera y del pie', 'gmarket' ), '#0b1e3a' ),
		'gmk_color_light'        => array( '--gmk-blue-light', __( 'Color principal claro', 'gmarket' ), '#0b74c8' ),
		'gmk_color_accent'       => array( '--gmk-green', __( 'Color de acento (buscador, precios destacados)', 'gmarket' ), '#3a9a2a' ),
		'gmk_color_accent_dark'  => array( '--gmk-green-dark', __( 'Color de acento oscuro', 'gmarket' ), '#2b7a1f' ),
		'gmk_color_accent_light' => array( '--gmk-green-light', __( 'Color de acento claro (mensajes sobre fondo oscuro)', 'gmarket' ), '#6cb52a' ),
		'gmk_color_button'       => array( '--gmk-yellow', __( 'Botón de compra', 'gmarket' ), '#ffd23f' ),
		'gmk_color_button_dark'  => array( '--gmk-yellow-dark', __( 'Botón de compra (al pasar el ratón)', 'gmarket' ), '#f5c518' ),
		'gmk_color_bg'           => array( '--gmk-bg', __( 'Fondo de la página', 'gmarket' ), '#eef2f6' ),
	);
}

add_action(
	'customize_register',
	function ( $wp_customize ) {
		$wp_customize->add_panel(
			'gmk_panel',
			array(
				'title'       => __( 'GMarket', 'gmarket' ),
				'description' => __( 'Tema desarrollado por GMETI. Los textos se editan en Apariencia › Textos de la tienda.', 'gmarket' ),
				'priority'    => 30,
			)
		);
		$wp_customize->add_section( 'gmk_colors', array( 'title' => __( 'Colores', 'gmarket' ), 'panel' => 'gmk_panel' ) );
		foreach ( gmk_color_settings() as $id => $color ) {
			$wp_customize->add_setting(
				$id,
				array(
					'default'           => $color[2],
					'sanitize_callback' => 'sanitize_hex_color',
				)
			);
			$wp_customize->add_control( new WP_Customize_Color_Control( $wp_customize, $id, array( 'label' => $color[1], 'section' => 'gmk_colors' ) ) );
		}

		$wp_customize->add_section( 'gmk_footer', array( 'title' => __( 'Pie de página', 'gmarket' ), 'panel' => 'gmk_panel' ) );
		$wp_customize->add_setting( 'gmk_footer_logo', array( 'sanitize_callback' => 'absint' ) );
		$wp_customize->add_control(
			new WP_Customize_Media_Control(
				$wp_customize,
				'gmk_footer_logo',
				array(
					'label'       => __( 'Logo del pie', 'gmarket' ),
					'description' => __( 'Versión clara del logo, para el fondo oscuro del pie. Si no hay, se usa el logo del sitio.', 'gmarket' ),
					'section'     => 'gmk_footer',
					'mime_type'   => 'image',
				)
			)
		);
	}
);

/**
 * Variables CSS con los colores elegidos (solo las que cambian respecto al tema).
 */
function gmk_colors_css() {
	$css = '';
	foreach ( gmk_color_settings() as $id => $color ) {
		$value = sanitize_hex_color( get_theme_mod( $id, $color[2] ) );
		if ( $value && strtolower( $value ) !== $color[2] ) {
			$css .= $color[0] . ':' . $value . ';';
		}
	}
	return $css ? ':root{' . $css . '}' : '';
}
