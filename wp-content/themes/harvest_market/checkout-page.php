<?php
/* Template Name: Шаблон сторінки оформлення замовлення */
$product    = $_GET['product'] ?? '';
$cart       = $_COOKIE['cart'] ?? '';
$coupon     = $_COOKIE['coupon'] ?? '';
$var        = variables();
$set        = $var['setting_home'];
$assets     = $var['assets'];
$url        = $var['url'];
$url_home   = $var['url_home'];
$admin_ajax = $var['admin_ajax'];
if ( ! $product && ! get_post( $product ) ) {
	header( 'Location: ' . $url );
	die();
}
get_header();
$user_id         = get_current_user_id();
$id              = get_the_ID();
$isLighthouse    = isLighthouse();
$size            = $isLighthouse ? 'thumbnail' : 'full';
$cart            = stripcslashes( $cart );
$cart            = json_decode( $cart, true );
$currency        = carbon_get_theme_option( 'currency' );
$title           = get_the_title();
$product_sum     = 0;
$discount_sum    = 0;
$coupon_discount = 0;
$ids             = [];
$qnts            = [];
$qnt             = $_GET['qnt'] ?? 1;
$min_order       = carbon_get_post_meta( $product, 'product_min_order' ) ?: '';
$max_value       = carbon_get_post_meta( $product, 'product_max_value' ) ?: '';
if ( $min_order && $qnt < $min_order ) {
	$qnt = $min_order;
}
if ( $max_value && $qnt > $max_value ) {
	$qnt = $max_value;
}
if ( $coupon ) {
	$coupon_id = get_coupon_id( $coupon );
	if ( $coupon_id !== false ) {
		$coupon_discount = carbon_get_post_meta( $coupon_id, 'coupon_discount' );
	}
}
$_id               = $product;
$_img              = get_the_post_thumbnail_url( $_id ) ?: $assets . 'img/product1.webp';
$_permalink        = get_the_permalink( $_id );
$_price            = carbon_get_post_meta( $_id, 'product_price' );
$_delivery_methods = carbon_get_post_meta( $_id, 'product_delivery_methods' );
$author_id         = get_post_field( 'post_author', $_id );
$user_company_name = carbon_get_user_meta( $author_id, 'user_company_name' ) ?: '';
$sub_sum           = $_price * $qnt;
if ( $coupon_discount ) {
	$discount     = ( $coupon_discount * $sub_sum ) / 100;
	$discount_sum = $discount_sum + $discount;
}
$types      = carbon_get_theme_option( 'delivery_types' );
$user       = get_user_by( 'ID', $user_id );
$first_name = $user_id ? $user->first_name : '';
$last_name  = $user_id ? $user->last_name : '';
$user_email = $user_id ? $user->user_email : '';
$phone      = $user_id ? carbon_get_user_meta( $user_id, 'user_phone' ) : '';
$user_city  = $user_id ? carbon_get_user_meta( $user_id, 'user_company_city' ) : '';
?>

    <section class="section-order pad_section_sm_top pad_section_bot">
        <div class="container">
            <div class="title-sm"> <?php echo $title; ?> </div>
            <div class="order-group">
                <div class="order-group__left">
					<?php if ( ! $user_id ): ?>
                        <div class="order-login">
                            <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                 style="enable-background:new 0 0 16.7 20" viewBox="0 0 16.7 20">
                                <path d="M8.2 9.6c1.3 0 2.5-.5 3.4-1.4 1-.9 1.4-2.1 1.4-3.4 0-1.3-.5-2.5-1.4-3.4C10.7.5 9.5 0 8.2 0 6.9 0 5.7.5 4.8 1.4c-.9.9-1.4 2.1-1.4 3.4 0 1.3.5 2.5 1.4 3.4.9 1 2.1 1.4 3.4 1.4zM16.6 15.4c0-.4-.1-.8-.2-1.3s-.2-.9-.3-1.3c-.1-.4-.3-.8-.5-1.2-.2-.4-.5-.7-.8-1-.3-.3-.7-.5-1.1-.7-.4-.2-.9-.3-1.4-.3-.2 0-.4.1-.8.3-.2.2-.5.3-.8.5-.3.2-.6.3-1.1.5-.4.1-.9.2-1.3.2s-.9-.1-1.3-.2-.8-.3-1.1-.5c-.3-.2-.6-.4-.8-.5-.3-.2-.5-.2-.7-.2-.5 0-1 .1-1.4.3-.4.2-.8.4-1.1.7-.3.3-.6.6-.8 1-.2.4-.4.8-.5 1.2-.1.4-.2.8-.3 1.3-.2.4-.2.8-.3 1.2v1.2c0 1 .3 1.9 1 2.5.6.6 1.5.9 2.5.9h9.6c1 0 1.9-.3 2.5-.9.7-.6 1-1.5 1-2.5.1-.4.1-.8 0-1.2z"
                                      style="fill:#81b4a7"/>
                            </svg>
                            <div class="order-login__text">Вже є обліковий запис? <a href="#">Авторизуватися </a></div>
                        </div>
					<?php endif; ?>
                    <form
                            action="<?php echo $admin_ajax; ?>" method="post"
                            novalidate
                            id="new-order-form"
                            class="new-order form-js"
                    >
                        <input type="hidden" name="action" value="new_order">
                        <input type="hidden" name="id" value="<?php echo $_id; ?>">
                        <input type="hidden" name="qnt" value="<?php echo $qnt; ?>">
                        <input type="hidden" name="promo" value="<?php echo $coupon; ?>">
                        <input type="hidden" name="order_seller_id" value="<?php echo $author_id; ?>">
                        <div class="order-form">
                            <div class="order-form__item">
                                <div class="order-form__item-title"> Контактні дані</div>
                                <div class="form-horizontal">
                                    <div class="form-group half">
                                        <input class="input_st" type="text"
                                               value="<?php echo $last_name; ?>"
                                               name="last_name" placeholder="Прізвище*"
                                               required="required"/>
                                    </div>
                                    <div class="form-group half">
                                        <input class="input_st"
                                               value="<?php echo $first_name; ?>"
                                               type="text" name="first_name" placeholder="Ім'я*"
                                               required="required"/>
                                    </div>
                                    <div class="form-group half">
                                        <input class="input_st" type="text" name="surname" placeholder="По батькові*"
                                               required="required"/>
                                    </div>
                                    <div class="form-group half">
                                        <input class="input_st" type="text"

                                               value="<?php echo $user_city; ?>"
                                               name="city" placeholder="Місто*"
                                               required="required"/>
                                    </div>
                                    <div class="form-group half">
                                        <input class="input_st" type="tel" name="tel"
                                               value="<?php echo $phone; ?>"
                                               placeholder="Номер телефону*" required="required"/>
                                    </div>
                                    <div class="form-group half">
                                        <input class="input_st"
                                               value="<?php echo $user_email; ?>"
                                               data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                                               type="email" name="email" placeholder="E-mail*" required="required"/>
                                    </div>
                                </div>
                            </div>
							<?php if ( $_delivery_methods ): ?>
                                <div class="order-form__item">
                                    <div class="order-form__item-title"> Доставка</div>
                                    <div class="form-horizontal">
                                        <div class="form-group half">
                                            <select name="delivery_method" class="select_st trigger-on-select">
												<?php foreach ( $_delivery_methods as $delivery_method ):
													$_delivery_method = get_delivery_method_by_value( $delivery_method, $types );
													$_type_item = $_delivery_method['_type'];
													$_title_item = $_delivery_method['title'];
													?>
                                                    <option
                                                            data-trigger=".delivery-method-<?php echo $_type_item; ?>"
                                                            value="<?php echo $delivery_method; ?>">
														<?php echo $_title_item; ?>
                                                    </option>
												<?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="form-group half trigger-element hidden delivery-method-own_service">
                                            <input class="input_st" type="text" name="address"
                                                   placeholder="Адреса доставки"/>
                                        </div>
                                        <div class="form-group half trigger-element hidden delivery-method-delivery_service">
                                            <input class="input_st" type="text" name="post_office"
                                                   placeholder="Відділення*"/>
                                        </div>
                                    </div>
									<?php foreach ( $_delivery_methods as $delivery_method ):
										$_delivery_method = get_delivery_method_by_value( $delivery_method, $types );
										$_type_item = $_delivery_method['_type'];
										$_cls = $_delivery_method['is_error'] ? 'error' : '';
										if ( $_delivery_method['text'] ):
											?>
                                            <div class="order-login <?php echo $_cls; ?> trigger-element delivery-method-<?php echo $_type_item; ?> hidden">
                                                <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                                                     style="enable-background:new 0 0 20 20" viewBox="0 0 20 20">
                                            <path d="M10 0C8 0 6.1.6 4.4 1.7 2.8 2.8 1.5 4.3.8 6.2 0 8-.2 10 .2 12s1.3 3.7 2.7 5.1c1.4 1.4 3.2 2.4 5.1 2.7 1.9.4 4 .2 5.8-.6 1.8-.8 3.4-2 4.5-3.7C19.4 13.9 20 12 20 10c0-2.7-1.1-5.2-2.9-7.1C15.2 1.1 12.7 0 10 0zm0 17.5c-.3 0-.5-.1-.7-.2-.3-.1-.4-.3-.5-.6-.1-.2-.1-.5-.1-.8.1-.3.2-.5.4-.7.2-.2.4-.3.7-.4.3-.1.5 0 .8.1.2.1.5.3.6.5.1.2.2.5.2.7 0 .4-.1.7-.4.9-.3.4-.6.5-1 .5zm2.3-11.2L10.7 13c0 .1-.1.3-.2.4-.1.1-.3.1-.4.1-.1 0-.3 0-.4-.1-.1-.1-.2-.2-.2-.4L7.7 6.3c-.1-.3-.1-.7 0-1.1.1-.3.2-.7.5-1 .2-.2.5-.5.8-.6.3-.2.7-.2 1-.2.4 0 .7.1 1 .2.3.2.6.4.8.7.2.3.4.6.4 1 .2.3.2.6.1 1z"
                                                  style="fill:#fc3636"/>
                                        </svg>
                                                <div class="order-login__text">
													<?php echo $_delivery_method['text']; ?>
                                                </div>
                                            </div>
										<?php endif; endforeach; ?>
                                </div>
							<?php endif; ?>
                            <div class="order-form__item">
                                <div class="order-form__item-title"> Оплата</div>
                                <div class="form-horizontal">
                                    <div class="form-group">
                                        <select name="payment_method" class="select_st">
                                            <option value="При отриманні товари">При отриманні товари</option>
                                            <option value="online">Онлайн оплата</option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="order-form__bot">
                            <a class="btn_st b_yelloow" href="<?php echo get_the_permalink( $_id ); ?>">
                                <span>Назад до товару</span>
                            </a>
                            <button class="btn_st" type="submit">
                                <span>Продовжити </span>
                            </button>
                        </div>
                    </form>
                </div>
                <div class="order-group__right">
                    <div class="cabinet-item card-main">
                        <div class="product-description__title">Ваше замовлення</div>
                        <div class="order-product">
                            <div class="order-product__item">
                                <div class="order-product__item-media">
                                    <img src="<?php echo $_img; ?>" alt=""/>
                                </div>
                                <div class="order-product__item-content">
                                    <a class="product-item__title" href="<?php echo $_permalink; ?>">
										<?php echo get_the_title( $_id ); ?>
                                    </a>
                                    <div class="cart-product__item-subtitle">
										<?php echo $user_company_name; ?>
                                    </div>
                                    <div class="order-product__bot">
                                        <div class="product-item__price">
											<?php echo get_price_html( $_id ); ?>
                                        </div>
                                        <div class="order-product__count">
                                            x<?php echo $qnt; ?>
                                        </div>
                                        <div class="product-item__price">
                                            <strong><?php echo $sub_sum; ?></strong><?php echo $currency; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="cart-info">
                            <div class="cart-info__item">
                                <span>Підсумок: </span>
                                <strong><?php echo $sub_sum . ' ' . $currency; ?></strong>
                            </div>
                            <div class="cart-info__item">
                                <span style="color:#FC3636">Знижка: </span>
                                <strong style="color:#FC3636">
									<?php echo (float) $discount_sum . ' ' . $currency; ?>
                                </strong>
                            </div>
                            <div class="cart-info__item">
                                <span>Доставка:</span>
                                <strong>0</strong>
                            </div>
                        </div>
                        <div class="cart-info__total">
                            <div class="cart-info__total-text"> Загалом:</div>
                            <div class="cart-info__total-price">
								<?php echo (float) ( $sub_sum - $discount_sum ) . ' ' . $currency; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

<?php get_footer(); ?>