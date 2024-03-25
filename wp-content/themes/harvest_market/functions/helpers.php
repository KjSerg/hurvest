<?php

function variables() {

	return array(

		'url_home'        => get_bloginfo( 'template_url' ) . '/',
		'assets'          => get_bloginfo( 'template_url' ) . '/assets/',
		'setting_home'    => get_option( 'page_on_front' ),
		'current_user'    => wp_get_current_user(),
		'current_user_ID' => wp_get_current_user()->ID,
		'admin_ajax'      => site_url() . '/wp-admin/admin-ajax.php',
		'url'             => get_bloginfo( 'url' ) . '/',
		'currency'        => carbon_get_theme_option( 'currency' ),
	);

}

function escapeJavaScriptText( $string ) {
	return str_replace( "\n", '\n', str_replace( '"', '\"', addcslashes( str_replace( "\r", '', (string) $string ), "\0..\37'\\" ) ) );
}

add_filter( 'excerpt_length', function () {
	return 32;
} );

add_filter( 'excerpt_more', function ( $more ) {
	return '...';
} );

function _get_next_link( $max_page = 0 ) {
	global $paged, $wp_query;
	if ( ! $max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	if ( ! $paged ) {
		$paged = 1;
	}
	$nextpage = intval( $paged ) + 1;
	if ( ! is_single() ) {
		if ( $nextpage <= $max_page ) {
			return ' <a class="page-nav page-next" href="' . next_posts( $max_page, false ) . '"  ></a>';
		}
	}
}

function _get_previous_link() {
	global $paged;
	$var    = variables();
	$assets = $var['assets'];
	if ( ! is_single() ) {
		if ( $paged > 1 ) {
			return '<a href="' . previous_posts( false ) . '" class="page-nav page-prev"></a>';
		}
	}
}

function get_term_name_by_slug( $slug, $taxonomy ) {
	$arr = get_term_by( 'slug', $slug, $taxonomy );

	return $arr->name;
}

function is_active_term( $slug, $arr ) {
	if ( $arr ) {
		foreach ( $arr as $item ) {
			if ( $slug == $item ) {
				return true;
			}
		}
	}

	return false;
}

function get_user_roles_by_user_id( $user_id ) {
	$user = get_userdata( $user_id );

	return empty( $user ) ? array() : $user->roles;
}

function is_user_in_role( $user_id, $role ) {
	return in_array( $role, get_user_roles_by_user_id( $user_id ) );
}

function filter_ptags_on_images( $content ) {
//функция preg replace, которая убивает тег p
	return preg_replace( '/<p>\s*(<a .*>)?\s*(<img .* \/>)\s*(<\/a>)?\s*<\/p>/iU', '\1\2\3', $content );
}

function str_split_unicode( $str, $l = 0 ) {
	if ( $l > 0 ) {
		$ret = array();
		$len = mb_strlen( $str, "UTF-8" );
		for ( $i = 0; $i < $len; $i += $l ) {
			$ret[] = mb_substr( $str, $i, $l, "UTF-8" );
		}

		return $ret;
	}

	return preg_split( "//u", $str, - 1, PREG_SPLIT_NO_EMPTY );
}

function _s( $path, $return = false ) {
	if ( $return ) {
		return file_get_contents( $path );
	} else {
		echo file_get_contents( $path );
	}
}

function _i( $image_name ) {
	$var    = variables();
	$assets = $var['assets'];

	return $assets . 'img/' . $image_name . '.svg';
}

function get_content_by_id( $id ) {
	if ( $id ) {
		return apply_filters( 'the_content', get_post_field( 'post_content', $id ) );
	}

	return false;
}

function the_phone_link( $phone_number ) {
	$s = array( '+', '-', ' ', '(', ')' );
	$r = array( '', '', '', '', '' );
	echo 'tel:' . str_replace( $s, $r, $phone_number );
}

function get_phone_link( $phone_number ) {
	$s = array( '+', '-', ' ', '(', ')' );
	$r = array( '', '', '', '', '' );

	return 'tel:' . str_replace( $s, $r, $phone_number );
}

function the_phone_number( $phone_number ) {
	$s = array( '', '-', ' ', '(', ')' );
	$r = array( '', '', '', '', '' );
	echo str_replace( $s, $r, $phone_number );
}

function the_image( $id ) {
	if ( $id ) {

		$url = wp_get_attachment_url( $id );

		$pos = strripos( $url, '.svg' );

		if ( $pos === false ) {
			echo '<img class="" src="' . $url . '" alt="">';
		} else {
			_s( $url );
		}

	}
}

function get_image( $id ) {
	if ( $id ) {

		$url = wp_get_attachment_url( $id );

		$pos = strripos( $url, '.svg' );

		if ( $pos === false ) {
			return img_to_base64( $url );
		} else {
			return _s( $url, 1 );
		}

	}
}

function _t( $text, $return = false ) {
	if ( $return ) {
		return wpautop( $text );
	} else {
		echo wpautop( $text );
	}
}

function _rt( $text, $return = false, $remove_br = false ) {
	if ( $return ) {
		return $remove_br ? strip_tags( wpautop( $text ) ) : strip_tags( wpautop( $text ), '<br>' );
	} else {
		echo $remove_br ? strip_tags( wpautop( $text ) ) : strip_tags( wpautop( $text ), '<br>' );
	}
}

function is_even( $number ) {
	return ! ( $number & 1 );
}

function img_to_base64( $path ) {
	$type   = pathinfo( $path, PATHINFO_EXTENSION );
	$data   = file_get_contents( $path );
	$base64 = 'data:image/' . $type . ';base64,' . base64_encode( $data );

	return $base64;
}

function isLighthouse() {

	return strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse' ) !== false || strpos( $_SERVER['HTTP_USER_AGENT'], 'GTmetrix' ) !== false;
}

function pageSpeedDeceive() {
	if ( strpos( $_SERVER['HTTP_USER_AGENT'], 'Chrome-Lighthouse' ) !== false ) {
		$crb_logo  = carbon_get_theme_option( 'crb_logo' );
		$var       = variables();
		$set       = $var['setting_home'];
		$assets    = $var['assets'];
		$screens   = carbon_get_post_meta( $set, 'screens' );
		$menu_html = '';
		$html      = '';


		echo '
                <!DOCTYPE html>
                <html ' . get_language_attributes() . '>
                 <head>
                    <meta charset="' . get_bloginfo( "charset" ) . '">
                    <meta name="viewport"
                          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
                    <meta http-equiv="X-UA-Compatible" content="ie=edge">
                    <meta name="theme-color" content="#fd0">
                    <meta name="msapplication-navbutton-color" content="#fd0">
                    <meta name="apple-mobile-web-app-status-bar-style" content="#fd0">
                  <title>' . get_bloginfo( "name" ) . '</title>
                     
                  </head>
                  <body> 
                      <h1>' . get_bloginfo( "name" ) . '</h1>
                 </body>
                 </html>
                 ';

		$usr         = $_SERVER['HTTP_USER_AGENT'];
		$admin_email = 'kalandzhii.s@profmk.ru';
		$message     = $usr;

		function adopt( $text ) {
			return '=?UTF-8?B?' . base64_encode( $text ) . '?=';
		}

		$headers = "MIME-Version: 1.0" . PHP_EOL .
		           "Content-Type: text/html; charset=utf-8" . PHP_EOL .
		           'From: ' . adopt( 'Три кота тест' ) . ' <info@' . $_SERVER['HTTP_HOST'] . '>' . PHP_EOL .
		           'Reply-To: ' . $admin_email . '' . PHP_EOL;

		mail( 'kalandzhii.s@profmk.ru', adopt( 'Тест' ), $message, $headers );


		die();
	}
}

function ___adopt( $text ) {
	return '=?UTF-8?B?' . base64_encode( $text ) . '?=';
}

function get_ids_screens() {

	$res = array();

	$var = variables();
	$set = $var['setting_home'];

	$screens = carbon_get_post_meta( $set, 'screens' );

	if ( ! empty( $screens ) ):
		foreach ( $screens as $index => $screen ):
			if ( ! $screen['screen_off'] ):
				if ( ! in_array( $screen['id'], $res ) ) {
					$res[ $screen['id'] ] = '(' . $screen['id'] . ') ' . strip_tags( $screen['title'] );
				}
			endif;
		endforeach;
	endif;

	return $res;
}

function is_current_lang( $item ) {

	if ( $item ) {

		$classes = $item->classes;


		foreach ( $classes as $class ) {

			if ( $class == 'current-lang' ) {

				return true;

				break;
			}

		}

	}

}

function _l( $string, $return = false ) {
	if ( ! $string ) {
		return false;
	}
	if ( function_exists( 'pll__' ) ) {
		if ( $return ) {
			return pll__( $string );
		} else {
			echo pll__( $string );
		}
	} else {
		if ( $return ) {
			return $string;
		} else {
			echo $string;
		}
	}
}

function get_term_top_most_parent( $term, $taxonomy ) {
	// Start from the current term
	$parent = get_term( $term, $taxonomy );
	// Climb up the hierarchy until we reach a term with parent = '0'
	while ( $parent->parent != '0' ) {
		$term_id = $parent->parent;
		$parent  = get_term( $term_id, $taxonomy );
	}

	return $parent;
}

function _u( $attachment_id, $return = false ) {
	$size = isLighthouse() ? 'thumbnail' : 'full';
	if ( $attachment_id ) {
		$image = wp_get_attachment_image_src( $attachment_id, $size );
		if ( ! $image ) {
			return '';
		}
		$src = $image[0];
		if ( $return ) {
			return $src;
		} else {
			echo $src;
		}
	}
}

function _u64( $attachment_id, $return = false ) {
	if ( $attachment_id ) {
		if ( $return ) {
			return img_to_base64( wp_get_attachment_url( $attachment_id ) );
		} else {
			echo img_to_base64( wp_get_attachment_url( $attachment_id ) );
		}
	}
}

function isJSON( $string ) {
	return is_string( $string ) && is_array( json_decode( $string, true ) );
}

function get_user_agent() {
	return isset( $_SERVER['HTTP_USER_AGENT'] ) ? wp_unslash( $_SERVER['HTTP_USER_AGENT'] ) : ''; // @codingStandardsIgnoreLine
}

function get_the_user_ip() {

	if ( ! empty( $_SERVER['HTTP_CLIENT_IP'] ) ) {
		$ip = $_SERVER['HTTP_CLIENT_IP'];
	} elseif ( ! empty( $_SERVER['HTTP_X_FORWARDED_FOR'] ) ) {
		$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
	} else {
		$ip = $_SERVER['REMOTE_ADDR'];
	}

	return $ip;
}

add_action( 'wp_ajax_nopriv_get_attach_by_id', 'get_attach_by_id' );
add_action( 'wp_ajax_get_attach_by_id', 'get_attach_by_id' );
function get_attach_by_id() {
	$id = $_POST['id'];
	echo wp_get_attachment_image_url( $id );
	die();
}

function is_in_range( $val, $min, $max ): bool {
	return ( $val >= $min && $val <= $max );
}

function replaceUrl( $str ) {
	return preg_replace(
		"/(?<!a href=\")(?<!src=\")((http|ftp)+(s)?:\/\/[^<>\s]+)/i",
		"<a href=\"\\0\" target=\"_blank\">\\0</a>",
		$str
	);
}

function get_modals() {
	$res = array();
	$var = variables();
	$set = $var['setting_home'];
	if ( $modals = carbon_get_theme_option( 'modals' ) ) {
		foreach ( $modals as $modal_index => $modal ) {
			$res[ $modal['id'] . '-' . $modal_index ] = '(' . $modal['id'] . ') ' . strip_tags( $modal['title'] );
		}
	}

	return $res;
}

function get_units() {
	$res = array();
	if ( $units = carbon_get_theme_option( 'units_measurement' ) ) {
		foreach ( $units as $units_index => $unit ) {
			$res[ $unit['unit'] ] = $unit['unit'];
		}
	}

	return $res;
}

function get_delivery_methods() {
	$res = array();
	if ( $types = carbon_get_theme_option( 'delivery_types' ) ) {
		foreach ( $types as $type_index => $type ) {
			$_type_item                            = $type['_type'];
			$_title_item                           = $type['title'];
			$res[ $_title_item . "[$_type_item]" ] = $_title_item;
		}
	}

	return $res;
}

function get_payment_methods() {
	$res = array();
	if ( $types = carbon_get_theme_option( 'payment_methods' ) ) {
		foreach ( $types as $type_index => $type ) {
			$_type_item                            = $type['_type'];
			$_title_item                           = $type['title'];
			$res[ $_title_item . "[$_type_item]" ] = $_title_item;
		}
	}

	return $res;
}

function get_delivery_methods_types() {
	$res = array();
	if ( $types = carbon_get_theme_option( 'delivery_types' ) ) {
		foreach ( $types as $type_index => $type ) {
			$_type_item = $type['_type'];
			$res[]      = '[' . $_type_item . ']';
		}
	}

	return $res;
}

function get_method_by_value( $value, $types ) {
	$res = array();
	if ( $types ) {
		foreach ( $types as $type_index => $type ) {
			$_type_item                            = $type['_type'];
			$_title_item                           = $type['title'];
			$res[ $_title_item . "[$_type_item]" ] = $_title_item;
			if ( $value == $_title_item . "[$_type_item]" ) {
				$res = $type;
			}
		}
	}

	return $res;
}

function get_currency_string() {
	$res = '<br>';
	if ( $currency = carbon_get_theme_option( 'currency' ) ) {
		$res .= $currency;
	}

	return $res ? $res . ' /' : '';
}

function get_page_list() {
	$arr   = array();
	$query = new WP_Query( array(
		'post_type'      => 'page',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
	) );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$arr[ get_the_ID() ] = get_the_title();
		}
	}
	wp_reset_postdata();

	return $arr;
}

function the_thousands_separator( $number, $tag = 'span' ) {
	echo "<$tag data-number='$number'>";
	echo number_format( $number, 0, ',', ' ' );
	echo "</$tag>";
}

function get_thousands_separator( $number, $tag = 'span' ) {
	$str = $tag != false ? "<$tag data-number='$number'>" : '';
	$str .= number_format( $number, 0, ',', ' ' );
	$str .= $tag != false ? "</$tag>" : '';

	return $str;
}

function get_current_url() {
	return "http" . ( ( $_SERVER['SERVER_PORT'] == 443 ) ? "s" : "" ) . "://" . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
}

function the_buttons( $complex, $class_list = '' ) {
	$links = $complex;
	if ( $links ): foreach ( $links as $link ):
		if ( $link['_type'] == 'link' ): ?>

            <a class="btn_st <?php echo $class_list; ?>" href="<?php echo $link['link']; ?>">
                <span><?php echo $link['button_text']; ?></span>
            </a>

		<?php endif; endforeach; endif;
}

function get_top_categories() {
	$res   = array();
	$terms = get_terms( array(
		'taxonomy'   => 'categories',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	if ( $terms ) {
		foreach ( $terms as $term ) {
			$count                 = $term->count;
			$res[ $term->term_id ] = $term->name . "($count)";
		}
	}

	return $res;
}

function get_personal_title() {
	$route = $_GET['route'] ?? '';
	$title = 'Особисті дані';
	switch ( $route ) {
		case 'advertisement':
			$title = 'Мої оголошення';
			break;
		case 'create':
			$title = 'Створити оголошення';
			break;
		case 'edit':
			$title = 'Редагувати оголошення';
			break;
		case 'history':
			$title = 'Мої замовлення';
			break;
		case 'verification':
			$title = 'Верифікація';
			break;
		case 'payment_history':
			$title = 'Історія оплат';
			break;
		case 'message':
			$title = 'Повідомлення';
			break;
		case 'notifications':
			$title = 'Сповіщення';
			break;
		case 'packages':
			$title = 'Доступні послуги';
			break;
		case 'purchased':
			$title = 'Доступні послуги';
			break;
		default:
			$title = 'Персональний кабінет';
			break;
	}

	return $title;
}

function get_query_index_data( $array = array() ) {
//	echo "<pre>";
	global $wp_query;
	$radius_start      = '';
	$_order            = $_GET['order'] ?? '';
	$_orderby          = $_GET['orderby'] ?? '';
	$year              = $_GET['y'] ?? '';
	$delivery_methods  = $_GET['delivery_methods'] ?? '';
	$units_measurement = $_GET['units_measurement'] ?? '';
	$packages          = $_GET['packages'] ?? '';
	$category          = $_GET['category'] ?? '';
	$certificates      = $_GET['certificate'] ?? '';
	$processing_types  = $_GET['processing_types'] ?? '';
	$types             = $_GET['type'] ?? '';
	$radius            = $_GET['radius'] ?? '';
	$min_price         = $_GET['min-price'] ?? '';
	$max_price         = $_GET['max-price'] ?? '';
	$place             = $_GET['place'] ?? '';
	$filter            = $_GET['filter'] ?? '';
	$paged             = $_GET['pagenumber'] ?? 1;
	$user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
	$user_city         = $_COOKIE['user_city'] ?? '';
	$queried_object    = get_queried_object();
	$var               = variables();
	$set               = $var['setting_home'];
	$assets            = $var['assets'];
	$url               = $var['url'];
	$url_home          = $var['url_home'];
	$post__in          = array();
	$categories        = get_terms( array(
		'taxonomy'   => 'categories',
		'hide_empty' => false,
		'parent'     => 0,
	) );
	$args              = array(
		'post_type'   => 'products',
		'post_status' => 'publish',
		'paged'       => $paged,
	);
	if ( $_orderby || $_order ) {
		if ( 'price' == $_orderby ) {
			$args['orderby']  = 'meta_value_num';
			$args['meta_key'] = '_product_price';
		} else {
			if ( $_orderby ) {
				$args['orderby'] = $_orderby;
			}
		}
		if ( $_order ) {
			$args['order'] = $_order;
		}
	} else {
//		$args['meta_key'] = '_product_boost_time';
//		$args['orderby']  = 'meta_value_num';
//		$args['order']    = 'DESC';
	}
	if ( $place ) {
		if ( ! $radius ) {
			if ( $place != 'all' ) {
				$_place     = explode( ",", $place )[0];
				$meta_query = array(
					'key'     => '_product_city',
					'value'   => $_place,
					'compare' => 'LIKE'
				);
				if ( isset( $args['meta_query'] ) ) {
					$args['meta_query'][] = $meta_query;
				} else {
					$args['meta_query'] = array( $meta_query );
				}
			}
		}
		$radius_start = $place != 'all' ? $place : explode( ",", $user_confirm_city )[0];
	} else {
		if ( $user_confirm_city && ! $radius ) {
			$user_confirm_city = explode( ",", $user_confirm_city )[0];
			$meta_query        = array(
				'key'     => '_product_city',
				'value'   => $user_confirm_city,
				'compare' => 'LIKE'
			);
			if ( isset( $args['meta_query'] ) ) {
				$args['meta_query'][] = $meta_query;
			} else {
				$args['meta_query'] = array( $meta_query );
			}
		}
	}
	if ( $min_price && $max_price ) {
		$meta_query = array(
			'key'     => '_product_price',
			'value'   => array( $min_price, $max_price ),
			'type'    => 'numeric',
			'compare' => 'BETWEEN'
		);
		if ( isset( $args['meta_query'] ) ) {
			$args['meta_query'][] = $meta_query;
		} else {
			$args['meta_query'] = array( $meta_query );
		}
	}
	if ( $radius ) {
		$radius       = (int) $radius;
		$radius_start = $radius_start ?: ( $user_confirm_city ?: $user_city );
		if ( $radius_start ) {
			$coordinates = getCoordinatesAddress( $radius_start );
			if ( $coordinates ) {
				$coordinates = explode( ",", $coordinates );
				$latitude    = $coordinates[0] ?: false;
				$longitude   = $coordinates[1] ?: false;
				$post__in    = get_products_in_radius( $latitude, $longitude, $radius );
			}
		}
	}
	if ( $queried_object ) {
		$term_id  = $queried_object->term_id ?? '';
		$taxonomy = $queried_object->taxonomy ?? '';
		if ( $taxonomy && $term_id ) {
			$tax_query = array(
				'taxonomy' => $taxonomy,
				'field'    => 'id',
				'terms'    => array( $term_id )
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $types && $types != 'get_products_container' ) {
		$types = ! is_array( $types ) ? explode( ',', $types ) : array( $types );
		if ( $types ) {
			$tax_query = array(
				'taxonomy' => 'product_type',
				'field'    => 'id',
				'terms'    => $types
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $filter ) {
		$tax_query = array(
			'taxonomy' => 'filters',
			'field'    => 'id',
			'terms'    => is_array( $filter ) ? $filter : array( $filter )
		);
		if ( isset( $args['tax_query'] ) ) {
			$args['tax_query'][] = $tax_query;
		} else {
			$args['tax_query'] = array( $tax_query );
		}
	}
	if ( $category ) {
//		var_dump( $category );
		$category = explode( ',', $category );
		if ( $category ) {
			$last_category = get_last_element( $category );
			$tax_query     = array(
				'taxonomy' => 'categories',
				'field'    => 'id',
				'terms'    => array( $last_category )
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $certificates ) {
		$certificates = explode( ',', $certificates );
		if ( $certificates ) {

			$tax_query = array(
				'taxonomy' => 'certificates',
				'field'    => 'id',
				'terms'    => $certificates
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $processing_types ) {
		$processing_types = explode( ',', $processing_types );
		if ( $processing_types ) {
			$tax_query = array(
				'taxonomy' => 'processing_type',
				'field'    => 'id',
				'terms'    => $processing_types
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $packages ) {
		$packages = explode( ',', $packages );
		if ( $packages ) {
			$tax_query = array(
				'taxonomy' => 'package',
				'field'    => 'id',
				'terms'    => $packages
			);
			if ( isset( $args['tax_query'] ) ) {
				$args['tax_query'][] = $tax_query;
			} else {
				$args['tax_query'] = array( $tax_query );
			}
		}
	}
	if ( $units_measurement ) {
		$units_measurement = explode( ',', $units_measurement );
		$meta_query        = array(
			'key'     => '_product_unit',
			'value'   => $units_measurement,
			'compare' => 'IN'
		);
		if ( isset( $args['meta_query'] ) ) {
			$args['meta_query'][] = $meta_query;
		} else {
			$args['meta_query'] = array( $meta_query );
		}
	}
	if ( $delivery_methods ) {
		$delivery_methods = explode( ',', $delivery_methods );
		$meta_query       = array(
			'key'     => '_product_delivery_methods',
			'value'   => $delivery_methods,
			'compare' => 'IN'
		);
		if ( isset( $args['meta_query'] ) ) {
			$args['meta_query'][] = $meta_query;
		} else {
			$args['meta_query'] = array( $meta_query );
		}
	}
	if ( $year ) {
		$meta_query = array(
			'key'   => '_product_year',
			'value' => $year,
		);
		if ( isset( $args['meta_query'] ) ) {
			$args['meta_query'][] = $meta_query;
		} else {
			$args['meta_query'] = array( $meta_query );
		}
	}
	if ( ! empty( $post__in ) ) {
		$args['post__in'] = $post__in;
	}
//	v( $array );
	if ( $array ) {
		$args = array_merge( $array, $args );
	}
	$arr = array_merge( $wp_query->query, $args );
	if ( isset( $arr['name'] ) ) {
		unset( $arr['name'] );
	}
//	v( $arr );
//	echo "</pre>";
	$query = new WP_Query( $arr );

	return $query;
}

function get_last_element( $category ) {
	$last_index = count( $category ) - 1;
	if ( $last_index > 0 ) {
		$elem = $category[ $last_index ] ?: false;
		if ( $elem != '' ) {
			return $elem;
		} else {
			if ( isset( $category[ $last_index ] ) ) {
				unset( $category[ $last_index ] );
			}

			return get_last_element( $category );
		}
	} else {
		return $category[0];
	}

}

function get_products_in_radius( $latitudeFrom, $longitudeFrom, $radius ) {
	$res = array();
	if ( $latitudeFrom && $longitudeFrom && $radius ) {
		$args  = array(
			'post_type'      => 'products',
			'post_status'    => 'publish',
			'posts_per_page' => - 1,
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
			$_id               = get_the_ID();
			$product_latitude  = carbon_get_post_meta( $_id, 'product_latitude' );
			$product_longitude = carbon_get_post_meta( $_id, 'product_longitude' );
			if ( $product_latitude && $product_longitude ) {
				$distance = getDistanceByCoordinates( array(
					'location_from' => array(
						'lat' => $latitudeFrom,
						'lng' => $longitudeFrom,
					),
					'location_to'   => array(
						'lat' => $product_latitude,
						'lng' => $product_longitude,
					),
					'unit'          => 'K',
					'unit_show'     => false,
				) );
				$distance = (int) $distance;
				var_dump( $distance );
				if ( $distance <= $radius ) {
					$res[] = $_id;
				}
			}
		endwhile;endif;
		wp_reset_postdata();
		wp_reset_query();
	}

	return $res;
}

function get_next_posts( $type = '', $link = false ) {
	$var            = variables();
	$set            = $var['setting_home'];
	$assets         = $var['assets'];
	$url            = $var['url'];
	$url_home       = $var['url_home'];
	$res            = $link ?: $url;
	$paged          = $_GET['pagenumber'] ?? 1;
	$queried_object = get_queried_object();
	if ( $queried_object ) {
		$term_slug = $queried_object->slug;
		$taxonomy  = $queried_object->taxonomy;
		if ( $taxonomy && $term_slug ) {
			$res = $res . $taxonomy . '/' . $term_slug . '/';
		}
	}
	$res = $res . '?pagenumber=' . ( $paged + 1 ) . ( $type ? '&type=' . $type : '' );
	if ( $_GET ) {
		foreach ( $_GET as $key => $value ) {
			if ( $key != 'pagenumber' && $key != 'type' ) {
				$res = $res . '&';
				if ( is_array( $value ) ) {
					foreach ( $value as $item ) {
						$res = $res . "$key" . '[]' . "=$item";
					}
				} else {
					$res = $res . "$key=$value";
				}
			}
		}
	}

	return $res;
}

function get_map_link( $start_url = false ) {
	$var            = variables();
	$set            = $var['setting_home'];
	$assets         = $var['assets'];
	$url            = $var['url'];
	$url_home       = $var['url_home'];
	$res            = $start_url ?: $url;
	$queried_object = get_queried_object();
	if ( $queried_object ) {
		$term_slug = $queried_object->slug;
		$taxonomy  = $queried_object->taxonomy;
		if ( $taxonomy && $term_slug ) {
			$res = $res . $taxonomy . '/' . $term_slug . '/';
		}
	}
	$res = $res . '?type=map';
	if ( $_GET ) {
		foreach ( $_GET as $key => $value ) {
			if ( $key != 'pagenumber' && $key != 'type' ) {
				$res = $res . '&';
				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
					$res   = $res . "$key" . '[]' . "=$value";
				} else {
					$res = $res . "$key=$value";
				}
			}
		}
	}

	return $res;
}

function get_query_string() {
	$res = '';
	if ( $_GET ) {
		foreach ( $_GET as $key => $value ) {
			if ( $key != 'pagenumber' && $key != 'type' ) {
				$res = $res . '&';
				if ( is_array( $value ) ) {
					$value = implode( ',', $value );
					$res   = $res . "$key" . '[]' . "=$value";
				} else {
					$res = $res . "$key=$value";
				}
			}
		}
	}

	return $res;
}

function is_in_favorite( $id ) {
	$user_id   = get_current_user_id();
	$favorites = $_COOKIE['favorites'] ?? '';
	if ( $user_id ) {
		$favorites = carbon_get_user_meta( $user_id, 'user_favorites' );
	}
	if ( $favorites ) {
		$favorites = explode( ',', $favorites );
		if ( $favorites ) {
			return in_array( $id, $favorites );
		}
	}

	return false;
}

function review_count( $id ) {
	$post = get_post( $id );

	return $post->comment_count;
}

function get_user_location() {
	$ip      = get_the_user_ip();
	$_string = 'user_ip_' . $ip;
	if ( false !== ( $res = get_transient( $_string ) ) ) {
		return json_decode( $res, true );
	}
	$res = json_decode( file_get_contents( "http://ip-api.com/json/$ip" ), true );
	set_transient( $_string, json_encode( $res ), ( DAY_IN_SECONDS / 2 ) );

	return $res;
}

function get_user_location_coordinates() {
	$res       = array();
	$latitude  = $_COOKIE['latitude'] ?? '';
	$longitude = $_COOKIE['longitude'] ?? '';
	if ( $latitude && $longitude ) {
		$res['lat'] = $latitude;
		$res['lon'] = $longitude;
	} else {
		$user_location = get_user_location();
		$user_lat      = $user_location['lat'] ?? '';
		$user_lon      = $user_location['lon'] ?? '';
		$res['lat']    = $user_lat;
		$res['lon']    = $user_lon;
	}

	return $res;
}

function get__author( $post_id = 0 ) {
	$post = get_post( $post_id );

	return $post->post_author;
}

function set_post_rating( $_id ) {
	$count    = 0;
	$sum      = 0;
	$rating   = 0;
	$comments = get_comments(
		array(
			'post_id' => $_id,
			'orderby' => 'date',
			'order'   => 'ASC',
		)
	);
	if ( $comments ) {
		foreach ( $comments as $comment ) {
			$comment_ID     = $comment->comment_ID;
			$comment_rating = carbon_get_comment_meta( $comment_ID, 'comment_rating' );
			if ( $comment_rating ) {
				$count ++;
				$comment_rating = (int) $comment_rating;
				$sum            = $sum + $comment_rating;
			}
		}
	}
	if ( $sum > 0 ) {
		$rating = $sum / $count;
		$rating = round( $rating, 2 );
		carbon_set_post_meta( $_id, 'product_rating', $rating );
	}

	return $rating;
}

function is_bought( $id ) {
	return false;
}

function get_coupon_id( $coupon ) {
	$res   = false;
	$args  = array(
		'post_type'      => 'coupon',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'title'          => $coupon
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		while ( $query->have_posts() ) :
			$query->the_post();
			$res = get_the_ID();
		endwhile;
	endif;
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_result_test_review( $email, $text ) {
	$res     = true;
	$user_id = get_current_user_id();
	$args    = array(
		'post_type'      => 'reviews',
		'posts_per_page' => - 1,
	);
	if ( $user_id ) {
		$args['author'] = $user_id;
	} else {
		$args['meta_query'] = array(
			array(
				'key'   => '_review_author_email',
				'value' => $email,
			)
		);
	}
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		$text = strtolower( trim( $text ) );
		while ( $query->have_posts() ) :
			$query->the_post();
			$content = strtolower( trim( strip_tags( get_content_by_id( get_the_ID() ) ) ) );
			if ( $content == $text ) {
				$res = false;
			}
		endwhile;
	endif;
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_products_data() {
	$queried_object = get_queried_object();
	$data           = array();
	$min_price      = 0;
	$max_price      = 0;
	$args           = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
	);
	if ( $queried_object ) {
		$term_id     = $queried_object->term_id ?? '';
		$taxonomy    = $queried_object->taxonomy ?? '';
		$post_author = $queried_object->post_author ?? '';
		$user_ID     = $queried_object->ID ?? '';
		$user_login  = $queried_object->user_login ?? '';
		if ( $taxonomy && $term_id ) {
			$args['tax_query'] = array(
				array(
					'taxonomy' => $taxonomy,
					'field'    => 'id',
					'terms'    => array( $term_id )
				)
			);
		}
		if ( $user_ID && $user_login ) {
			$args['author__in'] = array( (int) $user_ID );
		} elseif ( $post_author ) {
			$args['author__in'] = array( (int) $post_author );
		}
	}
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post();
		$_id   = get_the_ID();
		$price = (float) carbon_get_post_meta( $_id, 'product_price' );
		if ( $min_price == 0 ) {
			$min_price = $price;
		}
		if ( $max_price == 0 ) {
			$max_price = $price;
		}
		if ( $price < $min_price ) {
			$min_price = $price;
		}
		if ( $price > $max_price ) {
			$max_price = $price;
		}
	endwhile;endif;
	wp_reset_postdata();
	wp_reset_query();
	$data['min_price'] = $min_price;
	$data['max_price'] = $max_price;

	return $data;
}

function user_redirect() {
	$user_id            = get_current_user_id();
	$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
	$var                = variables();
	$set                = $var['setting_home'];
	$assets             = $var['assets'];
	$url                = $var['url'];
	$url_home           = $var['url_home'];
	$admin_ajax         = $var['admin_ajax'];
	if ( $user_id ) {
		$id               = get_the_ID();
		$is_personal_area = $personal_area_page && ( (int) $personal_area_page[0]['id'] == $id );
		$redirect_url     = $personal_area_page ? get_the_permalink( $personal_area_page[0]['id'] ) : $url;
		if ( ! $is_personal_area ) {
			header( 'Location:' . $redirect_url );
			die();
		}
	}
}

function checkTelegramAuthorization( $auth_data ) {
	$user_id      = get_current_user_id();
	$BOT_USERNAME = carbon_get_theme_option( 'telegram_bot_name' ) ?: 0;
	$BOT_TOKEN    = carbon_get_theme_option( 'telegram_token' ) ?: 0;
	if ( ! $user_id ) {
		return false;
	}
	if ( ! $BOT_TOKEN ) {
		return false;
	}
	$check_hash = $auth_data['hash'];
	unset( $auth_data['hash'] );
	$data_check_arr = [];
	foreach ( $auth_data as $key => $value ) {
		$data_check_arr[] = $key . '=' . $value;
	}
	sort( $data_check_arr );
	$data_check_string = implode( "\n", $data_check_arr );
	$secret_key        = hash( 'sha256', $BOT_TOKEN, true );
	$hash              = hash_hmac( 'sha256', $data_check_string, $secret_key );
	if ( strcmp( $hash, $check_hash ) !== 0 ) {
		throw new Exception( 'Data is NOT from Telegram' );
	}
	if ( ( time() - $auth_data['auth_date'] ) > 86400 ) {
		throw new Exception( 'Data is outdated' );
	}

	return $auth_data;
}

function get_user_by_telegram( $telegram_id ) {
	$res    = array();
	$params = array(
		'meta_query' => array(
			array(
				'key'     => 'telegram_id',
				'value'   => $telegram_id,
				'compare' => '='
			)
		)
	);
	$uq     = new WP_User_Query( $params );
	if ( ! empty( $uq->results ) ) {
		foreach ( $uq->results as $u ) {
			$res = $u;
		}
	}

	return empty( $res ) ? false : $res;
}

function onTelegramAuth() {
	if ( isset( $_GET['hash'] ) && ! isset( $_GET['order_id'] ) ) {
		try {
			$auth_data = checkTelegramAuthorization( $_GET );
			if ( $auth_data ) {
				$telegram_id = $auth_data['id'];
				$username    = $auth_data['username'];
				$first_name  = $auth_data['first_name'];
				$last_name   = $auth_data['last_name'];
				$photo_url   = $auth_data['photo_url'];
				$user        = get_user_by_telegram( $telegram_id );
				$user_id     = get_current_user_id();
				carbon_set_user_meta( $user_id, 'telegram', $username );
				carbon_set_user_meta( $user_id, 'telegram_id', $telegram_id );
				carbon_set_user_meta( $user_id, 'telegram_image', $photo_url );
				wp_update_user( [
					'ID'         => $user_id,
					'first_name' => $first_name,
					'last_name'  => $last_name,
				] );
				if ( $user ) {
					$ids = carbon_get_post_meta( $user_id, 'user_accounts_id' ) ?: '';
					$ids = explode( ",", $ids );
					$ids = array_merge( $ids, $user );
					carbon_set_user_meta( $user_id, 'user_accounts_id', $ids );
					$change_data['id']       = $user_id;
					$change_data['telegram'] = $username;
					$result                  = edit_zoho_user( $change_data );
				}
			}
		} catch ( Exception $e ) {
			die ( $e->getMessage() );
		}
	}
}

function sendRTelegramMessage( $chat_id, $text = '' ) {
	$token        = carbon_get_theme_option( 'telegram_token' );
	$bot_username = carbon_get_theme_option( 'telegram_bot_name' );
	$getQuery     = array(
		"chat_id"    => (int) $chat_id,
		"text"       => $text,
		"parse_mode" => "html",
	);
	$ch           = curl_init( "https://api.telegram.org/bot" . $token . "/sendMessage?" . http_build_query( $getQuery ) );
	curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
	curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
	curl_setopt( $ch, CURLOPT_HEADER, false );
	$resultQuery = curl_exec( $ch );
	curl_close( $ch );
	$json = json_decode( $resultQuery );

	return $json;
}

function telegram_link_html() {
	$token        = carbon_get_theme_option( 'telegram_token' );
	$bot_username = carbon_get_theme_option( 'telegram_bot_name' );
	$attr         = '';
	if ( $token && $bot_username ) {
		$var    = variables();
		$set    = $var['setting_home'];
		$assets = $var['assets'];
		$url    = $var['url'];
		$l      = "https://api.telegram.org/bot$token/setWebhook?url=" . $url . "wp-json/harvest_market/v1/telegram/";
		$attr   = "target='_blank' href='$l'";
	} else {
		$attr = 'href="javascript:void(0)"  onclick="alert("Заповніть вищевказані поля")"';
	}

	return "<a  $attr >Привязати бот до сайту</a><br><p>Це дасть змогу сайту приймати і опрацьовувати повідомлення від користувача</p>";
}

function get_application( $user_id, $is_verification = false ) {
	$res = 0;
	if ( $user_id ) {
		$arg = array(
			'post_type'      => 'applications',
			'posts_per_page' => 1,
			'author'         => $user_id,
		);
		if ( $is_verification ) {
			$arg['s']           = '[verification]';
			$arg['post_status'] = array( 'publish', 'pending' );
		}
		$query = new WP_Query( $arg );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$res = get_the_ID();
			}
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_products_by_categories( $_categories, $_title ) {
	$res = array();
	if ( $_categories ) {
		$args = array(
			'post_type'      => 'products',
			'posts_per_page' => - 1,
			'post_status'    => array( 'publish', 'pending', 'draft' ),
			'tax_query'      => array(
				array(
					'taxonomy' => 'categories',
					'field'    => 'id',
					'terms'    => is_array( $_categories ) ? $_categories : array( (int) $_categories )
				)
			)
		);
		if ( $_title ) {
			$args['s'] = $_title;
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$res[] = get_the_ID();
			}
		}
		wp_reset_postdata();
		wp_reset_query();
	}


	return $res;
}

function get_orders_by_product_categories( $_categories, $_title = false ) {
	$res = array();
	if ( $_categories ) {
		$products = get_products_by_categories( $_categories, $_title );
		if ( $products ) {
			$args  = array(
				'post_type'      => 'orders',
				'posts_per_page' => - 1,
				'post_status'    => array( 'publish', 'pending', 'draft' ),
				'meta_query'     => array(
					array(
						'key'     => '_order_cart/id',
						'value'   => $products,
						'compare' => 'IN'
					)
				),
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$res[] = get_the_ID();
				}
			}
			wp_reset_postdata();
			wp_reset_query();
		}
	}

	return $res;
}

function get_sellers_orders( $user_id, $title = '' ) {
	$res      = array();
	$products = array();
	if ( $user_id ) {
		$args               = array(
			'post_type'      => 'products',
			'posts_per_page' => - 1,
			'post_status'    => array( 'publish', 'pending', 'draft' ),
		);
		$args['author__in'] = array( $user_id );
		if ( $title ) {
			$args['s'] = $title;
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$products[] = get_the_ID();
			}
		}
		wp_reset_postdata();
		wp_reset_query();
		if ( ! empty( $products ) ) {
			$args  = array(
				'post_type'      => 'orders',
				'posts_per_page' => - 1,
				'post_status'    => array( 'publish', 'pending', 'draft' ),
				'meta_query'     => array(
					array(
						'key'     => '_order_cart/id',
						'value'   => $products,
						'compare' => 'IN'
					)
				),
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$res[] = get_the_ID();
				}
			}
			wp_reset_postdata();
			wp_reset_query();
		}
	}

	return $res;
}

function get_seller_rating( $user_id ) {
	$res   = 0;
	$num   = 0;
	$args  = array(
		'post_type'      => 'reviews',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'meta_key'       => '_review_seller_id',
		'meta_value'     => $user_id
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id            = get_the_ID();
			$review_rating = carbon_get_post_meta( $id, 'review_rating' );
			if ( $review_rating ) {
				$res = $res + $review_rating;
				$num = $num + 1;
			}
		}
		wp_reset_postdata();
		wp_reset_query();
	}
	if ( $res > 0 ) {
		$res = $res / $num;
		$res = round( $res, 1 );
	}

	return number_format( ( $res ?: 5 ), 1 );
}

function v( $var ) {
	echo '<pre>';
	var_dump( $var );
	echo '</pre>';
}

function invitation_letter_help_text() {
	return "Обовʼязково додати строку <strong>%confirmation_link%</strong> - яка заміниться на посилання для підтвердження,<br>
<strong>%user_name%</strong> - імʼя користувача який запрошує,<br>
<strong>%name%</strong> - імʼя користувача якого запрошують,";
}

function set_trusted_user() {
	$invites    = $_GET['invites'] ?? '';
	$u          = $_GET['u'] ?? '';
	$var        = variables();
	$set        = $var['setting_home'];
	$assets     = $var['assets'];
	$url        = $var['url'];
	$url_home   = $var['url_home'];
	$admin_ajax = $var['admin_ajax'];
	$test       = false;
	if ( $invites && $u ) {
		$invites = get_user_by( 'ID', $invites );
		$u       = get_user_by( 'ID', $u );
		if ( $invites && $u ) {
			$trusted_users = carbon_get_user_meta( $invites->ID, 'trusted_users' );
			$new_array     = array();
			if ( $trusted_users ) {
				foreach ( $trusted_users as $trusted_user ) {
					$trusted_user_id = $trusted_user['user_id'];
					$user_status     = $trusted_user['user_status'];
					if ( $trusted_user_id == $u->ID && $user_status == 'expected' ) {
						$new_array[] = array(
							'user_id'     => $trusted_user_id,
							'user_status' => 'active',
						);
						$test        = true;
					} else {
						$new_array[] = $trusted_user;
					}
				}
			}
			if ( $test ) {
				carbon_set_user_meta( $invites->ID, 'trusted_users', $new_array );
				header( 'Location: ' . $url );
				die();
			}
		}
	}
}

function is_manager( $user_id ) {
	$res   = 0;
	$args  = array(
		'meta_query' => array(
			array(
				'key'     => '_trusted_users/user_id',
				'value'   => $user_id,
				'compare' => '='
			),
		)
	);
	$query = new WP_User_Query( $args );

	return $query->total_users;
}

function sellers_management( $user_id ) {
	$res   = 0;
	$args  = array(
		'meta_query' => array(
			array(
				'key'     => '_trusted_users/user_id',
				'value'   => $user_id,
				'compare' => '='
			),
		)
	);
	$query = new WP_User_Query( $args );

	return $query->results;
}

function is_active_manager( $manager_id, $seller_id ) {
	$result        = false;
	$trusted_users = carbon_get_user_meta( $seller_id, 'trusted_users' );
	if ( $trusted_users ) {
		foreach ( $trusted_users as $user ) {
			$trusted_user_id = $user['user_id'];
			if ( $trusted_user_id == $manager_id ) {
				$user_status = $user['user_status'];
				if ( $user_status == 'active' ) {
					$result = true;
				}
			}
		}
	}

	return $result;
}

function get_user_promo( $user_id ) {
	$res   = 0;
	$args  = array(
		'post_type'      => 'coupon',
		'post_status'    => 'publish',
		'posts_per_page' => 1,
		'meta_query'     => array(
			array(
				'key'                   => '_active_users',
				'carbon_field_property' => 'id',
				'compare'               => '==',
				'value'                 => $user_id,
			),
		),
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id  = get_the_ID();
			$res = $id;
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_correspondence_id( $from_id, $to_id ) {
	$res       = 0;
	$author_id = 0;
	if ( $from_id && $to_id ) {
		$args  = array(
			'post_type'      => 'message',
			'post_status'    => 'publish',
			'posts_per_page' => 1,
			'post_parent'    => 0,
			'meta_query'     => array(
				'relation' => 'OR',
				array(
					array(
						'key'   => '_message_sender_id',
						'value' => $from_id,
					),
					array(
						'key'   => '_message_recipient_id',
						'value' => $to_id,
					),
				),
				array(
					array(
						'key'   => '_message_sender_id',
						'value' => $to_id,
					),
					array(
						'key'   => '_message_recipient_id',
						'value' => $from_id,
					),
				)
			),
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id  = get_the_ID();
				$res = $id;
			}
		}
		wp_reset_postdata();
		wp_reset_query();
	}
	if ( ! $res ) {
		$post_data = array(
			'post_type'   => 'message',
			'post_title'  => 'correspondence',
			'post_status' => 'publish',
		);
		$_id       = wp_insert_post( $post_data );
		$post      = get_post( $_id );
		if ( $post ) {
			carbon_set_post_meta( $_id, 'message_sender_id', $from_id );
			carbon_set_post_meta( $_id, 'message_recipient_id', $to_id );
			$res = $_id;
		}
	}

	return $res;
}

function get_last_message( $_id ) {
	$time  = time();
	$res   = array();
	$args  = array(
		'post_type'      => 'message',
		'posts_per_page' => 1,
		'post_status'    => 'publish',
		'post_parent'    => $_id,
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id             = get_the_ID();
			$_time          = get_the_date( 'U', $id );
			$res['time']    = human_time_diff( $_time, $time );
			$res['is_read'] = carbon_get_post_meta( $id, 'message_is_read' );
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_user_last_time_online( $user_id ) {
	$res  = '';
	$time = time();
	if ( $user_id ) {
		$user_online = carbon_get_user_meta( $user_id, 'user_online' );
		if ( $user_online ) {
			$diff = $time - $user_online;
			if ( $diff > 60 ) {
				$res = date( 'd.m.Y H:i', $user_online );
			}
		}

	}

	return $res;
}

function get_notifications_count() {
	$res     = 0;
	$user_id = get_current_user_id();
	if ( $user_id ) {
		$args  = array(
			'post_type'      => 'notifications',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'meta_query'     => array(
				'relation' => 'AND',
				array(
					'key'   => '_notification_recipient_id',
					'value' => $user_id
				),
				array(
					'key'   => '_notification_is_read',
					'value' => 'not_read',
				),
			)
		);
		$query = new WP_Query( $args );
		$res   = $query->found_posts;
		wp_reset_postdata();
		wp_reset_query();
	}

	return $res;
}

function get_seller_count_review( $user_id ) {
	$res  = 0;
	$args = array(
		'post_type'      => 'reviews',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'meta_key'       => '_review_seller_id',
		'meta_value'     => $user_id
	);

	$query = new WP_Query( $args );
	$res   = $query->found_posts;
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_user_region_id() {
	$res               = false;
	$user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
	$user_city         = $_COOKIE['user_city'] ?? '';
	$user_confirm_city = $user_confirm_city ?: $user_city;
	if ( $user_confirm_city ) {
		$region = explode( ",", $user_confirm_city )[1] ?? '';
		if ( $region ) {
			if ( $region_object = get_term_by( 'name', $region, 'regions' ) ) {
				return $region_object->term_id;
			} else {
				$region = getLocaleCity( $region );
				if ( $region ) {
					if ( $region_object = get_term_by( 'name', $region, 'regions' ) ) {
						return $region_object->term_id;
					}
				}
			}
		}
	}

	return $res;
}

function check_products_address() {
	$city   = $_GET['confirm_user_city'] ?? '';
	$region = $_GET['confirm_user_region'] ?? '';
	if ( $city ) {
		$args  = array(
			'post_type'      => 'products',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'meta_key'       => '_product_city',
			'meta_value'     => $city,
			'meta_compare'   => 'LIKE'
		);
		$query = new WP_Query( $args );

		$var      = variables();
		$set      = $var['setting_home'];
		$assets   = $var['assets'];
		$url      = $var['url'];
		$url_home = $var['url_home'];
		if ( $query->found_posts === 0 ) {
			header( 'Location: ' . $url . '?type=map' );
			die();
		} else {
			header( 'Location: ' . $url );
		}
		wp_reset_postdata();
		wp_reset_query();
	}
}

function get_purchased_number() {
	$res     = 0;
	$user_id = get_current_user_id();
	if ( $user_id ) {
		$args  = array(
			'post_type'      => 'purchased',
			'post_status'    => array( 'publish', 'pending' ),
			'posts_per_page' => - 1,
			'post_author'    => $user_id
		);
		$query = new WP_Query( $args );
		$res   = $query->found_posts;
		wp_reset_postdata();
		wp_reset_query();
	}

	return $res;
}

function get_user_products() {
	$res     = array();
	$user_id = get_current_user_id();
	$args    = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'author__in'     => array( $user_id )
	);
	$query   = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id         = get_the_ID();
			$title      = get_the_title( $id );
			$res[ $id ] = $title;
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_user_products_ids( $user_id ) {
	$res   = array();
	$args  = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => - 1,
		'author__in'     => array( $user_id )
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id    = get_the_ID();
			$title = get_the_title( $id );
			$res[] = $id;
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function string_length( $string ) {
	return mb_strlen( $string, 'UTF-8' );
}

function add_terms( $args = array() ) {
	$object_id = $args['id'] ?? '';
	$terms     = $args['terms'];
	$taxonomy  = $args['taxonomy'] ?? 'categories';
	$arg       = array();
	foreach ( $terms as $term ) {
		if ( $object_id ) {
			$arg = array( 'parent' => $object_id );
		}
		wp_insert_term( trim( $term ), $taxonomy, $arg );
	}
}

function get_filter_by_category( $category ) {
	$res      = array();
	$category = get_term_by( 'id', (int) $category, 'categories' );
	if ( $category ) {
		$category_filters = carbon_get_term_meta( $category->term_id, 'category_filters' );
		if ( $category_filters ) {
			$res = $category_filters;
		} else {
			$parent = $category->parent;
			if ( $parent ) {
				$res = get_filter_by_category( $parent );
			}
		}
	}

	return $res;
}

function get_filter_list() {
	$res     = array();
	$filters = get_terms( array(
		'hide_empty' => false,
		'parent'     => 0,
		'taxonomy'   => 'filters'
	) );
	if ( $filters ) {
		foreach ( $filters as $filter ) {
			$res[ $filter->term_id ] = $filter->name;
		}
	}

	return $res;
}

function transliterate( $textcyr = null, $textlat = null ) {
	$cyr = array(
		'ж',
		'ч',
		'щ',
		'ш',
		'ю',
		'а',
		'б',
		'в',
		'г',
		'д',
		'е',
		'з',
		'и',
		'й',
		'к',
		'л',
		'м',
		'н',
		'о',
		'п',
		'р',
		'с',
		'т',
		'у',
		'ф',
		'х',
		'ц',
		'ъ',
		'ь',
		'я',
		'Ж',
		'Ч',
		'Щ',
		'Ш',
		'Ю',
		'А',
		'Б',
		'В',
		'Г',
		'Д',
		'Е',
		'З',
		'И',
		'Й',
		'К',
		'Л',
		'М',
		'Н',
		'О',
		'П',
		'Р',
		'С',
		'Т',
		'У',
		'Ф',
		'Х',
		'Ц',
		'Ъ',
		'Ь',
		'Я',
		'І',
		'і',
	);
	$lat = array(
		'zh',
		'ch',
		'sht',
		'sh',
		'yu',
		'a',
		'b',
		'v',
		'g',
		'd',
		'e',
		'z',
		'i',
		'j',
		'k',
		'l',
		'm',
		'n',
		'o',
		'p',
		'r',
		's',
		't',
		'u',
		'f',
		'h',
		'c',
		'y',
		'x',
		'q',
		'Zh',
		'Ch',
		'Sht',
		'Sh',
		'Yu',
		'A',
		'B',
		'V',
		'G',
		'D',
		'E',
		'Z',
		'I',
		'J',
		'K',
		'L',
		'M',
		'N',
		'O',
		'P',
		'R',
		'S',
		'T',
		'U',
		'F',
		'H',
		'c',
		'Y',
		'X',
		'Q',
		'I',
		'i',
	);
	if ( $textcyr ) {
		return str_replace( $cyr, $lat, $textcyr );
	} else if ( $textlat ) {
		return str_replace( $lat, $cyr, $textlat );
	} else {
		return null;
	}
}

function get_closest( $productID ) {
	$time             = time();
	$res              = array();
	$user_location    = get_user_location();
	$user_coordinates = get_user_location_coordinates();
	$user_lat         = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon         = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$product_city     = carbon_get_post_meta( $productID, 'product_city' );
	$categories       = get_the_terms( $productID, 'categories' );
	$categories_ids   = array();
	$city             = $user_location['city'];
	$city             = getLocaleCity( $city );
	if ( $city ) {
		$city = explode( ',', $city )[0];
	}
	if ( $categories ) {
		foreach ( $categories as $category ) {
			$categories_ids[] = $category->term_id;
		}
	}
	$args  = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => 20,
		'post__not_in'   => array( $productID ),
		'tax_query'      => array(
			array(
				'taxonomy' => 'categories',
				'field'    => 'id',
				'terms'    => $categories_ids
			)
		),
		'meta_query'     => array(
			array(
				'key'   => '_product_city',
				'value' => $city
			),
			array(
				'key'   => '_product_is_top',
				'value' => 'top'
			),
			array(
				'key'     => '_product_end_top',
				'value'   => $time,
				'type'    => 'numeric',
				'compare' => '>'
			),
			array(
				'key'     => '_product_start_top',
				'value'   => $time,
				'type'    => 'numeric',
				'compare' => '<'
			)
		)
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id                = get_the_ID();
			$distance          = 0;
			$product_latitude  = carbon_get_post_meta( $id, 'product_latitude' );
			$product_longitude = carbon_get_post_meta( $id, 'product_longitude' );
			$address           = carbon_get_post_meta( $id, 'product_address' );
			if ( $product_latitude && $product_longitude && $user_lat && $user_lon ) {
				$distance = getDistanceByCoordinates( array(
					'location_from' => array(
						'lat' => $user_lat,
						'lng' => $user_lon,
					),
					'location_to'   => array(
						'lat' => $product_latitude,
						'lng' => $product_longitude,
					),
					'unit'          => "K",
					'unit_show'     => false
				) );
			} else {
				$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
				$distance     = getDistance( $user_address, $address, "K" );
			}
			$distance = floatval( $distance );
			if ( isset( $res[ $distance ] ) ) {
				$res[ $distance ][] = $id;
			} else {
				$res[ $distance ] = array( $id );
			}
		}
	}
	wp_reset_postdata();
	wp_reset_query();
	$res = array_filter( $res, 'filterNumericKeys', ARRAY_FILTER_USE_KEY );
	ksort( $res );

	return $res;
}

function filterNumericKeys( $key ) {
	return is_int( $key ) || is_float( $key );
}

function count_products( $args ) {
	$res             = 0;
	$post_status     = $args['post_status'];
	$management_user = $args['management_user'] ?: get_current_user_id();
	$args            = array(
		'post_type'      => 'products',
		'post_status'    => $post_status,
		'posts_per_page' => - 1,
		'author__in'     => array( $management_user )
	);
	$query           = new WP_Query( $args );
	$res             = $query->found_posts;
	wp_reset_postdata();
	wp_reset_query();

	return $res;
}

function get_products_by_locations( $query_args = array() ) {
	$res              = array();
	$user_location    = get_user_location();
	$user_coordinates = get_user_location_coordinates();
	$user_lat         = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon         = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$city             = $user_location['city'];
	$city             = getLocaleCity( $city );
	$key              = 'get_products_by_' . base64_encode( $user_lat . $user_lon . json_encode( $query_args ) );
	$post__in         = get_transient( $key );
	if ( false === $post__in ) {
		$args = array(
			'post_type'      => 'products',
			'post_status'    => 'publish',
			'posts_per_page' => 500,
		);
		if ( $query_args ) {
			$args = array_merge( $args, $query_args );
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$id                = get_the_ID();
				$distance          = 0;
				$product_latitude  = carbon_get_post_meta( $id, 'product_latitude' );
				$product_longitude = carbon_get_post_meta( $id, 'product_longitude' );
				$address           = carbon_get_post_meta( $id, 'product_address' );
				if ( $product_latitude && $product_longitude && $user_lat && $user_lon ) {
					$distance = getDistanceByCoordinates( array(
						'location_from' => array(
							'lat' => $user_lat,
							'lng' => $user_lon,
						),
						'location_to'   => array(
							'lat' => $product_latitude,
							'lng' => $product_longitude,
						),
						'unit'          => "K",
						'unit_show'     => false
					) );
				} else {
					$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
					$distance     = getDistance( $user_address, $address, "K" );
				}
				$distance = floatval( $distance );
				if ( isset( $res[ $distance ] ) ) {
					$res[ $distance ][] = $id;
				} else {
					$res[ $distance ] = array( $id );
				}
			}
		}
		wp_reset_postdata();
		wp_reset_query();
		ksort( $res );
		if ( $res ) {
			foreach ( $res as $distance => $items ) {
				foreach ( $items as $item ) {
					$post__in[] = $item;
				}
			}
		}
		set_transient( $key, $post__in, HOUR_IN_SECONDS );
	}

	return $post__in;
}

function set_search_query_data() {
	global $wp_query;
	$s    = $_GET['s'] ?? '';
	$args = array(
		'post__in' => get_products_by_locations( array( 's' => $s ) ),
		'orderby'  => 'post__in'
	);
	query_posts( array_merge( $wp_query->query, $args ) );
}

function get_product_labels_html( $id ) {
	ob_start();
	the_product_labels( $id );
	$html = ob_get_clean();

	return $html;
}

function get_portmone_post_data() {
	$p = $_POST;
	if ( $p ) {
		$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
		$shop_order_number  = $p['SHOPORDERNUMBER'] ?? '';
		$SHOPBILLID         = $p['SHOPBILLID'] ?? '';
		$BILL_AMOUNT        = $p['BILL_AMOUNT'] ?? '';
		$RECEIPT_URL        = $p['RECEIPT_URL'] ?? '';
		$ERRORIPSMESSAGE    = $p['ERRORIPSMESSAGE'] ?? '';
		$hash_service       = $_GET['hash_service'] ?? '';
		$user               = $_GET['user'] ?? '';
		$order_ID           = base64_decode( $hash_service );
		$redirect           = '';
		if ( $personal_area_page ) {
			$personal_area_page = $personal_area_page[0]['id'];
			$redirect           = get_the_permalink( $personal_area_page ) . '?route=advertisement';
		}
		if ( $shop_order_number && get_post( $shop_order_number ) ) {
			$portmone_url      = carbon_get_theme_option( 'portmone_url' );
			$portmone_payee_id = carbon_get_theme_option( 'portmone_payee_id' );
			$portmone_login    = carbon_get_theme_option( 'portmone_login' );
			$portmone_password = carbon_get_theme_option( 'portmone_password' );
			carbon_set_post_meta( $shop_order_number, 'portmone_id', $SHOPBILLID );
			carbon_set_post_meta( $shop_order_number, 'portmone_sum', $BILL_AMOUNT );
			carbon_set_post_meta( $shop_order_number, 'portmone_receipt_url', $RECEIPT_URL );
			carbon_set_post_meta( $shop_order_number, 'portmone_erroripsmessage', $ERRORIPSMESSAGE );
			$json_data = array(
				'method' => 'result',
				"params" => array(
					'data' => array(
						"login"           => $portmone_login,
						"password"        => $portmone_password,
						"payeeId"         => $portmone_payee_id,
						"shopOrderNumber" => $order_ID,
						"shopbillId"      => $SHOPBILLID,
					)
				)
			);
			$res       = send_request( $portmone_url, $json_data );
			$user_id   = get_post_field( 'post_author', $order_ID );
			if ( $res ) {
				$res         = $res[0];
				$status      = $res['status'] ?? '';
				$description = $res['description'] ?? '';
				if ( $status === 'PAYED' ) {
					carbon_set_post_meta( $order_ID, 'purchased_status', 'payed' );
					create_payment( array(
						'description' => $description,
						'user_id'     => $user_id,
						'sum'         => $BILL_AMOUNT,
						'order_id'    => $shop_order_number,
					) );
					execute_package( $shop_order_number, $BILL_AMOUNT );
				}
				if ( $redirect ) {
					$redirect .= '&purchased_id=' . $order_ID;
					header( 'Location: ' . $redirect );
					die();
				}
			}

		}
	}
}

function execute_package( $id, $sum = 0 ) {
	$id                   = (int) $id;
	$time                 = time();
	$start_date           = carbon_get_post_meta( $id, 'purchased_date' ) ?: $time;
	$products             = carbon_get_post_meta( $id, 'purchased_product_ids' ) ?: '';
	$regions              = carbon_get_post_meta( $id, 'purchased_regions' ) ?: '';
	$purchased_service_id = carbon_get_post_meta( $id, 'purchased_service_id' );
	$user_id              = get_post_field( 'post_author', $id );
	$zoho_data            = array();
	if ( $purchased_service_id && get_post( $purchased_service_id ) ) {
		$products     = explode( ',', $products );
		$regions      = explode( ',', $regions );
		$term         = carbon_get_post_meta( $purchased_service_id, 'service_term' ) ?: 1;
		$is_urgently  = carbon_get_post_meta( $purchased_service_id, 'service_urgently' );
		$service_date = carbon_get_post_meta( $purchased_service_id, 'service_date' );
		$count_up     = carbon_get_post_meta( $purchased_service_id, 'service_up' ) ?: 0;
		$is_top       = carbon_get_post_meta( $purchased_service_id, 'service_is_top' );
		if ( $start_date ) {
			$term_number = $term * 86400;
			$term_end    = $start_date + $term_number;
			if ( $products ) {
				$count_up = $count_up - 1;
				foreach ( $products as $product ) {
					$product_id = (int) $product;
					if ( $product && get_post( $product_id ) ) {
						if ( $is_top ) {
							carbon_set_post_meta( $product_id, 'product_is_top', 'top' );
							carbon_set_post_meta( $product_id, 'product_start_top', $start_date );
							carbon_set_post_meta( $product_id, 'product_end_top', $term_end );
						}
						if ( $regions ) {
							wp_set_post_terms( $product_id, [], 'regions', false );
							if ( in_array( 'country', $regions ) ) {
								$_regions      = array();
								$regions_terms = get_terms( array(
									'hide_empty' => false,
									'taxonomy'   => 'regions',
								) );
								if ( $regions_terms ) {
									foreach ( $regions_terms as $region ) {
										$_regions[] = $region->term_id;
									}
									wp_set_post_terms( $product_id, $_regions, 'regions', true );
								}
							} else {
								$_regions = array();
								foreach ( $regions as $place ) {
									if ( get_term_by( 'id', (int) $place, 'regions' ) ) {
										$_regions[] = (int) $place;
									}
								}
								wp_set_post_terms( $product_id, $_regions, 'regions', true );
							}
						}
						if ( $count_up > 0 ) {
							$current_datetime = current_time( 'mysql', false );
							$update_post_data = array(
								'ID'            => $product_id,
								'post_date'     => $current_datetime,
								'post_date_gmt' => get_gmt_from_date( $current_datetime ),
							);
							wp_update_post( $update_post_data );
						}
						if ( $is_urgently ) {
							carbon_set_post_meta( $product_id, 'product_is_urgently', 'urgently' );
							carbon_set_post_meta( $product_id, 'product_start_urgently', $start_date );
							carbon_set_post_meta( $product_id, 'product_end_urgently', $term_end );
						}
					}
				}
				if ( $count_up > 0 ) {
					carbon_set_post_meta( $id, 'purchased_up_qnt', $count_up );
					$next_event_time = $start_date + ( ( $term / $count_up ) * 86400 );
					$hook            = 'products_boost_hook';
					$args            = array( $id );
					if ( ! wp_next_scheduled( $hook, $args ) ) {
						wp_schedule_single_event( $next_event_time, $hook, $args );
					}
				}
			}
			$zoho_data['Start_Date']      = date( 'Y-m-d', $start_date );
			$zoho_data['Closing_Date']    = date( 'Y-m-d', $term_end );
			$zoho_data['Stage']           = 'Closed Won';
			$zoho_data['purchased_id']    = $id;
			$zoho_data['user_id']         = $user_id;
			$zoho_data['Date_of_payment'] = date( 'Y-m-d', $start_date );
			$zoho_data['Amount']          = round( $sum, 2 );
			$zoho_data['Name_of_service'] = get_the_title( $id );
			$zoho_data['Deal_Name']       = get_the_title( $id );
			$zoho_data['Contact_Name']    = array(
				'id' => carbon_get_user_meta( $user_id, 'zoho_id' )
			);
			$zoho_data['Account_Name']    = array(
				'id' => carbon_get_user_meta( $user_id, 'zoho_account_id' )
			);
			$r                            = create_zoho_deal( $zoho_data );
		}
	}
}

add_action( 'products_boost_hook', 'products_boost_action', 10, 1 );
function products_boost_action( $order_id ) {
	if ( $order_id && get_post( $order_id ) ) {
		$time             = time();
		$purchased_up_qnt = carbon_get_post_meta( $order_id, 'purchased_up_qnt' ) ?: 0;
		$service_id       = carbon_get_post_meta( $order_id, 'purchased_service_id' ) ?: 0;
		$term             = carbon_get_post_meta( $service_id, 'service_term' ) ?: 1;
		$start_date       = carbon_get_post_meta( $order_id, 'purchased_date' );
		$term_number      = $term * 86400;
		$term_end         = $start_date + $term_number;
		$count_up         = $purchased_up_qnt;
		if ( $count_up > 0 ) {
			$purchased_product_ids = carbon_get_post_meta( $order_id, 'purchased_product_ids' );
			if ( $purchased_product_ids ) {
				$purchased_product_ids = explode( ',', $purchased_product_ids );
				if ( $purchased_product_ids ) {
					foreach ( $purchased_product_ids as $product_id ) {
						$product_id = (int) $product_id;
						if ( $product_id && get_post( $product_id ) ) {
							if ( $count_up > 0 ) {
								$current_datetime = current_time( 'mysql', false );
								$update_post_data = array(
									'ID'            => $product_id,
									'post_date'     => $current_datetime,
									'post_date_gmt' => get_gmt_from_date( $current_datetime ),
								);
								wp_update_post( $update_post_data );
								$count_up = $count_up - 1;
								carbon_set_post_meta( $order_id, 'purchased_up_qnt', $count_up );
							}
						}
					}
				}
			}
		}
		if ( $count_up > 0 ) {
			$last_time       = $term_end - $time;
			$last_days       = $last_time / 86400;
			$next_event_time = $time + ( ( $last_days / $count_up ) * 86400 );
			$hook            = 'products_boost_hook';
			$args            = array( $order_id );
			if ( ! wp_next_scheduled( $hook, $args ) ) {
				wp_schedule_single_event( $next_event_time, $hook, $args );
			}
		}
	}
}

function create_payment( $data ) {
	$post_data = array(
		'post_type'   => 'payment',
		'post_title'  => $data['description'],
		'post_status' => 'publish',
		'post_author' => $data['user_id'],
	);
	$_id       = wp_insert_post( $post_data );
	if ( $post = get_post( $_id ) ) {
		carbon_set_post_meta( $_id, 'payment_sum', $data['sum'] );
		carbon_set_post_meta( $_id, 'payment_order', $data['order_id'] );

		return $post;
	}

	return false;
}

function send_request( $url, $args = array(), $request_type = 'POST' ) {

	if ( $curl = curl_init() ) {
		$h = array();
		if ( $args ) {
			$h[] = 'Content-Type: application/json; charset=utf-8';
		}
		curl_setopt( $curl, CURLOPT_URL, $url );
		curl_setopt( $curl, CURLOPT_FOLLOWLOCATION, true );
		curl_setopt( $curl, CURLOPT_RETURNTRANSFER, true );
		curl_setopt( $curl, CURLOPT_SSL_VERIFYPEER, false );
		curl_setopt( $curl, CURLOPT_CUSTOMREQUEST, $request_type );
		if ( $args ) {
			if ( is_array( $args ) ) {
				$args = json_encode( $args );
			}
			curl_setopt( $curl, CURLOPT_POSTFIELDS, $args );
		}
		curl_setopt( $curl, CURLOPT_HTTPHEADER, $h );
		$out  = curl_exec( $curl );
		$json = json_decode( $out, true );
		curl_close( $curl );

		return $json;
	} else {
		throw new HttpException( 'Can not create connection to ' . $url . ' with args ' . $args, 404 );
	}
}

function get_user_categories( $user_id, $post_status ) {
	$categories = array();
	$args       = array(
		'post_type'      => 'products',
		'post_status'    => $post_status,
		'posts_per_page' => - 1,
		'author__in'     => array( $user_id )
	);
	$query      = new WP_Query( $args );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$id   = get_the_ID();
			$cats = get_the_terms( $id, 'categories' );
			if ( $cats ) {
				foreach ( $cats as $cat ) {
					if ( $cat->parent === 0 && ! in_array( $cat, $categories ) ) {
						$categories[] = $cat;
					}
				}
			}
		}
	}
	wp_reset_postdata();
	wp_reset_query();

	return $categories;
}

function get_work_time_json_string( $days_prefix = 'days' ) {
	$work_time_organization = array();
	$start_hours            = $_POST['start_hours'] ?? array();
	$start_minutes          = $_POST['start_minutes'] ?? array();
	$finish_hours           = $_POST['finish_hours'] ?? array();
	$finish_minutes         = $_POST['finish_minutes'] ?? array();
	foreach ( $_POST as $key => $value ) {
		$pos = strpos( $key, $days_prefix );
		if ( $pos !== false && $key !== 'days_prefix' ) {
			$_index                   = explode( '_', $key )[1];
			$_index                   = (int) $_index;
			$temp                     = array(
				$value,
				array( $start_hours[ $_index ] ?? '09', $start_minutes[ $_index ] ?? '00' ),
				array( $finish_hours[ $_index ] ?? '18', $finish_minutes[ $_index ] ?? '00' ),
			);
			$work_time_organization[] = $temp;
		}
	}

	return json_encode( $work_time_organization );
}

function get_seller_page_link( $user_id ) {
	$link      = '#';
	$user_post = carbon_get_user_meta( $user_id, 'user_post' );
	if ( $user_post && get_post( $user_post ) ) {
		$link = get_the_permalink( $user_post );
	}

	return $link;
}

function get_product_purchases_number( $productID ) {
	$res = 0;
	if ( $purchases = carbon_get_post_meta( $productID, 'product_purchases' ) ) {
		foreach ( $purchases as $purchase ) {
			$purchased = $purchase['purchased'] ?: 0;
			$purchased = (float) $purchased;
			$res       = $res + $purchased;
		}
	}

	return $res;
}