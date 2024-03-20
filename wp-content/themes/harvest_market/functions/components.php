<?php

function the_product( $id = false ) {
	$id                  = $id ?: get_the_ID();
	$gallery             = carbon_get_post_meta( $id, 'product_gallery' );
	$address             = carbon_get_post_meta( $id, 'product_address' );
	$city                = carbon_get_post_meta( $id, 'product_city' );
	$price               = carbon_get_post_meta( $id, 'product_price' );
	$unit                = carbon_get_post_meta( $id, 'product_unit' );
	$rating              = carbon_get_post_meta( $id, 'product_rating' );
	$product_latitude    = carbon_get_post_meta( $id, 'product_latitude' );
	$product_longitude   = carbon_get_post_meta( $id, 'product_longitude' );
	$author_id           = get_post_field( 'post_author', $id );
	$seller_rating       = get_seller_rating( $author_id );
	$seller_count_review = get_seller_count_review( $author_id );
	$user_company_name   = carbon_get_user_meta( $author_id, 'user_company_name' ) ?: '';
	$img                 = get_the_post_thumbnail_url( $id );
	$title               = get_the_title( $id );
	$is_favorite         = is_in_favorite( $id );
	$reviews_count       = review_count( $id );
	$cls                 = $is_favorite ? 'active' : '';
	$user_location       = get_user_location();
	$user_coordinates    = get_user_location_coordinates();
	$user_lat            = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon            = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$distance            = 0;
	if ( $product_latitude && $product_longitude && $user_lat && $user_lon ) {
		$distance = getDistanceByCoordinates( array(
			'location_from' => array(
				'lat' => (float) $user_lat,
				'lng' => (float) $user_lon,
			),
			'location_to'   => array(
				'lat' => $product_latitude,
				'lng' => $product_longitude,
			),
			'unit'          => "K"
		) );

	} else {
		$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
		$distance     = getDistance( $user_address, $address, "K" );

	}
	$author_link = '#';
	$user_post   = carbon_get_user_meta( $author_id, 'user_post' );
	if ( $user_post && get_post( $user_post ) ) {
		$author_link = get_the_permalink( $user_post );
	}
	?>

    <div class="product-item">
		<?php the_product_labels( $id ); ?>
        <a class="product-item__favorite add-to-favorite <?php echo $cls; ?>" data-id="<?php echo $id; ?>" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                 style="enable-background:new 0 0 659.3 578.6" viewBox="0 0 659.3 578.6">
                                    <path d="m78 325 231.8 217.7c8 7.5 12 11.2 16.7 12.2 2.1.4 4.3.4 6.4 0 4.7-.9 8.7-4.7 16.7-12.2L581.3 325c65.2-61.3 73.1-162.1 18.3-232.7L589.3 79C523.7-5.6 392 8.6 345.9 105.2c-6.5 13.6-25.9 13.6-32.4 0C267.4 8.6 135.7-5.6 70.1 79L59.7 92.3C4.9 163 12.8 263.8 78 325z"
                                          style="fill:none;stroke:#fff;stroke-width:46.6667;stroke-miterlimit:133.3333"/>
                                </svg>
        </a>
        <div class="product-item__slider">
			<?php if ( $gallery ): foreach ( $gallery as $j => $image_id ): if ( $j < 3 ): ?>
                <div>
                    <img src="<?php _u( $image_id ); ?>" alt="<?php echo $title; ?>"/>
                </div>
			<?php endif; endforeach; elseif ( $img ): ?>
                <div>
                    <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>"/>
                </div>
			<?php endif; ?>
        </div>
        <div class="product-item__content">
            <a class="product-item__title" href="<?php echo get_the_permalink( $id ); ?>">
				<?php echo $title; ?> <span
                        class="reviews-count"><?php echo $seller_count_review ?: 0; ?> відгуків</span>
            </a>
            <a class="product-item__title product-item__organization" href="<?php echo $author_link; ?>">
				<?php echo $user_company_name; ?>
                <span class="reviews-rating">  <strong><?php echo $seller_rating; ?> </span></strong>

            </a>
            <ul class="product-item__place">
                <li><?php echo $city ?: $address; ?></li>
                <li>Відстань: <?php echo $distance; ?></li>
            </ul>
            <div class="product-item__bot">
                <div class="product-item__price">
					<?php echo get_price_html( $id ); ?>
                </div>

            </div>
        </div>
    </div>

	<?php
}

function the_product_labels( $id ) {
	$html              = '<div class="product-labels">';
	$time              = time();
	$product_is_top    = carbon_get_post_meta( $id, 'product_is_top' );
	$product_end_top   = carbon_get_post_meta( $id, 'product_end_top' );
	$product_start_top = carbon_get_post_meta( $id, 'product_start_top' );
	if ( $product_is_top == 'top' && $product_end_top > $time && $time > $product_start_top ) {
		$image      = _i( 'top' );
		$image_html = '<span class="product-label__image"><img src="' . $image . '" alt=""></span>';
		$html       .= '<div data-end="' . $product_end_top . '" class="product-label product-label--top">' . $image_html . 'TOP</div>';
	}
	$product_is_urgently  = carbon_get_post_meta( $id, 'product_is_urgently' );
	$product_end_urgently = carbon_get_post_meta( $id, 'product_end_urgently' );
	if ( $product_is_urgently == 'urgently' && $product_end_urgently > $time ) {
		$html .= '<div data-end="' . $product_end_urgently . '" class="product-label ">Терміново</div>';
	}
	$html .= '</div>';
	echo $html;
}

function get_price_html( $id ) {
	$price    = carbon_get_post_meta( $id, 'product_price' );
	$unit     = carbon_get_post_meta( $id, 'product_unit' );
	$currency = carbon_get_theme_option( 'currency' );

	return "<strong>$price</strong>$currency/$unit";
}

function the_home_categories( $categories ) {
	if ( $categories ):
		$type = $_GET['type'] ?? '';
		$suffix_link = $type ? "?type=$type&save_center=true#map-list" : '';
		$current_term_id = get_queried_object_id();
		?>
        <div class="category-list">
			<?php foreach ( $categories as $j => $category_id ):
				if ( $term = get_term_by( 'id', $category_id, 'categories' ) ):
					$cls = '';
					if ( $current_term_id == $category_id ) {
						$cls = 'active';
					}

					?>
                    <div class="category-list__item">
                        <a class="category-list__item-link <?php echo $cls; ?> "
                           href="<?php echo get_term_link( $term->term_id ) . $suffix_link; ?>">
                            <div class="category-list__item-media">
                                <img src="<?php _u( carbon_get_term_meta( $category_id, 'category_image' ) ); ?>"
                                     alt="<?php echo $term->name; ?>">
                            </div>
                            <div class="category-list__item-title">
								<?php echo $term->name; ?>
                            </div>
                        </a>
                    </div>
				<?php endif; endforeach; ?>
        </div>
	<?php endif;
}

function the_home_catalog() {
	$query = get_query_index_data();

//    v($query);
	?>
    <div class="catalog container-js">
		<?php
		if ( $query->have_posts() ) : while ( $query->have_posts() ) : $query->the_post(); ?>
			<?php the_product(); ?>
		<?php endwhile; else : ?>
            <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
                Не знайдено!
            </div>
		<?php endif;
		wp_reset_postdata();
		wp_reset_query(); ?>
    </div>

    <div class="btn_center pagination-js">
		<?php echo _get_more_link( $query->max_num_pages ); ?>
    </div>
	<?php

}

function _get_more_link( $max_page = 0 ) {
	global $wp_query;
	if ( ! $max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	$paged    = $_GET['pagenumber'] ?? 1;
	$nextpage = intval( $paged ) + 1;
	$image    = '<svg
                        xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                        style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                    <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                          style="fill:#fff"/>
                                </svg>';
	if ( ! is_single() ) {
		if ( $nextpage <= $max_page ) {
			$next_posts_link = get_next_posts( 'get_products_container' );

			return '<a class="btn_st next-post-link-js" href="' . $next_posts_link . '">
                <span>Більше товарів' . $image . '</span></a>';
		}

	}
}

function __get_more_link( $max_page = 0 ) {
	global $wp_query;
	if ( ! $max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	$paged    = $_GET['pagenumber'] ?? 1;
	$nextpage = intval( $paged ) + 1;
	$image    = '<svg
                        xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                        style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                    <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                          style="fill:#fff"/>
                                </svg>';
	if ( ! is_single() ) {
		if ( $nextpage <= $max_page ) {
			$next_posts_link = get_next_posts( '', get_the_permalink() );

			return '<a class="btn_st next-post-link-js" href="' . $next_posts_link . '">
                <span>Більше товарів' . $image . '</span></a>';
		}

	}
}

function _get_more_author_link( $max_page, $current_author_id ) {
	global $wp_query;
	if ( ! $max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	$paged    = $_GET['pagenumber'] ?? 1;
	$nextpage = intval( $paged ) + 1;
	$image    = '<svg
                        xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                        style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                    <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                          style="fill:#fff"/>
                                </svg>';

	if ( $nextpage <= $max_page ) {
		$current_author    = $wp_query->get_queried_object();
		$current_author_id = $current_author_id ?: $current_author->ID;
		$next_posts_link   = get_next_posts( '', get_author_posts_url( $current_author_id ) );

		return '<a class="btn_st next-post-link-js" href="' . $next_posts_link . '">
                <span>Більше товарів' . $image . '</span></a>';
	}

}

function _get_more_reviews_link( $max_page, $current_author_id ) {
	global $wp_query;
	if ( ! $max_page ) {
		$max_page = $wp_query->max_num_pages;
	}
	$paged    = $_GET['pagenum'] ?? 1;
	$nextpage = intval( $paged ) + 1;
	$image    = '<svg
                        xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                        style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                    <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                          style="fill:#fff"/>
                                </svg>';

	if ( $nextpage <= $max_page ) {
		$current_author    = $wp_query->get_queried_object();
		$current_author_id = $current_author_id ?: $current_author->ID;
		$next_posts_link   = get_author_posts_url( $current_author_id ) . '?pagenum=' . $nextpage;

		return '<a class="btn_st b_yelloow reviews-next-link next-post-link-js" href="' . $next_posts_link . '">
                <span>Більше відгуків' . $image . '</span></a>';
	}

}

function the_filter_categories( $categories ) {
	$queried_object = get_queried_object();
	$_category      = $_GET['category'] ?? '';
	if ( $_category ) {
		$_category = ! is_array( $_category ) ? explode( ',', $_category ) : $_category;
	} else {
		$_category = array();
	}
	if ( $queried_object ) {
		$term_id  = $queried_object->term_id;
		$taxonomy = $queried_object->taxonomy;
		if ( $taxonomy && $term_id ) {
			if ( $taxonomy == 'categories' && ! in_array( $term_id, $_category ) ) {
				$_category[] = $term_id;
			}
		}
	}
	foreach ( $categories as $category ):
		$test = $_category && in_array( $category->term_id, $_category );
		$attr       = $test ? 'checked' : '';
		?>
        <div class="filter-check__item">
            <label class="check-item">
                <input
                        class="check_st filter-check-input"
                        data-name="category" <?php echo $attr; ?>
                        value="<?php echo $category->term_id; ?>"
                        type="checkbox"
                />
                <span></span>
                <i class="check-item__text"><?php echo $category->name; ?></i>
            </label>
        </div>
		<?php
		$parent       = $category->term_id;
		$i            = 1;
		while ( get_terms( array(
				'taxonomy'   => 'categories',
				'hide_empty' => false,
				'parent'     => $parent,
			) ) != false ):
			$subcategories = get_terms( array(
				'taxonomy'   => 'categories',
				'hide_empty' => false,
				'parent'     => $parent,
			) );
			foreach ( $subcategories as $subcategory ):
				$parent = $subcategory->term_id;
				$test = $_category && in_array( $subcategory->term_id, $_category );
				$attr = $test ? 'checked' : '';
				?>
                <div class="filter-check__item">
                    <label class="check-item">
                        <input <?php echo $attr; ?>
                                class="check_st filter-check-input"
                                data-name="category"
                                value="<?php echo $subcategory->term_id; ?>"
                                type="checkbox"
                        />
                        <span></span>
                        <i class="check-item__text">
							<?php for ( $a = 1; $a <= $i; $a ++ ) {
								echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
							}
							echo $subcategory->name; ?>
                        </i>
                    </label>
                </div>
			<?php endforeach;
			$i ++;
		endwhile;
	endforeach;
}

function the_seller_review( $id = false ) {
	$id            = $id ?: get_the_ID();
	$review_rating = carbon_get_post_meta( $id, 'review_rating' ) ?: 5;
	$_email        = carbon_get_post_meta( $id, 'review_author_email' );
	$_user_id      = carbon_get_post_meta( $id, 'review_user_id' );
	$author_id     = get_post_field( 'post_author', $id );
	$current_user  = get_user_by( 'ID', $author_id );
	$email         = $current_user->user_email ?: '';
	$display_name  = $current_user->display_name ?: '';
	$first_name    = $current_user->first_name ?: '';
	$last_name     = $current_user->last_name ?: '';
	$name          = $first_name ?: $display_name;
	$user_avatar   = carbon_get_user_meta( $author_id, 'user_avatar' );
	$avatar_url    = ! $_user_id && $_email ? get_avatar_url( $_email ) : ( $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $email ?: $author_id ) );
	$content       = get_content_by_id( $id );
	$string        = strip_tags( $content );
	$title         = get_the_title( $id );
	?>
    <div class="testimonials-item">
        <div class="testimonials-item__top">
            <div class="testimonials-item__ava">
                <img src="<?php echo $avatar_url; ?>" alt=""/>
            </div>
            <div class="testimonials-item__info">
                <div class="testimonials-item__info-top">
                    <div class="testimonials-item__title">
						<?php echo $title ?: ( $first_name . ' ' . $last_name ); ?>
                    </div>
                    <ul class="rating">
						<?php for ( $a = 1; $a <= 5; $a ++ ): ?>
                            <li class="<?php echo $a <= $review_rating ? 'active' : ''; ?>">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 12 11.2"
                                     viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                            </li>
						<?php endfor; ?>
                    </ul>
                </div>
                <div class="testimonials-item__date">
					<?php echo get_the_date( 'd.m.Y', $id ); ?>
                </div>
            </div>
        </div>
        <div class="testimonials-item__text">
			<?php if ( mb_strlen( $string, 'UTF-8' ) > 100 ): ?>
                <div class="text-group preview-text">
                    <p>
						<?php echo mb_strimwidth( $string, 0, 100, "...", 'UTF-8' ); ?>
                    </p>
                </div>
                <div class="hidden-text-wrap">
                    <div class="hidden-text">
                        <div class="text-group">
							<?php echo $content; ?>
                        </div>
                    </div>
                    <a class="more-text-btn" href="#" data-text="Показати ще" data-show="Менше">
                        <span>Показати ще</span>
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                            <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                                  style="fill:#4d76ff"/>
                                        </svg>
                    </a>
                </div>
			<?php else: ?>
                <div class="text-group ">
					<?php echo $content; ?>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
}

function the_vip_catalog() {
	$time           = time();
	$posts_per_page = carbon_get_theme_option( 'vip_product_count' ) ?: 4;
	$args           = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'orderby'        => 'rand',
	);
	$meta_query     = array(
		'key'     => '_product_end_vip',
		'value'   => $time,
		'type'    => 'numeric',
		'compare' => '>'
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$meta_query = array(
		'key'     => '_product_start_vip',
		'value'   => $time,
		'type'    => 'numeric',
		'compare' => '<'
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$meta_query = array(
		'key'   => '_product_is_vip',
		'value' => 'vip',
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$queried_object = get_queried_object();
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
	if ( $user_region_id = get_user_region_id() ) {
		$tax_query = array(
			'taxonomy' => 'regions',
			'field'    => 'id',
			'terms'    => array( $user_region_id )
		);
		if ( isset( $args['tax_query'] ) ) {
			$args['tax_query'][] = $tax_query;
		} else {
			$args['tax_query'] = array( $tax_query );
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			?>
            <div class="catalog-group line_bot">
                <div class="title-sm">
                    VIP-оголошення
                </div>
                <div class="catalog ">
					<?php while ( $query->have_posts() ) :
						$query->the_post();
						the_product();
					endwhile; ?>
                </div>
            </div>


		<?php
		endif;
	}
	wp_reset_postdata();
	wp_reset_query();
}

function the_top_catalog() {
	$time           = time();
	$posts_per_page = carbon_get_theme_option( 'product_product_count' ) ?: 4;
	$args           = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'posts_per_page' => $posts_per_page,
		'orderby'        => 'rand',
	);
	$meta_query     = array(
		'key'     => '_product_end_top',
		'value'   => $time,
		'type'    => 'numeric',
		'compare' => '>'
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$meta_query = array(
		'key'     => '_product_start_top',
		'value'   => $time,
		'type'    => 'numeric',
		'compare' => '<'
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$meta_query = array(
		'key'   => '_product_is_top',
		'value' => 'top',
	);
	if ( isset( $args['meta_query'] ) ) {
		$args['meta_query'][] = $meta_query;
	} else {
		$args['meta_query'] = array( $meta_query );
	}
	$queried_object = get_queried_object();
	if ( $queried_object ) {
		$term_id  = $queried_object->term_id;
		$taxonomy = $queried_object->taxonomy;
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
	if ( $user_region_id = get_user_region_id() ) {
		$tax_query = array(
			'taxonomy' => 'regions',
			'field'    => 'id',
			'terms'    => array( $user_region_id )
		);
		if ( isset( $args['tax_query'] ) ) {
			$args['tax_query'][] = $tax_query;
		} else {
			$args['tax_query'] = array( $tax_query );
		}
		$query = new WP_Query( $args );
		if ( $query->have_posts() ) :
			?>
            <div class="catalog-group line_bot">
                <div class="title-sm">
                    TOP-оголошення
                </div>
                <div class="catalog ">
					<?php
					while ( $query->have_posts() ) :
						$query->the_post();
						the_product();
					endwhile;
					?>
                </div>
            </div>

		<?php
		endif;
	}
	wp_reset_postdata();
	wp_reset_query();
}

function the_organization( $user_id ) {
	$current_user         = get_user_by( 'ID', $user_id );
	$company_description  = carbon_get_user_meta( $user_id, 'user_company_description' );
	$gallery              = carbon_get_user_meta( $user_id, 'user_company_gallery' );
	$seller_rating        = get_seller_rating( $user_id );
	$seller_count_review  = get_seller_count_review( $user_id );
	$user_company_address = carbon_get_user_meta( $user_id, 'user_company_address' );
	$user_company_name    = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
	?>

    <div class="product-item">
        <div class="product-item__slider">
			<?php if ( $gallery ): foreach ( $gallery as $j => $image_id ): if ( $j < 3 ): ?>
                <div>
                    <img src="<?php _u( $image_id ); ?>" alt="<?php echo $user_company_name; ?>"/>
                </div>
			<?php endif; endforeach; endif; ?>
        </div>
        <div class="product-item__content">
            <a class="product-item__title" href="<?php echo get_author_posts_url( $user_id ); ?>">
				<?php echo $user_company_name; ?>
            </a>
            <ul class="product-item__place">
                <li><?php echo $user_company_address; ?></li>
            </ul>
            <div class="product-item__bot">

            </div>
        </div>
    </div>

	<?php
}

function the_user_testimonials( $author_id ) {

	$var                 = variables();
	$set                 = $var['setting_home'];
	$assets              = $var['assets'];
	$url                 = $var['url'];
	$url_home            = $var['url_home'];
	$admin_ajax          = $var['admin_ajax'];
	$current_user_id     = get_current_user_id();
	$seller_rating       = get_seller_rating( $author_id );
	$seller_count_review = get_seller_count_review( $author_id );
	$_order              = $_GET['order'] ?? '';
	$_orderby            = $_GET['orderby'] ?? '';
	$pagenum             = $_GET['pagenum'] ?? 1;
	$is_logged           = is_user_logged_in();
	$current_author_id   = $author_id ?: get_queried_object()->ID;
	$user_id             = $current_author_id;
	?>
    <div class="user-testimonials">
        <div class="product-testimonials__top">
            <div class="product-testimonials__top-item">
                <div class="reviews-rating"> <?php echo $seller_rating ?></div>
            </div>
            <div class="product-testimonials__top-item">
				<?php echo $seller_count_review; ?> відгуків
            </div>
        </div>
        <div class="testimonials container-js">
			<?php
			$args  = array(
				'post_type'   => 'reviews',
				'post_status' => 'publish',
				'paged'       => $pagenum,
				'meta_key'    => '_review_seller_id',
				'meta_value'  => $author_id
			);
			$query = new WP_Query( $args );
			if ( $query->have_posts() ):
				while ( $query->have_posts() ) :
					$query->the_post();
					the_seller_review();
				endwhile;
				wp_reset_postdata();
				wp_reset_query();
				?>
			<?php else: ?>
                <div class="text-group" style="text-align: center; margin: 2rem; width: 100%;">
					<?php echo $is_logged ? 'Залиште відгук першим!' : 'Відгуки відсутні!'; ?>
                </div>
			<?php endif; ?>
        </div>
        <div class="testimonials-pagination pagination-js">
			<?php echo _get_more_reviews_link( $query->max_num_pages, $author_id ); ?>
        </div>
        <div class="cabinet-item">
			<?php if ( $current_user_id ): ?>
                <div class="cabinet-item__title">Залишити відгук</div>
                <form class="form-js seller-comment-form" id="seller-comment-form" method="post" novalidate>
                    <input type="hidden" name="action" value="new_seller_review">
                    <input type="hidden" name="seller_id" value="<?php echo $current_author_id; ?>">
                    <div class="form-horizontal">
                        <div class="form-group half">
                            <input class="input_st"
                                   type="text"
                                   name="name"
                                   placeholder="Ім'я" required="required"/>
                        </div>
                        <div class="form-group half">
                            <input class="input_st"
                                   type="email"
                                   name="email"
                                   data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                                   placeholder="E-mail" required="required"/>
                        </div>
                        <div class="form-group">
                            <div class="feedback-rating">
                                <div class="feedback-rating__text"> Оцініть товар:</div>
                                <div class="rating">
									<?php for ( $a = 1; $a <= 5; $a ++ ): ?>
                                        <label class="rating-item">
                                            <input type="radio" name="rating" value="<?php echo $a ?>"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
									<?php endfor; ?>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                                <textarea class="input_st" name="text"
                                          placeholder="Ваш коментар"
                                          required="required"></textarea>
                        </div>
                    </div>
                    <div class="form-bot">
                        <div class="form-consent">
                            <label>
                                <input class="check_st" name="consent" type="checkbox"/><span></span>
                            </label>
                            <div class="form-consent__text">
                                Даю згоду на обробку персональних даних та погоджуюся з <a href="#">політикою
                                    конфіденційності </a>
                            </div>
                        </div>
                        <button class="btn_st" type="submit">
                            <span> Відправити відгук </span>
                        </button>
                    </div>
                </form>
			<?php else: ?>
                <div class="cabinet-item__title">Залишити відгук можуть тільки авторизовані користувачі</div>
			<?php endif; ?>
        </div>
    </div>
	<?php
}

function the_organization_work_time() {
	$hours            = 24;
	$minutes          = 59;
	$user_id          = get_current_user_id();
	$work_time_string = carbon_get_user_meta( $user_id, 'user_work_time_organization' ) ?: '';
	$work_time_arr    = $work_time_string ? json_decode( $work_time_string, true ) : array();
	?>

    <div class="form-hours">
        <input type="hidden" name="days_prefix" value="days">
        <div class="faq-item__title">Години роботи</div>
        <div class="form-hours__list">
			<?php
			if ( $work_time_arr ) {
				foreach ( $work_time_arr as $k => $item ) {
					$start_time       = $item[1] ?? array();
					$finish_time      = $item[2] ?? array();
					$is_remove_button = $k != ( count( $work_time_arr ) - 1 );
					the_work_time_row(
						array(
							'is_remove_button' => $is_remove_button,
							'days_index'       => $k,
							'days'             => $item[0],
							'start_hour'       => $start_time[0] ?? '09',
							'start_minutes'    => $start_time[1] ?? '00',
							'finish_hour'      => $finish_time[0] ?? '18',
							'finish_minutes'   => $finish_time[1] ?? '00',
						)
					);
				}
			} else {
				the_work_time_row();
			}
			?>
        </div>
    </div>
	<?php
}

function the_work_time_row( $args = array() ) {
	$hours            = 24;
	$minutes          = 59;
	$days_prefix      = $args['days_prefix'] ?? 'days';
	$days_index       = $args['days_index'] ?? 0;
	$days             = $args['days'] ?? array( '1' );
	$start_hour       = $args['start_hour'] ?? '09';
	$start_minutes    = $args['start_minutes'] ?? '00';
	$finish_hour      = $args['finish_hour'] ?? '18';
	$finish_minutes   = $args['finish_minutes'] ?? '00';
	$is_remove_button = $args['is_remove_button'] ?? false;

	?>
    <div class="form-hours__item"
         data-days-prefix="<?php echo $days_prefix; ?>">
        <div class="form-horizontal">
            <div class="form-group quarter">
                <div class="form-hours-input">
                    <div class="form-hours-input__title">Виберіть дні</div>
                    <select class="select_st work-time-days-select" required
                            name="<?php echo $days_prefix; ?>_<?php echo $days_index; ?>[]"
                            multiple>
                        <option
							<?php echo in_array( '1', $days ) ? 'selected' : ''; ?>
                                value="1">
                            Понеділок
                        </option>
                        <option <?php echo in_array( '2', $days ) ? 'selected' : ''; ?> value="2">Вівторок</option>
                        <option <?php echo in_array( '3', $days ) ? 'selected' : ''; ?> value="3">Середа</option>
                        <option <?php echo in_array( '4', $days ) ? 'selected' : ''; ?> value="4">Четвер</option>
                        <option <?php echo in_array( '5', $days ) ? 'selected' : ''; ?> value="5">П'ятниця</option>
                        <option <?php echo in_array( '6', $days ) ? 'selected' : ''; ?> value="6">Субота</option>
                        <option <?php echo in_array( '7', $days ) ? 'selected' : ''; ?> value="7">Неділя</option>
                    </select>
                </div>
            </div>
            <div class="form-group quarter">
                <div class="form-hours-input">
                    <div class="form-hours-input__title">З котрої години ви працюєте</div>
                    <div class="form-hours-select">
                        <div class="form-hours-select__item">
                            <select class="select_st" required name="start_hours[]">
								<?php for ( $h = 0; $h < $hours; $h ++ ):
									$v = $h < 10 ? '0' . $h : $h;
									$attr = $v == $start_hour ? 'selected' : '';
									?>
                                    <option <?php echo $attr; ?>>
										<?php echo $v ?>
                                    </option>
								<?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-hours-select__item">
                            <select class="select_st" required name="start_minutes[]">
								<?php for ( $m = 0; $m <= $minutes; $m ++ ):
									if ( $m % 10 == 0 ) :
										$v = $m < 10 ? '0' . $m : $m;
										$attr = $v == $start_minutes ? 'selected' : '';
										?>
                                        <option <?php echo $attr; ?>>
											<?php echo $v; ?>
                                        </option>
									<?php endif; endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group quarter">
                <div class="form-hours-input">
                    <div class="form-hours-input__title">До котрої години працюєте</div>
                    <div class="form-hours-select">
                        <div class="form-hours-select__item">
                            <select class="select_st" required name="finish_hours[]">
								<?php for ( $h = 0; $h <= $hours; $h ++ ):
									$v = $h < 10 ? '0' . $h : $h;
									$attr = $v == $finish_hour ? 'selected' : '';
									?>
                                    <option <?php echo $attr; ?>>
										<?php echo $v; ?>
                                    </option>
								<?php endfor; ?>
                            </select>
                        </div>
                        <div class="form-hours-select__item">
                            <select class="select_st" required name="finish_minutes[]">
								<?php for ( $m = 0; $m < $minutes; $m ++ ):
									if ( $m % 10 == 0 ) :
										$v = $m < 10 ? '0' . $m : $m;
										$attr = $v == $finish_minutes ? 'selected' : '';
										?>
                                        <option <?php echo $attr; ?>>
											<?php echo $v; ?>
                                        </option>
									<?php endif; endfor; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group quarter">
				<?php if ( $is_remove_button ): ?>
                    <a class="btn_st btn_yellow w100 work-time-row-button remove-button"
                       data-days-prefix="<?php echo $days_prefix; ?>"
                       data-remove="Видалити"
                       data-add="Додати робочі дні"
                       data-index="<?php echo $days_index; ?>"
                       href="#">
                        <span>Видалити</span>
                    </a>
				<?php else: ?>
                    <a class="btn_st btn_yellow w100 work-time-row-button"
                       data-days-prefix="<?php echo $days_prefix; ?>"
                       data-remove="Видалити"
                       data-add="Додати робочі дні"
                       data-index="<?php echo $days_index; ?>"
                       href="#">
                        <span>Додати робочі дні </span>
                    </a>
				<?php endif; ?>

            </div>
        </div>
    </div>
	<?php
}

function the_user_social_networks() {
	$names   = get_social_networks_name();
	$user_id = get_current_user_id();
	$items   = array();
	if ( $names ) {
		foreach ( $names as $str ) {
			$value         = get_user_meta( $user_id, strtolower( $str ), true );
			$items[ $str ] = $value;
		}
	}
	?>
    <div class="user-social-networks-wrapper">
        <div class="faq-item__title">Соціальні мережі</div>
        <div class="user-social-networks">
			<?php
			if ( $items ) {
				$k = 0;
				foreach ( $items as $name => $item ) {
					$is_remove_button = $k != ( count( $items ) );
					$k ++;
					the_social_network_row( array(
						'names'            => $names,
						'name'             => $name,
						'value'            => $item,
						'is_remove_button' => $is_remove_button,
					) );
				}
			} else {
				the_social_network_row( array(
					'names' => $names
				) );
			}
			?>
			<?php the_social_network_row(); ?>
        </div>
    </div>
	<?php
}

function get_social_networks_name() {
	return array(
		'Facebook',
		'Instagram',
		'LinkedIn',
		'MySpace',
		'Pinterest',
		'SoundCloud',
		'Tumblr',
		'Twitter',
		'YouTube',
		'Wikipedia',
	);
}

function the_social_network_row( $array = array() ) {
	$names            = $array['names'] ?? get_social_networks_name();
	$name             = $array['name'] ?? '';
	$value            = $array['value'] ?? '';
	$is_remove_button = $array['is_remove_button'] ?? false;
	if ( $names ):
		?>
        <div class="user-social-network">
            <div class="form-horizontal">
                <div class="form-group third">
                    <div class="form-hours-input">
                        <div class="form-hours-input__title">Виберіть соціальну мережу</div>
                        <select class="select_st" required
                                name="social_network[]"
                        >
							<?php foreach ( $names as $_name ): ?>
                                <option
									<?php echo $name == $_name ? 'selected' : ''; ?>
                                        value="<?php echo $_name; ?>">
									<?php echo $_name; ?>
                                </option>
							<?php endforeach; ?>
                        </select>
                    </div>
                </div>
                <div class="form-group third">
                    <div class="form-hours-input">
                        <div class="form-hours-input__title">Заповніть посилання</div>
                        <input class="input_st" placeholder="Посилання профілю" value="<?php echo $value; ?>"
                               type="url" name="social_network_url[]">
                    </div>
                </div>
                <div class="form-group third">
					<?php if ( $is_remove_button ): ?>
                        <a class="btn_st btn_yellow w100 social-network-row-button remove-button"
                           data-remove="Видалити"
                           data-add="Додати соціальну мережу"
                           href="#">
                            <span>Видалити</span>
                        </a>
					<?php else: ?>
                        <a class="btn_st btn_yellow w100 social-network-row-button"
                           data-remove="Видалити"
                           data-add="Додати соціальну мережу"
                           href="#">
                            <span>Додати соціальну мережу</span>
                        </a>
					<?php endif; ?>

                </div>
            </div>
        </div>
	<?php
	endif;
}