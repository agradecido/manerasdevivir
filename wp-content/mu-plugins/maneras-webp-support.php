<?php
/*
 * Plugin Name:   WebP Support
 * Description:   Allows uploading and displaying WebP images in WordPress.
 * Author:        Javier Sierra <agradecido@manerasdevivir.com>
 * Version:       1.0
 */

// 1) Habilita .webp en la librería de medios.
function soportar_webp_subida( $mimes ) {
	$mimes['webp'] = 'image/webp';
	return $mimes;
}
add_filter( 'upload_mimes', 'soportar_webp_subida' );

// 2) Asegura que WordPress reconozca el MIME y extensión.
function webp_corregir_tipo( $data, $file, $filename, $mimes ) {
	$ext = pathinfo( $filename, PATHINFO_EXTENSION );
	if ( strtolower( $ext ) === 'webp' ) {
		$data['ext']  = 'webp';
		$data['type'] = 'image/webp';
	}
	return $data;
}
add_filter( 'wp_check_filetype_and_ext', 'webp_corregir_tipo', 10, 4 );

// 3) Permite previsualizar WebP en la librería.
function webp_mostrar_imagen( $result, $path ) {
	$info = @getimagesize( $path );
	if ( isset( $info['mime'] ) && $info['mime'] === 'image/webp' ) {
		return true;
	}
	return $result;
}
add_filter( 'file_is_displayable_image', 'webp_mostrar_imagen', 10, 2 );
