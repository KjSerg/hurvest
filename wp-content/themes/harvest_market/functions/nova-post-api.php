<?php
function get_nova_post_cities( $find = '' ) {
	$novaposhta_api_end_point = carbon_get_theme_option( 'novaposhta_api_end_point' );
	$novaposhta_api_key       = carbon_get_theme_option( 'novaposhta_api_key' ) ?: '';
	$res                      = array();
	if ( $novaposhta_api_end_point ) {
		$json = array(
			'apiKey'           => $novaposhta_api_key,
			'modelName'        => 'Address',
			'calledMethod'     => 'getCities',
			'methodProperties' => array()
		);
		if ( $find ) {
			$json['methodProperties']['FindByString'] = $find;
		}
		$key = sha1( $find );
		$res = get_transient( $key );
		if ( false === $res ) {
			$res = send_request( $novaposhta_api_end_point, $json );
			set_transient( $key, $res, HOUR_IN_SECONDS * 24 );
		}


	}

	return $res;
}

function get_nova_post_offices( $ref ) {
	$novaposhta_api_end_point = carbon_get_theme_option( 'novaposhta_api_end_point' );
	$novaposhta_api_key       = carbon_get_theme_option( 'novaposhta_api_key' ) ?: '';
	$res                      = array();
	if ( $novaposhta_api_end_point ) {
		$json = array(
			'apiKey'           => $novaposhta_api_key,
			'modelName'        => 'Address',
			'calledMethod'     => 'getWarehouses',
			'methodProperties' => array(
				'CityRef' => $ref
			)
		);

		$key = sha1( $ref );
		$res = get_transient( $key );
		if ( false === $res ) {
			$res = send_request( $novaposhta_api_end_point, $json );
			set_transient( $key, $res, HOUR_IN_SECONDS * 24 );
		}

	}

	return $res;
}