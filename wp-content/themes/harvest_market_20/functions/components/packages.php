<?php
function the_packages_page() {
	the_header_cabinet();
	$var          = variables();
	$set          = $var['setting_home'];
	$assets       = $var['assets'];
	$url          = $var['url'];
	$url_home     = $var['url_home'];
	$admin_ajax   = $var['admin_ajax'];
	$user_id      = get_current_user_id();
	$current_user = get_user_by( 'ID', $user_id );
	$email        = $current_user->user_email ?: '';
	$display_name = $current_user->display_name ?: '';
	$first_name   = $current_user->first_name ?: '';
	$last_name    = $current_user->last_name ?: '';
	$regions      = get_terms( array(
		'taxonomy'   => 'regions',
		'hide_empty' => false
	) );
	?>
    <div class="create-item-main">
        <div class="package-service">
            <div class="package-service__left">
				<?php
				$args  = array(
					'post_type'      => 'services',
					'post_status'    => 'publish',
					'posts_per_page' => - 1,
				);
				$query = new WP_Query( $args );
				if ( $query->have_posts() ) :
					while ( $query->have_posts() ) :
						$query->the_post();
						the_package( get_the_ID(), $regions );
					endwhile;
				else:
					?>
                    <div class="cabinet-item">
                        <div class="package-service__buy">
                            <div class="package-service__buy-media">
                                <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                            </div>
                            <div class="package-service__buy-title"> Тут пусто ...</div>
                            <div class="package-service__buy-text">
                                Купуйте і користуйтеся всіма нашими платними послугами
                            </div>
                        </div>
                    </div>
				<?php
				endif;
				?>
            </div>
            <div class="package-service__right">
				<?php the_right_bar_packages(); ?>
            </div>
        </div>
    </div>
	<?php
}

function the_right_bar_packages() {
	$var               = variables();
	$set               = $var['setting_home'];
	$assets            = $var['assets'];
	$url               = $var['url'];
	$url_home          = $var['url_home'];
	$admin_ajax        = $var['admin_ajax'];
	$order             = $_POST['order'] ?? '';
	$product           = ( $_POST['product'] ?? '' ) ?: ( $_GET['product'] ?? '' );
	$currency          = carbon_get_theme_option( 'currency' );
	if ( ! $order || $order == '{}' ):
		$count_purchased = get_purchased_number();
		if ( $count_purchased === 0 ):
			?>
            <div class="cabinet-item">
                <div class="package-service__buy">
                    <div class="package-service__buy-media">
                        <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                    </div>
                    <div class="package-service__buy-title">
                        Тут пусто...
                    </div>
                    <div class="package-service__buy-text">
                        Купуйте і користуйтеся всіма нашими платними послугами
                    </div>
                </div>
            </div>
		<?php else:
			$personal_page = carbon_get_theme_option( 'personal_area_page' );
			$_url      = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
			?>
            <div class="cabinet-item">
                <div class="package-service__buy">
                    <div class="package-service__buy-media">
                        <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                    </div>
                    <div class="package-service__buy-title">
                        Послуги які ви придбиали
                    </div>
                    <a class="btn_st w100 " href="<?php echo $_url . '?route=purchased' ?>">
                        <span>Ваші послуги</span>
                    </a>
                </div>
            </div>
		<?php
		endif;
	else:
		$order_string = stripcslashes( $order );
		$order         = json_decode( $order_string, true );
		$sum           = 0;
		$checkout_test = true;
		$products_list = get_user_products();
		?>
        <form class="cabinet-item form-js checkout-packages-order"
              method="post"
              id="checkout-packages-order">
            <input type="hidden" name="action" value='new_packages_order'>
            <input type="hidden" name="order" value='<?php echo $order_string; ?>'>
            <div class="package-service__cart">
                <div class="cabinet-item__title">
                    Ви обрали
                </div>
                <div class="package-service__cart-list">
					<?php if ( $order ): foreach ( $order as $key => $item ):
						$ID = $item['ID'] ?? '';
						if ( $ID && get_post( $ID ) ):
							$val = $item['val'] ?? '';
							$count = $item['count'] ?? '';
							$placesList = $item['placesList'] ?? '';
							$regions_qnt = carbon_get_post_meta( $ID, 'service_regions_qnt' );
							$qnt_suffix = carbon_get_post_meta( $ID, 'service_qnt_suffix' ) ?: '';
							$sub_sum = carbon_get_post_meta( $ID, 'service_full_price' ) * $val;
							$str = "Вся країна";
							$placesList = $placesList ? explode( ',', $placesList ) : array();
							if ( is_int( $count ) ) {
								$count   = (int) $count;
								$sub_sum = carbon_get_post_meta( $ID, 'service_price' ) * $val * $count;
								$str     = "Область";
								if ( $count > count( $placesList ) ) {
									$checkout_test = false;
								}
							}
							$sum = $sum + $sub_sum;
							?>
                            <div data-id="<?php echo $ID; ?>" class="package-service__cart-item">
                                <div class="package-service__cart-item-top">
                                    <div class="package-service__cart-item-left">
                                        <div class="package-service__cart-item-title">
											<?php echo get_the_title( $ID ); ?>
                                        </div>
                                        <div class="package-service__cart-item-place">
											<?php
											if ( $placesList ) {
												foreach ( $placesList as $place ) {
													if ( $place_object = get_term_by( 'id', $place, 'regions' ) ) {
														echo $place_object->name . '<br>';
													}
												}
											} else {
												echo $str;
											}
											?>
                                        </div>
                                    </div>
                                    <div class="package-service__cart-item-right">
                                        <div class="package-service__cart-item-price">
											<?php echo $sub_sum . ' ' . $currency; ?>
                                        </div>
                                        <div class="package-service__cart-item-date">
											<?php echo $val . ' ' . $qnt_suffix; ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="package-service__cart-item-calendar">
                                    <div class="package-service__cart-item-calendar-title">
                                        Виберіть оголошення
                                    </div>
                                    <select required name="product[<?php echo $key; ?>]" class="select_st">
										<?php if ( $products_list ) {
											foreach ( $products_list as $product_id => $product_title ) {
												$attr = $product == $product_id ? 'selected' : '';
												echo "<option $attr value='$product_id'>$product_title</option>";
											}
										} ?>
                                    </select>
                                </div>
								<?php if ( ! carbon_get_post_meta( $ID, 'service_boost' ) ): ?>
                                    <div class="package-service__cart-item-calendar">
                                        <div class="package-service__cart-item-calendar-title">
                                            Дата запуску реклами
                                        </div>
                                        <div class="package-service__cart-item-calendar-main">
                                            <input class="input_st js-range-period order-date-input" type="text"
                                                   name="<?php echo $key; ?>"
                                                   required
                                                   value=""/>
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 15 15"
                                                 viewBox="0 0 15 15"><path
                                                        d="M4.1.7c0-.4-.3-.7-.7-.7s-.8.3-.8.7v.7c0 .4.3.7.8.7s.8-.3.8-.7V.7zM12.4.7c0-.4-.4-.7-.8-.7s-.8.3-.8.7v.7c0 .4.3.7.8.7s.8-.3.8-.7V.7zM0 5.4v8.2c0 .8.7 1.4 1.5 1.4h12c.8 0 1.5-.6 1.5-1.4V5.4H0zm4.5 7.1c0 .4-.3.7-.8.7H3c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7H3c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm4.1 3.9c0 .4-.3.7-.8.7h-.7c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7h-.7c-.4 0-.7-.3-.7-.7v-.7c0-.4.3-.7.8-.7H8c.4 0 .8.3.8.7v.7zm4.2 3.9c0 .4-.3.7-.8.7h-.8c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7h-.8c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7z"
                                                        style="fill:#014433"/>
                                                <path d="M15 4.6V2.5c0-.8-.7-1.4-1.5-1.4h-.4v.4c0 .8-.7 1.4-1.5 1.4s-1.5-.6-1.5-1.4v-.4H4.9v.4c0 .8-.7 1.4-1.5 1.4s-1.5-.7-1.5-1.5v-.3h-.4C.7 1.1 0 1.7 0 2.5v2.1h15z"
                                                      style="fill:#014433"/>
                                                    </svg>
                                        </div>
                                    </div>
								<?php endif; ?>
                            </div>
						<?php
						endif;
					endforeach;
					endif;
					?>
                </div>
                <div class="package-service__cart-total">
                    <div class="package-service__cart-total-title">
                        Загалом:
                    </div>
                    <div class="package-service__cart-total-price">
						<?php echo $sum . "<sub>$currency</sub>"; ?>
                    </div>
                </div>
                <div class="package-service__cart-btn">
					<?php if ( $checkout_test ): ?>
                        <button class="btn_st w100">
                            <span>Оплатити</span>
                        </button>
					<?php else: ?>
                        <div class="cabinet-item__title" style="text-align: center">
                            Для оформлення замовлення вам потрібно обрати область/області
                        </div>
					<?php endif; ?>
                </div>
            </div>
        </form>
	<?php
	endif;
}

function the_package( $id = false, $regions = false ) {
	$id          = $id ?: get_the_ID();
	$regions     = $regions ?: get_terms( array(
		'taxonomy'   => 'regions',
		'hide_empty' => false
	) );
	$telegram    = carbon_get_post_meta( $id, 'service_telegram' );
	$top         = carbon_get_post_meta( $id, 'service_top' );
	$vip         = carbon_get_post_meta( $id, 'service_vip' );
	$urgently    = carbon_get_post_meta( $id, 'service_urgently' );
	$boost       = carbon_get_post_meta( $id, 'service_boost' );
	$list        = carbon_get_post_meta( $id, 'service_list' );
	$term        = carbon_get_post_meta( $id, 'service_term' ) ?: 1;
	$price       = carbon_get_post_meta( $id, 'service_price' );
	$full_price  = carbon_get_post_meta( $id, 'service_full_price' );
	$regions_qnt = carbon_get_post_meta( $id, 'service_regions_qnt' );
	$hint        = carbon_get_post_meta( $id, 'service_hint' );
	$qnt_suffix  = carbon_get_post_meta( $id, 'service_qnt_suffix' );
	$image       = get_the_post_thumbnail_url( $id );
	?>
    <div class="package-service__item"
         data-id="<?php echo $id; ?>"
         data-qnt-suffix="<?php echo $qnt_suffix; ?>"
    >
        <div
			<?php if ( $image ) {
				echo "style='background-image: url($image)'";
			} ?>
                class="package-service__item-main">
            <div class="package-service__item-main-title">
				<?php echo get_the_title( $id ); ?>
            </div>
            <ul class="package-service__item-main-list">
				<?php if ( $vip ): ?>
                    <li>VIP на <?php echo $term; ?> день</li>
				<?php endif; ?>
				<?php if ( $top ): ?>
                    <li>TOP на <?php echo $term; ?> день</li>
				<?php endif; ?>
				<?php if ( $urgently ): ?>
                    <li>Підйом оголошення <?php echo $term; ?> раз</li>
				<?php endif; ?>
				<?php if ( $boost ): ?>
                    <li>Підйом оголошення <?php echo $term; ?> раз</li>
				<?php endif; ?>
				<?php if ( $telegram ): ?>
                    <li>Телеграм бот</li>
				<?php endif; ?>
				<?php if ( $list ): foreach ( $list as $item ): ?>
                    <li><?php echo $item['text']; ?></li>
				<?php endforeach; endif; ?>
            </ul>
        </div>
        <div class="package-service__item-list">
            <div class="package-service__item-list-main js-package" id="service-<?php echo $id; ?>-1">
                <div class="package-service__item-list-main-title">
                    Область
                </div>
                <div class="package-service__item-list-main-place">
                    <a class="js-package-place " href="#service-<?php echo $id; ?>-1-place">
                        Зробіть вибір
                    </a>
                </div>
                <div class="package-service__item-list-main-price">
                    <span class="js-price"></span> грн.
                </div>
                <div class="package-service__item-list-main-count">
                    <div class="counter_service">
                        <div class="btn_count num_minus disabled">
                            <span> </span>
                        </div>
                        <input class="counter_input" type="text"
                               readonly="" data-count="1"
                               value="0"
                               data-price="<?php echo $price; ?>"
                               data-total=""/>
                        <div class="btn_count num_pluss active">
                            <span> </span>
                        </div>
                    </div>
                </div>
                <div class="package-service__item-list-main-subtitle">
					<?php echo $hint; ?>
                </div>
                <div class="modal modal-sm" id="service-<?php echo $id; ?>-1-place">
                    <div class="modal-content">
                        <div class="modal-title">
                            <div class="modal-title__main">Оберіть одну область</div>
                        </div>
                        <div class="place-list-modal" data-parent="#service-<?php echo $id; ?>-1">
                            <div class="place-list" data-id="<?php echo $id; ?>" data-choice="1">
								<?php if ( $regions ): foreach ( $regions as $region ): ?>
                                    <div class="filter-check__item">
                                        <label class="check-item">
                                            <input class="check_st" type="checkbox"
                                                   data-value="<?php echo $region->term_id; ?>"
                                                   value="<?php echo $region->name; ?>"
                                            />
                                            <span></span>
                                            <i class="check-item__text"><?php echo $region->name; ?></i>
                                        </label>
                                    </div>
								<?php endforeach; endif; ?>
                            </div>
                        </div>
                        <a class="btn_st w100 select-region-button" style="display: none" >
                            <span>Обрати</span>
                        </a>
                    </div>
                </div>
            </div>
			<?php if ( $regions_qnt ): ?>
                <div class="package-service__item-list-main js-package" id="service-<?php echo $id; ?>-2">
                    <div class="package-service__item-list-main-title">
						<?php echo $regions_qnt ?> області
                    </div>
                    <div class="package-service__item-list-main-place">
                        <a class="js-package-place " href="#service-<?php echo $id; ?>-2-place">
                            Оберіть область
                        </a>
                    </div>
                    <div class="package-service__item-list-main-price">
                        <span class="js-price"></span> грн.
                    </div>
                    <div class="package-service__item-list-main-count">
                        <div class="counter_service">
                            <div class="btn_count num_minus disabled"><span> </span></div>
                            <input class="counter_input" type="text" readonly="" value="0"
                                   data-count="<?php echo $regions_qnt; ?>"
                                   data-price="<?php echo $regions_qnt * $price; ?>"
                                   data-total=""/>
                            <div class="btn_count num_pluss active"><span> </span></div>
                        </div>
                    </div>
                    <div class="package-service__item-list-main-subtitle">
						<?php echo $hint; ?>
                    </div>
                    <div class="modal modal-sm" id="service-<?php echo $id; ?>-2-place">
                        <div class="modal-content">
                            <div class="modal-title">
                                <div class="modal-title__main">Оберіть <?php echo $regions_qnt; ?> області(ь)</div>
                            </div>
                            <div class="place-list-modal" data-parent="#service-<?php echo $id; ?>-2">
                                <div class="place-list" data-id="<?php echo $id; ?>"
                                     data-choice="<?php echo $regions_qnt; ?>">
									<?php if ( $regions ): foreach ( $regions as $region ): ?>
                                        <div class="filter-check__item">
                                            <label class="check-item">
                                                <input class="check_st" type="checkbox"
                                                       data-count="<?php echo $regions_qnt; ?>"
                                                       data-value="<?php echo $region->term_id; ?>"
                                                       value="<?php echo $region->name; ?>"
                                                />
                                                <span></span>
                                                <i class="check-item__text"><?php echo $region->name; ?></i>
                                            </label>
                                        </div>
									<?php endforeach; endif; ?>
                                </div>
                            </div>
                            <a class="btn_st w100 select-region-button" style="display: none" >
                                <span>Обрати</span>
                            </a>
                        </div>
                    </div>
                </div>
			<?php endif; ?>
            <div class="package-service__item-list-main js-package" id="service-<?php echo $id; ?>-3">
                <div class="package-service__item-list-main-title">Всі регіони</div>
                <div class="package-service__item-list-main-place"> Україна</div>
                <div class="package-service__item-list-main-price">
                    <span class="js-price"></span> грн.
                </div>
                <div class="package-service__item-list-main-count">
                    <div class="counter_service">
                        <div class="btn_count num_minus disabled"><span> </span></div>
                        <input class="counter_input" type="text" readonly="" value="0"
                               data-count="Вся країна"
                               data-price="<?php echo $full_price; ?>"
                               data-total=""/>
                        <div class="btn_count num_pluss active"><span> </span></div>
                    </div>
                </div>
                <div class="package-service__item-list-main-subtitle"> <?php echo $hint; ?></div>
            </div>
        </div>
    </div>
	<?php
}

function the_purchased_page() {
	the_header_cabinet();
	$var           = variables();
	$set           = $var['setting_home'];
	$assets        = $var['assets'];
	$url           = $var['url'];
	$url_home      = $var['url_home'];
	$admin_ajax    = $var['admin_ajax'];
	$currency      = carbon_get_theme_option( 'currency' );
	$personal_page = carbon_get_theme_option( 'personal_area_page' );
	$_url          = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$user_id       = get_current_user_id();
	$current_user  = get_user_by( 'ID', $user_id );
	$email         = $current_user->user_email ?: '';
	$display_name  = $current_user->display_name ?: '';
	$first_name    = $current_user->first_name ?: '';
	$last_name     = $current_user->last_name ?: '';

	?>
    <div class="create-item-main">
        <div class="package-service-main">
            <div class="package-service-main__top">
                <div class="all-list">Ваші послуги: </div>
                <a class="btn_st b_yelloow" href="<?php echo $_url . '?route=packages'; ?>">
                    <span>Назад до всіх послуг </span>
                </a>
            </div>
            <div class="package-service-main__list">
				<?php the_purchased(); ?>
            </div>
        </div>
    </div>
	<?php
}

function the_purchased() {
	$var        = variables();
	$set        = $var['setting_home'];
	$assets     = $var['assets'];
	$url        = $var['url'];
	$url_home   = $var['url_home'];
	$admin_ajax = $var['admin_ajax'];
	$user_id    = get_current_user_id();
	$currency   = carbon_get_theme_option( 'currency' );
	$args       = array(
		'post_type'      => 'purchased',
		'posts_per_page' => - 1,
		'author__in'     => array( $user_id ),
		'post_status'    => array( 'publish', 'pending' ),
	);
	$query      = new WP_Query( $args );
	$counter    = 0;
	if ( $query->have_posts() ):
		while ( $query->have_posts() ) :
			$query->the_post();
			the_purchased_service( get_the_ID(), $currency );
		endwhile;
	else: ?>
        <div class="cabinet-item">
            <div class="package-service__buy">
                <div class="package-service__buy-media">
                    <img src="<?php echo $assets; ?>img/help2.webp" alt=""/>
                </div>
                <div class="package-service__buy-title"> Тут пусто ...</div>
                <div class="package-service__buy-text">
                    Купуйте і користуйтеся всіма нашими платними послугами
                </div>
            </div>
        </div>
	<?php endif;
}

function the_purchased_service( $id = false, $currency = '' ) {
	$time                     = time();
	$id                       = $id ?: get_the_ID();
	$currency                 = $currency ?: carbon_get_theme_option( 'currency' );
	if ( $list = carbon_get_post_meta( $id, 'purchased_order' ) ):
		foreach ( $list as $index => $item ):
			$remains = 0;
			$is_not_active    = $item['is_not_active'];
			$auto_continue    = $item['auto_continue'];
			$service_id       = $item['service_id'];
			$product_id       = $item['product_id'];
			$name             = $item['name'];
			$date             = $item['date'];
			$sub_sum          = $item['sub_sum'];
			$qnt              = $item['qnt'];
			$regions          = $item['regions'] ? explode( ',', $item['regions'] ) : '';
			$qnt_suffix       = carbon_get_post_meta( $service_id, 'service_qnt_suffix' );
			$service_top      = carbon_get_post_meta( $service_id, 'service_top' );
			$service_vip      = carbon_get_post_meta( $service_id, 'service_vip' );
			$service_urgently = carbon_get_post_meta( $service_id, 'service_urgently' );
			$service_term     = carbon_get_post_meta( $service_id, 'service_term' );
			$term_number      = $service_term * 86400;
			$stops            = $item['stops'] ?: array();
			if ( $service_top ) {
				$product_start_top = (int)carbon_get_post_meta( $product_id, 'product_start_top' );
				$product_end_top   = (int)carbon_get_post_meta( $product_id, 'product_end_top' );
				if ( $remains == 0 ) {
					$remains = $product_end_top - $product_start_top;
				}
			}
			if ( $service_vip ) {
				$product_start_vip = (int)carbon_get_post_meta( $product_id, 'product_start_vip' );
				$product_end_vip   = (int)carbon_get_post_meta( $product_id, 'product_end_vip' );
				if ( $remains == 0 ) {
					$remains = $product_end_vip - $product_start_vip;
				}
			}
			if ( $service_urgently ) {
				$product_start_urgently =(int) carbon_get_post_meta( $product_id, 'product_start_urgently' );
				$product_end_urgently   =(int) carbon_get_post_meta( $product_id, 'product_end_urgently' );
				if ( $remains == 0 ) {
					$remains = $product_end_urgently - $product_start_urgently;
				}
			}
				?>
                <div class="package-service-main__list-item">

                    <div class="package-service-main-item">
                        <div class="package-service-main-item__left">
                            <div class="package-service__cart-item-title">
								<?php echo $name ?: get_the_title( $service_id ); ?>
                            </div>
                            <div class="package-service__cart-item-place">
								<?php if ( $regions ) {
									foreach ( $regions as $region ) {
										if ( $term = get_term_by( 'id', $region, 'regions' ) ) {
											echo $term->name;
											echo '<br>';
										}
									}
								} ?>
                            </div>
                        </div>
                        <div class="package-service-main-item__price">
                            <div class="package-service__cart-item-price"><?php echo $sub_sum . $currency; ?></div>
                            <div class="package-service__cart-item-date"> (<?php echo $qnt . ' ' . $qnt_suffix; ?>)
                            </div>
                        </div>
                        <div class="package-service-main-item__date">
							<?php echo $date ? date( 'd-m-Y', (int) $date ) : ''; ?>
                        </div>
                        <ul class="package-service-main-item__links">
                            <li>
								<?php if ( $is_not_active ): ?>
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
								<?php else: ?>
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
								<?php endif; ?>
                            </li>
                            <li>
                                <a href="#"
                                   class="delete-purchased"
                                   data-index="<?php echo $index; ?>"
                                   data-id="<?php echo $id; ?>"
                                >
                                    Видалити
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 13 15" viewBox="0 0 13 15">
                                                    <path d="m.9 4.4.8 9.4c0 .7.6 1.2 1.3 1.2h7c.7 0 1.2-.5 1.3-1.2l.8-9.4H.9zm3.4 8.8c-.2 0-.4-.2-.4-.4l-.4-7.1c0-.2.2-.5.4-.5s.4.2.5.4l.4 7.1c0 .3-.2.5-.5.5zm2.6-.4c0 .2-.2.4-.4.4s-.4-.2-.4-.4V5.7c0-.2.2-.4.4-.4s.4.2.4.4v7.1zm2.6-7.1-.4 7.1c0 .2-.2.4-.5.4-.2 0-.4-.2-.4-.5l.4-7.1c0-.2.2-.4.5-.4.3.1.4.3.4.5zM12.1 1.8H9.5v-.5C9.5.6 9 0 8.2 0H4.8C4 0 3.5.6 3.5 1.3v.4H.9c-.5.1-.9.5-.9.9 0 .5.4.9.9.9h11.3c.5 0 .9-.4.9-.9-.1-.4-.5-.8-1-.8zm-3.4 0H4.3v-.5c0-.2.2-.4.4-.4h3.5c.2 0 .4.2.4.4v.5z"
                                                          style="fill:#fc3636"/>
                                                </svg>
                                </a>
                            </li>
                            <li>
                                <div class="select-product__item-continue">
                                    <span>Автопродовження</span>
                                    <label class="switch_st">
                                        <input <?php echo $auto_continue ? 'checked' : ''; ?>
                                                class="change-auto-continue-purchased"
                                                data-index="<?php echo $index; ?>"
                                                data-id="<?php echo $id; ?>"
                                                type="checkbox"/>
                                        <span></span>
                                    </label>
                                </div>
                            </li>
                        </ul>
                    </div>
                </div>
			<?php
		endforeach;
	endif;
}