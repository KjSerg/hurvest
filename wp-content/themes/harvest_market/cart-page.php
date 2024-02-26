<?php
/* Template Name: Шаблон сторінки корзини */
$cart       = $_COOKIE['cart'] ?? '';
$coupon     = $_COOKIE['coupon'] ?? '';
$var        = variables();
$set        = $var['setting_home'];
$assets     = $var['assets'];
$url        = $var['url'];
$url_home   = $var['url_home'];
$admin_ajax = $var['admin_ajax'];
get_header();
$id              = get_the_ID();
$isLighthouse    = isLighthouse();
$size            = $isLighthouse ? 'thumbnail' : 'full';
$cart            = stripcslashes( $cart );
$cart            = json_decode( $cart, true );
$currency        = carbon_get_theme_option( 'currency' );
$product_sum     = 0;
$discount_sum    = 0;
$coupon_discount = 0;
$ids             = [];
$qnts            = [];
if ( $coupon ) {
	$coupon_id = get_coupon_id( $coupon );
	if ( $coupon_id !== false ) {
		$coupon_discount = carbon_get_post_meta( $coupon_id, 'coupon_discount' );
	}
}
?>

<section class="section-cart pad_section_sm_top pad_section_bot cart-render-js">
    <div class="container">
        <div class="title-section-group db_xs">
            <div class="title-sm">
				<?php echo get_the_title(); ?> (<span class="count-cart">0</span>)
            </div>
            <a class="remove-cart" href="#">
                Очистити корзину
                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 17.3 20"
                     viewBox="0 0 17.3 20">
                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                        </svg>
            </a>
        </div>
        <div class="cart-group">
            <div class="cart-group__left">
				<?php if ( $cart ): ?>
                    <div class="cart-product">
                        <div class="cart-product__top">
                            <div class="cart-product__top-item"> Товар</div>
                            <div class="cart-product__top-item"> Ціна</div>
                            <div class="cart-product__top-item"> Кількість</div>
                            <div class="cart-product__top-item"> Підсумок</div>
                        </div>
                        <div class="cart-product__main">
							<?php foreach ( $cart as $_id => $item ):
								if ( $_id && get_post( $_id ) ):
									$_qnt = $item['qnt'] ?: 1;
									$_img = get_the_post_thumbnail_url( $_id ) ?: $assets . 'img/product1.webp';
									$_title = get_the_title( $_id );
									$_permalink = get_the_permalink( $_id );
									$_price = carbon_get_post_meta( $_id, 'product_price' );
									$author_id = get_post_field( 'post_author', $_id );
									$user_company_name = carbon_get_user_meta( $author_id, 'user_company_name' ) ?: '';
									$min_order = carbon_get_post_meta( $_id, 'product_min_order' ) ?: '';
									$max_value = carbon_get_post_meta( $_id, 'product_max_value' ) ?: '';
									$_unit = carbon_get_post_meta( $_id, 'product_unit' );
									$sub_sum = $_price * $_qnt;
									$product_sum = $product_sum + $sub_sum;
									$ids[] = $_id;
									$qnts[] = $_qnt;
									if ( $coupon_discount ) {
										$discount     = ( $coupon_discount * $sub_sum ) / 100;
										$discount_sum = $discount_sum + $discount;
									}
									?>
                                    <div class="cart-product__item">
                                        <div class="cart-product__item-info">
                                            <div class="cart-product__item-media">
                                                <img src="<?php echo $_img; ?>" alt="<?php echo $_title; ?>"/>
                                            </div>
                                            <div class="cart-product__item-info-content">
                                                <a class="product-item__title" href="<?php echo $_permalink; ?>">
													<?php echo $_title; ?>
                                                </a>
                                                <div class="cart-product__item-subtitle">
													<?php echo $user_company_name; ?>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="cart-product__item-price">
                                            <div class="product-item__price">
												<?php echo get_price_html( $_id ); ?>
                                            </div>
                                        </div>
                                        <div class="counter_product">
                                            <div class="btn_count num_minus disabled">
                                                <span> </span>
                                            </div>
                                            <input class="counter_input counter_input--cart"
                                                   type="text"
                                                   readonly=""
                                                   data-currency="<?php echo $currency; ?>"
                                                   data-max="<?php echo $max_value; ?>"
                                                   data-min="<?php echo $min_order; ?>"
                                                   data-unit="<?php echo $_unit; ?>"
                                                   data-price="<?php echo $_price; ?>"
                                                   data-id="<?php echo $_id; ?>"
                                                   value="<?php echo $_qnt; ?>"/>
                                            <div class="btn_count num_pluss active">
                                                <span> </span>
                                            </div>
                                        </div>
                                        <div class="cart-product__item-total">
                                            <div class="product-item__price">
                                                <strong><?php echo $sub_sum; ?></strong><?php echo $currency; ?>
                                            </div>
                                        </div>
                                        <a class="cart-product__item-remove"
                                           data-id="<?php echo $_id; ?>"
                                           href="#">
                                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                 style="enable-background:new 0 0 17.3 20" viewBox="0 0 17.3 20">
                                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                                        </svg>
                                        </a>
                                    </div>
								<?php endif; endforeach; ?>
                        </div>
                        <div class="cart-product__bot">
                            <a class="btn_st b_yelloow" href="<?php echo $url; ?>"><span>В каталог </span></a>
                            <a class="btn_st btn_yellow modal_open"
                               href="#buy_click"><span>Купити в один клік</span></a>
                            <a class="btn_st" href="#"><span>Продовжити</span></a>
                        </div>
                    </div>
				<?php else: ?>
                    <div class="product-description__title">Кошик порожній</div>
				<?php endif; ?>
            </div>
            <div class="cart-group__right">
                <div class="cabinet-item card-main">
                    <div class="product-description__title">Всього у кошику</div>
                    <div class="cart-info">
                        <div class="cart-info__item">
                            <span>Підсумок: </span><strong><?php echo $product_sum . ' ' . $currency; ?></strong>
                        </div>
                        <div class="cart-info__item">
                            <span style="color:#FC3636">Знижка: </span>
                            <strong style="color:#FC3636"><?php echo (float) $discount_sum . ' ' . $currency; ?></strong>
                        </div>
                    </div>
                    <div class="cart-info__total">
                        <div class="cart-info__total-text"> Загалом:</div>
                        <div class="cart-info__total-price"><?php echo (float) ( $product_sum - $discount_sum ) . ' ' . $currency; ?></div>
                    </div>
                </div>
                <div class="cabinet-item js-collapse">
                    <div class="filter-list__item js-collapse-item">
                        <div class="filter-list__item-title js-collapse--title">Промокод на знижку</div>
                        <div class="filter-list__item-content js-collapse-content">
                            <form action="<?php echo $admin_ajax; ?>"
                                  method="post"
                                  novalidate
                                  id="coupon-form"
                                  class="set-coupon form-js">
                                <input type="hidden" name="action" value="set_coupon">
                                <div class="promo-group">
                                    <input class="input_st" type="text"
                                           required="required" name="promo"
                                           value="<?php echo $coupon; ?>"
                                           placeholder="Промокод"/>
                                    <button class="btn_st" type="submit">
                                        <span>ok</span>
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
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
                <input type="hidden" name="id" value="<?php echo implode( ',', $ids ); ?>">
                <input type="hidden" name="qnt" class="qnt-input-js" value="<?php echo implode( ',', $qnts ); ?>">
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
                    <div class="form-count__price"><?php echo '<strong>' . ( $product_sum - $discount_sum ) . '</strong> ' . $currency; ?></div>
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
</section>


<?php get_footer(); ?>
