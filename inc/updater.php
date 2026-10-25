<?php
/**
 * Actualizaciones automáticas desde las releases de GitHub.
 *
 * Cuando se publica una release nueva (etiqueta "v1.2.3" con el archivo gmarket.zip
 * adjunto), WordPress muestra el aviso en Apariencia › Temas y en Escritorio ›
 * Actualizaciones, y el tema se actualiza con un clic.
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

define( 'GMK_REPO', 'wpdesigndev/gmarket' );
define( 'GMK_ASSET', 'gmarket.zip' );

/** Última release publicada (se guarda 6 horas para no consultar GitHub en cada carga). */
function gmk_latest_release() {
	// "Comprobar de nuevo" en Escritorio › Actualizaciones consulta GitHub al momento.
	// phpcs:ignore WordPress.Security.NonceVerification.Recommended
	$force  = is_admin() && ! empty( $_GET['force-check'] ) && current_user_can( 'update_themes' );
	$cached = get_site_transient( 'gmk_github_release' );
	if ( ! $force && is_array( $cached ) ) {
		return $cached;
	}

	$release  = array();
	$response = wp_remote_get(
		'https://api.github.com/repos/' . GMK_REPO . '/releases/latest',
		array(
			'timeout' => 10,
			'headers' => array(
				'Accept'     => 'application/vnd.github+json',
				'User-Agent' => 'GMarket/' . GMK_VERSION,
			),
		)
	);
	if ( ! is_wp_error( $response ) && 200 === (int) wp_remote_retrieve_response_code( $response ) ) {
		$body = json_decode( wp_remote_retrieve_body( $response ), true );
		if ( is_array( $body ) && ! empty( $body['tag_name'] ) && empty( $body['draft'] ) && empty( $body['prerelease'] ) ) {
			foreach ( isset( $body['assets'] ) ? (array) $body['assets'] : array() as $asset ) {
				// Solo se acepta el zip publicado en este mismo repositorio.
				if ( isset( $asset['name'], $asset['browser_download_url'] ) && GMK_ASSET === $asset['name']
					&& 0 === strpos( $asset['browser_download_url'], 'https://github.com/' . GMK_REPO . '/releases/download/' ) ) {
					$release = array(
						'version' => ltrim( (string) $body['tag_name'], 'vV' ),
						'package' => $asset['browser_download_url'],
						'url'     => isset( $body['html_url'] ) ? $body['html_url'] : 'https://github.com/' . GMK_REPO,
					);
					break;
				}
			}
		}
	}

	// Si GitHub no responde, se vuelve a intentar en 1 hora.
	set_site_transient( 'gmk_github_release', $release, $release ? 6 * HOUR_IN_SECONDS : HOUR_IN_SECONDS );
	return $release;
}

add_filter(
	'pre_set_site_transient_update_themes',
	function ( $transient ) {
		if ( ! is_object( $transient ) ) {
			return $transient;
		}
		$release = gmk_latest_release();
		if ( empty( $release['version'] ) ) {
			return $transient;
		}
		$slug = get_template();
		$item = array(
			'theme'        => $slug,
			'new_version'  => $release['version'],
			'url'          => $release['url'],
			'package'      => $release['package'],
			'requires'     => '6.4',
			'requires_php' => '7.4',
		);
		if ( version_compare( $release['version'], GMK_VERSION, '>' ) ) {
			$transient->response[ $slug ] = $item;
		} else {
			$transient->no_update[ $slug ] = $item;
		}
		return $transient;
	}
);

add_action(
	'upgrader_process_complete',
	function () {
		delete_site_transient( 'gmk_github_release' );
	}
);
