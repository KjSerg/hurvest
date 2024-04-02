<?php
/* Template Name: Шаблон сторінки подяки */
get_header();
$order_id               = $_GET['order_id'] ?? '';
$hash                   = $_GET['hash'] ?? '';
$current_user_id        = get_current_user_id();
$var                    = variables();
$set                    = $var['setting_home'];
$assets                 = $var['assets'];
$url                    = $var['url'];
$url_home               = $var['url_home'];
$id                     = get_the_ID();
$isLighthouse           = isLighthouse();
$size                   = isLighthouse() ? 'thumbnail' : 'full';
$img                    = get_the_post_thumbnail_url() ?: $assets . 'img/bg_thanks.webp';
$pick_up_address_string = '';
if ( ( $hash == base64_encode( $order_id ) ) && get_post( $order_id ) ) {
	$order_cart     = carbon_get_post_meta( $order_id, 'order_cart' );
	$payment_status = carbon_get_post_meta( $order_id, 'payment_status' );
	if ( $payment_status == 'paid' ) {
		$order_delivery_method = carbon_get_post_meta( $order_id, 'order_delivery_method' );
		$pos                   = strripos( $order_delivery_method, 'pickup' );
		$pos1                  = strripos( $order_delivery_method, 'market' );
		if ( $order_cart && ( $pos !== false || $pos1 !== false ) ) {
			$product_id = $order_cart[0]['id'];
			if ( get_post( $product_id ) ) {
				$pick_up_address = carbon_get_post_meta( $product_id, 'pick_up_address' );
				if ( $pick_up_address ) {
					foreach ( $pick_up_address as $address ) {
						$_address               = $address['address'];
						$_work_time             = $address['work_time'];
						$pick_up_address_string .= "<br>$_address <i>$_work_time</i>";
					}
				}
			}
		}
	}
}
?>
    <section class="section-thanks" style="background:url(<?php echo $img; ?>) no-repeat top center/cover">
        <div class="container">
            <div class="thanks-content">
                <div class="title-sm" style="color:#EBAB03">
					<?php echo get_the_title(); ?>
                </div>
                <div class="text-group">
					<?php the_post();
					the_content(); ?>
					<?php if ( $pick_up_address_string ): ?>
                        <p>
                            <strong style="font-weight: 400;font-size: 12px; color:#818486">Адреса самовивозу:</strong>
							<?php echo $pick_up_address_string; ?>
                        </p>
					<?php endif; ?>
                </div>
                <a class="btn_st btn_yellow" href="<?php echo $url; ?>">
                    <span>На головну сторінку</span>
                </a>
            </div>
        </div>
    </section>
<?php get_footer(); ?>