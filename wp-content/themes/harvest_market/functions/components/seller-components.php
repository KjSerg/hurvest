<?php
function the_seller_about_page() {
	global $wp_query;
	$id                       = get_the_ID();
	$author_id                = get_post_field( 'post_author', $id );
	$var                      = variables();
	$set                      = $var['setting_home'];
	$assets                   = $var['assets'];
	$url                      = $var['url'];
	$url_home                 = $var['url_home'];
	$current_author_id        = $author_id ?: $wp_query->get_queried_object()->ID;
	$user_id                  = (int) $current_author_id;
	$user_company_address     = carbon_get_user_meta( $user_id, 'user_company_address' );
	$user_company_latitude    = carbon_get_user_meta( $user_id, 'user_company_latitude' );
	$user_company_longitude   = carbon_get_user_meta( $user_id, 'user_company_longitude' );
	$verification             = carbon_get_user_meta( $user_id, 'user_verification' );
	$user_company_name        = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
	$user_company_city        = carbon_get_user_meta( $user_id, 'user_company_city' ) ?: '';
	$user_company_description = carbon_get_user_meta( $user_id, 'user_company_description' ) ?: '';
	$verification_img         = $verification ? '<img src="' . $assets . 'img/verified.svg" alt=""/>' : '';
	$seller_rating            = get_seller_rating( $user_id );
	$seller_count_review      = get_seller_count_review( $user_id );
	$user_link                = get_seller_page_link( $author_id );
	$user_location            = get_user_location();
	$user_coordinates         = get_user_location_coordinates();
	$user_lat                 = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon                 = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$distance                 = 0;
	if ( $user_company_latitude && $user_company_longitude && $user_lat && $user_lon ) {
		$distance = getDistanceByCoordinates( array(
			'location_from' => array(
				'lat' => (float) $user_lat,
				'lng' => (float) $user_lon,
			),
			'location_to'   => array(
				'lat' => $user_company_latitude,
				'lng' => $user_company_longitude,
			),
			'unit'          => "K"
		) );

	} else {
		$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
		$distance     = getDistance( $user_address, $user_company_address, "K" );
	}
	?>

    <div class="about-seller">
		<?php about_seller_left(); ?>
        <div class="about-seller__right">
            <div class="text-group">
				<?php _t( $user_company_description ); ?>
            </div>
        </div>
    </div>

	<?php
}

function about_seller_left() {
	global $wp_query;
	$id                       = get_the_ID();
	$author_id                = get_post_field( 'post_author', $id );
	$var                      = variables();
	$set                      = $var['setting_home'];
	$assets                   = $var['assets'];
	$url                      = $var['url'];
	$url_home                 = $var['url_home'];
	$current_author_id        = $author_id ?: $wp_query->get_queried_object()->ID;
	$user_id                  = (int) $current_author_id;
	$user_company_address     = carbon_get_user_meta( $user_id, 'user_company_address' );
	$user_company_latitude    = carbon_get_user_meta( $user_id, 'user_company_latitude' );
	$user_company_longitude   = carbon_get_user_meta( $user_id, 'user_company_longitude' );
	$verification             = carbon_get_user_meta( $user_id, 'user_verification' );
	$user_company_name        = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
	$user_company_city        = carbon_get_user_meta( $user_id, 'user_company_city' ) ?: '';
	$user_company_description = carbon_get_user_meta( $user_id, 'user_company_description' ) ?: '';
	$verification_img         = $verification ? '<img src="' . $assets . 'img/verified.svg" alt=""/>' : '';
	$seller_rating            = get_seller_rating( $user_id );
	$seller_count_review      = get_seller_count_review( $user_id );
	$user_link                = get_seller_page_link( $author_id );
	$user_location            = get_user_location();
	$user_coordinates         = get_user_location_coordinates();
	$user_lat                 = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon                 = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$distance                 = 0;
	if ( $user_company_latitude && $user_company_longitude && $user_lat && $user_lon ) {
		$distance = getDistanceByCoordinates( array(
			'location_from' => array(
				'lat' => (float) $user_lat,
				'lng' => (float) $user_lon,
			),
			'location_to'   => array(
				'lat' => $user_company_latitude,
				'lng' => $user_company_longitude,
			),
			'unit'          => "K"
		) );

	} else {
		$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
		$distance     = getDistance( $user_address, $user_company_address, "K" );
	}
	?>
    <div class="about-seller__left">
        <div class="about-seller__title">
			<?php echo $user_company_name . $verification_img; ?>
        </div>
        <div class="about-seller__rating">
            <div class="about-seller__rating-testimonials"> <?php echo $seller_count_review; ?> відгуків</div>
            <div class="about-seller__rating-main"> <?php echo $seller_rating; ?></div>
        </div>
        <div class="about-seller__map">
            <div class="about-seller__place">
                <div class="about-seller__place-item"><?php echo $user_company_city; ?></div>
                <div class="about-seller__place-item">Відстань: <?php echo $distance; ?></div>
            </div>
            <div class="seller-map" id="seller-map"
                 data-lat="<?php echo $user_company_latitude; ?>"
                 data-long="<?php echo $user_company_longitude; ?>"
                 data-pin="<?php echo $assets ?>img/pin.png"></div>
			<?php if ( $map_api_url = carbon_get_theme_option( 'autocomplete_api_url' ) ): ?>
                <script src="<?php echo $map_api_url; ?>" id="google-map-api" defer></script>
			<?php endif; ?>
        </div>
    </div>
	<?php
}

function the_seller_reviews_page() {
	global $wp_query;
	$current_user_id     = get_current_user_id();
	$_order              = $_GET['order'] ?? '';
	$_orderby            = $_GET['orderby'] ?? '';
	$pagenum             = $_GET['pagenum'] ?? 1;
	$is_logged           = is_user_logged_in();
	$id                  = get_the_ID();
	$author_id           = get_post_field( 'post_author', $id );
	$var                 = variables();
	$set                 = $var['setting_home'];
	$assets              = $var['assets'];
	$url                 = $var['url'];
	$url_home            = $var['url_home'];
	$current_author_id   = $author_id ?: $wp_query->get_queried_object()->ID;
	$user_id             = (int) $current_author_id;
	$seller_rating       = get_seller_rating( $user_id );
	$seller_count_review = get_seller_count_review( $user_id );
	$user_link           = get_seller_page_link( $author_id );
	$policy_page_id      = (int) get_option( 'wp_page_for_privacy_policy' );
	$consent_text        = $policy_page_id ?
		' Даю згоду на обробку персональних даних та погоджуюся з <a href="' . get_the_permalink( $policy_page_id ) . '">політикою конфіденційності </a>' :
		'Даю згоду на обробку персональних даних та погоджуюся з політикою конфіденційності';
	?>
    <div class="seller-testimonials">
        <div class="seller-testimonials__left">
            <div class="seller-testimonials__top">
                <div class="seller-testimonials__title"><?php echo $seller_count_review; ?> відгуків</div>
                <div class="seller-testimonials__rating"><?php echo $seller_rating; ?></div>
            </div>
            <div class="seller-testimonials__list testimonials container-js">
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
						the_seller_testimonial();
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
        </div>
        <div class="seller-testimonials__right">
			<?php if ( $current_user_id ): ?>
                <div class="seller-testimonials__form">
                    <div class="seller-testimonials__form-top">
                        <div class="seller-testimonials__form-title">Залишити відгук</div>
                    </div>
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
                                    <div class="feedback-rating__text"> Оцініть продавця:</div>
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
									<?php echo $consent_text; ?>
                                </div>
                            </div>
                            <button class="btn_st" type="submit">
                                <span> Відправити відгук </span>
                            </button>
                        </div>
                    </form>
                </div>
			<?php else: ?>
                <div class="seller-testimonials__form">
                    <div class="seller-testimonials__form-top">
                        <div class="seller-testimonials__form-text">
                            Відгук може залишити тільки залогінений користувач
                        </div>
                    </div>
                </div>
			<?php endif; ?>
        </div>
    </div>
	<?php
}

function the_seller_testimonial( $id = false ) {
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

    <div class="seller-testimonials__item testimonials-item__text">
        <div class="seller-testimonials__item-top">
            <div class="seller-testimonials__item-user">
                <div class="seller-testimonials__item-user-ava">
                    <img src="<?php echo $avatar_url; ?>" alt=""/>
                </div>
                <div class="seller-testimonials__item-user-content">
                    <div class="seller-testimonials__item-user-title">
						<?php echo $title ?: ( $first_name . ' ' . $last_name ); ?>
                    </div>
                    <div class="seller-testimonials__item-user-date">
						<?php echo get_the_date( 'd.m.Y', $id ); ?>
                    </div>
                </div>
            </div>
            <div class="seller-testimonials__item-rating"> <?php echo $review_rating; ?></div>
        </div>
		<?php if ( mb_strlen( $string, 'UTF-8' ) > 100 ): ?>
            <div class="seller-testimonials__item-content">
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
                    <a class="more-text-btn" href="#" data-text="Показати ще"
                       data-show="Менше"><span>Показати ще</span>
                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                             style="enable-background:new 0 0 8 15" viewbox="0 0 8 15">
                                                <path d="M3.3.7v12l-2.2-2.2c-.6-.6-1.6.3-.9 1l3.3 3.4c.3.3.7.3.9 0l3.3-3.4c.2-.2.3-.4.3-.6 0-.6-.7-.9-1.1-.5l-2.2 2.2V.6c-.1-.9-1.4-.8-1.4.1z"
                                                      style="fill:#4d76ff"></path>
                                            </svg>
                    </a>
                </div>
            </div>
		<?php else: ?>
            <div class="seller-testimonials__item-content">
                <div class="text-group">
                    <p>
						<?php echo mb_strimwidth( $string, 0, 100, "...", 'UTF-8' ); ?>
                    </p>
                </div>
            </div>
		<?php endif; ?>
    </div>
	<?php
}

function the_seller_contacts_page() {
	global $wp_query;
	$current_user_id          = get_current_user_id();
	$id                       = get_the_ID();
	$author_id                = get_post_field( 'post_author', $id );
	$var                      = variables();
	$set                      = $var['setting_home'];
	$assets                   = $var['assets'] . 'seller/';
	$url                      = $var['url'];
	$url_home                 = $var['url_home'];
	$current_author_id        = $author_id ?: $wp_query->get_queried_object()->ID;
	$user_id                  = (int) $current_author_id;
	$current_user             = get_user_by( 'ID', $user_id );
	$email                    = $current_user->user_email ?: '';
	$user_company_address     = carbon_get_user_meta( $user_id, 'user_company_address' );
	$user_company_office_type = carbon_get_user_meta( $user_id, 'user_company_office_type' ) ?: '';
	$user_company_phone       = carbon_get_user_meta( $user_id, 'user_company_phone' ) ?: '';
	$user_company_latitude    = carbon_get_user_meta( $user_id, 'user_company_latitude' );
	$user_company_longitude   = carbon_get_user_meta( $user_id, 'user_company_longitude' );
	$verification             = carbon_get_user_meta( $user_id, 'user_verification' );
	$user_company_name        = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
	$user_company_city        = carbon_get_user_meta( $user_id, 'user_company_city' ) ?: '';
	$user_company_description = carbon_get_user_meta( $user_id, 'user_company_description' ) ?: '';
	$user_payment_methods     = carbon_get_user_meta( $user_id, 'user_payment_methods' ) ?: '';
	$payment_methods          = carbon_get_theme_option( 'payment_methods' );
	$_delivery_methods        = carbon_get_user_meta( $user_id, 'user_delivery_methods' );
	$types                    = carbon_get_theme_option( 'delivery_types' );
	$verification_img         = $verification ? '<img src="' . $assets . 'img/verified.svg" alt=""/>' : '';
	$seller_rating            = get_seller_rating( $user_id );
	$seller_count_review      = get_seller_count_review( $user_id );
	$user_link                = get_seller_page_link( $author_id );
	$user_location            = get_user_location();
	$user_coordinates         = get_user_location_coordinates();
	$user_lat                 = $user_coordinates['lat'] ?? ( $user_location['lat'] ?? '' );
	$user_lon                 = $user_coordinates['lon'] ?? ( $user_location['lon'] ?? '' );
	$distance                 = 0;
	$login_page               = carbon_get_theme_option( 'login_page' );
	$socials                  = get_social_networks_name();
	if ( $user_company_latitude && $user_company_longitude && $user_lat && $user_lon ) {
		$distance = getDistanceByCoordinates( array(
			'location_from' => array(
				'lat' => (float) $user_lat,
				'lng' => (float) $user_lon,
			),
			'location_to'   => array(
				'lat' => $user_company_latitude,
				'lng' => $user_company_longitude,
			),
			'unit'          => "K"
		) );

	} else {
		$user_address = ( $user_location['country'] ?? '' ) . ' ' . ( $user_location['regionName'] ?? '' ) . ' ' . ( $user_location['city'] ?? '' );
		$distance     = getDistance( $user_address, $user_company_address, "K" );
	}
	$user_payment_methods_names = array();
	foreach ( $user_payment_methods as $method ) {
		$_payment_method              = get_method_by_value( $method, $payment_methods );
		$_type_item                   = $_payment_method['_type'];
		$_title_item                  = $_payment_method['title'];
		$user_payment_methods_names[] = $_title_item;
	}
	?>

    <div class="about-seller">
		<?php about_seller_left(); ?>
        <div class="about-seller__right">
			<?php if ( $current_user_id ): ?>
                <div class="seller-contact">
					<?php if ( $user_company_phone ):
						$user_company_phones = explode( ',', $user_company_phone );
						if ( $user_company_phones ):
							?>
                            <div class="seller-contact__item">
                                <div class="seller-contact__item-ico">
                                    <img src="<?php echo $assets; ?>img/seller-contact1.svg" alt=""/>
                                </div>
                                <div class="seller-contact__item-main">
									<?php foreach ( $user_company_phones as $phone ): ?>
                                        <div class="seller-contact__item-main-item">
                                            <strong><a href="<?php the_phone_link( $phone ); ?>"><?php echo $phone; ?></a></strong>
                                        </div>
									<?php endforeach; ?>
                                </div>
                            </div>
						<?php endif; ?>
					<?php endif; ?>
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico">
                            <img src="<?php echo $assets; ?>img/seller-contact2.svg"
                                 alt=""/></div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item">
                                <strong><a href="mailto:<?php echo $email; ?>"><?php echo $email; ?></a></strong>
                            </div>
                        </div>
                    </div>
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico"><img src="<?php echo $assets; ?>img/seller-contact3.svg"
                                                                   alt=""/></div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item">
                                <a href="#">Почати чат</a>
                            </div>
                        </div>
                    </div>
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico">
                            <img src="<?php echo $assets; ?>img/seller-contact4.svg" alt=""/>
                        </div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item">
                                <span><?php echo $user_company_address; ?> </span>(<?php echo $user_company_office_type; ?>
                                продавця)
                            </div>
                        </div>
                    </div>
                </div>
			<?php else: ?>
				<?php if ( $login_page ): ?>
                    <div class="seller-text-info">
                        <a href="<?php echo get_the_permalink( $login_page[0]['id'] ) ?>">Увійдіть,</a> щоб побачити
                        контактну інформацію продавця
                    </div>
				<?php endif; ?>
                <div class="seller-contact">
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico"><img src="<?php echo $assets; ?>img/seller-contact1.svg"
                                                                   alt=""/></div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item">
                                <strong>+38 (068) 646 XX XX</strong>
                            </div>
                            <div class="seller-contact__item-main-item">
                                <strong>+38 (068) 646 XX XX</strong>
                            </div>
                        </div>
                    </div>
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico"><img src="<?php echo $assets; ?>img/seller-contact2.svg"
                                                                   alt=""/></div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item"><strong>mail@gmail.com</strong></div>
                        </div>
                    </div>
					<?php if ( $login_page ): ?>
                        <div class="seller-contact__item">
                            <div class="seller-contact__item-ico"><img
                                        src="<?php echo $assets; ?>img/seller-contact3.svg"
                                        alt=""/></div>
                            <div class="seller-contact__item-main">
                                <div class="seller-contact__item-main-item">
                                    <a href="<?php echo get_the_permalink( $login_page[0]['id'] ) ?>">Увійдіти, </a>щоб
                                    почати чат
                                </div>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="seller-contact__item">
                        <div class="seller-contact__item-ico">
                            <img src="<?php echo $assets; ?>img/seller-contact4.svg" alt=""/></div>
                        <div class="seller-contact__item-main">
                            <div class="seller-contact__item-main-item">
                                <span><?php echo $user_company_address; ?> </span>(<?php echo $user_company_office_type; ?>
                                продавця)
                            </div>
                        </div>
                    </div>
                </div>
			<?php endif; ?>

			<?php the_seller_socials( $user_id ); ?>
        </div>
    </div>
	<?php the_seller_schedule( $user_id ); ?>
    <div class="seller-info-column">
		<?php if ( $_delivery_methods ): foreach ( $_delivery_methods as $delivery_method ):
			$_delivery_method = get_method_by_value( $delivery_method, $types );
			$_type_item = $_delivery_method['_type'];
			$_title_item = $_delivery_method['title'];
			$_image = $_delivery_method['image'];
			?>
            <div class="seller-info-column__item">
				<?php if ( $_image ): ?>
                    <div class="seller-info-column__item-media img100">
                        <img src="<?php _u( $_image ); ?>" alt=""/>
                    </div>
				<?php endif; ?>
                <div class="seller-info-column__item-content">
                    <div class="seller-info-column__item-title"><?php echo $_title_item; ?></div>
                    <div class="seller-info-column__item-text"></div>
                </div>
            </div>
		<?php endforeach; endif; ?>
        <div class="seller-info-column__item">
            <div class="seller-info-column__item-media" style="background:#EBAB03"><img
                        src="<?php echo $assets; ?>img/payment-ico.svg"
                        alt=""/></div>
            <div class="seller-info-column__item-content">
                <div class="seller-info-column__item-title">Оплата</div>
                <div class="seller-info-column__item-text">
					<?php echo implode( ', ', $user_payment_methods_names ); ?>
                </div>
            </div>
        </div>
    </div>
	<?php
}

function the_seller_schedule( $user_id ) {
	$user_work_time_organization = carbon_get_user_meta( $user_id, 'user_work_time_organization' );
	if ( $user_work_time_organization ):
		$user_work_time_organization = json_decode( $user_work_time_organization, true );
		$work_days               = get_work_days_organization( $user_work_time_organization );
		if ( $work_days ):
			?>

            <div class="seller-shedule">
                <div class="seller-shedule__item">
                    <div class="seller-shedule__title">Графік роботи:</div>
                    <ul class="seller-shedule__list">
						<?php foreach ( $work_days as $day => $time ): ?>
                            <li><span><?php echo get_day_name( $day ); ?>: </span><?php echo $time; ?></li>
						<?php endforeach; ?>
                    </ul>
                </div>
            </div>

		<?php
		endif;
	endif;
}

function get_day_name( $number ) {
	$number   = (int) $number;
	$dayNames = [
		__( 'Monday' ),
		__( 'Tuesday' ),
		__( 'Wednesday' ),
		__( 'Thursday' ),
		__( 'Friday' ),
		__( 'Saturday' ),
		__( 'Sunday' ),
	];

	return $dayNames[ $number - 1 ];
}

function get_work_days_organization( $user_work_time_organization ) {
	$res = array();
	if ( $user_work_time_organization ) {
		foreach ( $user_work_time_organization as $index => $data ) {
			$days = $data[0];
			if ( $days ) {
				foreach ( $days as $day ) {
					$res[ $day ] = implode( ':', $data[1] ) . '-' . implode( ':', $data[2] );
				}
			}
		}
	}

	return $res;
}

function the_seller_socials( $user_id ) {
	$items    = get_user_current_social_networks( $user_id );
	$telegram = carbon_get_user_meta( $user_id, 'telegram' );
	if ( $items || $telegram ):
		?>
        <div class="seller-social">
            <div class="seller-social__text">Наші соціальні мережі:</div>
            <ul class="seller-social__main">
				<?php
				if ( $items ) :
					foreach ( $items as $name => $item ) :
						if ( 'TikTok' == $name ):
							?>
                            <li>
                                <a href="<?php echo $item; ?>" target="_blank" rel="nofollow"
                                   style="background:#010101;">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 13 14" viewBox="0 0 13 14">
                                            <path d="M6.5 0c-.4 0-.7.3-.7.6v8.9c0 .7-.6 1.3-1.3 1.3s-1.3-.6-1.3-1.3c0-.7.6-1.3 1.3-1.3.4 0 .6-.3.6-.6V5.7c0-.4-.3-.6-.6-.6C2 5.1 0 7.1 0 9.5 0 12 2 14 4.6 14c2.5 0 4.6-2 4.6-4.5V6.1c1 .5 2.1.9 3.2.9.3 0 .6-.3.6-.6V4.5c0-.4-.3-.6-.6-.6-1.8 0-3.2-1.4-3.2-3.2-.1-.4-.4-.7-.8-.7H6.5z"
                                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                                        </svg>
                                </a>
                            </li>
						<?php elseif ( 'YouTube' == $name ): ?>
                            <li><a href="<?php echo $item; ?>" target="_blank" rel="nofollow"
                                   style="background:#FF0000;">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 14 11" viewBox="0 0 14 11">
                                            <path d="M11.4 0H2.6C1.2 0 0 1.3 0 2.9v5.2C0 9.7 1.2 11 2.6 11h8.7c1.5 0 2.6-1.3 2.6-2.9V2.9C14 1.3 12.8 0 11.4 0zM5.2 7.9V3.1l3.7 2.4-3.7 2.4z"
                                                  style="fill:#fff"/>
                                        </svg>
                                </a></li>
						<?php elseif ( 'Instagram' == $name ): ?>
                            <li><a href="<?php echo $item; ?>" target="_blank" rel="nofollow"
                                   style="background:#D81F78;">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 14 14" viewBox="0 0 14 14">
                                            <path d="M7 4.4C5.6 4.4 4.4 5.6 4.4 7S5.6 9.6 7 9.6 9.6 8.4 9.6 7 8.4 4.4 7 4.4z"
                                                  style="fill:#fff"/>
                                        <path d="M10.9 0H3.1C1.4 0 0 1.4 0 3.1v7.8C0 12.6 1.4 14 3.1 14h7.8c1.7 0 3.1-1.4 3.1-3.1V3.1C14 1.4 12.6 0 10.9 0zM7 11.5c-2.5 0-4.5-2-4.5-4.5s2-4.5 4.5-4.5 4.5 2 4.5 4.5-2 4.5-4.5 4.5zm4.6-8.2c-.5 0-1-.4-1-1s.4-1 1-1c.5 0 1 .4 1 1s-.4 1-1 1z"
                                              style="fill:#fff"/>
                                        </svg>
                                </a></li>
						<?php elseif ( 'Facebook' == $name ): ?>
                            <li>
                                <a href="<?php echo $item; ?>" target="_blank" rel="nofollow"
                                   style="background:#0866FF;">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 8.4 14" viewBox="0 0 8.4 14">
                                            <path d="M2.3 7.9H.4c-.3 0-.4-.1-.4-.4V5.4c0-.3.1-.4.4-.4h1.9V3.5c0-.7.1-1.4.5-2 .4-.6 1-1 1.7-1.3C5.1.1 5.6 0 6.1 0H8c.3 0 .4.1.4.3v2c0 .2-.1.3-.4.3H6.4c-.5 0-.8.2-.8.7v1.6h2.3c.3 0 .4.1.4.4v2.1c0 .3-.1.4-.4.4H5.6v5.7c0 .3-.1.4-.4.4H2.8c-.3 0-.4-.1-.4-.4-.1-1.7-.1-5.5-.1-5.6z"
                                                  style="fill:#fff"/>
                                        </svg>
                                </a>
                            </li>
						<?php
						endif;
					endforeach;
				endif;
				?>

				<?php
				if ( $telegram ):
					?>

                    <li>
                        <a href="https://t.me/<?php echo $telegram; ?>" target="_blank" rel="nofollow"
                           style="background:#25A3E1;">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 14 12" viewBox="0 0 14 12">
                                            <path d="m5.5 7.9-.2 3.4c.3 0 .5-.1.6-.3l1.6-1.5 3.2 2.4c.6.3 1 .2 1.2-.6L14 1.1c.2-.9-.3-1.2-.9-1L.6 5c-.8.3-.8.8-.1 1l3.2 1 7.4-4.7c.3-.2.7-.1.4.1l-6 5.5z"
                                                  style="fill:#fff"></path>
                                        </svg>
                        </a>

                    </li>
				<?php endif; ?>

            </ul>
        </div>
	<?php
	endif;
}