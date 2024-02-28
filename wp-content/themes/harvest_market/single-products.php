<?php
get_header();
$var               = variables();
$set               = $var['setting_home'];
$assets            = $var['assets'];
$url               = $var['url'];
$url_home          = $var['url_home'];
$admin_ajax        = $var['admin_ajax'];
$current_user_id   = get_current_user_id();
$id                = get_the_ID();
$author_id         = get_post_field( 'post_author', $id );
$isLighthouse      = isLighthouse();
$product_latitude  = carbon_get_post_meta( $id, 'product_latitude' );
$product_longitude = carbon_get_post_meta( $id, 'product_longitude' );
$size              = $isLighthouse ? 'thumbnail' : 'full';
$gallery           = carbon_get_post_meta( $id, 'product_gallery' );
$img               = get_the_post_thumbnail_url( $id );
$title             = get_the_title();
$content           = get_content_by_id( $id );
$reviews_count     = review_count( $id );
$rating            = carbon_get_post_meta( $id, 'product_rating' );
$is_favorite       = is_in_favorite( $id );
$is_bought         = is_bought( $id );
$cls               = $is_favorite ? 'active' : '';
$min_order         = carbon_get_post_meta( $id, 'product_min_order' );
$max_value         = carbon_get_post_meta( $id, 'product_max_value' );
$currency          = carbon_get_theme_option( 'currency' );
$delivery_methods  = carbon_get_post_meta( $id, 'product_delivery_methods' );
$unit              = carbon_get_post_meta( $id, 'product_unit' );
$address           = carbon_get_post_meta( $id, 'product_address' );
$city              = carbon_get_post_meta( $id, 'product_city' );
$price             = carbon_get_post_meta( $id, 'product_price' );
$products          = carbon_get_post_meta( $id, 'product_products' );
$product_views     = carbon_get_post_meta( $id, 'product_views' ) ?: 0;
$user_location     = get_user_location();
$user_coordinates  = get_user_location_coordinates();
$user_lat          = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
$user_lon          = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
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
	$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
	$distance     = getDistance( $user_address, $address, "K" );
}
$map_api_url         = carbon_get_theme_option( 'map_api_url' );
$company_description = carbon_get_user_meta( $author_id, 'user_company_description' );
$verification        = carbon_get_user_meta( $author_id, 'user_verification' );
$user_company_name   = carbon_get_user_meta( $author_id, 'user_company_name' ) ?: '';
$delivery_count      = carbon_get_user_meta( $author_id, 'delivery_count' ) ?: 0;
$categories          = get_the_terms( $id, 'categories' );
$cart_page           = carbon_get_theme_option( 'checkout_page' );
$cart_page           = $cart_page ? $cart_page[0]['id'] : 0;
$personal_page       = carbon_get_theme_option( 'personal_area_page' );
$_url                = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
$product_views ++;
carbon_set_post_meta( $id, 'product_views', $product_views );
$author_link = '#';
$user_post   = carbon_get_user_meta( $author_id, 'user_post' );
if ( $user_post && get_post( $user_post ) ) {
	$author_link = get_the_permalink( $user_post );
}
?>

    <section class="section-product pad_section_bot">
        <div class="container">
            <ul class="breadcrumbs">
                <li><a href="<?php echo $url; ?>"><?php echo get_the_title( $set ); ?></a></li>
                <li> <?php echo $title; ?> </li>
            </ul>
            <div class="product-group pad_section_sm_top">
                <div class="product-group__left">
					<?php the_product_labels( $id ); ?>
                    <a class="product-card__favorite add-to-favorite <?php echo $cls; ?>"
                       data-id="<?php echo $id; ?>"
                       href="#">
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 659.3 578.6" viewBox="0 0 659.3 578.6">
                                            <path d="m78 325 231.8 217.7c8 7.5 12 11.2 16.7 12.2 2.1.4 4.3.4 6.4 0 4.7-.9 8.7-4.7 16.7-12.2L581.3 325c65.2-61.3 73.1-162.1 18.3-232.7L589.3 79C523.7-5.6 392 8.6 345.9 105.2c-6.5 13.6-25.9 13.6-32.4 0C267.4 8.6 135.7-5.6 70.1 79L59.7 92.3C4.9 163 12.8 263.8 78 325z"
                                                  style="fill:none;stroke:#fff;stroke-width:46.6667;stroke-miterlimit:133.3333"/>
                                        </svg>
                    </a>
                    <div class="product-slider">
						<?php if ( $gallery ): foreach ( $gallery as $j => $image_id ): if ( $j < 3 ): ?>
                            <div>
                                <a href="<?php _u( $image_id ); ?>" class="product-slider__item"
                                   data-caption="<?php echo $title; ?>"
                                   data-gallery>
                                    <img src="<?php _u( $image_id ); ?>" alt="<?php echo $title; ?>"/>
                                </a>
                            </div>
						<?php endif; endforeach; elseif ( $img ): ?>
                            <div>
                                <a href="<?php echo $img; ?>" class="product-slider__item"
                                   data-caption="<?php echo $title; ?>"
                                   data-gallery>
                                    <img src="<?php echo $img; ?>" alt="<?php echo $title; ?>"/>
                                </a>
                            </div>
						<?php endif; ?>
                    </div>
					<?php
					if ( $content ):
						$content_arr = explode( '<!--more-->', $content );
						if ( count( $content_arr ) > 1 ):
							?>
                            <div class="product-description">
                                <div class="product-description__title"> Опис</div>
                                <div class="text-group">
									<?php echo $content_arr[0]; ?>
                                </div>
                                <div class="hidden-text-wrap">
                                    <div class="hidden-text">
                                        <div class="text-group">
											<?php foreach ( $content_arr as $j => $value ) {
												if ( $j > 0 ) {
													echo $value;
												}
											} ?>
                                        </div>
                                    </div>
                                    <a class="more-text" href="#" data-text="Показати ще"
                                       data-show="Менше">
                                        <span>Показати ще</span>
                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                             style="enable-background:new 0 0 8 15" viewBox="0 0 8 15">
                                        <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                              style="fill:#4d76ff"/>
                                    </svg>
                                    </a>
                                </div>
                            </div>
						<?php else: ?>
                            <div class="product-description">
                                <div class="product-description__title"> Опис</div>
                                <div class="text-group">
									<?php echo $content; ?>
                                </div>
                            </div>
						<?php endif; ?>
					<?php endif; ?>
					<?php echo comments_template(); ?>
                </div>
                <div class="product-group__right">
                    <div class="cabinet-item card-main">
                        <div class="product-card">
                            <div class="product-card__top">
                                <div class="product-card__title">
									<?php echo $title; ?>
                                </div>
                            </div>
                            <div class="product-card__list">
								<?php if ( $delivery_methods ):
									$delivery_methods_str = implode( ', ', $delivery_methods );
									$delivery_methods_str = str_replace(
										get_delivery_methods_types(),
										array( '', '', '' ),
										$delivery_methods_str
									);
									?>
                                    <div class="product-card__list-item">
                                        <div class="product-card__list-title margin-bottom-auto margin-right-min">
                                            Доставка:
                                        </div>
                                        <div class="product-card__list-main">
											<?php echo $delivery_methods_str; ?>
                                        </div>
                                    </div>
								<?php endif; ?>
								<?php if ( $min_order ): ?>
                                    <div class="product-card__list-item">
                                        <div class="product-card__list-title">Мінімальний заказ:</div>
                                        <div class="product-card__list-main">
                                            від <?php echo $min_order . ' ' . $unit; ?></div>
                                    </div>
								<?php endif; ?>
								<?php if ( $max_value ): ?>
                                    <div class="product-card__list-item">
                                        <div class="product-card__list-title">В наявності:</div>
                                        <div class="product-card__list-main"> <?php echo $max_value . ' ' . $unit; ?></div>
                                    </div>
								<?php endif; ?>
                                <div class="product-card__list-item">
                                    <div class="product-card__list-title">Рейтинг:</div>
                                    <div class="product-card__list-main">
                                        <ul class="product-item__reviews">
                                            <li><?php echo $reviews_count; ?> відгуків</li>
                                            <li>
                                                <strong><?php echo (float) ( $rating ?: 5 ); ?> </strong>
                                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                     style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                        <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                              style="fill:#ffc327"/>
                                                    </svg>
                                            </li>
                                        </ul>
                                    </div>
                                </div>
                            </div>
                            <div class="product-card__count">
                                <div class="product-item__price">
									<?php echo get_price_html( $id ); ?>
                                </div>
                                <div class="counter_product">
                                    <div class="btn_count num_minus disabled">
                                        <span></span>
                                    </div>
                                    <input class="counter_input"
                                           data-unit="<?php echo $unit; ?>"
                                           data-price="<?php echo $price; ?>"
                                           data-currency="<?php echo $currency; ?>"
                                           data-max="<?php echo $max_value; ?>"
                                           data-min="<?php echo $min_order; ?>"
                                           type="text" readonly=""
                                           value="1"/>
                                    <div class="btn_count num_pluss active">
                                        <span></span>
                                    </div>
                                </div>
                                <div class="product-card__count-main">
                                    <div class="product-item__price product__price-js">
										<?php echo '<strong>' . $price . '</strong> ' . $currency; ?>
                                    </div>
                                </div>
                            </div>
                            <div class="product-card__btn">
								<?php if ( $cart_page ): ?>
                                    <a class="btn_st add-to-cart <?php if ( $min_order && $min_order > 1 ) {
										echo 'not-active';
									} ?>"
                                       data-id="<?php echo $id; ?>"
                                       data-qnt="1"
                                       data-max="<?php echo $max_value; ?>"
                                       href="<?php echo get_the_permalink( $cart_page ); ?>">
                                        <span>Оформити замовлення</span>
                                    </a>
								<?php endif; ?>
                                <a class="btn_st b_yelloow modal_open" href="#buy_click">
                                    <span>Купити в один клік</span>
                                </a>
                            </div>
                        </div>
                    </div>
                    <div class="cabinet-item">

                        <a href="<?php echo $author_link; ?>"
                           class="form-description__item-title">
							<?php echo carbon_get_post_meta( $id, 'product_company_name' ) ?: $user_company_name; ?>
                        </a>
						<?php if ( $verification ): ?>
                            <div class="product-verified">
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M8.2.3C8 .1 7.8 0 7.5 0c-.3 0-.5.1-.7.3l-.9.9h-.2L4.5.9c-.2-.1-.5 0-.7.1-.3.1-.5.4-.5.6l-.4 1.3V3h-.1l-1.2.3c-.2 0-.5.2-.6.4-.1.3-.2.6-.1.8l.3 1.2v.2l-.9.9c-.2.2-.3.4-.3.7 0 .3.1.5.3.7l.9.9v.2l-.3 1.2c-.1.3 0 .5.1.8.1.2.4.4.6.5l1.2.3h.1v.1l.3 1.2c.1.3.2.5.5.6.2.1.5.2.8.1l1.2-.3h.2l.9.9c.2.2.4.3.7.3.3 0 .5-.1.7-.3l.9-.9h.2l1.2.3c.3.1.5 0 .8-.1.2-.1.4-.4.5-.6l.3-1.2v-.1h.1l1.2-.3c.3-.1.5-.2.6-.5.1-.2.2-.5.1-.8l-.3-1.2v-.2l.9-.9c.2-.2.3-.4.3-.7 0-.3-.1-.5-.3-.7l-.9-.9v-.2l.3-1.2c.1-.3 0-.5-.1-.8-.1-.2-.4-.4-.6-.5l-1.2-.3h-.1v-.1l-.3-1.2c-.1-.3-.2-.5-.5-.6-.2-.1-.5-.2-.8-.1l-1.3.3H9L8.2.3zm-1.8 10c.1 0 .2 0 .3-.1L10.9 6c.2-.2.2-.6 0-.8l-.3-.3c-.2-.2-.6-.2-.8 0L6.4 8.4 5.1 7.1c-.2-.2-.6-.2-.8 0l-.2.2c-.2.2-.2.6 0 .8l2 2.1c.1 0 .2.1.3.1z"
                                          style="fill-rule:evenodd;clip-rule:evenodd;fill:#4d76ff"/>
                                </svg>
                                Верифікований<span class="info-attention"><svg xmlns="http://www.w3.org/2000/svg"
                                                                               xml:space="preserve"
                                                                               style="enable-background:new 0 0 13 13"
                                                                               viewBox="0 0 13 13">
                                        <path d="M11.1 1.9C8.6-.6 4.4-.6 1.9 1.9c-2.5 2.5-2.5 6.7 0 9.2 2.5 2.5 6.7 2.5 9.2 0 2.5-2.5 2.5-6.7 0-9.2zM7.3 9.3c0 .5-.4.8-.8.8-.5 0-.8-.4-.8-.8V5.9c0-.5.4-.8.8-.8.5 0 .8.4.8.8v3.4zm-.8-4.8c-.5 0-.8-.4-.8-.8s.3-.8.8-.8.8.3.8.8c0 .4-.3.8-.8.8z"
                                              style="opacity:.3;fill:#6d6d6d;enable-background:new"/>
                                    </svg>
                                <span class="info-attention__content"> Надав усі документи та підтвердив свою діяльність </span></span>
                            </div>
						<?php endif; ?>
						<?php if ( $company_description ): ?>
                            <div class="text-group description_fermer">
								<?php _t( $company_description ); ?>
                            </div>
						<?php endif; ?>
						<?php if ( $delivery_count ): ?>
                            <div class="product-card__list">
                                <div class="product-card__list-item">
                                    <div class="product-card__list-title">Доставка:</div>
                                    <div class="product-card__list-main" style="color:#4D76FF">
										<?php echo $delivery_count; ?> успішних доставок
                                    </div>
                                </div>
                            </div>
						<?php endif; ?>
						<?php if ( $current_user_id && $current_user_id != $author_id ): ?>
                            <div class="product-card__btn w_100_btn">
                                <a class="btn_st move-to-correspondence"
                                   data-product="<?php echo $id; ?>"
                                   data-user="<?php echo $current_user_id; ?>"
                                   href="#">
                                    <span>Написати повідомлення </span>
                                </a>
                                <a class="btn_st b_yelloow show_tel" data-id="<?php echo $id; ?>" href="#">
                                    <span class="show_tel_text">Показати телефон</span>
                                </a>
                            </div>
						<?php endif; ?>
                    </div>
                    <div class="cabinet-item">
                        <div class="cabinet-item__suptitle">Місцезнаходження</div>
                        <ul class="product-item__place marg_0">
                            <li><strong> <?php echo $city ?: $address; ?> </strong></li>
                            <li><strong> Відстань: <?php echo $distance; ?></strong></li>
                        </ul>
						<?php if ( $map_api_url ): ?>
                            <div class="map-product" id="map-product"
                                 data-lat="<?php echo $product_latitude; ?>"
                                 data-long="<?php echo $product_longitude; ?>"
                                 data-pin="<?php echo $assets; ?>img/pin.webp"></div>
                            <script src="<?php echo $map_api_url; ?>" defer></script>
						<?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </section>
<?php
$args  = array(
	'post_type'      => 'products',
	'post_status'    => 'publish',
	'posts_per_page' => 5,
	'post__not_in'   => array( $id ),
	'author__in'     => array( $author_id )
);
$query = new WP_Query( $args );
if ( $query->have_posts() ) :
	?>
    <section class="section-slider line_top pad_section">
        <div class="container">
            <div class="title-section-group">
                <div class="title-sm">
                    Інші оголошення автора
                    <a href="<?php echo get_author_posts_url( $author_id ); ?>"> Дивитися всі
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 15 8" viewBox="0 0 15 8">
                                <path d="M.7 4.7h12l-2.2 2.2c-.6.6.3 1.6 1 .9l3.4-3.3c.3-.3.3-.7 0-.9L11.5.3c-.2-.2-.4-.3-.6-.3-.6 0-.9.7-.5 1.1l2.2 2.2H.6c-.9.1-.8 1.4.1 1.4z"
                                      style="fill:#4d76ff"/>
                            </svg>
                    </a></div>
                <div class="nav-slider">
                    <button class="slick-prev"></button>
                    <button class="slick-next"></button>
                </div>
            </div>
            <div class="similar-slider">
				<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                    <div>
						<?php the_product(); ?>
                    </div>
				<?php endwhile; ?>
            </div>
        </div>
    </section>
<?php endif;
wp_reset_postdata();
wp_reset_query(); ?>

<?php $closest   = get_closest( $id );
if ( $closest ) :
	?>
    <section class="section-slider line_top pad_section">
        <div class="container">
            <div class="title-section-group">
                <div class="title-sm">
                    Схожі оголошення
                </div>
                <div class="nav-slider">
                    <button class="slick-prev"></button>
                    <button class="slick-next"></button>
                </div>
            </div>
            <div class="similar-slider">
				<?php foreach ( $closest as $distance => $items ) : foreach ($items as $item): ?>
                    <div>
						<?php the_product( $item ); ?>
                    </div>
				<?php endforeach; endforeach; ?>
            </div>
        </div>
    </section>
<?php endif; ?>


<?php
if ( $products ):
	$args = array(
		'post_type'      => 'products',
		'post_status'    => 'publish',
		'post__not_in'   => array( $id ),
		'posts_per_page' => 5,
		'post__in'       => explode( ',', $products )
	);
	$query = new WP_Query( $args );
	if ( $query->have_posts() ) :
		?>
        <section class="section-slider line_top pad_section">
            <div class="container">
                <div class="title-section-group">
                    <div class="title-sm">
                        Краще смакує разом
                    </div>
                    <div class="nav-slider">
                        <button class="slick-prev"></button>
                        <button class="slick-next"></button>
                    </div>
                </div>
                <div class="similar-slider">
					<?php while ( $query->have_posts() ) : $query->the_post(); ?>
                        <div>
							<?php the_product(); ?>
                        </div>
					<?php endwhile; ?>
                </div>
            </div>
        </section>
	<?php
	endif;
endif;
wp_reset_postdata();
wp_reset_query();
?>

    <div class="modal modal-sm" id="buy_click">
        <div class="modal-content">
            <div class="modal-title text-center">
                <div class="modal-title__main">Покупка в один клік</div>
            </div>
            <form action="<?php echo $admin_ajax; ?>"
                  method="post"
                  novalidate
                  id="new-advertisement-form"
                  class="new-quick-order form-js">
                <input type="hidden" name="action" value="new_order">
                <input type="hidden" name="type" value="quick_order">
                <input type="hidden" name="id" value="<?php echo $id; ?>">
                <input type="hidden" name="qnt" class="qnt-input-js" value="1">
                <div class="form-group">
                    <input class="input_st" type="text" name="name" placeholder="Ваше ім'я" required="required"/>
                </div>
                <div class="form-group">
                    <input class="input_st" type="tel" name="tel" placeholder="Ваше номер телефону"
                           required="required"/>
                </div>
                <div class="form-group">
                    <input class="input_st" type="text" name="promo" placeholder="Промокод на знижку"/>
                </div>
                <div class="form-count">
                    <div class="form-count__text"> Загалом:</div>
                    <div class="form-count__price"><?php echo '<strong>' . $price . '</strong> ' . $currency; ?></div>
                </div>
				<?php if ( $privacy_policy_text = carbon_get_user_meta( $set, 'privacy_policy_text' ) ): ?>
                    <div class="form-consent">
                        <label>
                            <input class="check_st"
                                   name="consent"
                                   type="checkbox"/>
                            <span></span>
                        </label>
                        <div class="form-consent__text">
							<?php echo $privacy_policy_text; ?>
                        </div>
                    </div>
				<?php endif; ?>
                <button class="btn_st w100 marg_top_15" type="submit">
                    <span>Оформити замовлення </span>
                </button>
            </form>
        </div>
    </div>

<?php get_footer(); ?>