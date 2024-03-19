<?php

add_action( 'wp_ajax_nopriv_get_locations', 'get_locations' );
add_action( 'wp_ajax_get_locations', 'get_locations' );
function get_locations() {
	$res                    = array();
	$radius_start           = '';
	$categories             = $_GET['categories'] ?? '';
	$year                   = $_GET['y'] ?? '';
	$delivery_methods       = $_GET['delivery_methods'] ?? '';
	$units_measurement      = $_GET['units_measurement'] ?? '';
	$packages               = $_GET['packages'] ?? '';
	$category               = $_GET['category'] ?? '';
	$certificates           = $_GET['certificate'] ?? '';
	$processing_types       = $_GET['processing_types'] ?? '';
	$types                  = $_GET['type'] ?? '';
	$radius                 = $_GET['radius'] ?? '';
	$min_price              = $_GET['min-price'] ?? '';
	$max_price              = $_GET['max-price'] ?? '';
	$place                  = $_GET['place'] ?? '';
	$filter                 = $_GET['filter'] ?? '';
	$paged                  = $_GET['pagenumber'] ?? 1;
	$delivery_methods_types = get_delivery_methods_types();
	$args                   = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => - 1
	);
	if ( $categories ) {
		$categories        = ! is_array( $categories ) ? explode( ',', $categories ) : $categories;
		$args['tax_query'] = array(
			array(
				'taxonomy' => 'categories',
				'field'    => 'id',
				'terms'    => $categories
			)
		);
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
	if ( $types ) {
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
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		$temp_array = array();
		while ( $query->have_posts() ) :
			$query->the_post();
			$id                = get_the_ID();
			$title             = get_the_title( $id );
			$link              = get_the_permalink( $id );
			$address           = carbon_get_post_meta( $id, 'product_address' );
			$city              = carbon_get_post_meta( $id, 'product_city' );
			$product_latitude  = (float) carbon_get_post_meta( $id, 'product_latitude' );
			$product_longitude = (float) carbon_get_post_meta( $id, 'product_longitude' );
			$unit              = carbon_get_post_meta( $id, 'product_unit' );
			$rating            = carbon_get_post_meta( $id, 'product_rating' );
			$delivery_methods  = carbon_get_post_meta( $id, 'product_delivery_methods' );
			$min_order         = carbon_get_post_meta( $id, 'product_min_order' );
			$max_value         = carbon_get_post_meta( $id, 'product_max_value' );
			$is_favorite       = is_in_favorite( $id );
			$author_id         = get_post_field( 'post_author', $id );
			$user_verification = carbon_get_user_meta( $author_id, 'user_verification' );
			$currency          = carbon_get_theme_option( 'currency' );
			$user_location     = get_user_location();
			$user_coordinates  = get_user_location_coordinates();
			$user_lat          = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
			$user_lon          = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
			$reviews_count     = review_count( $id );
			$product_price     = carbon_get_post_meta( $id, 'product_price' ) ?: 0;
			$user_address      = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
			$distance          = 0;
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
					'unit'          => "K"
				) );
			} else {
				$distance = getDistance( $user_address, $address, "K" );
			}
			$slider              = array();
			$gallery             = carbon_get_post_meta( $id, 'product_gallery' );
			$img                 = get_the_post_thumbnail_url( $id );
			$author_id           = get_post_field( 'post_author', $id );
			$delivery_count      = carbon_get_user_meta( $author_id, 'delivery_count' ) ?: 0;
			$user_company_name   = carbon_get_user_meta( $author_id, 'user_company_name' ) ?: '';
			$product_labels_html = get_product_labels_html( $id );
			if ( $gallery ): foreach ( $gallery as $j => $image_id ): if ( $j < 3 ):
				$slider[] = _u( $image_id, 1 );
			endif; endforeach;
            elseif ( $img ):
				$slider[] = $img;
			endif;
			$description          = array();
			$delivery_methods_str = '';
			if ( $delivery_methods ) {
				$delivery_methods_str = implode( ', ', $delivery_methods );
				$delivery_methods_str = str_replace(
					$delivery_methods_types,
					array( '', '', '' ),
					$delivery_methods_str
				);
				$description[]        = array(
					'"Доставка:"' => $delivery_methods_str
				);
			}
			if ( $min_order ) {
				$description[] = array(
					'"Мінімальний заказ:"' => $min_order . ' ' . $unit
				);
			}
			if ( $max_value ) {
				$description[] = array(
					'"В наявності:"' => $max_value . ' ' . $unit
				);
			}
			$key         = md5( $product_latitude . $product_longitude );
			$author_link = '#';
			$user_post   = carbon_get_user_meta( $author_id, 'user_post' );
			if ( $user_post && get_post( $user_post ) ) {
				$author_link = get_the_permalink( $user_post );
			}
			if ( ! $temp_array[ $key ] ) {
				$temp_array[ $key ] = array(
					'labels_html' => [ $product_labels_html ],
					'favorites'   => [ $is_favorite ? 'active' : '' ],
					'id'          => [ $id ],
					'seller_link' => [ $author_link ],
					'title'       => [ $title ],
					'link'        => [ $link ],
					'verified'    => [ $user_verification ],
					'date'        => [ '' ],
					'lat'         => $product_latitude,
					'lng'         => $product_longitude,
					'subtitle'    => [ $user_company_name ],
					'delivery'    => [ $delivery_count . ' успішних доставок' ],
					'place'       => $city ?: $address,
					'distance'    => 'Відстань: ' . $distance,
					'price'       => [ $product_price ],
					'price_value' => [ "$currency/$unit" ],
					'reviews'     => [ ( $reviews_count ?: 0 ) . ' відгуків' ],
					'rating'      => [ $rating ?: 5 ],
					'slider'      => [ $slider ],
					'shipping'    => [ " $delivery_methods_str" ],
					'order'       => [ $min_order ? " $min_order  $unit" : '' ],
					'stock'       => [ $max_value ? " $max_value  $unit" : '' ],
				);
			} else {
				$temp_array[ $key ]['labels_html'][] = $product_labels_html;
				$temp_array[ $key ]['favorites'][]   = $is_favorite ? 'active' : '';
				$temp_array[ $key ]['seller_link'][] = $author_link;
				$temp_array[ $key ]['id'][]          = $id;
				$temp_array[ $key ]['title'][]       = $title;
				$temp_array[ $key ]['link'][]        = $link;
				$temp_array[ $key ]['verified'][]    = $user_verification;
				$temp_array[ $key ]['date'][]        = '';
				$temp_array[ $key ]['subtitle'][]    = $user_company_name;
				$temp_array[ $key ]['delivery'][]    = $delivery_count . ' успішних доставок';
				$temp_array[ $key ]['price'][]       = $product_price;
				$temp_array[ $key ]['price_value'][] = "$currency/$unit";
				$temp_array[ $key ]['reviews'][]     = ( $reviews_count ?: 0 ) . ' відгуків';
				$temp_array[ $key ]['rating'][]      = $rating ?: 5;
				$temp_array[ $key ]['slider'][]      = $slider;
				$temp_array[ $key ]['shipping'][]    = "$delivery_methods_str";
				$temp_array[ $key ]['order'][]       = $min_order ? "  $min_order  $unit" : '';
				$temp_array[ $key ]['stock'][]       = $max_value ? "$max_value  $unit" : '';
			}
		endwhile;
		if ( $temp_array ) {
			foreach ( $temp_array as $item ) {
				$res[] = $item;
			}
		}
	endif;
	wp_reset_postdata();
	wp_reset_query();
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_get_subcategories', 'get_subcategories' );
add_action( 'wp_ajax_get_subcategories', 'get_subcategories' );
function get_subcategories() {
	$parent     = $_POST['parent'] ?? '';
	$product_id = $_POST['product_id'] ?? '';
	$is_filter  = $_POST['is_filter'] ?? 'false';
	if ( $parent ) {
		$categories  = get_terms( array(
			'taxonomy'   => 'categories',
			'hide_empty' => false,
			'parent'     => (int) $parent,
		) );
		$_categories = array();
		if ( $product_id && get_post( $product_id ) ) {
			$_categories = get_the_terms( $product_id, 'categories' );
		}
		if ( $categories ) {
			if ( $is_filter == 'true' ) {
				?>
                <option selected value="">Зробіть вибір</option>
				<?php
			}

			foreach ( $categories as $item ):
				$attr = '';
				if ( $_categories ) {
					foreach ( $_categories as $_category ) {
						if ( $_category->term_id == $item->term_id ) {
							$attr = 'selected';
						}
					}
				}
				?>
                <option <?php echo $attr; ?>
                value="<?php echo $item->term_id; ?>"><?php echo $item->name; ?></option><?php endforeach;
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_product_filters', 'get_product_filters' );
add_action( 'wp_ajax_get_product_filters', 'get_product_filters' );
function get_product_filters() {
	$category   = $_POST['category'] ?? '';
	$product_id = $_POST['product_id'] ?? '';
	if ( $category ) {
		$filters = get_filter_by_category( $category );
		if ( $filters ) {
			$_filters = array();
			if ( $product_id && get_post( $product_id ) ) {
				$_filters = get_the_terms( $product_id, 'filters' );
			}
			foreach ( $filters as $filter_id ) {
				$filter_item = get_term_by( 'id', $filter_id, 'filters' );
				if ( $filter_item ) {
					$attr_filter_item = 'disabled';
					?>
                    <option <?php echo $attr_filter_item; ?>
                    value="<?php echo $filter_item->term_id; ?>"><?php echo $filter_item->name; ?></option><?php
					$values = get_term_children( $filter_id, 'filters' );
					if ( $values ) {
						foreach ( $values as $value ) {
							$child = get_term_by( 'id', $value, 'filters' );
							$attr  = '';
							if ( $_filters ) {
								foreach ( $_filters as $_filter ) {
									if ( $_filter->term_id == $child->term_id ) {
										$attr = 'selected';
									}
								}
							}
							?>
                        <option <?php echo $attr; ?>
                                value="<?php echo $child->term_id; ?>">--<?php echo $child->name; ?></option><?php
						}
					}
				}
			}
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_new_product', 'new_product' );
add_action( 'wp_ajax_new_product', 'new_product' );
function new_product() {
	$user_id            = get_current_user_id();
	$product_author_ID  = $user_id;
	$res                = array();
	$post               = $_POST;
	$ID                 = $_POST['ID'] ?? '';
	$is_company_address = $_POST['is_company_address'] ?? '';
	$user_name          = $post['user_name'] ?? '';
	$company_name       = $post['company_name'] ?? '';
	$phone              = $post['phone'] ?? '';
	$email              = $post['email'] ?? '';
	$user_postcode      = $post['user_postcode'] ?? '';
	$user_country       = $post['user_country'] ?? '';
	$user_country_code  = $post['user_country_code'] ?? '';
	$user_city          = $post['user_city'] ?? '';
	$lat                = $post['lat'] ?? '';
	$lng                = $post['lng'] ?? '';
	$title              = $post['title'] ?? '';
	$content            = $post['content'] ?? '';
	$price              = $post['price'] ?? '';
	$address            = $post['address'] ?? '';
	$pick_up_address    = $post['pick_up_address'] ?? '';
	$pick_up_work_time  = $post['pick_up_work_time'] ?? '';
	$product_type       = $post['product_type'] ?? '';
	$categories         = $post['categories'] ?? '';
	$certificates       = $post['certificates'] ?? '';
	$processing_type    = $post['processing_type'] ?? '';
	$units_measurement  = $post['units_measurement'] ?? '';
	$product_max_value  = $post['product_max_value'] ?? '';
	$delivery_types     = $post['delivery_types'] ?? '';
	$year               = $post['year'] ?? '';
	$products           = $post['products'] ?? '';
	$package            = $post['package'] ?? '';
	$automatically      = $post['automatically_continue'] ?? '';
	$product_min_order  = $post['product_min_order'] ?? '';
	$user_region        = $post['user_region'] ?? '';
	$management_user    = $post['management_user'] ?? '';
	$author_id          = $post['author_id'] ?? '';
	$category_name      = $post['category_name'] ?? '';
	$filters            = $post['filters'] ?? '';
	$test_management    = true;
	if ( $management_user ) {
		$test_management = is_active_manager( $user_id, $management_user );
	}
	if ( $test_management ) {
		if ( $title && $categories && $price && $_FILES ) {
			$var       = variables();
			$set       = $var['setting_home'];
			$assets    = $var['assets'];
			$url       = $var['url'];
			$time      = time();
			$post_data = array(
				'post_type'    => 'products',
				'post_title'   => $title,
				'post_status'  => 'pending',
				'post_content' => $content,
			);
			if ( $author_id ) {
				$post_data['post_author'] = $author_id;
				$product_author_ID        = $author_id;
			} elseif ( $user_id ) {
				$post_data['post_author'] = $user_id;
			} else {
				if ( $user = get_user_by( 'email', $email ) ) {
					$post_data['post_author'] = $user->ID;
					$product_author_ID        = $user->ID;
				}
			}
			if ( $ID ) {
				$old_date = get_the_date( 'U', $ID );
				if ( wp_next_scheduled( 'set_product_status_action', array( $ID, 'archive' ) ) ) {
					wp_clear_scheduled_hook( 'set_product_status_action', array( $ID, 'archive' ) );
				}
				$post_data['ID']        = $ID;
				$post_data['post_date'] = date( 'Y-m-d H:i:s', $time );
			}
			$_id  = $ID ? wp_update_post( $post_data, true ) : wp_insert_post( $post_data );
			$post = get_post( $_id );
			if ( ! is_wp_error( $_id ) && $post ) {
				$price = str_replace( ',', '.', $price );
				$price = (float) $price;
				carbon_set_post_meta( $_id, 'is_company_address', ( $is_company_address == 'true' || $is_company_address == 'on' ) );
				carbon_set_post_meta( $_id, 'product_custom_category', $category_name );
				carbon_set_post_meta( $_id, 'product_min_order', $product_min_order );
				carbon_set_post_meta( $_id, 'product_price', $price );
				carbon_set_post_meta( $_id, 'product_max_value', $product_max_value );
				carbon_set_post_meta( $_id, 'product_year', $year );
				carbon_set_post_meta( $_id, 'product_unit', $units_measurement );
				carbon_set_post_meta( $_id, 'product_region', $user_region );
				carbon_set_post_meta( $_id, 'product_auto_continue', $automatically == 'on' ?: false );
				wp_set_post_terms( $_id, array(), 'categories', false );
				wp_set_post_terms( $_id, array(), 'product_type', false );
				wp_set_post_terms( $_id, array(), 'regions', true );
				$product_author = get_user_by( 'id', $product_author_ID );
				carbon_set_post_meta( $_id, 'product_company_name', carbon_get_user_meta( $product_author_ID, 'user_company_name' ) );
				carbon_set_post_meta( $_id, 'product_user_name', $product_author->first_name );
				carbon_set_post_meta( $_id, 'product_user_phone', carbon_get_user_meta( $product_author_ID, 'user_company_phone' ) );
				carbon_set_post_meta( $_id, 'product_user_email', $product_author->user_email );
				if ( $is_company_address == 'true' || $is_company_address == 'on' ) {
					carbon_set_post_meta( $_id, 'product_address', carbon_get_user_meta( $product_author_ID, 'user_company_address' ) );
					carbon_set_post_meta( $_id, 'product_user_postcode', carbon_get_user_meta( $product_author_ID, 'user_company_postcode' ) );
					carbon_set_post_meta( $_id, 'product_user_country', carbon_get_user_meta( $product_author_ID, 'user_company_country' ) );
					carbon_set_post_meta( $_id, 'product_user_country_code', carbon_get_user_meta( $product_author_ID, 'user_company_country' ) );
					carbon_set_post_meta( $_id, 'product_city', carbon_get_user_meta( $product_author_ID, 'user_company_city' ) );
					carbon_set_post_meta( $_id, 'product_latitude', carbon_get_user_meta( $product_author_ID, 'user_company_latitude' ) );
					carbon_set_post_meta( $_id, 'product_longitude', carbon_get_user_meta( $product_author_ID, 'user_company_longitude' ) );
					carbon_set_post_meta( $_id, 'product_region', carbon_get_user_meta( $product_author_ID, 'user_company_region' ) );
					$res['$product_author_ID'] = $product_author_ID;
				} else {
					carbon_set_post_meta( $_id, 'product_user_postcode', $user_postcode );
					carbon_set_post_meta( $_id, 'product_user_country', $user_country );
					carbon_set_post_meta( $_id, 'product_user_country_code', $user_country_code );
					carbon_set_post_meta( $_id, 'product_city', $user_city );
					carbon_set_post_meta( $_id, 'product_latitude', $lat );
					carbon_set_post_meta( $_id, 'product_longitude', $lng );
					carbon_set_post_meta( $_id, 'product_address', $address );
					if ( $pick_up_address ) {
						$pick_up_address_arr = array();
						if ( is_array( $pick_up_address ) ) {
							foreach ( $pick_up_address as $i => $item ) {
								$wt = '';
								if ( $pick_up_work_time ) {
									if ( is_array( $pick_up_work_time ) ) {
										if ( isset( $pick_up_work_time[ $i ] ) ) {
											$wt = $pick_up_work_time[ $i ];
										}
									} else {
										if ( $i == 0 ) {
											$wt = $pick_up_work_time;
										}
									}
								}
								$pick_up_address_arr[] = array(
									'address'   => $item,
									'work_time' => $wt,
								);
							}
						} else {
							$pick_up_address_arr[] = array(
								'address'   => $pick_up_address,
								'work_time' => $pick_up_work_time,
							);
						}
						carbon_set_post_meta( $_id, 'pick_up_address', $pick_up_address_arr );
					}
				}

				if ( $user_region ) {
					if ( is_array( $user_region ) ) {
						foreach ( $user_region as $user_region_item ) {
							if ( $region_term = get_term_by( 'name', $user_region_item, 'regions' ) ) {
								$user_region_item = $region_term->term_id;
								wp_set_post_terms( $_id, array( $user_region_item ), 'regions', true );
							}
						}
					} else {
						if ( $region_term = get_term_by( 'name', $user_region, 'regions' ) ) {
							$user_region_item = $region_term->term_id;
							wp_set_post_terms( $_id, array( $user_region_item ), 'regions', true );
						}
					}
				}
				if ( $delivery_types ) {
					$__arr = array();
					if ( is_array( $delivery_types ) ) {
						foreach ( $delivery_types as $item ) {
							$__arr[] = $item;
						}
					} else {
						$__arr[] = $delivery_types;
					}
					carbon_set_post_meta( $_id, 'product_delivery_methods', $__arr );
				}
				if ( $processing_type ) {
					if ( is_array( $processing_type ) ) {
						foreach ( $processing_type as $item ) {
							$item = (int) $item;
							wp_set_post_terms( $_id, array( $item ), 'processing_type', true );
						}
					} else {
						$item = (int) $processing_type;
						wp_set_post_terms( $_id, array( $item ), 'processing_type', true );
					}
				}
				if ( $package ) {
					if ( is_array( $package ) ) {
						foreach ( $package as $item ) {
							$item = (int) $item;
							wp_set_post_terms( $_id, array( $item ), 'package', true );
						}
					} else {
						$item = (int) $package;
						wp_set_post_terms( $_id, array( $item ), 'package', true );
					}
				}
				if ( is_array( $filters ) ) {
					foreach ( $filters as $value ) {
						$value = (int) $value;
						wp_set_post_terms( $_id, array( $value ), 'filters', true );
					}
				} else {
					$value = (int) $filters;
					wp_set_post_terms( $_id, array( $value ), 'filters', true );
				}
				if ( is_array( $categories ) ) {
					foreach ( $categories as $category ) {
						$category = (int) $category;
						wp_set_post_terms( $_id, array( $category ), 'categories', true );
					}
				} else {
					$category = (int) $categories;
					wp_set_post_terms( $_id, array( $category ), 'categories', true );
				}
				if ( is_array( $certificates ) ) {
					foreach ( $certificates as $certificate ) {
						$certificate = (int) $certificate;
						wp_set_post_terms( $_id, array( $certificate ), 'certificates', true );
					}
				} else {
					$certificates = (int) $certificates;
					wp_set_post_terms( $_id, array( $certificates ), 'certificates', true );
				}
				if ( is_array( $product_type ) ) {
					foreach ( $product_type as $item ) {
						$item = (int) $item;
						wp_set_post_terms( $_id, array( $item ), 'product_type', true );
					}
				} else {
					$item = (int) $product_type;
					wp_set_post_terms( $_id, array( $item ), 'product_type', true );
				}
				if ( $products ) {
					$products = is_array( $products ) ? implode( ',', $products ) : $products;
					carbon_set_post_meta( $_id, 'product_products', $products );
				}

				if ( $ID ) {
					$gallery = carbon_get_post_meta( $ID, 'product_gallery' );
					if ( $gallery ) {
						foreach ( $gallery as $item ) {
							wp_delete_attachment( $item );
						}
						carbon_set_post_meta( $_id, 'product_gallery', array() );
					}
				}
				$files         = $_FILES["upfile"];
				$arr           = array();
				$res['$files'] = $files;
				foreach ( $files['name'] as $key => $value ) {
					if ( $files['name'][ $key ] ) {
						$file   = array(
							'name'     => $files['name'][ $key ],
							'type'     => $files['type'][ $key ],
							'tmp_name' => $files['tmp_name'][ $key ],
							'error'    => $files['error'][ $key ],
							'size'     => $files['size'][ $key ]
						);
						$_FILES = array( "file" => $file );
						foreach ( $_FILES as $file => $array ) {
							$arr[] = my_handle_attachment( $file );
						}
						carbon_set_post_meta( $_id, 'product_gallery', $arr );
					}
				}
				$res['$arr'] = $arr;
				if ( $arr ) {
					if ( wp_attachment_is_image( $arr[0] ) ) {
						set_post_thumbnail( $_id, $arr[0] );
					}
				} else {
					delete_post_thumbnail( $_id );
				}
				send_notification( $user_id, $_id );
				$res['type']   = 'success';
				$personal_page = carbon_get_theme_option( 'personal_area_page' );
				if ( $personal_page ) {
					$_url       = get_the_permalink( $personal_page[0]['id'] );
					$res['url'] = $_url . '?route=advertisement';
				}
				set_product_status( $_id, get_the_date( 'U', $_id ) );
			} else {
				if ( is_wp_error( $_id ) ) {
					$res['msg'] = $_id->get_error_message();
				} else {
					$res['msg'] = 'Помилка';
				}
				$res['type'] = 'error';
			}

		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Заповніть обовязкові поля';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Вам не дозволено редагувати цей продукт';
	}
	echo json_encode( $res );
	die();
}

function set_product_status( $product_id, $created_date = false, $status = 'archive' ) {
	if ( get_post( $product_id ) ) {
		$time          = $created_date ?: time();
		$product_id    = (int) $product_id;
		$days_count    = carbon_get_theme_option( 'days_count' ) ?: 30;
		$day           = ( 60 * 60 * 24 );
		$end_time      = $days_count * $day;
		$function_time = $time + $end_time;
		if ( ! wp_next_scheduled( 'set_product_status_action', array( $product_id, $status ) ) ) {
			wp_schedule_single_event( $function_time, 'set_product_status_action', array(
				$product_id,
				$status
			) );
		}
	}
}

add_action( 'set_product_status_action', 'set_product_status_event', 10, 2 );

function set_product_status_event( $product_id, $status ) {
	if ( get_post( $product_id ) ) {
		$auto_continue = carbon_get_post_meta( $product_id, 'product_auto_continue' );
		if ( ! $auto_continue ) {
			$my_post                = array();
			$my_post['ID']          = $product_id;
			$my_post['post_status'] = $status;
			$ID                     = wp_update_post( $my_post, true );
			if ( is_wp_error( $ID ) ) {
				$error_message = "[Помилка змінення статуса; ID:$ID]" . PHP_EOL . $ID->get_error_message();
				send_message( $error_message );
			}
		} else {
			$time                 = time();
			$my_post              = array();
			$my_post['ID']        = $product_id;
			$my_post['post_date'] = date( 'Y-m-d H:i:s', $time );
			$ID                   = wp_update_post( $my_post, true );
			if ( is_wp_error( $ID ) ) {
				$error_message = "[Помилка автопродовження; ID:$ID]" . PHP_EOL . $ID->get_error_message();
				send_message( $error_message );
			} else {
				set_product_status( $product_id, $time );
			}
		}
	}
}

add_action( 'wp_ajax_nopriv_new_comment', 'new_comment' );
add_action( 'wp_ajax_new_comment', 'new_comment' );
function new_comment() {
	$res       = array();
	$rating    = trim( $_POST['rating'] );
	$name      = trim( $_POST['name'] );
	$email     = trim( $_POST['email'] );
	$comment   = trim( $_POST['comment'] );
	$_id       = (int) $_POST['post_id'];
	$user_id   = get_current_user_id();
	$languages = function_exists( 'pll_languages_list' ) ? pll_languages_list() : false;
	if ( $comment ) {
		if ( get_post( $_id ) ) {
			$data       = [
				'comment_post_ID'      => $_id,
				'comment_author'       => $name,
				'comment_author_email' => $email,
				'comment_content'      => $comment,
				'comment_type'         => 'comment',
				'comment_parent'       => 0,
				'user_id'              => $user_id,
				'comment_author_IP'    => get_the_user_ip(),
				'comment_agent'        => get_user_agent(),
				'comment_approved'     => 0,
			];
			$comment_id = wp_insert_comment( wp_slash( $data ) );
			if ( $comment_id ) {
				if ( isset( $rating ) ) {
					$rating = (int) $rating;
					if ( $rating > 0 ) {
						carbon_set_comment_meta( $comment_id, 'comment_rating', $rating );
						$res['rating'] = set_post_rating( $_id );
					}
				}
				$res['msg']        = _l( 'Дякую за коментар', 1 );
				$res['comment_id'] = $comment_id;
				$comments          = get_comments(
					array(
						'post_id'          => $_id,
						'orderby'          => 'date',
						'order'            => 'DESC',
						'comment_approved' => 1,
					)
				);
				$res['html']       = wp_list_comments( [
					'callback'     => 'harvy_comments_callback',
					'end-callback' => 'harvy_comments_callback_end',
					'echo'         => false,
				], $comments );
			} else {
				$res['msg'] = _l( 'Помилка!', 1 );
			}
		} else {
			$res['msg'] = _l( 'Помилка!', 1 );
		}
	} else {
		$res['msg'] = _l( 'Залиште коментар', 1 );
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_set_user_favorites', 'set_user_favorites' );
add_action( 'wp_ajax_set_user_favorites', 'set_user_favorites' );
function set_user_favorites() {
	$user_id = get_current_user_id();
	$arr     = array();
	if ( $user_id ) {
		$favorites = $_COOKIE['favorites'] ?? '';
		carbon_set_user_meta( $user_id, 'user_favorites', $favorites );
		$arr['user_id']   = $user_id;
		$arr['favorites'] = $favorites;
	} else {
		$arr['msg'] = 'Користувач не авторизований';
	}
	echo json_encode( $arr );
	die();
}

add_action( 'wp_ajax_nopriv_new_order', 'new_order' );
add_action( 'wp_ajax_new_order', 'new_order' );
function new_order() {
	$res             = array();
	$type            = $_POST['type'] ?? '';
	$ids             = $_POST['id'] ?? '';
	$name            = $_POST['name'] ?? '';
	$tel             = $_POST['tel'] ?? '';
	$last_name       = $_POST['last_name'] ?? '';
	$first_name      = $_POST['first_name'] ?? '';
	$surname         = $_POST['surname'] ?? '';
	$city            = $_POST['city'] ?? '';
	$email           = $_POST['email'] ?? '';
	$promo           = $_POST['promo'] ?? ( $_COOKIE['coupon'] ?? '' );
	$qnts            = $_POST['qnt'] ?? '';
	$delivery_method = $_POST['delivery_method'] ?? '';
	$address         = $_POST['address'] ?? '';
	$post_office     = $_POST['nova_post_office'] ?? ( $_POST['post_office'] ?? '' );
	$payment_method  = $_POST['payment_method'] ?? '';
	$coupon          = $promo;
	$order_seller_id = $_POST['order_seller_id'] ?? '';
	$emails          = array();
	if ( $ids && $tel ) {
		$name      = $name ?: $last_name . ' ' . $first_name;
		$user_id   = get_current_user_id();
		$title     = $type == 'quick_order' ? '[Швидке замовлення] ' : '';
		$title     = "$name $tel $title";
		$post_data = array(
			'post_type'   => 'orders',
			'post_title'  => $title,
			'post_status' => 'pending',
			'post_author' => (int) $user_id,
		);
		$_id       = wp_insert_post( $post_data );
		$post      = get_post( $_id );
		if ( $post ) {
			$currency        = carbon_get_theme_option( 'currency' );
			$ids             = explode( ',', $ids );
			$qnts            = explode( ',', $qnts );
			$cart            = array();
			$product_sum     = 0;
			$discount_sum    = 0;
			$coupon_discount = 0;
			if ( $ids ) {
				if ( $coupon ) {
					$coupon_id = get_coupon_id( $coupon );
					if ( $coupon_id !== false ) {
						$coupon_discount = carbon_get_post_meta( $coupon_id, 'coupon_discount' );
					}
				}
				foreach ( $ids as $index => $id ) {
					$qnt          = $qnts[ $index ];
					$price        = carbon_get_post_meta( $id, 'product_price' );
					$product_unit = carbon_get_post_meta( $id, 'product_unit' );
					$sub_sum      = $price * $qnt;
					$product_sum  = $product_sum + $sub_sum;
					if ( $coupon_discount ) {
						$discount     = ( $coupon_discount * $sub_sum ) / 100;
						$discount_sum = $discount_sum + $discount;
					}
					$cart[]         = array(
						'image' => get_post_thumbnail_id( $id ),
						'title' => get_the_title( $id ),
						'id'    => $id,
						'qnt'   => $qnt,
						'price' => $price . $currency . ' / ' . $product_unit,
						'sum'   => $sub_sum,
					);
					$post_author_id = get_post_field( 'post_author', $id );
					if ( $post_author_id ) {
						$post_author = get_user_by( 'ID', $post_author_id );
						if ( ! in_array( $post_author->user_email, $emails ) ) {
							$emails[] = $post_author->user_email;
						}
					}
				}
			}
			carbon_set_post_meta( $_id, 'order_cart', $cart );
			carbon_set_post_meta( $_id, 'order_product_sum', $product_sum );
			$product_sum = $discount_sum ? ( $product_sum - $discount_sum ) : $product_sum;
			carbon_set_post_meta( $_id, 'order_sum', $product_sum );
			carbon_set_post_meta( $_id, 'order_user_name', $name . ( $surname ? ' ' . $surname : '' ) );
			carbon_set_post_meta( $_id, 'order_user_tel', $tel );
			carbon_set_post_meta( $_id, 'order_user_city', $city );
			carbon_set_post_meta( $_id, 'order_user_email', $email );
			carbon_set_post_meta( $_id, 'order_seller_id', $order_seller_id );
			carbon_set_post_meta( $_id, 'order_delivery_method', $delivery_method );
			carbon_set_post_meta( $_id, 'order_delivery_address', $address );
			carbon_set_post_meta( $_id, 'order_post_office', $post_office );
			carbon_set_post_meta( $_id, 'order_payment_method', $payment_method );
			if ( $coupon ) {
				carbon_set_post_meta( $_id, 'order_promo', $promo );
				carbon_set_post_meta( $_id, 'order_discount', $discount_sum );
			}
			if ( $type == 'quick_order' ) {
				$res['msg'] = 'Дякуємо за замовлення! ';
			} else {
				$page = carbon_get_theme_option( 'thanks_page' );
				if ( $page ) {
					$page       = $page[0]['id'];
					$res['url'] = get_the_permalink( $page ) . '?order_id=' . $_id . '&hash=' . base64_encode( $_id );
				} else {
					$res['msg'] = 'Дякуємо за замовлення! ';
				}
			}
			$res['type']        = 'success';
			$var                = variables();
			$set                = $var['setting_home'];
			$assets             = $var['assets'];
			$url                = $var['url'];
			$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
			$_l                 = $personal_area_page ? get_the_permalink( $personal_area_page[0]['id'] ) : $url;
			if ( $emails ) {
				$_l           = $_l . '?route=history&subpage=sales';
				$message_link = "<a href='$_l' target='_blank'>персональний кабінет</a>";
				$message_text = "<strong>Вітаємо!</strong><br> У вас нове замовлення! Перейдіть в $message_link для уточнення деталей.";
				foreach ( $emails as $_email ) {
					send_message( $message_text, $_email, 'Нове замовлення №' . $_id );
				}
			}
			if ( $email ) {
				$_l           = $_l . '?route=history';
				$message_link = "<a href='$_l' target='_blank'>персональний кабінет</a>";
				$message_text = "<strong>Дякуємо за замовлення!</strong><br> Перейдіть в $message_link для перегляду деталей.";
				send_message( $message_text, $email, 'Ваше замовлення №' . $_id );
			}
		} else {
			$res['msg']  = 'Помилка! ';
			$res['type'] = 'error';
		}
	} else {
		$res['msg']  = 'Помилка, спробуйте ще раз! ';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_set_coupon', 'set_coupon' );
add_action( 'wp_ajax_set_coupon', 'set_coupon' );
function set_coupon() {
	$res    = array();
	$coupon = $_POST['promo'] ?? '';
	if ( $coupon ) {
		$coupon_id = get_coupon_id( $coupon );
		if ( $coupon_id !== false ) {
			$coupon_discount = carbon_get_post_meta( $coupon_id, 'coupon_discount' );
			if ( $coupon_discount ) {
				$res['type']     = 'success';
				$res['discount'] = $coupon_discount;
				$res['coupon']   = $coupon;
				$res['msg']      = "Скидка купона -$coupon_discount%";
			} else {
				$res['msg']  = 'Помилка, купон не активний! ';
				$res['type'] = 'error';
			}
		} else {
			$res['msg']  = 'Помилка, купон не знайдено! ';
			$res['type'] = 'error';
		}
	} else {
		$res['msg']  = 'Помилка, спробуйте ще раз! ';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_get_user_city', 'get_user_city' );
add_action( 'wp_ajax_get_user_city', 'get_user_city' );
function get_user_city() {
	$user_location = get_user_location();
	$city          = getLocaleCity( $user_location['city'] );
	echo json_encode( array(
		'user_location' => $user_location,
		'city'          => trim( "$city" ),
	) );
	die();
}

add_action( 'wp_ajax_nopriv_get_products_places', 'get_products_places' );
add_action( 'wp_ajax_get_products_places', 'get_products_places' );
function get_products_places() {
	$city = $_POST['city'] ?? '';
	$arr  = [];
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
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();
				$id        = get_the_ID();
				$c         = carbon_get_post_meta( $id, 'product_city' );
				$region    = carbon_get_post_meta( $id, 'product_region' );
				$latitude  = carbon_get_post_meta( $id, 'product_latitude' );
				$longitude = carbon_get_post_meta( $id, 'product_longitude' );
				if ( ! in_array( $c, $arr ) ) {
					$arr[] = $c;
					echo "<li class='select-user-city-js' data-latitude='$latitude' data-longitude='$longitude' data-value='$c, $region'>$c, $region</li>";
				}
			endwhile;
		else:
			echo "<li class='not-active'> Оголошень в цьому місці не знайдено</li>";
		endif;
		wp_reset_postdata();
		wp_reset_query();
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_products_addresses', 'get_products_addresses' );
add_action( 'wp_ajax_get_products_addresses', 'get_products_addresses' );
function get_products_addresses() {
	$string = $_POST['string'] ?? '';
	$arr    = [];
	echo "<li class='select-filter-address-js' data-value='all'><strong>Вся Україна</strong></li>";
	if ( $string ) {
		$args  = array(
			'post_type'      => 'products',
			'post_status'    => 'publish',
			'posts_per_page' => 10,
			'meta_key'       => '_product_city',
			'meta_value'     => $string,
			'meta_compare'   => 'LIKE'
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			while ( $query->have_posts() ) :
				$query->the_post();
				$c      = carbon_get_post_meta( get_the_ID(), 'product_city' );
				$region = carbon_get_post_meta( get_the_ID(), 'product_region' );
				if ( ! in_array( $c, $arr ) ) {
					$arr[] = $c;
					echo "<li class='select-filter-address-js' data-value='$c, $region'>$c, $region</li>";
				}
			endwhile;
		else:
			echo "<li class='not-active'>Оголошень в цьому місці не знайдено</li>";
		endif;
		wp_reset_postdata();
		wp_reset_query();
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_phone_numbers', 'get_phone_numbers' );
add_action( 'wp_ajax_get_phone_numbers', 'get_phone_numbers' );
function get_phone_numbers() {
	$id   = $_POST['id'] ?? '';
	$user = $_POST['user'] ?? '';
	if ( $id && get_post( $id ) ) {
		$product_user_phone = carbon_get_post_meta( $id, 'product_user_phone' );
		if ( $product_user_phone ) {
			$link = get_phone_link( $product_user_phone );
			?>
            <a class="btn_st b_yelloow " href="<?php echo $link; ?>">
                <span class="show_tel_text"><?php echo $product_user_phone; ?></span>
            </a>
			<?php
		} else {
			$author_id  = get_post_field( 'post_author', $id );
			$user_phone = carbon_get_user_meta( $author_id, 'user_phone' );
			if ( $user_phone ):
				$link = get_phone_link( $user_phone );
				?>
                <a class="btn_st b_yelloow " href="<?php echo $link; ?>">
                    <span class="show_tel_text"><?php echo $user_phone; ?></span>
                </a>
			<?php
			endif;
		}
	} elseif ( $user ) {
		$user_company_phone = carbon_get_user_meta( $user, 'user_company_phone' );
		if ( $user_company_phone ) {
			$link = get_phone_link( $user_company_phone );
			?>
            <a class="btn_st" href="<?php echo $link; ?>">
                <span class=""><?php echo $user_company_phone; ?></span>
            </a>
			<?php
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_create_new_user', 'create_new_user' );
add_action( 'wp_ajax_create_new_user', 'create_new_user' );
function create_new_user() {
	$res        = array();
	$first_name = $_POST['first_name'] ?? '';
	$last_name  = $_POST['last_name'] ?? '';
	$email      = $_POST['email'] ?? '';
	$tel        = $_POST['tel'] ?? '';
	$password   = $_POST['password'] ?? '';
	if ( $email && $tel && $password ) {
		if ( $user_id = email_exists( $email ) ) {
			$res['type']    = 'error';
			$res['user_id'] = $user_id;
			$res['msg']     = 'Email вже занятий';
		} else {
			$user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
			$user_city         = $_COOKIE['user_city'] ?? '';
			$latitude          = $_COOKIE['latitude'] ?? '';
			$longitude         = $_COOKIE['longitude'] ?? '';
			$is_fop            = $_COOKIE['is_fop'] ?? '';
			$_user_id          = wp_create_user( $email, $password, $email );
			$res['type']       = 'success';
			$res['user_id']    = $_user_id;
			$res['msg']        = 'Вас зареєстровано';
			carbon_set_user_meta( $_user_id, 'user_phone', $tel );
			carbon_set_user_meta( $_user_id, 'user_city', $user_confirm_city ?: $user_city );
			setcookie( "is_fop", '', time() - 3600, '/' );
			$route              = '?email=' . $email;
			$args               = array(
				'ID' => $user_id,
			);
			$args['first_name'] = $first_name;
			$args['last_name']  = $last_name;
			wp_update_user( $args );
			if ( $is_fop == 'true' ) {
				carbon_set_user_meta( $_user_id, 'user_fop', true );
			}
			if ( $login_page = carbon_get_theme_option( 'login_page' ) ) {
				$res['url'] = get_permalink( $login_page[0]['id'] ) . $route;
			}
			$var        = variables();
			$set        = $var['setting_home'];
			$assets     = $var['assets'];
			$url        = $var['url'];
			$url_home   = $var['url_home'];
			$admin_ajax = $var['admin_ajax'];
			$link       = $res['url'] ?: $url;
			$m          = "Вітаємо!<br> Для авторизації на сайті перейдіть за <a href='$link'>посиланням</a>";
			if ( $m = carbon_get_post_meta( $set, 'register_letter' ) ) {
				$m = _t( str_replace( '%url%', $link, $m ), 1 );
			}
			send_message( $m, $email, 'Успішна реєстрація на сайті ' . get_bloginfo( 'name' ) );
			$r           = create_zoho_user( array(
				'first_name' => $first_name,
				'last_name'  => $last_name,
				'email'      => $email,
				'phone'      => $tel,
				'id'         => $_user_id,
				'city'       => $user_confirm_city ?: $user_city,
			) );
			$res['zoho'] = $r;
		}
	} else {
		$res['msg']  = 'Всі поля обовязкові для заповнення';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_sign_in_user', 'sign_in_user' );
add_action( 'wp_ajax_sign_in_user', 'sign_in_user' );
function sign_in_user() {
	$time     = time();
	$res      = array();
	$var      = variables();
	$set      = $var['setting_home'];
	$assets   = $var['assets'];
	$url      = $var['url'];
	$email    = trim( $_POST['email'] ?? '' );
	$password = $_POST['password'] ?? '';
	if ( $email && $password ) {
		$user = wp_signon( array(
			'user_login'    => $email,
			'user_password' => $password,
			'remember'      => true,
		) );
		if ( is_wp_error( $user ) ) {
			$res['type'] = 'error';
			if ( $user_id = email_exists( $email ) ) {
				$res['msg'] = 'Невірний пароль';
			} else {
				$res['msg'] = 'Невірний email';
			}
		} else {
			$user_id          = email_exists( $email );
			$res['type']      = 'success';
			$res['user_id']   = $user_id;
			$res['is_reload'] = 'true';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Заповніть поля, щоб увійти';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_forgot_password', 'forgot_password' );
add_action( 'wp_ajax_forgot_password', 'forgot_password' );
function forgot_password() {
	$res       = array();
	$email     = trim( $_POST['email'] );
	$logged_in = is_user_logged_in();
	if ( $email ) {
		$user_id = email_exists( $email );
		if ( $user_id ) {
			$login_page     = carbon_get_theme_option( 'login_page' );
			$res['type']    = 'success';
			$res['user_id'] = $user_id;
			$res['msg']     = 'Перевірте поштову скриньку';
			if ( $login_page ) {
				$res['url'] = get_the_permalink( $login_page[0]['id'] ) . '?email=' . $email;
			}
			send_reset_password( $user_id );
			if ( $logged_in ) {
				wp_logout();
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Невірний email';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Введіть електронну адресу свого облікового запису, і ми надішлемо вам новий пароль';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_upload_avatar', 'upload_avatar' );
add_action( 'wp_ajax_upload_avatar', 'upload_avatar' );
function upload_avatar() {
	$user_id = get_current_user_id();
	$res     = array();
	if ( $user_id ) {
		$files         = $_FILES["upfile"];
		$arr           = array();
		$res['$files'] = $files;
		foreach ( $files['name'] as $key => $value ) {
			if ( $files['name'][ $key ] ) {
				$file   = array(
					'name'     => $files['name'][ $key ],
					'type'     => $files['type'][ $key ],
					'tmp_name' => $files['tmp_name'][ $key ],
					'error'    => $files['error'][ $key ],
					'size'     => $files['size'][ $key ]
				);
				$_FILES = array( "file" => $file );
				foreach ( $_FILES as $file => $array ) {
					$arr[] = my_handle_attachment( $file );
				}
			}
		}
		carbon_set_user_meta( $user_id, 'user_avatar', $arr[0] );
		$res['user_avatar'] = _u( $arr[0], 1 );
		$res['type']        = 'success';
	} else {
		$res['type']      = 'error';
		$res['msg']       = 'Авторизуйтесь';
		$res['is_reload'] = 'true';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_delete_avatar', 'delete_avatar' );
add_action( 'wp_ajax_delete_avatar', 'delete_avatar' );
function delete_avatar() {
	$user_id = get_current_user_id();
	if ( $user_id ) {
		$user_avatar = carbon_get_user_meta( $user_id, 'user_avatar' );
		if ( $user_avatar ) {
			wp_delete_attachment( $user_avatar, true );
			carbon_set_user_meta( $user_id, 'user_avatar', '' );
		}
	}
	echo get_avatar_url( $user_id );
	die();
}

add_action( 'wp_ajax_nopriv_change_password', 'change_password' );
add_action( 'wp_ajax_change_password', 'change_password' );
function change_password() {
	$user_id      = get_current_user_id();
	$old_password = $_POST['old_password'] ?? '';
	$new_password = $_POST['new_password'] ?? '';
	$res          = array();
	if ( $user_id ) {
		if ( $old_password && $new_password ) {
			$user = get_user_by( 'ID', $user_id );
			if ( $user && wp_check_password( $old_password, $user->data->user_pass, $user->ID ) ) {
				wp_set_password( $new_password, $user_id );
				$res['type'] = 'success';
				$res['msg']  = 'Пароль змінено';
				wp_logout();
				$login_page = carbon_get_theme_option( 'login_page' );
				if ( $login_page ) {
					$res['url'] = get_the_permalink( $login_page[0]['id'] );
				} else {
					$res['is_reload'] = 'true';
				}
			} else {
				$res['type'] = 'error';
				$res['msg']  = 'Старий пароль невірний';
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Заповніть обовязкові поля';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Авторизуйтесь';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_change_user_data', 'change_user_data' );
add_action( 'wp_ajax_change_user_data', 'change_user_data' );
function change_user_data() {
	$user_id      = get_current_user_id();
	$first_name   = $_POST['first_name'] ?? '';
	$last_name    = $_POST['last_name'] ?? '';
	$user_surname = $_POST['user_surname'] ?? '';
	$city         = $_POST['city'] ?? '';
	$tel          = $_POST['tel'] ?? '';
	$email        = $_POST['email'] ?? '';
	$res          = array();
	$result       = array();
	$change_data  = array();
	if ( $user_id ) {
		$user          = get_user_by( 'ID', $user_id );
		$_first_name   = $user->first_name;
		$_last_name    = $user->last_name ?? '';
		$_user_email   = $user->user_email ?? '';
		$_user_surname = carbon_get_user_meta( $user_id, 'user_surname' );
		$_user_city    = carbon_get_user_meta( $user_id, 'user_city' );
		$_user_phone   = carbon_get_user_meta( $user_id, 'user_phone' );
		$args          = array(
			'ID' => $user_id,
		);
		if ( $_first_name != $first_name ) {
			$args['first_name'] = $first_name;
			wp_update_user( $args );
			$result[]                  = 'Імя змінено';
			$change_data['first_name'] = $first_name;
		}
		if ( $last_name != $_last_name ) {
			$change_data['last_name'] = $last_name;
			$args['last_name']        = $last_name;
			wp_update_user( $args );
			$result[] = "Прізвище змінено";
		}
		if ( $_user_surname != $user_surname ) {
			carbon_set_user_meta( $user_id, 'user_surname', $user_surname );
			$result[]               = "По-батькові змінено";
			$change_data['surname'] = $user_surname;
		}
		if ( $city != $_user_city ) {
			carbon_set_user_meta( $user_id, 'user_city', $city );
			$result[]            = "Місто змінено";
			$change_data['city'] = $city;
		}
		if ( $tel != $_user_phone ) {
			carbon_set_user_meta( $user_id, 'user_phone', $tel );
			$result[]             = "Телефон змінено";
			$change_data['phone'] = $tel;
		}
		if ( $email && $email != $_user_email ) {
			if ( email_exists( $email ) ) {
				$result[] = 'Email вже занятий';
			} else {
				$result[]           = 'Email змінений';
				$args['user_email'] = $email;
				wp_update_user( $args );
				$change_data['email'] = $email;
			}
		}
		$res['msg']  = implode( ', ', $result );
		$res['type'] = 'success';
		if ( $change_data ) {
			$change_data['id'] = $user_id;
			$result            = edit_zoho_user( $change_data );
			$res['zoho']       = $result;
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Авторизуйтесь';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_add_enterprise', 'add_enterprise' );
add_action( 'wp_ajax_add_enterprise', 'add_enterprise' );
function add_enterprise() {
	$res                    = array();
	$user_id                = get_current_user_id();
	$postcode               = $_POST['postcode'] ?? '';
	$type                   = $_POST['type'] ?? '';
	$country                = $_POST['country'] ?? '';
	$country_code           = $_POST['country_code'] ?? '';
	$city                   = $_POST['city'] ?? '';
	$region                 = $_POST['region'] ?? '';
	$lat                    = $_POST['lat'] ?? '';
	$lng                    = $_POST['lng'] ?? '';
	$address                = $_POST['address'] ?? '';
	$name                   = $_POST['name'] ?? '';
	$phone                  = $_POST['phone'] ?? '';
	$text                   = $_POST['text'] ?? '';
	$days_prefix            = $_POST['days_prefix'] ?? '';
	$delivery_types         = $_POST['delivery_types'] ?? '';
	$payment_methods        = $_POST['payment_methods'] ?? '';
	$office_type            = $_POST['office_type'] ?? '';
	$social_network         = $_POST['social_network'] ?? '';
	$social_network_urls    = $_POST['social_network_url'] ?? '';
	$application            = get_application( $user_id );
	$work_time_organization = get_work_time_json_string( $days_prefix );
	$phone                  = implode( ',', $phone );
	if ( $user_id ) {
		if ( $delivery_types ) {
			$__arr = array();
			if ( is_array( $delivery_types ) ) {
				foreach ( $delivery_types as $item ) {
					$__arr[] = $item;
				}
			} else {
				$__arr[] = $delivery_types;
			}
			carbon_set_user_meta( $user_id, 'user_delivery_methods', $__arr );
		}
		if ( $payment_methods ) {
			$__arr = array();
			if ( is_array( $payment_methods ) ) {
				foreach ( $payment_methods as $item ) {
					$__arr[] = $item;
				}
			} else {
				$__arr[] = $payment_methods;
			}
			carbon_set_user_meta( $user_id, 'user_payment_methods', $__arr );
		}
		if ( $social_network && $social_network_urls ) {
			foreach ( $social_network as $social_network_index => $network_name ) {
				$social_network_url = $social_network_urls[ $social_network_index ] ?? '';
				if ( filter_var( $social_network_url, FILTER_VALIDATE_URL ) === false ) {
					$social_network_url = false;
				}
				if ( $network_name && $social_network_url ) {
					$network_name = strtolower( $network_name );
					update_user_meta( $user_id, $network_name, $social_network_url );
				}
			}
		}
	}
	if ( $application == 0 ) {
		if ( $user_id && $city && $phone && $name ) {
			$post_data = array(
				'post_type'    => 'applications',
				'post_title'   => $name,
				'post_status'  => 'pending',
				'post_author'  => (int) $user_id,
				'post_content' => $text
			);
			$_id       = wp_insert_post( $post_data );
			$post      = get_post( $_id );
			if ( $post ) {
				carbon_set_post_meta( $_id, 'application_company_postcode', $postcode );
				carbon_set_post_meta( $_id, 'application_company_country', $country );
				carbon_set_post_meta( $_id, 'application_company_country_code', $country_code );
				carbon_set_post_meta( $_id, 'application_company_latitude', $lat );
				carbon_set_post_meta( $_id, 'application_company_longitude', $lng );
				carbon_set_post_meta( $_id, 'application_company_region', $region );
				carbon_set_post_meta( $_id, 'application_address', $address );
				carbon_set_post_meta( $_id, 'application_city', $city );
				carbon_set_post_meta( $_id, 'application_phone', $phone );
				carbon_set_post_meta( $_id, 'application_work_time_organization', $work_time_organization ?: '' );
				carbon_set_user_meta( $user_id, 'user_company_office_type', $office_type ?: '' );
				$files         = $_FILES["upfile"];
				$arr           = array();
				$res['$files'] = $files;
				foreach ( $files['name'] as $key => $value ) {
					if ( $files['name'][ $key ] ) {
						$file   = array(
							'name'     => $files['name'][ $key ],
							'type'     => $files['type'][ $key ],
							'tmp_name' => $files['tmp_name'][ $key ],
							'error'    => $files['error'][ $key ],
							'size'     => $files['size'][ $key ]
						);
						$_FILES = array( "file" => $file );
						foreach ( $_FILES as $file => $array ) {
							$arr[] = my_handle_attachment( $file );
						}
					}
				}
				carbon_set_user_meta( $user_id, 'user_company_gallery', $arr );
				$res['$arr'] = $arr;
				$res['msg']  = 'Ми розглянемо вашу заявку';
				send_message( "Користувач ID: $user_id подав заявку на створення фермерського господарства '$name'. Перейдіть в адмін-панель щоб розглянути заявку.", get_bloginfo( 'admin_email' ), 'Заявка на створення нового господарства' );
			} else {
				$res['type'] = 'error';
				$res['msg']  = 'Помилка';
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Помилка';
		}
	} else {
		if ( $type == 'edit' && $application ) {
			$post_data = array(
				'post_type'    => 'applications',
				'post_title'   => $name . ' [' . $type . ']',
				'post_status'  => 'publish',
				'post_author'  => (int) $user_id,
				'post_content' => $text
			);
			$_id       = wp_insert_post( $post_data );
			$post      = get_post( $_id );
			if ( $post ) {
				$gallery = carbon_get_user_meta( $user_id, 'user_company_gallery' );
				if ( $gallery ) {
					foreach ( $gallery as $image_id ) {
						wp_delete_attachment( $image_id );
					}
					carbon_set_user_meta( $user_id, 'user_company_gallery', array() );
				}

				carbon_set_post_meta( $_id, 'application_company_postcode', $postcode );
				carbon_set_post_meta( $_id, 'application_company_country', $country );
				carbon_set_post_meta( $_id, 'application_company_country_code', $country_code );
				carbon_set_post_meta( $_id, 'application_company_latitude', $lat );
				carbon_set_post_meta( $_id, 'application_company_longitude', $lng );
				carbon_set_post_meta( $_id, 'application_company_region', $region );

				carbon_set_post_meta( $_id, 'application_address', $address );
				carbon_set_post_meta( $_id, 'application_city', $city );
				carbon_set_post_meta( $_id, 'application_phone', $phone );

				carbon_set_post_meta( $_id, 'application_work_time_organization', $work_time_organization ?: '' );

				carbon_set_user_meta( $user_id, 'user_company_address', $address );
				carbon_set_user_meta( $user_id, 'user_company_city', $city );
				carbon_set_user_meta( $user_id, 'user_company_phone', $phone );
				carbon_set_user_meta( $user_id, 'user_company_name', $name );
				carbon_set_user_meta( $user_id, 'user_company_description', $text );

				carbon_set_user_meta( $user_id, 'user_company_postcode', $postcode );
				carbon_set_user_meta( $user_id, 'user_company_country', $country );
				carbon_set_user_meta( $user_id, 'user_company_country_code', $country_code );
				carbon_set_user_meta( $user_id, 'user_company_latitude', $lat );
				carbon_set_user_meta( $user_id, 'user_company_longitude', $lng );
				carbon_set_user_meta( $user_id, 'user_company_region', $region );

				carbon_set_user_meta( $user_id, 'user_work_time_organization', $work_time_organization ?: '' );
				carbon_set_user_meta( $user_id, 'user_company_office_type', $office_type ?: '' );

				$res['msg']    = 'Інформацію змінено';
				$files         = $_FILES["upfile"];
				$arr           = array();
				$res['$files'] = $files;
				foreach ( $files['name'] as $key => $value ) {
					if ( $files['name'][ $key ] ) {
						$file   = array(
							'name'     => $files['name'][ $key ],
							'type'     => $files['type'][ $key ],
							'tmp_name' => $files['tmp_name'][ $key ],
							'error'    => $files['error'][ $key ],
							'size'     => $files['size'][ $key ]
						);
						$_FILES = array( "file" => $file );
						foreach ( $_FILES as $file => $array ) {
							$arr[] = my_handle_attachment( $file );
						}
					}
				}
				carbon_set_user_meta( $user_id, 'user_company_gallery', $arr );
				$res['$arr']                   = $arr;
				$r                             = edit_zoho_account( array(
					'name'        => $name,
					'description' => $text,
					'region'      => $region,
					'phone'       => $phone,
					'user_id'     => $user_id,
				) );
				$res['$r']                     = $r;
				$res['work_time_organization'] = $work_time_organization;
			} else {
				$res['type'] = 'error';
				$res['msg']  = 'Помилка';
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Ми розглядаємо вашу заявку';
		}
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_change_auto_continue', 'change_auto_continue' );
add_action( 'wp_ajax_change_auto_continue', 'change_auto_continue' );
function change_auto_continue() {
	$res        = array();
	$user_id    = get_current_user_id();
	$res        = array();
	$id         = $_POST['id'] ?? '';
	$is_checked = $_POST['isChecked'] ?? 'false';
	if ( $user_id && $id ) {
		carbon_set_post_meta( (int) $id, 'product_auto_continue', ( $is_checked == 'true' ) );

	} else {
		$res['msg'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_change_product_status', 'change_product_status' );
add_action( 'wp_ajax_change_product_status', 'change_product_status' );
function change_product_status() {
	$res     = array();
	$id      = $_POST['id'] ?? '';
	$status  = $_POST['status'] ?? 'publish';
	$user_id = get_current_user_id();
	if ( $user_id && $id && get_post( $id ) ) {
		$my_post                = array();
		$my_post['ID']          = $id;
		$my_post['post_status'] = $status;
		wp_update_post( $my_post );
		$res['type'] = 'success';
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_activate_auto_renew', 'activate_auto_renew' );
add_action( 'wp_ajax_activate_auto_renew', 'activate_auto_renew' );
function activate_auto_renew() {
	$ids = $_POST['ids'];
	$res = array();
	if ( $ids ) {
		$res['type'] = 'success';
		foreach ( $ids as $id ) {
			if ( get_post( $id ) ) {
				carbon_set_post_meta( $id, 'product_auto_continue', true );
			} else {
				$res['type'] = 'error';
			}
		}
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_deactivate_products', 'deactivate_products' );
add_action( 'wp_ajax_deactivate_products', 'deactivate_products' );
function deactivate_products() {
	$res     = array();
	$ids     = $_POST['ids'] ?? '';
	$status  = $_POST['status'] ?? 'archive';
	$user_id = get_current_user_id();
	$strings = array();
	if ( $user_id && $ids ) {
		$res['type'] = 'success';
		foreach ( $ids as $id ) {
			$my_post                = array();
			$my_post['ID']          = $id;
			$my_post['post_status'] = $status;
			$id                     = wp_update_post( $my_post, true );
			if ( is_wp_error( $id ) ) {
				$strings[]   = "ID:$id [" . $id->get_error_message() . ']';
				$res['type'] = 'error';
			} else {
				carbon_set_post_meta( $id, 'product_auto_continue', false );
			}
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	if ( $strings ) {
		$res['msg'] = implode( ',<br>', $strings );
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_new_seller_review', 'new_seller_review' );
add_action( 'wp_ajax_new_seller_review', 'new_seller_review' );
function new_seller_review() {
	$res       = array();
	$user_id   = get_current_user_id();
	$name      = $_POST['name'] ?? '';
	$email     = $_POST['email'] ?? '';
	$text      = $_POST['text'] ?? '';
	$seller_id = $_POST['seller_id'] ?? '';
	$rating    = $_POST['rating'] ?? '';
	if ( $user_id ) {
		if ( $name && $text && $email && $seller_id ) {
			$review_test = get_result_test_review( $email, $text );
			if ( $review_test ) {
				$post_data = array(
					'post_type'    => 'reviews',
					'post_title'   => $name,
					'post_status'  => 'pending',
					'post_author'  => (int) $user_id,
					'post_content' => $text,
				);
				$_id       = wp_insert_post( $post_data );
				$post      = get_post( $_id );
				if ( $post ) {
					carbon_set_post_meta( $_id, 'review_seller_id', $seller_id );
					carbon_set_post_meta( $_id, 'review_author_email', $email );
					carbon_set_post_meta( $_id, 'review_rating', $rating );
					carbon_set_post_meta( $_id, 'review_user_id', $user_id );
					$res['type'] = 'success';
					$res['msg']  = 'Дякуємо за відгук! Він зʼявиться після модерації';
				} else {
					$res['type'] = 'error';
					$res['msg']  = 'Помилка';
				}
			} else {
				$res['type'] = 'error';
				$res['msg']  = 'Ви вже це казали';
			}

		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Сталась помилка спробуйте пізніше';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Щоб залишити коментар потрібно авторизуватись!';
	}

	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_cancel_order', 'cancel_order' );
add_action( 'wp_ajax_cancel_order', 'cancel_order' );
function cancel_order() {
	$res     = array();
	$user_id = get_current_user_id();
	$id      = $_POST['id'] ?? '';
	if ( $user_id && $id && get_post( $id ) ) {
		$author_id = get_post_field( 'post_author', $id );
		if ( $author_id == $user_id ) {
			$my_post                = array();
			$my_post['ID']          = $id;
			$my_post['post_status'] = 'draft';
			wp_update_post( $my_post );
			$res['type'] = 'success';
			$res['text'] = 'Скасовано';
		} else {
			$res['msg']  = 'Вам не дозволено';
			$res['type'] = 'error';
		}
	} else {
		$res['msg']  = 'Помилка';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_change_order_status', 'change_order_status' );
add_action( 'wp_ajax_change_order_status', 'change_order_status' );
function change_order_status() {
	$res     = array();
	$user_id = get_current_user_id();
	$id      = $_POST['id'] ?? '';
	$status  = $_POST['status'] ?? '';
	if ( $user_id && $id && get_post( $id ) ) {
		$author_id          = get_post_field( 'post_author', $id );
		$post_author        = get_user_by( 'ID', $author_id );
		$var                = variables();
		$set                = $var['setting_home'];
		$assets             = $var['assets'];
		$url                = $var['url'];
		$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
		$_l                 = $personal_area_page ? get_the_permalink( $personal_area_page[0]['id'] ) : $url;
		$message_text       = 'Продавець змінив статус замовлення на: ';
		carbon_set_post_meta( $id, 'delivery_status', $status );
		if ( get_post_status( $id ) != 'publish' ) {
			$my_post                = array();
			$my_post['ID']          = $id;
			$my_post['post_status'] = 'publish';
			wp_update_post( $my_post );
		}
		$res['post_status'] = get_post_status( $id );
		if ( $status != carbon_get_post_meta( $id, 'delivery_status' ) ) {
			$res['msg']  = 'Помилка';
			$res['type'] = 'error';
		}
		$res['status'] = carbon_get_post_meta( $id, 'delivery_status' );
		$order_cart    = carbon_get_post_meta( $id, 'order_cart' );
		if ( $order_cart ) {
			foreach ( $order_cart as $item ) {
				$_item_id  = $item['id'];
				$_item_qnt = $item['qnt'];
				if ( $_item_id && get_post( $_item_id ) ) {
					$product_purchased = carbon_get_post_meta( $_item_id, 'product_purchased' ) ?: 0;
					$product_purchases = carbon_get_post_meta( $_item_id, 'product_purchases' ) ?: array();
					if ( $status == 'delivered' ) {
						$product_purchased = $product_purchased + 1;
						carbon_set_post_meta( $_item_id, 'product_purchased', $product_purchased );
						$product_purchases[] = array(
							'order_id'  => $id,
							'purchased' => $_item_qnt,
						);
						carbon_set_post_meta( $_item_id, 'product_purchases', $product_purchases );
						$message_text .= ' доставлено';
					} else {
						$product_purchased = $product_purchased - 1;
						$product_purchased = max( 0, $product_purchased );
						carbon_set_post_meta( $_item_id, 'product_purchased', $product_purchased );
						$product_purchases_new = array();
						if ( $product_purchases ) {
							foreach ( $product_purchases as $purchase ) {
								if ( $purchase['order_id'] != $id ) {
									$product_purchases_new[] = $purchase;
								}
							}
						}
						carbon_set_post_meta( $_item_id, 'product_purchases', $product_purchases_new );
						$message_text .= ' в процесі';
					}
					send_message( $message_text, $post_author->user_email, 'Замовлення ID:' . $id . ' було опрацьовано продавцем' );
				}
			}
		}
	} else {
		$res['msg']  = 'Помилка';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_verification_seller', 'verification_seller' );
add_action( 'wp_ajax_verification_seller', 'verification_seller' );
function verification_seller() {
	$res     = array();
	$user_id = get_current_user_id();
	if ( $user_id && $_FILES ) {
		$current_user = get_user_by( 'ID', $user_id );
		$name         = carbon_get_user_meta( $user_id, 'user_company_name' );
		if ( ! $name ) {
			$email        = $current_user->user_email ?: '';
			$display_name = $current_user->display_name ?: '';
			$first_name   = $current_user->first_name ?: '';
			$last_name    = $current_user->last_name ?: '';
			$name         = $first_name ? ( $first_name . ' ' . $last_name ) : $display_name;
		}
		$post_data = array(
			'post_type'   => 'applications',
			'post_title'  => $name . " [verification]",
			'post_status' => 'pending',
			'post_author' => $user_id,
		);
		$_id       = wp_insert_post( $post_data );
		$post      = get_post( $_id );
		if ( $post ) {
			$files         = $_FILES["upfile"];
			$arr           = array();
			$res['$files'] = $files;
			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file   = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ]
					);
					$_FILES = array( "file" => $file );
					foreach ( $_FILES as $file => $array ) {
						$arr[] = my_handle_attachment( $file );
					}
				}
			}
			if ( $arr ) {
				$documents = array();
				foreach ( $arr as $item ) {
					$documents[] = array(
						'image' => $item,
						'url'   => wp_get_attachment_url( $item ),
					);
				}
				carbon_set_post_meta( $_id, 'application_documents', $documents );
				$res['$documents'] = $documents;
			}
			$res['$arr']      = $arr;
			$res['type']      = 'success';
			$res['is_reload'] = 'true';
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Помилка';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_add_user_email', 'add_user_email' );
add_action( 'wp_ajax_add_user_email', 'add_user_email' );
function add_user_email() {
	$res                = array();
	$user_id            = get_current_user_id();
	$email              = $_POST['email'] ?? '';
	$current_user       = get_user_by( 'ID', $user_id );
	$current_user_email = $current_user->user_email ?: '';
	if ( $user_id && $email && ( $current_user_email != $email ) ) {
		$display_name = $current_user->display_name ?: '';
		$first_name   = $current_user->first_name ?: '';
		$last_name    = $current_user->last_name ?: '';
		$name         = $first_name ? $first_name . ' ' . $last_name : $display_name;
		if ( $_user_id = email_exists( $email ) ) {
			$_current_user = get_user_by( 'ID', $_user_id );
			$_display_name = $_current_user->display_name ?: '';
			$_first_name   = $_current_user->first_name ?: '';
			$_last_name    = $_current_user->last_name ?: '';
			$_name         = $_first_name ? $_first_name . ' ' . $_last_name : $_display_name;
			$var           = variables();
			$set           = $var['setting_home'];
			$assets        = $var['assets'];
			$url           = $var['url'];
			$url_home      = $var['url_home'];
			$admin_ajax    = $var['admin_ajax'];
			$trusted_users = carbon_get_user_meta( $user_id, 'trusted_users' );
			if ( $trusted_users ) {
				foreach ( $trusted_users as $trusted_user ) {
					$trusted_user_id = $trusted_user['user_id'];
					if ( $trusted_user_id == $_user_id ) {
						$res['type'] = 'error';
						$res['msg']  = 'Помилка. Цей користувач в списку довірених.';
						echo json_encode( $res );
						die();
					}
				}
			} else {
				$trusted_users = array();
			}
			$trusted_users[] = array(
				'user_id'     => $_user_id,
				'user_status' => 'expected',
			);
			carbon_set_user_meta( $user_id, 'trusted_users', $trusted_users );
			$invitation_letter = carbon_get_post_meta( $set, 'invitation_letter' );
			$msg               = array( 'Користувача додано' );
			if ( $invitation_letter ) {
				$link              = $url . "?invites=$user_id&u=$_user_id";
				$link              = "<a target='_blank' href='$link'>Підтвердити</a>";
				$invitation_letter = str_replace( '%confirmation_link%', $link, $invitation_letter );
				$invitation_letter = str_replace( '%user_name%', $name, $invitation_letter );
				$invitation_letter = str_replace( '%name%', $_name, $invitation_letter );
				send_message( $invitation_letter, $email, 'Запрошення у управлінні оголошеннями' );
				if ( ! empty( $msg ) ) {
					$msg[] = 'а також повідомлення відправлене';
				} else {
					$msg[] = 'Повідомлення відправлене';
				}
			}
			$res['msg']       = implode( ', ', $msg );
			$res['is_reload'] = 'true';
			set_notification( array(
				'type'         => 'manager',
				'sender_id'    => $user_id,
				'recipient_id' => $_user_id,
			) );
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Помилка. Користувача із такою поштою не існує.';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_remove_trusted_user', 'remove_trusted_user' );
add_action( 'wp_ajax_remove_trusted_user', 'remove_trusted_user' );
function remove_trusted_user() {
	$res          = array();
	$user_id      = get_current_user_id();
	$ID           = $_POST['id'] ?? '';
	$current_user = get_user_by( 'ID', $user_id );
	if ( $user_id && $ID && $current_user ) {
		$trusted_users = carbon_get_user_meta( $user_id, 'trusted_users' );
		$new_array     = array();
		$msg           = array();
		if ( $trusted_users ) {
			foreach ( $trusted_users as $trusted_user ) {
				$trusted_user_id = $trusted_user['user_id'];
				if ( $trusted_user_id != $ID ) {
					$new_array[] = $trusted_user;
				} else {
					$msg[] = "Користувача із ID:$trusted_user_id видалино";
				}
			}
		}
		carbon_set_user_meta( $user_id, 'trusted_users', $new_array );
		$res['msg'] = implode( '; <br> ', $msg );
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_change_user_status', 'change_user_status' );
add_action( 'wp_ajax_change_user_status', 'change_user_status' );
function change_user_status() {
	$res          = array();
	$user_id      = get_current_user_id();
	$ID           = $_POST['id'] ?? '';
	$isChecked    = $_POST['isChecked'] ?? '';
	$current_user = get_user_by( 'ID', $user_id );
	if ( $user_id && $ID && $current_user ) {
		$trusted_users = carbon_get_user_meta( $user_id, 'trusted_users' );
		$new_array     = array();
		if ( $trusted_users ) {
			foreach ( $trusted_users as $trusted_user ) {
				$trusted_user_id = $trusted_user['user_id'];
				if ( $trusted_user_id == $ID ) {
					$new_array[] = array(
						'user_id'     => $trusted_user_id,
						'user_status' => $isChecked == 'true' ? 'active' : 'not_active',
					);
					$res['text'] = $isChecked == 'true' ? 'Активний' : 'Не активний';
				} else {
					$new_array[] = $trusted_user;
				}
			}
		}
		carbon_set_user_meta( $user_id, 'trusted_users', $new_array );
		$res['type'] = 'success';
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_get_correspondence_link', 'get_correspondence_link' );
add_action( 'wp_ajax_get_correspondence_link', 'get_correspondence_link' );
function get_correspondence_link() {
	$res     = array();
	$user_id = get_current_user_id();
	$ID      = $_POST['product_id'] ?? '';
	$userID  = $_POST['user_id'] ?? '';
	if ( $user_id && $ID ) {
		$correspondence_id = get_correspondence_id( $ID, $user_id );
		if ( $correspondence_id ) {
			$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
			if ( $personal_area_page ) {
				$res['url'] = get_the_permalink( $personal_area_page[0]['id'] ) . '?route=message&correspondence=' . $correspondence_id;
			} else {
				$var        = variables();
				$set        = $var['setting_home'];
				$assets     = $var['assets'];
				$url        = $var['url'];
				$url_home   = $var['url_home'];
				$admin_ajax = $var['admin_ajax'];
				$res['url'] = $url . '?route=message&correspondence=' . $correspondence_id;
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Помилка';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_new_message', 'new_message' );
add_action( 'wp_ajax_new_message', 'new_message' );
function new_message() {
	$correspondence = $_POST['correspondence'] ?? '';
	$text           = $_POST['text'] ?? '';
	$recipient_id   = $_POST['message_recipient_id'] ?? '';
	$sender_id      = get_current_user_id();
	if ( $correspondence && $sender_id ) {
		$message_product_id = carbon_get_post_meta( $correspondence, 'message_product_id' );
		$post_data          = array(
			'post_type'    => 'message',
			'post_title'   => 'message',
			'post_status'  => 'publish',
			'post_parent'  => $correspondence,
			'post_content' => base64_encode( $text ),
		);
		$_id                = wp_insert_post( $post_data, true );
		$post               = get_post( $_id );
		if ( ! is_wp_error( $_id ) && $post ) {
			carbon_set_post_meta( $_id, 'message_sender_id', $sender_id );
			carbon_set_post_meta( $_id, 'message_recipient_id', $recipient_id );
			carbon_set_post_meta( $_id, 'message_product_id', $message_product_id );
			$files = $_FILES["upfile"];
			$arr   = array();
			foreach ( $files['name'] as $key => $value ) {
				if ( $files['name'][ $key ] ) {
					$file   = array(
						'name'     => $files['name'][ $key ],
						'type'     => $files['type'][ $key ],
						'tmp_name' => $files['tmp_name'][ $key ],
						'error'    => $files['error'][ $key ],
						'size'     => $files['size'][ $key ]
					);
					$_FILES = array( "file" => $file );
					foreach ( $_FILES as $file => $array ) {
						$arr[] = my_handle_attachment( $file );
					}
					carbon_set_post_meta( $_id, 'message_media', _u( $arr[0], 1 ) );
				}
			}
			the_correspondence_messages( $correspondence );
			$notification_id = set_notification( array(
				'type'         => 'message',
				'product_id'   => $message_product_id,
				'sender_id'    => $sender_id,
				'recipient_id' => $recipient_id,
				'message_id'   => $_id,
			) );
			if ( $notification_id ) {
				carbon_set_post_meta( $_id, 'message_notification_id', $notification_id );
			}
		} else {
			if ( is_wp_error( $_id ) ) {
				echo $_id->get_error_message();
			}
		}
	} else {
		echo 'Помилка';
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_messages_page', 'get_messages_page' );
add_action( 'wp_ajax_get_messages_page', 'get_messages_page' );
function get_messages_page() {
	$correspondence = $_POST['correspondence'] ?? '';
	$paged          = $_POST['paged'] ?? 1;
	if ( $correspondence ) {
		the_correspondence_messages( $correspondence, $paged );
	}
	die();
}

add_action( 'wp_ajax_nopriv_remove_correspondence', 'remove_correspondence' );
add_action( 'wp_ajax_remove_correspondence', 'remove_correspondence' );
function remove_correspondence() {
	$correspondence = $_POST['correspondence'] ?? '';
	if ( $correspondence ) {
		$args  = array(
			'post_type'      => 'message',
			'posts_per_page' => - 1,
			'post_status'    => 'publish',
			'post_parent'    => $correspondence
		);
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) {
			while ( $query->have_posts() ) {
				$query->the_post();
				$_id = get_the_ID();
				wp_delete_post( $_id );
			}
		}
		wp_delete_post( $correspondence );
		wp_reset_postdata();
		wp_reset_query();
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_notifications_number', 'get_notifications_number' );
add_action( 'wp_ajax_get_notifications_number', 'get_notifications_number' );
function get_notifications_number() {
	echo get_notifications_count();
	die();
}

add_action( 'wp_ajax_nopriv_remove_notification', 'remove_notification' );
add_action( 'wp_ajax_remove_notification', 'remove_notification' );
function remove_notification() {
	$id = $_POST['notification'] ?? '';
	if ( $id && get_post( $id ) ) {
		$res = wp_delete_post( $id );
		echo json_encode( $res );
	} else {
		$user_id = get_current_user_id();
		if ( $user_id ) {
			$args  = array(
				'post_type'      => 'notifications',
				'posts_per_page' => - 1,
				'post_status'    => 'publish',
				'meta_query'     => array(
					array(
						'key'   => '_notification_recipient_id',
						'value' => $user_id
					),
				)
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ) {
				while ( $query->have_posts() ) {
					$query->the_post();
					$_id = get_the_ID();
					wp_delete_post( $_id );
				}
			}
			wp_reset_postdata();
			wp_reset_query();
			?>
            <div class="text-group">
                <h6>Сповіщень поки немає</h6>
            </div>
			<?php
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_new_packages_order', 'new_packages_order' );
add_action( 'wp_ajax_new_packages_order', 'new_packages_order' );
function new_packages_order() {
	$res     = array();
	$post    = $_POST;
	$order   = $post['order'] ?? '';
	$product = $post['product'] ?? array();
	$errors  = array();
	if ( $order ) {
		$time         = time();
		$order_string = stripcslashes( $order );
		$order        = json_decode( $order_string, true );
		$user_id      = get_current_user_id();
		$sum          = 0;
		$array        = array();
		if ( $order ) {
			foreach ( $order as $key => $item ) {
				$ID = $item['ID'] ?? '';
				if ( $ID && get_post( $ID ) ) {
					$val         = $item['val'] ?? '';
					$count       = $item['count'] ?? '';
					$placesList  = $item['placesList'] ?? '';
					$product_id  = $product[ $key ] ?? '';
					$date        = $post[ $key ] ?? '';
					$date        = $date ? strtotime( "$date 00:00" ) : '';
					$regions_qnt = carbon_get_post_meta( $ID, 'service_regions_qnt' );
					$qnt_suffix  = carbon_get_post_meta( $ID, 'service_qnt_suffix' ) ?: '';
					$sub_sum     = carbon_get_post_meta( $ID, 'service_full_price' ) * $val;
					$str         = " [Вся країна]";
					$placesList  = $placesList ? explode( ',', $placesList ) : array();
					if ( is_int( $count ) ) {
						$count   = (int) $count;
						$sub_sum = carbon_get_post_meta( $ID, 'service_price' ) * $val * $count;
						$str     = '';
					}
					$sum     = $sum + $sub_sum;
					$array[] = array(
						'name'       => get_the_title( $ID ) . $str,
						'qnt'        => $val,
						'date'       => $date ?? '',
						'sub_sum'    => $sub_sum,
						'product_id' => $product_id,
						'service_id' => $ID,
						'regions'    => $item['placesList'] ?? '',
					);
					if ( $product_id && get_post( $product_id ) ) {
						$product_id         = (int) $product_id;
						$term               = carbon_get_post_meta( $ID, 'service_term' ) ?: 1;
						$service_top        = carbon_get_post_meta( $ID, 'service_top' );
						$service_vip        = carbon_get_post_meta( $ID, 'service_vip' );
						$service_urgently   = carbon_get_post_meta( $ID, 'service_urgently' );
						$service_boost      = carbon_get_post_meta( $ID, 'service_boost' );
						$term_number        = $term * 86400;
						$term_end           = ( $date ?: $time ) + $term_number;
						$res['date']        = $date;
						$res['term']        = $term;
						$res['term_number'] = $term_number;
						$res['term_end']    = $term_end;
						if ( $service_top ) {
							carbon_set_post_meta( $product_id, 'product_is_top', 'top' );
							carbon_set_post_meta( $product_id, 'product_start_top', $date );
							carbon_set_post_meta( $product_id, 'product_end_top', $term_end );
							if ( $placesList && is_int( $count ) ) {
								$regions = array();
								foreach ( $placesList as $place ) {
									if ( $term = get_term_by( 'id', (int) $place, 'regions' ) ) {
										$regions[] = (int) $place;
									}
								}
								wp_set_post_terms( $product_id, $regions, 'regions', true );
							} elseif ( ! $placesList && ! is_int( $count ) ) {
								$regions       = array();
								$regions_terms = get_terms( array(
									'hide_empty' => false,
									'taxonomy'   => 'regions',
								) );
								if ( $regions_terms ) {
									foreach ( $regions_terms as $region ) {
										$regions[] = $region->term_id;
									}
									wp_set_post_terms( $product_id, $regions, 'regions', true );
								}
							}
						}
						if ( $service_vip ) {
							carbon_set_post_meta( $product_id, 'product_is_vip', 'vip' );
							carbon_set_post_meta( $product_id, 'product_start_vip', $date );
							carbon_set_post_meta( $product_id, 'product_end_vip', $term_end );
							if ( $placesList && is_int( $count ) ) {
								$regions = array();
								foreach ( $placesList as $place ) {
									if ( $term = get_term_by( 'id', (int) $place, 'regions' ) ) {
										$regions[] = (int) $place;
									}
								}
								wp_set_post_terms( $product_id, $regions, 'regions', true );
							} elseif ( ! $placesList && ! is_int( $count ) ) {
								$regions       = array();
								$regions_terms = get_terms( array(
									'hide_empty' => false,
									'taxonomy'   => 'regions',
								) );
								if ( $regions_terms ) {
									foreach ( $regions_terms as $region ) {
										$regions[] = $region->term_id;
									}
									wp_set_post_terms( $product_id, $regions, 'regions', true );
								}
							}
						}
						if ( $service_urgently ) {
							carbon_set_post_meta( $product_id, 'product_is_urgently', 'urgently' );
							carbon_set_post_meta( $product_id, 'product_start_urgently', $date );
							carbon_set_post_meta( $product_id, 'product_end_urgently', $term_end );
							if ( $placesList && is_int( $count ) ) {
								$regions = array();
								foreach ( $placesList as $place ) {
									if ( $term = get_term_by( 'id', (int) $place, 'regions' ) ) {
										$regions[] = (int) $place;
									}
								}
								wp_set_post_terms( $product_id, $regions, 'regions', true );
							} elseif ( ! $placesList && ! is_int( $count ) ) {
								$regions       = array();
								$regions_terms = get_terms( array(
									'hide_empty' => false,
									'taxonomy'   => 'regions',
								) );
								if ( $regions_terms ) {
									foreach ( $regions_terms as $region ) {
										$regions[] = $region->term_id;
									}
									wp_set_post_terms( $product_id, $regions, 'regions', true );
								}
							}
						}
						if ( $service_boost ) {
							$current_datetime = current_time( 'mysql', false );
							$update_post_data = array(
								'ID'            => $product_id,
								'post_date'     => $current_datetime,
								'post_date_gmt' => get_gmt_from_date( $current_datetime ),
							);
							wp_update_post( $update_post_data );
							carbon_set_post_meta( $product_id, 'product_boost_time', $time );
							if ( $placesList && is_int( $count ) ) {
								$regions = array();
								foreach ( $placesList as $place ) {
									if ( $term = get_term_by( 'id', (int) $place, 'regions' ) ) {
										$regions[] = (int) $place;
									}
								}
								wp_set_post_terms( $product_id, $regions, 'regions', true );
							} elseif ( ! $placesList && ! is_int( $count ) ) {
								$regions       = array();
								$regions_terms = get_terms( array(
									'hide_empty' => false,
									'taxonomy'   => 'regions',
								) );
								if ( $regions_terms ) {
									foreach ( $regions_terms as $region ) {
										$regions[] = $region->term_id;
									}
									wp_set_post_terms( $product_id, $regions, 'regions', true );
								}
							}
						}
					}
				}
			}
			if ( $array ) {
				$post_data = array(
					'post_type'   => 'purchased',
					'post_title'  => 'Замовлення послуги',
					'post_status' => 'pending',
					'post_author' => $user_id,
				);
				$_id       = wp_insert_post( $post_data );
				if ( $post = get_post( $_id ) ) {
					carbon_set_post_meta( $_id, 'purchased_order', $array );
					carbon_set_post_meta( $_id, 'purchased_sum', $sum );
					$res['type'] = 'success';
					if ( $errors ) {
						$res['msg'] = implode( ', ', $errors );
					}
					if ( $personal_area_page = carbon_get_theme_option( 'personal_area_page' ) ) {
						$personal_area_page = $personal_area_page[0]['id'];
						$res['url']         = get_the_permalink( $personal_area_page ) . '?route=purchased';
					}
				} else {
					$res['msg']  = 'Помилка, спробуйте ще раз! ';
					$res['type'] = 'error';
				}
			} else {
				$res['msg']  = 'Помилка, спробуйте ще раз! ';
				$res['type'] = 'error';
			}
		} else {
			$res['msg']  = 'Помилка, спробуйте ще раз! ';
			$res['type'] = 'error';
		}
	} else {
		$res['msg']  = 'Помилка, спробуйте ще раз! ';
		$res['type'] = 'error';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_delete_purchased', 'delete_purchased' );
add_action( 'wp_ajax_delete_purchased', 'delete_purchased' );
function delete_purchased() {
	$index = $_POST['index'] ?? 0;
	$id    = $_POST['id'] ?? 0;
	if ( $id && get_post( $id ) ) {
		$user_id   = get_current_user_id();
		$author_id = get_post_field( 'post_author', $id );
		if ( $user_id == $author_id ) {
			if ( $purchased_order = carbon_get_post_meta( $id, 'purchased_order' ) ) {
				$new_array = array();
				foreach ( $purchased_order as $key => $item ) {
					if ( $key != $index ) {
						$new_array[] = $item;
					}
				}
				carbon_set_post_meta( $id, 'purchased_order', $new_array );
				$purchased_order = carbon_get_post_meta( $id, 'purchased_order' );
				if ( count( $purchased_order ) == 0 ) {
					wp_delete_post( $id );
				}
			}
			the_purchased();
		} else {
			echo 'Вам не дозволено редагувати замовлення!';
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_set_purchased_status', 'set_purchased_status' );
add_action( 'wp_ajax_set_purchased_status', 'set_purchased_status' );
function set_purchased_status() {
	$status = $_POST['status'] ?? 'deactivate';
	$index  = $_POST['index'] ?? 0;
	$id     = $_POST['id'] ?? 0;
	if ( $id && get_post( $id ) ) {
		$time      = time();
		$user_id   = get_current_user_id();
		$author_id = get_post_field( 'post_author', $id );
		if ( $user_id == $author_id ) {
			if ( $purchased_order = carbon_get_post_meta( $id, 'purchased_order' ) ) {
				$new_array = array();
				$remains   = 0;
				foreach ( $purchased_order as $key => $item ) {
					if ( $key == $index ) {
						$temp                  = $item;
						$product_id            = $temp['product_id'];
						$service_id            = $temp['service_id'];
						$date                  = $temp['date'];
						$temp['is_not_active'] = ( $status == 'deactivate' ) ? true : false;
						$stops                 = $temp['stops'] ?: array();
						$stops_check           = $stops;
						if ( $product_id && get_post( $product_id ) && $service_id && get_post( $service_id ) ) {
							$service_top      = carbon_get_post_meta( $service_id, 'service_top' );
							$service_vip      = carbon_get_post_meta( $service_id, 'service_vip' );
							$service_urgently = carbon_get_post_meta( $service_id, 'service_urgently' );
							$service_term     = carbon_get_post_meta( $service_id, 'service_term' );
							$term_number      = $service_term * 86400;
							if ( $status == 'deactivate' ) {
								if ( $service_top ) {
									$product_start_top = carbon_get_post_meta( $product_id, 'product_start_top' );
									$product_end_top   = carbon_get_post_meta( $product_id, 'product_end_top' );
									carbon_set_post_meta( $product_id, 'product_end_top', $time );
									if ( $remains == 0 ) {
										if ( $product_start_top > $time ) {
											$remains = $product_end_top - $product_start_top;
										} else {
											$remains = $product_end_top - $time;
										}
									}
								}
								if ( $service_vip ) {
									$product_start_vip = carbon_get_post_meta( $product_id, 'product_start_vip' );
									$product_end_vip   = carbon_get_post_meta( $product_id, 'product_end_vip' );
									carbon_set_post_meta( $product_id, 'product_end_vip', $time );
									if ( $remains == 0 ) {
										if ( $product_start_vip > $time ) {
											$remains = $product_end_vip - $product_start_vip;
										} else {
											$remains = $product_end_vip - $time;
										}
									}
								}
								if ( $service_urgently ) {
									$product_start_urgently = carbon_get_post_meta( $product_id, 'product_start_urgently' );
									$product_end_urgently   = carbon_get_post_meta( $product_id, 'product_end_urgently' );
									carbon_set_post_meta( $product_id, 'product_end_urgently', $time );
									if ( $remains == 0 ) {
										if ( $product_start_urgently > $time ) {
											$remains = $product_end_urgently - $product_start_urgently;
										} else {
											$remains = $product_end_urgently - $time;
										}
									}
								}
							} else {
								$last            = $stops_check[ count( $stops_check ) - 1 ];
								$last_date_start = $last['date_start'];
								$last_date_stop  = $last['date_stop'];
								$last_remains    = $last['remains'];
								$last_term       = $term_number - $last_remains;
								$remains         = (int) $last_remains;
								$end             = ( $time + $remains );
								if ( $date > $time ) {
									$end = ( $date + $remains );
								}
								if ( $service_top ) {
									carbon_set_post_meta( $product_id, 'product_start_top', ( $date > $time ) ? $date : $time );
									carbon_set_post_meta( $product_id, 'product_end_top', $end );
								}
								if ( $service_vip ) {
									carbon_set_post_meta( $product_id, 'product_start_vip', ( $date > $time ) ? $date : $time );
									carbon_set_post_meta( $product_id, 'product_end_vip', $end );
								}
								if ( $service_urgently ) {
									carbon_set_post_meta( $product_id, 'product_start_urgently', ( $date > $time ) ? $date : $time );
									carbon_set_post_meta( $product_id, 'product_end_urgently', $end );
								}
							}
							$stops[]       = array(
								'date_stop'  => ( $status == 'deactivate' ) ? $time : '',
								'date_start' => ! ( $status == 'deactivate' ) ? $time : '',
								'remains'    => $remains,
							);
							$temp['stops'] = $stops;
						}
						$new_array[] = $temp;
					} else {
						$new_array[] = $item;
					}
				}
				carbon_set_post_meta( $id, 'purchased_order', $new_array );
				if ( $remains > 0 ) {
					if ( $status == 'deactivate' ) {
						?>
                        <a
                                href="#"
                                class="activate-purchased"
                                data-index="<?php echo $index; ?>"
                                data-id="<?php echo $id; ?>"
                        >
                            Активувати
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 13" viewBox="0 0 15 13">
                                                    <path d="M7.5 2.7c1.9 0 3.4 1.5 3.4 3.4 0 .4-.1.9-.3 1.2l2 2c1-.9 1.8-2 2.3-3.2-1.1-3-4-5.1-7.4-5.1-1 0-1.8.2-2.7.5L6.2 3c.4-.2.9-.3 1.3-.3zM.7.9l1.8 1.8C1.4 3.6.5 4.8 0 6.2c1.2 3 4.1 5.1 7.5 5.1 1 0 2.1-.2 3-.5l2.3 2.3.9-.9L1.6 0 .7.9zm3.8 3.8 1 1v.5c0 1.2.9 2.1 2.1 2.1.1 0 .3 0 .5-.1l1 1c-.5.3-1 .4-1.5.4-1.9 0-3.4-1.5-3.4-3.4-.1-.6 0-1.1.3-1.5zm2.9-.6 2.1 2.1v-.1c0-1.2-.9-2.1-2.1-2 .1 0 .1 0 0 0z"
                                                          style="fill:#fc3636"/>
                                                </svg>
                        </a>
					<?php } else { ?>
                        <a
                                href="#"
                                class="deactivate-purchased"
                                data-index="<?php echo $index; ?>"
                                data-id="<?php echo $id; ?>"
                        >
                            Деактивувати
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 15 13" viewBox="0 0 15 13">
                                                    <path d="M7.5 2.7c1.9 0 3.4 1.5 3.4 3.4 0 .4-.1.9-.3 1.2l2 2c1-.9 1.8-2 2.3-3.2-1.1-3-4-5.1-7.4-5.1-1 0-1.8.2-2.7.5L6.2 3c.4-.2.9-.3 1.3-.3zM.7.9l1.8 1.8C1.4 3.6.5 4.8 0 6.2c1.2 3 4.1 5.1 7.5 5.1 1 0 2.1-.2 3-.5l2.3 2.3.9-.9L1.6 0 .7.9zm3.8 3.8 1 1v.5c0 1.2.9 2.1 2.1 2.1.1 0 .3 0 .5-.1l1 1c-.5.3-1 .4-1.5.4-1.9 0-3.4-1.5-3.4-3.4-.1-.6 0-1.1.3-1.5zm2.9-.6 2.1 2.1v-.1c0-1.2-.9-2.1-2.1-2 .1 0 .1 0 0 0z"
                                                          style="fill:#fc3636"/>
                                                </svg>
                        </a>
						<?php
					}
				} else {
					echo 'Термін пакету минув';
				}
			}
		} else {
			echo 'Вам не дозволено редагувати замовлення!';
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_change_user_color', 'change_user_color' );
add_action( 'wp_ajax_change_user_color', 'change_user_color' );
function change_user_color() {
	$color           = $_POST['color'];
	$current_user_id = get_current_user_id();
	if ( $current_user_id ) {
		carbon_set_user_meta( $current_user_id, 'user_company_color', $color );
		echo $color;
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_service_prices', 'get_service_prices' );
add_action( 'wp_ajax_get_service_prices', 'get_service_prices' );
function get_service_prices() {
	$res   = array();
	$arg   = array(
		'post_type'      => 'services',
		'posts_per_page' => - 1,
		'post_status'    => 'publish'
	);
	$query = new WP_Query( $arg );
	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$temp           = array();
			$_id            = get_the_ID();
			$service_price  = carbon_get_post_meta( $_id, 'service_price' ) ?: 0;
			$service_prices = carbon_get_post_meta( $_id, 'service_prices' );
			$temp[1]        = array(
				'sum'     => (float) $service_price,
				'price'   => (float) $service_price,
				'qnt'     => (int) 1,
				'percent' => (int) 0,
			);
			if ( $service_prices ) {
				foreach ( $service_prices as $item ) {
					$qnt          = (int) $item['qnt'];
					$percent      = (int) $item['percent'];
					$sub_price    = $service_price - ( ( $percent * $service_price ) / 100 );
					$temp["$qnt"] = array(
						'sum'     => round( $sub_price * $qnt, 1 ),
						'price'   => round( $sub_price, 1 ),
						'qnt'     => (int) $qnt,
						'percent' => (int) $percent,
					);
				}
			}
			$res[ $_id ] = $temp;
		}
	}
	wp_reset_postdata();
	wp_reset_query();
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_checkout_service', 'checkout_service' );
add_action( 'wp_ajax_checkout_service', 'checkout_service' );
function checkout_service() {
	$res                = array();
	$user_id            = get_current_user_id();
	$regions            = $_POST['regions'] ?? '';
	$products           = $_POST['products'] ?? '';
	$start_date         = $_POST['start_date'] ?? '';
	$id                 = $_POST['id'] ?? '';
	$portmone_url       = carbon_get_theme_option( 'portmone_url' ) ?: '';
	$payee_id           = carbon_get_theme_option( 'portmone_payee_id' ) ?: '';
	$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
	$var                = variables();
	$set                = $var['setting_home'];
	$assets             = $var['assets'];
	$url                = $var['url'];
	$url_home           = $var['url_home'];
	$zoho_data          = array();
	$description        = get_the_title( $id );
	if ( $id && get_post( $id ) && $user_id ) {
		$is_top           = carbon_get_post_meta( $id, 'service_is_top' );
		$service_up       = carbon_get_post_meta( $id, 'service_up' );
		$service_urgently = carbon_get_post_meta( $id, 'service_urgently' );
		if ( $is_top || $service_up || $service_urgently ) {
			if ( $regions && $products ) {
				$titles = array();
				foreach ( $products as $product ) {
					$titles[] = get_the_title( $product );
				}
				$description .= ' для ' . implode( ', ', $titles );
				$post_data   = array(
					'post_type'   => 'purchased',
					'post_title'  => 'Замовлення послуги ' . get_the_title( $id ),
					'post_status' => 'publish',
					'post_author' => $user_id,
				);
				$_id         = wp_insert_post( $post_data );
				if ( $post = get_post( $_id ) ) {
					$time           = time();
					$id             = (int) $id;
					$term           = carbon_get_post_meta( $id, 'service_term' ) ?: 1;
					$is_urgently    = carbon_get_post_meta( $id, 'service_urgently' );
					$service_date   = carbon_get_post_meta( $id, 'service_date' );
					$count_up       = carbon_get_post_meta( $id, 'service_up' ) ?: 0;
					$count_products = count( $products );
					$count_regions  = count( $regions );
					$service_price  = get_service_price( $id, $regions );
					if ( $start_date ) {
						$start_date = strtotime( "$start_date 00:00" );
						$start_date = $start_date <= $time ? $time : $start_date;
					} else {
						$start_date = time();
					}
					$sum                   = $service_price * $count_products;
					$res['service_price']  = $service_price;
					$res['count_products'] = $count_products;
					$res['sum']            = $sum;
					carbon_set_post_meta( $_id, 'purchased_sum', $sum );
					carbon_set_post_meta( $_id, 'purchased_status', 'not_pay' );
					carbon_set_post_meta( $_id, 'purchased_name', get_the_title( $id ) );
					carbon_set_post_meta( $_id, 'purchased_date', $start_date );
					carbon_set_post_meta( $_id, 'purchased_service_id', $id );
					carbon_set_post_meta( $_id, 'purchased_product_ids', implode( ',', $products ) );
					carbon_set_post_meta( $_id, 'purchased_regions', implode( ',', $regions ) );
					$res['type'] = 'success';
					if ( $portmone_url ) {
						$success_url = $url;
						$failure_url = $url;
						$success_url = $success_url . '?hash_service=' . base64_encode( $_id ) . '&user=' . $user_id;
						$failure_url = $failure_url . '?hash_service=' . base64_encode( $_id ) . '&user=' . $user_id . '&type=error';
						$form_html   = '<form action="https://www.portmone.com.ua/gateway/" method="post">';
						$form_html   .= '<input type="hidden" name="payee_id" value="' . $payee_id . '" />';
						$form_html   .= '<input type="hidden" name="shop_order_number" value="' . $_id . '" />';
						$form_html   .= '<input type="hidden" name="bill_amount" value="' . $sum . '" />';
						$form_html   .= '<input type="hidden" name="description" value="' . $description . '" />';
						$form_html   .= '<input type="hidden" name="success_url" value="' . $success_url . '" />';
						$form_html   .= '<input type="hidden" name="failure_url" value="' . $failure_url . '" />';
						$form_html   .= '<input type="hidden" name="encoding"  value= "UTF-8" /><input type="hidden" name="exp_time"  value= "400" />';
						$form_html   .= '</form>';
						$res['form'] = $form_html;
					} else {
						if ( $personal_area_page ) {
							$personal_area_page = $personal_area_page[0]['id'];
							$res['url']         = get_the_permalink( $personal_area_page ) . '?route=advertisement';
						}
					}
				} else {
					$res['msg']  = 'Помилка, спробуйте ще раз! ';
					$res['type'] = 'error';
				}
			} else {
				$res['type'] = 'error';
				$res['msg']  = 'Виберіть регіон і/або оголошення';
			}
		} else {
			$res['type'] = 'error';
			$res['msg']  = 'Помилка';
		}
	} else {
		$res['type'] = 'error';
		$res['msg']  = 'Помилка';
	}
	echo json_encode( $res );
	die();
}

add_action( 'wp_ajax_nopriv_get_np_cities', 'get_np_cities' );
add_action( 'wp_ajax_get_np_cities', 'get_np_cities' );
function get_np_cities() {
	$val    = $_POST['val'] ?? '';
	$cities = get_nova_post_cities( $val );
	if ( $cities ) {
		$cities = $cities['data'];
		foreach ( $cities as $city ) {
			$Description               = $city['Description'];
			$DescriptionRu             = $city['DescriptionRu'];
			$SettlementTypeDescription = $city['SettlementTypeDescription'];
			$Ref                       = $city['Ref'];
			echo "<li data-ref='$Ref' data-value='$Description'>$Description [$SettlementTypeDescription]</li>";
		}
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_np_offices', 'get_np_offices' );
add_action( 'wp_ajax_get_np_offices', 'get_np_offices' );
function get_np_offices() {
	$ref     = $_POST['ref'] ?? '';
	$offices = get_nova_post_offices( $ref );
	if ( $offices ) {
		$offices = $offices['data'];
		echo '<li class="not-active">Відділення*</li>';
		foreach ( $offices as $office ) {
			$Description           = $office['Description'];
			$DescriptionRu         = $office['DescriptionRu'];
			$TotalMaxWeightAllowed = $office['TotalMaxWeightAllowed'];
			$string                = $Description;
			if ( $TotalMaxWeightAllowed && $TotalMaxWeightAllowed > 0 ) {
				$string .= " [Вага: $TotalMaxWeightAllowed кг]";
			}
			echo "<li  data-value='$Description'>$string</li>";
		}
	} else {
		echo '<li  class="not-active">Відділення відсутні</li>';
	}
	die();
}

add_action( 'wp_ajax_nopriv_get_work_time_row_html', 'get_work_time_row_html' );
add_action( 'wp_ajax_get_work_time_row_html', 'get_work_time_row_html' );
function get_work_time_row_html() {
	$index = $_POST['index'] ?? 1;
	the_work_time_row( array(
		'days_index' => $index
	) );
	die();
}

add_action( 'wp_ajax_nopriv_get_social_networks_row_html', 'get_social_networks_row_html' );
add_action( 'wp_ajax_get_social_networks_row_html', 'get_social_networks_row_html' );
function get_social_networks_row_html() {
	the_social_network_row();
	die();
}

function get_service_price( $id, $regions ) {
	$res            = 0;
	$count_regions  = count( $regions );
	$is_country     = in_array( 'country', $regions );
	$service_price  = carbon_get_post_meta( $id, 'service_price' ) ?: 0;
	$service_prices = carbon_get_post_meta( $id, 'service_prices' );
	if ( $service_prices ) {
		$max = 0;
		foreach ( $service_prices as $item ) {
			$qnt       = (int) $item['qnt'];
			$percent   = (int) $item['percent'];
			$sub_price = $service_price - ( ( $percent * $service_price ) / 100 );
			$sum       = round( $sub_price * $qnt, 1 );
			$price     = round( $sub_price, 1 );
			$qnt       = (int) $qnt;
			$percent   = (int) $percent;
			if ( $is_country ) {
				if ( $qnt > $max ) {
					$max = $qnt;
					$res = $sum;
				}
			} else {
				if ( $count_regions >= $qnt ) {
					$res = $price * $count_regions;
				}
			}
		}
	} else {
		$res = $service_price * $count_regions;
	}
	if ( $res === 0 ) {
		$res = $service_price * $count_regions;
	}


	return round( $res, 1 );
}

function set_notification( $array = array() ) {
	$notification_id = 0;
	$type            = $array['type'] ?? 'message';
	if ( $type == 'message' ) {
		$message_product_id = $array['product_id'] ?? '';
		$sender_id          = $array['sender_id'] ?? '';
		$recipient_id       = $array['recipient_id'] ?? '';
		$message_id         = $array['message_id'] ?? '';
		$notification_data  = array(
			'post_type'   => 'notifications',
			'post_title'  => '%user% залишив вам повідомлення',
			'post_status' => 'publish',
		);
		$notification_id    = wp_insert_post( $notification_data, true );
		$notification_post  = get_post( $notification_id );
		if ( ! is_wp_error( $notification_id ) && $notification_post ) {
			$notification_html = "<a href='#' data-product='$message_product_id' class='move-to-correspondence'>Відкрити переписку</a>";
			carbon_set_post_meta( $notification_id, 'notification_text', $notification_html );
			carbon_set_post_meta( $notification_id, 'notification_sender_id', $sender_id );
			carbon_set_post_meta( $notification_id, 'notification_recipient_id', $recipient_id );
			carbon_set_post_meta( $notification_id, 'notification_is_read', 'not_read' );
		}
	} elseif ( $type == 'manager' ) {
		$sender_id         = $array['sender_id'] ?? '';
		$recipient_id      = $array['recipient_id'] ?? '';
		$notification_data = array(
			'post_type'   => 'notifications',
			'post_title'  => '%user% запросив керувати оголошеннями',
			'post_status' => 'publish',
		);
		$notification_id   = wp_insert_post( $notification_data, true );
		$notification_post = get_post( $notification_id );
		if ( ! is_wp_error( $notification_id ) && $notification_post ) {
			$notification_html = "Користувач %user% запросив вас керувати своїми оголошеннями. Перейдіть на свою пошту, щоб підтвердити";
			carbon_set_post_meta( $notification_id, 'notification_text', $notification_html );
			carbon_set_post_meta( $notification_id, 'notification_sender_id', $sender_id );
			carbon_set_post_meta( $notification_id, 'notification_recipient_id', $recipient_id );
			carbon_set_post_meta( $notification_id, 'notification_is_read', 'not_read' );
		}
	}

	return $notification_id;
}

function my_handle_attachment( $file_handler, $post_id = 0, $set_thu = false ) {

	if ( $_FILES[ $file_handler ]['error'] !== UPLOAD_ERR_OK ) {
		__return_false();
	}

	require_once( ABSPATH . "wp-admin" . '/includes/image.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/file.php' );
	require_once( ABSPATH . "wp-admin" . '/includes/media.php' );

	return media_handle_upload( $file_handler, $post_id );
}

