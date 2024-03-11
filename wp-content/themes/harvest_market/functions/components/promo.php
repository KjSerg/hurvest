<?php
function the_promo_page() {
	$arg   = array(
		'post_type'      => 'services',
		'posts_per_page' => - 1,
		'post_status'    => 'publish'
	);
	$query = new WP_Query( $arg );
	if ( ! $query->have_posts() ) {
		the_user_advertisement();
		die();
	}
	the_header_cabinet();
	$regions      = get_terms( array(
		'taxonomy'   => 'regions',
		'hide_empty' => false,
		'parent'     => 0
	) );
	$user_id      = get_current_user_id();
	$current_user = get_user_by( 'ID', $user_id );
	$email        = $current_user->user_email ?: '';
	$display_name = $current_user->display_name ?: '';
	$first_name   = $current_user->first_name ?: '';
	$last_name    = $current_user->last_name ?: '';
	$products_ids = get_user_products_ids( $user_id );
	?>
    <div class="create-item-main">
        <div class="advertise">
			<?php while ( $query->have_posts() ): $query->the_post();
				$_id = get_the_ID();
				the_promo_package(
					array( 'regions' => $regions, 'products_ids' => $products_ids, 'id' => $_id )
				);
			endwhile; ?>
        </div>
    </div>
	<?php
	wp_reset_postdata();
	wp_reset_query();
}

function the_promo_package( $args = array() ) {
	$product_ID          = $_GET['product'] ?? '';
	$id                  = $args['id'] ?? get_the_ID();
	$user_products_ids   = $args['products_ids'];
	$price               = carbon_get_post_meta( $id, 'service_price' ) ?: 0;
	$is_date             = carbon_get_post_meta( $id, 'service_date' );
	$service_up          = carbon_get_post_meta( $id, 'service_up' );
	$service_urgently    = carbon_get_post_meta( $id, 'service_urgently' );
	$service_term        = carbon_get_post_meta( $id, 'service_term' );
	$service_hint1       = carbon_get_post_meta( $id, 'service_hint1' );
	$service_hint2       = carbon_get_post_meta( $id, 'service_hint2' );
	$service_hint3       = carbon_get_post_meta( $id, 'service_hint3' );
	$service_text        = carbon_get_post_meta( $id, 'service_text' );
	$is_top              = carbon_get_post_meta( $id, 'service_is_top' );
	$currency            = carbon_get_theme_option( 'currency' );
	if ( $is_top || $service_up || $service_urgently ):
		$formatted_price = number_format( $price, 2 );
		$formatted_price .= " $currency";
		$regions         = $args['regions'] ?? get_terms( array(
				'taxonomy'   => 'regions',
				'hide_empty' => false,
				'parent'     => 0
			) );
		if ( $user_products_ids || ( $product_ID && get_post( $product_ID ) ) ):
			?>
            <form novalidate id="checkout-service-<?php echo $id ?>"
                  class="advertise-item advertise-buy form-js" method="post"
                  data-id="<?php echo $id; ?>">
                <input type="hidden" value="checkout_service" name="action">
                <input type="hidden" value="<?php echo $id ?>" name="id">

                <div class="advertise-item__content">
                    <div class="advertise-item__title ">
						<?php echo get_the_title( $id ); ?><span></span>
                    </div>
                    <div class="advertise-item__price-main">
						<?php echo $formatted_price; ?> <span>/ область</span>
                    </div>
                    <div class="advertise-item__select form-group">
                        <div class="advertise-item__select-item">
                            <div class="advertise-item__select-text">Оберіть область</div>
                            <select class="select_st select-region" name="regions[]" required multiple="multiple">
                                <option disabled value="">
                                    Зробіть вибір областей
                                </option>
                                <option data-all="data-all" value="country">Вся Україна</option>
								<?php if ( $regions ): foreach ( $regions as $region ): ?>
                                    <option value="<?php echo $region->term_id; ?>">
										<?php echo $region->name; ?>
                                    </option>
								<?php endforeach; endif; ?>
                            </select>
                        </div>
                        <div class="advertise-item__select-item form-group">
                            <div class="advertise-item__select-text">Оберіть оголошення для реклами</div>
                            <select class="select_st select-product" name="products[]" required multiple="multiple">
                                <option disabled value="">
                                    Зробіть вибір оголошень
                                </option>
								<?php if ( $product_ID ): ?>
                                    <option selected value="<?php echo $product_ID ?>">
										<?php echo get_the_title( $product_ID ); ?>
                                    </option>
								<?php endif; ?>
								<?php if ( $user_products_ids ): foreach ( $user_products_ids as $product_id ): if ( get_post( $product_id ) && $product_id != $product_ID ): ?>
                                    <option value="<?php echo $product_id ?>">
										<?php echo get_the_title( $product_id ); ?>
                                    </option>
								<?php endif; endforeach; endif; ?>
                            </select>
                        </div>
                    </div>
					<?php if ( $is_date ): ?>
                        <div class="package-service__cart-item-calendar form-group">
                            <div class="package-service__cart-item-calendar-title"> Дата старту реклами</div>
                            <div class="package-service__cart-item-calendar-main">
                                <input class="input_st js-range-period"
                                       required
                                       name="start_date"
                                       readonly
                                       type="text"
                                       value=""/>
                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                     style="enable-background:new 0 0 15 15" viewBox="0 0 15 15">
                                            <path d="M4.1.7c0-.4-.3-.7-.7-.7s-.8.3-.8.7v.7c0 .4.3.7.8.7s.8-.3.8-.7V.7zM12.4.7c0-.4-.4-.7-.8-.7s-.8.3-.8.7v.7c0 .4.3.7.8.7s.8-.3.8-.7V.7zM0 5.4v8.2c0 .8.7 1.4 1.5 1.4h12c.8 0 1.5-.6 1.5-1.4V5.4H0zm4.5 7.1c0 .4-.3.7-.8.7H3c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7H3c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm4.1 3.9c0 .4-.3.7-.8.7h-.7c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7h-.7c-.4 0-.7-.3-.7-.7v-.7c0-.4.3-.7.8-.7H8c.4 0 .8.3.8.7v.7zm4.2 3.9c0 .4-.3.7-.8.7h-.8c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7zm0-3.9c0 .4-.3.7-.8.7h-.8c-.4 0-.8-.3-.8-.7v-.7c0-.4.3-.7.8-.7h.8c.4 0 .8.3.8.7v.7z"
                                                  style="fill:#014433"/>
                                    <path d="M15 4.6V2.5c0-.8-.7-1.4-1.5-1.4h-.4v.4c0 .8-.7 1.4-1.5 1.4s-1.5-.6-1.5-1.4v-.4H4.9v.4c0 .8-.7 1.4-1.5 1.4s-1.5-.7-1.5-1.5v-.3h-.4C.7 1.1 0 1.7 0 2.5v2.1h15z"
                                          style="fill:#014433"/>
                                        </svg>
                            </div>
                        </div>
					<?php endif; ?>
                    <div class="advertise-item__listtitle">Що включено:</div>
                    <ul>
						<?php if ( $is_top ): ?>
                            <li>ТОП-оголошення на <?php echo $service_term; ?> дні <?php if ( $service_hint1 ): ?><span
                                        class="info-attention"><svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            xml:space="preserve"
                                            style="enable-background:new 0 0 13 13"
                                            viewBox="0 0 13 13">
                                                <path d="M11.1 1.9C8.6-.6 4.4-.6 1.9 1.9c-2.5 2.5-2.5 6.7 0 9.2 2.5 2.5 6.7 2.5 9.2 0 2.5-2.5 2.5-6.7 0-9.2zM7.3 9.3c0 .5-.4.8-.8.8-.5 0-.8-.4-.8-.8V5.9c0-.5.4-.8.8-.8.5 0 .8.4.8.8v3.4zm-.8-4.8c-.5 0-.8-.4-.8-.8s.3-.8.8-.8.8.3.8.8c0 .4-.3.8-.8.8z"
                                                      style="opacity:.3;fill:#6d6d6d;enable-background:new"/>
                                            </svg><span
                                            class="info-attention__content"> <?php echo $service_hint1; ?> </span><?php endif; ?></span>
                            </li>
						<?php endif; ?>
						<?php if ( $service_up ): ?>
                            <li>
                                Підйом оголошення <?php echo $service_up; ?> рази
								<?php if ( $service_hint2 ): ?>
                                    <span class="info-attention">
                                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                         style="enable-background:new 0 0 13 13"
                                         viewBox="0 0 13 13">
                                                <path d="M11.1 1.9C8.6-.6 4.4-.6 1.9 1.9c-2.5 2.5-2.5 6.7 0 9.2 2.5 2.5 6.7 2.5 9.2 0 2.5-2.5 2.5-6.7 0-9.2zM7.3 9.3c0 .5-.4.8-.8.8-.5 0-.8-.4-.8-.8V5.9c0-.5.4-.8.8-.8.5 0 .8.4.8.8v3.4zm-.8-4.8c-.5 0-.8-.4-.8-.8s.3-.8.8-.8.8.3.8.8c0 .4-.3.8-.8.8z"
                                                      style="opacity:.3;fill:#6d6d6d;enable-background:new"/>
                                            </svg><span
                                            class="info-attention__content"> <?php echo $service_hint2; ?> </span>
                                    </span><?php endif; ?>
                            </li>
						<?php endif; ?>
						<?php if ( $service_urgently ): ?>
                            <li>Позначка <strong style="color:#FC3636">Терміново</strong><?php if ( $service_hint3 ): ?>
                                    <span class="info-attention">
                                    <svg
                                            xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                            style="enable-background:new 0 0 13 13" viewBox="0 0 13 13">
                                                <path d="M11.1 1.9C8.6-.6 4.4-.6 1.9 1.9c-2.5 2.5-2.5 6.7 0 9.2 2.5 2.5 6.7 2.5 9.2 0 2.5-2.5 2.5-6.7 0-9.2zM7.3 9.3c0 .5-.4.8-.8.8-.5 0-.8-.4-.8-.8V5.9c0-.5.4-.8.8-.8.5 0 .8.4.8.8v3.4zm-.8-4.8c-.5 0-.8-.4-.8-.8s.3-.8.8-.8.8.3.8.8c0 .4-.3.8-.8.8z"
                                                      style="opacity:.3;fill:#6d6d6d;enable-background:new"/>
                                            </svg><span
                                            class="info-attention__content"> <?php echo $service_hint3; ?> </span>
                                    </span><?php endif; ?>
                            </li>
						<?php endif; ?>

                    </ul>
					<?php if ( $service_text ): ?>
                        <div class="all-list">
							<?php echo $service_text; ?>
                        </div>
					<?php endif; ?>
                </div>
                <div class="advertise-item__bot">
                    <div class="advertise-item__bot-price">
                        <div class="advertise-item__bot-price-title"> Загалом:</div>
                        <div class="advertise-item__price">
                            <span class="advertise-new-price"><?php echo $formatted_price; ?></span>
                            <span class="advertise-old-price"></span>
                        </div>
                    </div>
                    <button type="submit" class="btn_st checkout-service-js " data-id="<?php echo $id; ?>">
                        <span> Сплатити </span>
                    </button>
                </div>
            </form>
		<?php
		endif;
	endif;
}