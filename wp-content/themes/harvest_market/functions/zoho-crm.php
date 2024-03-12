<?php
function get_zoho_authorization() {
	$url           = carbon_get_theme_option( 'zoho_url_token' );
	$refresh_token = carbon_get_theme_option( 'zoho_refresh_token' );
	$client_id     = carbon_get_theme_option( 'zoho_client_id' );
	$client_secret = carbon_get_theme_option( 'zoho_client_secret' );
	if ( $url && $refresh_token && $client_id && $client_secret ) {
		$payload      = "refresh_token=$refresh_token&client_id=$client_id&client_secret=$client_secret&grant_type=refresh_token";
		$data         = zoho_authorization_request( $url, $payload );
		$access_token = $data['access_token'] ?? '';

		return $access_token ?: false;
	}

	return false;
}

function create_zoho_user( $user_data = array() ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url .= 'Contacts';
		if ( isset( $user_data['email'] ) ) {
			$contact_data = array(
				'Email' => $user_data['email'],
				'Phone' => $user_data['phone'],
			);
			if ( isset( $user_data['first_name'] ) ) {
				$contact_data['First_Name'] = $user_data['first_name'];
			}
			if ( isset( $user_data['last_name'] ) ) {
				$contact_data['Last_Name'] = $user_data['last_name'];
			}
			if ( isset( $user_data['surname'] ) ) {
				$contact_data['Patronymic_Name'] = $user_data['surname'];
			}
			if ( isset( $user_data['region'] ) ) {
				$contact_data['Mailing_State'] = $user_data['region'];
			}
			if ( isset( $user_data['city'] ) ) {
				$contact_data['Mailing_City'] = $user_data['city'];
			}
			if ( isset( $user_data['description'] ) ) {
				$contact_data['Description'] = strip_tags( trim( $user_data['description'] ) );
			}
			$tag                 = $user_data['tag'] ?? 'Buyer';
			$contact_data['Tag'] = array(
				array(
					'name'       => $tag,
					'color_code' => $tag == 'Seller' ? '#fc3636' : '#D297EE',
				)
			);
			$data                = array(
				'data' => array(
					$contact_data
				)
			);
			$json_data           = json_encode( $data );
			$res                 = zoho_request( $url, $json_data );
			if ( $d = $res['data'] ) {
				if ( $d[0]['code'] === 'SUCCESS' ) {
					$details = $d[0]['details'] ?? '';
					if ( $details ) {
						carbon_set_user_meta( $user_data['id'], 'zoho_id', $details['id'] ?? '' );
					}
				}
			}

			return $res;
		}
	}
}

function edit_zoho_user( $user_data = array() ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url     .= 'Contacts/';
		$user_id = $user_data['id'] ?? '';
		if ( $user_id ) {
			$zoho_id = carbon_get_user_meta( $user_id, 'zoho_id' );
			if ( $zoho_id ) {
				$url          .= $zoho_id;
				$contact_data = array();
				if ( isset( $user_data['email'] ) ) {
					$contact_data['Email'] = $user_data['email'];
				}
				if ( isset( $user_data['phone'] ) ) {
					$contact_data['Phone'] = $user_data['phone'];
				}
				if ( isset( $user_data['first_name'] ) ) {
					$contact_data['First_Name'] = $user_data['first_name'];
				}
				if ( isset( $user_data['last_name'] ) ) {
					$contact_data['Last_Name'] = $user_data['last_name'];
				}
				if ( isset( $user_data['surname'] ) ) {
					$contact_data['Patronymic_Name'] = $user_data['surname'];
				}
				if ( isset( $user_data['region'] ) ) {
					$contact_data['Mailing_State'] = $user_data['region'];
				}
				if ( isset( $user_data['city'] ) ) {
					$contact_data['Mailing_City'] = $user_data['city'];
				}
				if ( isset( $user_data['telegram'] ) ) {
					$contact_data['Telegram'] = $user_data['telegram'];
				}
				if ( isset( $user_data['description'] ) ) {
					$contact_data['Description'] = strip_tags( trim( $user_data['description'] ) );
				}
				if ( isset( $user_data['Account_Name'] ) ) {
					$contact_data['Account_Name'] = $user_data['Account_Name'];
				}
				if ( isset( $user_data['tag'] ) ) {
					$contact_data['Tag'] = array(
						array(
							'name'       => $user_data['tag'],
							'color_code' => $user_data['tag'] == 'Seller' ? '#fc3636' : '#D297EE'
						)
					);
				}

				$data      = array(
					'data' => array(
						$contact_data
					)
				);
				$json_data = json_encode( $data );
				$res       = zoho_request( $url, $json_data, 'PUT' );

				return $res;
			}
		}
	}

	return false;
}

function create_zoho_account( $args = array() ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url     .= 'Accounts';
		$user_id = $args['user_id'] ?? '';
		if ( $user_id ) {
			if ( $contact_id = carbon_get_user_meta( $user_id, 'zoho_id' ) ) {
				$data      = array(
					'data' => array(
						array(
							'Account_Name'  => $args['name'] ?? '',
							'Description'   => strip_tags( trim( $args['description'] ?? '' ) ),
							'Mailing_State' => $args['region'] ?? '',
							'Phone'         => $args['phone'] ?? '',
							'Email'         => $args['email'] ?? get_user_by( 'ID', $user_id )->user_email,
							'Tag'           => array(
								array(
									'name'       => 'Seller',
									'color_code' => '#fc3636'
								)
							),
						)
					)
				);
				$json_data = json_encode( $data );
				$res       = zoho_request( $url, $json_data, 'POST' );
				if ( $d = $res['data'] ) {
					if ( $d[0]['code'] === 'SUCCESS' ) {
						$details = $d[0]['details'] ?? '';
						if ( $details ) {
							carbon_set_user_meta( $user_id, 'zoho_account_id', $details['id'] ?? '' );
							$r = edit_zoho_user( array(
								'id'           => $user_id,
								'tag'          => 'Seller',
								'Account_Name' => array(
									'name' => $args['name'] ?? '',
									'id'   => $details['id'] ?? '',
								),
							) );
							if ( $r ) {
								$res['r'] = $r;
							}
						}
					}
				}

				return $res;
			}
		}

	}

	return false;
}

function edit_zoho_account( $args = array() ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url     .= 'Accounts';
		$user_id = $args['user_id'] ?? '';
		if ( $user_id ) {
			if ( $contact_id = carbon_get_user_meta( $user_id, 'zoho_id' ) ) {
				$zoho_account_id = carbon_get_user_meta( $user_id, 'zoho_account_id' );
				$url             .= '/' . $zoho_account_id;
				$data            = array(
					'data' => array(
						array(
							'Account_Name'  => $args['name'] ?? '',
							'Description'   => strip_tags( trim( $args['description'] ?? '' ) ),
							'Mailing_State' => $args['region'] ?? '',
							'Phone'         => $args['phone'] ?? '',
							'Email'         => $args['email'],
							'Tag'           => array(
								array(
									'id'         => 'Seller',
									'color_code' => '#fc3636'
								)
							),
						)
					)
				);
				$json_data       = json_encode( $data );
				$res             = zoho_request( $url, $json_data, 'PUT' );

				return $res;
			}
		}

	}

	return false;
}

function create_zoho_deal( $args = array() ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url          .= 'Deals';
		$user_id      = $args['user_id'] ?? '';
		$purchased_id = $args['purchased_id'] ?? '';
		$data         = array(
			'data' => array(
				$args
			)
		);
		$json_data    = json_encode( $data );
		$res          = zoho_request( $url, $json_data, 'POST' );
		if ( $d = $res['data'] ) {
			if ( $d[0]['code'] === 'SUCCESS' ) {
				$details = $d[0]['details'] ?? '';
				if ( $details ) {
					carbon_set_post_meta( $purchased_id, 'purchased_zoho_id', $details['id'] ?? '' );
				}
			}
		}

		return $res;

	}

	return false;
}

function search_zoho_contact_by_email( $email ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url .= 'Contacts/search?email=' . $email;

		return zoho_request( $url, false, 'GET' );
	}
}

function link_zoho_account() {
	$user_id = get_current_user_id();
	$zoho_id = carbon_get_user_meta( $user_id, 'zoho_id' );
	if ( ! $zoho_id ) {

		$current_user      = get_user_by( 'ID', $user_id );
		$email             = $current_user->user_email ?: '';
		$display_name      = $current_user->display_name ?: '';
		$first_name        = $current_user->first_name ?: '';
		$last_name         = $current_user->last_name ?: '';
		$user_surname      = carbon_get_user_meta( $user_id, 'user_surname' ) ?: '';
		$user_city         = carbon_get_user_meta( $user_id, 'user_city' ) ?: '';
		$user_phone        = carbon_get_user_meta( $user_id, 'user_phone' ) ?: '';
		$user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
		$r                 = create_zoho_user( array(
			'first_name' => $first_name,
			'last_name'  => $last_name,
			'surname'    => $user_surname,
			'email'      => $email,
			'phone'      => $user_phone,
			'id'         => $user_id,
			'city'       => $user_confirm_city ?: $user_city,
		) );
		if ( $r ) {
			$is_seller       = carbon_get_user_meta( $user_id, 'user_seller' ) ?: '';
			$zoho_account_id = carbon_get_user_meta( $user_id, 'zoho_account_id' );
			if ( $is_seller && ! $zoho_account_id ) {
				$company_name  = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
				$text          = carbon_get_user_meta( $user_id, 'user_company_description' ) ?: '';
				$region        = carbon_get_user_meta( $user_id, 'user_company_region' ) ?: '';
				$company_phone = carbon_get_user_meta( $user_id, 'user_company_phone' ) ?: '';
				$r             = create_zoho_account( array(
					'name'        => $company_name,
					'description' => strip_tags( trim( $text ) ),
					'region'      => $region,
					'phone'       => $company_phone,
					'user_id'     => $user_id,
					'email'       => $email,
				) );
			}
		}
	}
}

function get_zoho_account_by_user_id( $user_id = 0 ) {
	$user_id         = $user_id ?: get_current_user_id();
	$url             = carbon_get_theme_option( 'zoho_url' );
	$zoho_account_id = carbon_get_user_meta( $user_id, 'zoho_account_id' );
	if ( $url && $zoho_account_id ) {
		$url .= 'Accounts/' . $zoho_account_id;

		return zoho_request( $url, false, 'GET' );
	}
}

function get_zoho_deal_by_id( $id ) {
	$url = carbon_get_theme_option( 'zoho_url' );
	if ( $url ) {
		$url .= 'Deals/' . $id;

		return zoho_request( $url, false, 'GET' );
	}
}

function zoho_authorization_request( $api_url, $args ) {
	if ( $curl = curl_init() ) {
		curl_setopt( $curl, CURLOPT_URL, $api_url );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_POST, true );
		curl_setopt( $curl, CURLOPT_POSTFIELDS, $args );
		curl_setopt( $curl, CURLOPT_HTTPHEADER, array(
			'Content-Type: application/x-www-form-urlencoded'
		) );
		$out  = curl_exec( $curl );
		$json = json_decode( $out, true );
		curl_close( $curl );

		return $json;
	} else {
		throw new HttpException( 'Can not create connection to ' . $api_url . ' with args ' . $args, 404 );
	}
}

function zoho_request( $api_url, $args = array(), $request_type = 'POST' ) {
	$token = get_zoho_authorization();
	if ( ! $token ) {
		return false;
	}
	if ( $curl = curl_init() ) {
		$h = array(
			"Authorization: Zoho-oauthtoken $token",
		);
		if ( $args ) {
			$h[] = 'Content-Type: application/json';
		}
		curl_setopt( $curl, CURLOPT_URL, $api_url );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $request_type );
		if ( ! empty( $args ) ) {
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $args );
		}
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $h );
		$out  = curl_exec( $curl );
		$json = json_decode( $out, true );
		curl_close( $curl );

		return $json;
	} else {
		throw new HttpException( 'Can not create connection to ' . $api_url . ' with args ' . $args, 404 );
	}
}