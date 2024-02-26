<?php
$id        = get_the_ID();
$author_id = get_post_field( 'post_author', $id );
get_header();
global $wp_query;
$is_logged            = is_user_logged_in();
$var                  = variables();
$set                  = $var['setting_home'];
$assets               = $var['assets'];
$url                  = $var['url'];
$url_home             = $var['url_home'];
$id                   = get_the_ID();
$isLighthouse         = isLighthouse();
$size                 = isLighthouse() ? 'thumbnail' : 'full';
$current_author_id    = $author_id ?: $wp_query->get_queried_object()->ID;
$user_id              = $current_author_id;
$current_user_id      = get_current_user_id();
$current_user         = get_user_by( 'ID', $user_id );
$email                = $current_user->user_email ?: '';
$display_name         = $current_user->display_name ?: '';
$first_name           = $current_user->first_name ?: '';
$last_name            = $current_user->last_name ?: '';
$name                 = $first_name ?: $display_name;
$user_avatar          = carbon_get_user_meta( $user_id, 'user_avatar' );
$user_seller          = carbon_get_user_meta( $user_id, 'user_seller' );
$avatar_url           = $user_avatar ? _u( $user_avatar, 1 ) : get_avatar_url( $user_id );
$_order               = $_GET['order'] ?? '';
$_orderby             = $_GET['orderby'] ?? '';
$pagenum              = $_GET['pagenum'] ?? 1;
$days_count           = carbon_get_theme_option( 'days_count' ) ?: 30;
$posts_per_page       = get_option( 'posts_per_page' );
$categories           = get_terms( array(
	'taxonomy'   => 'categories',
	'hide_empty' => false,
	'parent'     => 0,
) );
$company_description  = carbon_get_user_meta( $user_id, 'user_company_description' );
$verification         = carbon_get_user_meta( $user_id, 'user_verification' );
$user_company_name    = carbon_get_user_meta( $user_id, 'user_company_name' ) ?: '';
$delivery_count       = carbon_get_user_meta( $user_id, 'delivery_count' ) ?: 0;
$user_company_gallery = carbon_get_user_meta( $user_id, 'user_company_gallery' );
$user_phone           = carbon_get_user_meta( $user_id, 'user_phone' );
$user_company_address = carbon_get_user_meta( $user_id, 'user_company_address' );
$user_company_color   = carbon_get_user_meta( $user_id, 'user_company_color' );
$seller_rating        = get_seller_rating( $user_id );
$seller_count_review  = get_seller_count_review( $user_id );
$head_banner          = $user_company_gallery ? _u( $user_company_gallery[0], 1 ) : $assets . 'img/user_head.webp';
$attr                 = $user_company_color ? "style='background-color:$user_company_color'" : '';

?>

    <section class="section-user  " <?php echo $attr; ?>>
        <div class="user-farming__head"
             style="background:url(<?php echo $head_banner; ?>) no-repeat top center/cover">
            <div class="container">
                <div class="user-farming__title">
					<?php echo $user_company_name; ?>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="user-farming pad_bot_40 pad_top_40">
                <div class="faq js-collapse">
                    <div class="faq-item js-collapse-item">
                        <div class="faq-item__title js-collapse-title">Фермерське господарство<span></span></div>
                        <div class="faq-item__content js-collapse-content">
                            <div class="farming-info">
                                <div class="farming-info__left">
                                    <div class="farming-info__list">
                                        <div class="farming-info__list-item">
                                            <div class="farming-info__list-item-title">Рейтинг:</div>
                                            <div class="farming-info__list-item-main">
                                                <ul class="product-item__reviews">
                                                    <li>
                                                        <a class="move-to-element" href="#reviews-section">
															<?php echo $seller_count_review ?> відгуків
                                                        </a>
                                                    </li>
                                                    <li><strong><?php echo $seller_rating; ?> </strong>
                                                        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                             style="enable-background:new 0 0 12 11.2"
                                                             viewBox="0 0 12 11.2">
                                                                <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                                      style="fill:#ffc327"/>
                                                            </svg>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                        <div class="farming-info__list-item">
                                            <div class="farming-info__list-item-title">Господарство:</div>
                                            <div class="farming-info__list-item-main">
                                                <strong>
													<?php echo $user_company_name; ?>
                                                </strong>
                                            </div>
                                        </div>
										<?php if ( $user_phone ): ?>
                                            <div class="farming-info__list-item">
                                                <div class="farming-info__list-item-title">Номер телефону:</div>
                                                <div class="farming-info__list-item-main">
                                                    <a href="<?php the_phone_link( $user_phone ); ?>">
                                                        <strong><?php echo $user_phone; ?></strong>
                                                    </a>
                                                </div>
                                            </div>
										<?php endif; ?>
										<?php if ( $user_company_address ): ?>
                                            <div class="farming-info__list-item">
                                                <div class="farming-info__list-item-title">Адреса:</div>
                                                <div class="farming-info__list-item-main">
													<?php echo $user_company_address; ?>
                                                </div>
                                            </div>
										<?php endif; ?>
										<?php if ( $author_id  == $current_user_id ): ?>
                                            <div class="farming-info__list-item">
                                                <div class="farming-info__list-item-title">Колір фону сторінки:</div>
                                                <div class="farming-info__list-item-main">
                                                    <input type="color" name="user_color"
                                                           value="<?php echo $user_company_color; ?>"
                                                           class="user-color-input">
                                                </div>
                                            </div>
										<?php endif; ?>
                                    </div>
                                </div>
                                <div class="farming-info__right">
									<?php if ( $verification ): ?>
                                        <div class="product-verified">
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                                <path d="M8.2.3C8 .1 7.8 0 7.5 0c-.3 0-.5.1-.7.3l-.9.9h-.2L4.5.9c-.2-.1-.5 0-.7.1-.3.1-.5.4-.5.6l-.4 1.3V3h-.1l-1.2.3c-.2 0-.5.2-.6.4-.1.3-.2.6-.1.8l.3 1.2v.2l-.9.9c-.2.2-.3.4-.3.7 0 .3.1.5.3.7l.9.9v.2l-.3 1.2c-.1.3 0 .5.1.8.1.2.4.4.6.5l1.2.3h.1v.1l.3 1.2c.1.3.2.5.5.6.2.1.5.2.8.1l1.2-.3h.2l.9.9c.2.2.4.3.7.3.3 0 .5-.1.7-.3l.9-.9h.2l1.2.3c.3.1.5 0 .8-.1.2-.1.4-.4.5-.6l.3-1.2v-.1h.1l1.2-.3c.3-.1.5-.2.6-.5.1-.2.2-.5.1-.8l-.3-1.2v-.2l.9-.9c.2-.2.3-.4.3-.7 0-.3-.1-.5-.3-.7l-.9-.9v-.2l.3-1.2c.1-.3 0-.5-.1-.8-.1-.2-.4-.4-.6-.5l-1.2-.3h-.1v-.1l-.3-1.2c-.1-.3-.2-.5-.5-.6-.2-.1-.5-.2-.8-.1l-1.3.3H9L8.2.3zm-1.8 10c.1 0 .2 0 .3-.1L10.9 6c.2-.2.2-.6 0-.8l-.3-.3c-.2-.2-.6-.2-.8 0L6.4 8.4 5.1 7.1c-.2-.2-.6-.2-.8 0l-.2.2c-.2.2-.2.6 0 .8l2 2.1c.1 0 .2.1.3.1z"
                                                      style="fill-rule:evenodd;clip-rule:evenodd;fill:#4d76ff"/>
                                            </svg>
                                            Верифікований
                                        </div>
									<?php endif; ?>
                                    <div class="farming-info__text">
                                        <div class="text-group">
											<?php _t( $company_description ); ?>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
			<?php if ( $user_company_gallery ): ?>
                <div class="user-farming-gal pad_bot_40 line_bot">
                    <div class="title-section-group">
                        <div class="title-sm">Фото господарства</div>
                        <div class="nav-slider">
                            <button class="slick-prev"></button>
                            <button class="slick-next"></button>
                        </div>
                    </div>
                    <div class="farming-gal">
						<?php foreach ( $user_company_gallery as $image_id ): ?>
                            <div>
                                <a class="farming-gal__item"
                                   href="<?php _u( $image_id ); ?>"
                                   data-fancybox="farming-gal">
                                    <img src="<?php _u( $image_id ); ?>" alt=""/>
                                </a>
                            </div>
						<?php endforeach; ?>
                    </div>
                </div>
			<?php endif; ?>
            <div class="sort-wrap pad_top_40">
                <div class="search-wrap">
                    <a class="tog-filter" href="#">
                        <span>Смарт фільтри<svg
                                    xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                    style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                    <path d="M15 12.7c0 .1 0 .2-.1.3 0 .1-.1.2-.2.2-.1.1-.2.1-.2.2-.1 0-.2.1-.3.1H7.4c-.2.4-.4.8-.8 1.1-.4.3-.9.4-1.3.4-.5 0-.9-.1-1.3-.4-.4-.3-.7-.6-.8-1.1H.8c-.2 0-.4-.1-.5-.2-.2-.2-.3-.4-.3-.6 0-.2.1-.4.2-.5.2-.1.4-.2.6-.2h2.4c.2-.4.4-.8.8-1.1.4-.3.8-.4 1.3-.4s.9.1 1.3.4c.4.3.7.6.8 1.1h6.9c.1 0 .2 0 .3.1.1 0 .2.1.2.2.1.1.1.2.2.2v.2zm-.8-6h-1.6c-.2-.4-.4-.8-.8-1.1-.4-.3-.8-.4-1.3-.4s-.9.2-1.3.5c-.4.2-.7.6-.8 1H.8c-.2 0-.4.1-.6.3-.1.1-.2.3-.2.5s.1.4.2.5c.1.1.3.2.5.2h7.6c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.4-.3.7-.6.8-1.1h1.6c.2 0 .4-.1.5-.2.3-.1.4-.3.4-.5s-.1-.4-.2-.5c-.2-.2-.4-.3-.6-.3zM.8 3h3.9c.2.4.4.8.8 1.1.4.3.8.4 1.3.4s.9-.1 1.3-.4c.3-.3.6-.7.8-1.1h5.4c.2 0 .4-.1.5-.2.1-.2.2-.4.2-.6 0-.2-.1-.4-.2-.5-.1-.1-.3-.2-.5-.2H8.9C8.7 1.1 8.4.7 8 .4 7.7.1 7.2 0 6.8 0s-1 .1-1.3.4c-.4.3-.7.7-.9 1.1H.8c-.2 0-.4.1-.5.2-.2.2-.3.4-.3.5 0 .2.1.4.2.5.2.2.4.3.6.3z"
                                          style="fill:#262c40"/>
                                </svg></span>
                    </a>
                </div>
                <div class="sort-group">
                    <form method="get" action="<?php echo get_author_posts_url( $user_id ); ?>">
                        <input type="hidden" name="order" value="desc">
						<?php
						if ( $_GET ) {
							foreach ( $_GET as $name => $value ) {
								if ( $name && $value ) {
									echo "<input type='hidden' name='$name' value='$value'>";
								}
							}
						}
						?>
                        <div class="form-horizontal">
                            <div class="half" style="margin-left: auto;">
                                <select class="select_st sort-select trigger-on-change" name="orderby">
                                    <option value="">Сортувати</option>
                                    <option
										<?php echo $_order == 'desc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                            value="date" data-order="desc">Спочатку новіші
                                    </option>
                                    <option
										<?php echo $_order == 'asc' && $_orderby == 'date' ? 'selected' : ''; ?>
                                            value="date" data-order="asc">Спочатку старіші
                                    </option>
                                </select>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="catalog-in pad_section_sm_top pad_bot_40 line_bot">
                <div class="catalog container-js">
					<?php
					$query = get_query_index_data( array( 'author__in' => array( $user_id ) ) );
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
					<?php echo _get_more_author_link( $query->max_num_pages, $author_id ); ?>
                </div>
            </div>
        </div>
    </section>

    <section class="seller-reviews-section pad_top_40" id="reviews-section" <?php echo $attr; ?>>
        <div class="container">
            <div class="user-testimonials">
                <div class="product-testimonials__top">
                    <div class="product-testimonials__top-item">
                        <img src="<?php echo $assets; ?>img/star-gold.svg" alt=""/><?php echo $seller_rating ?>
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
						'meta_value'  => $user_id
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
                                        <label class="rating-item"><input type="radio" name="rating"
                                                                          value="1"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
                                        <label class="rating-item">
                                            <input type="radio" name="rating" value="2"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
                                        <label class="rating-item">
                                            <input type="radio" name="rating" value="3"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
                                        <label class="rating-item">
                                            <input type="radio" name="rating" value="4"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
                                        <label class="rating-item"><input type="radio" name="rating" value="5"/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 12 11.2" viewBox="0 0 12 11.2">
                                                    <path d="M12 4.2c-.1-.2-.3-.4-.5-.4L8 3.5 6.6.4C6.5.1 6.3 0 6 0s-.5.1-.6.4L4 3.5l-3.4.3c-.3 0-.5.2-.6.4 0 .3 0 .5.2.7l2.6 2.2-.8 3.3c-.1.2 0 .5.2.6.1.1.2.1.4.1.1 0 .2 0 .3-.1l3-1.7 3 1.7c.2.1.5.1.7 0 .2-.1.3-.4.2-.6l-.6-3.3 2.6-2.2c.2-.2.2-.4.2-.7z"
                                                          style="fill:#ffc327"/>
                                                </svg>
                                        </label>
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
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>