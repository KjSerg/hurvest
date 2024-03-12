<?php
/* Template Name: Шаблон сторінки персональний кабінет  */
$user_id    = get_current_user_id();
$var        = variables();
$set        = $var['setting_home'];
$assets     = $var['assets'];
$url        = $var['url'];
$url_home   = $var['url_home'];
$admin_ajax = $var['admin_ajax'];
$login_page = carbon_get_theme_option( 'login_page' );
if ( ! $user_id ) {
	if ( $login_page ) {
		header( 'Location:' . get_the_permalink( $login_page[0]['id'] ) );
	} else {
		header( 'Location:' . $url );
	}
	die();
}
get_header();
$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
$id                 = get_the_ID();
$isLighthouse       = isLighthouse();
$size               = $isLighthouse ? 'thumbnail' : 'full';
$current_user       = get_user_by( 'ID', $user_id );
$email              = $current_user->user_email ?: '';
$display_name       = $current_user->display_name ?: '';
$first_name         = $current_user->first_name ?: '';
$last_name          = $current_user->last_name ?: '';
$name               = $first_name ?: $display_name;
$user_surname       = carbon_get_user_meta( $user_id, 'user_surname' ) ?: '';
$user_city          = carbon_get_user_meta( $user_id, 'user_company_city' ) ?: '';
$user_phone         = carbon_get_user_meta( $user_id, 'user_phone' ) ?: '';
$form_support       = carbon_get_post_meta( $id, 'short_code_form_support' ) ?: '';
$route              = $_GET['route'] ?? '';
$purchased_id       = $_GET['purchased_id'] ?? '';
$_purchased_id      = $_COOKIE['purchased_id'] ?? '';
link_zoho_account();
echo '<pre>';
//var_dump(search_zoho_contact_by_email( $email ));
//print_r(search_zoho_contact_by_email( 'tim.berloger@gmail.com' ));
//$r = create_zoho_account( array(
//	'name'        => carbon_get_user_meta( $user_id, 'user_company_name' ),
//	'description' => carbon_get_user_meta( $user_id, 'user_company_description' ),
//	'region'      => carbon_get_user_meta( $user_id, 'user_company_region' ),
//	'phone'       => carbon_get_user_meta( $user_id, 'user_company_phone' ),
//	'email'       => $email,
//	'user_id'     => $user_id,
//) );
//var_dump($r);
//$url = carbon_get_theme_option( 'zoho_url' );
//if ( $url ) {
//	$url .= 'Deals/669263000000406401';
//	$url .= 'Deals/669263000000497014';
//	$url .= 'Accounts/669263000000498016';

//	var_dump(zoho_request( $url, false, 'GET' ));
//}
//edit_zoho_user(array(
//        'id' => $user_id,
//        'tag' => 'Seller',
//));
//print_r(get_zoho_account_by_user_id());
echo '</pre>';
?>

<section class="main-cabinet">
	<?php the_aside(); ?>
    <div class="main-cabinet__content">
		<?php
		if ( $route == '' ) {
			the_user_data_editing();
		} elseif ( $route == 'advertisement' ) {
			the_user_advertisement();
		} elseif ( $route == 'create' ) {
			create_advertisements();
		} elseif ( $route == 'edit' ) {
			edit_advertisement();
		} elseif ( $route == 'history' ) {
			the_user_history();
		} elseif ( $route == 'verification' ) {
			the_user_verification();
		} elseif ( $route == 'users' ) {
			the_users_settings();
		} elseif ( $route == 'payment_history' ) {
			the_payment_history();
		} elseif ( $route == 'message' ) {
			the_chat_page();
		} elseif ( $route == 'notifications' ) {
			the_notifications_page();
		} elseif ( $route == 'packages' ) {
			the_promo_page();
		}
		?>
    </div>
</section>

<?php if ( $form_support ): ?>

    <a class="support-link modal_open" href="#modal-support">
        <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve" style="enable-background:new 0 0 494.9 496"
             viewBox="0 0 494.9 496">
                <path d="m402 432-18.5-55.6c-6.5-19.4-23-33.7-43.1-37.4l-53.5-9.7v-4.1c9.3-5.4 17.4-12.6 23.8-21.2h16.2c13.2 0 24-10.8 24-24v-96c0-57.3-46.7-104-104-104s-104 46.7-104 104v56c0 15.4 11 28.3 25.5 31.3 4.5 23 18.9 42.4 38.5 53.8v4.1l-53.5 9.7c-20.1 3.7-36.6 18-43.1 37.4l-3.2 9.6-27.2-27.2c-3.1-3.1-3.1-8.2 0-11.3l31.6-31.6-59.3-59.3-35.7 35.7C5.8 302.9 0 316.9 0 331.9s5.8 29 16.4 39.6l108.1 108.1c10.6 10.6 24.6 16.4 39.6 16.4s29-5.8 39.3-16.2l39-35.7-12.1-12.1H402zm-67.1-152c0 4.4-3.6 8-8 8h-6.7c2.3-5.3 4.1-10.9 5.2-16.7 3.4-.7 6.6-2.1 9.5-3.8V280zm-8-26.2v-27.6c4.8 2.8 8 7.9 8 13.8s-3.3 11-8 13.8zm-160 0c-4.8-2.8-8-7.9-8-13.8s3.2-11 8-13.8v27.6zm0-53.8v9.1c-2.8.7-5.5 1.9-8 3.3V184c0-48.5 39.5-88 88-88s88 39.5 88 88v28.4c-2.5-1.4-5.2-2.6-8-3.3V200h-8c-23.7 0-45.9-9.2-62.6-25.9l-9.4-9.4-9.4 9.4c-16.7 16.7-39 25.9-62.6 25.9h-8zm16 56v-40.3c24-1.8 46.4-11.7 64-28.4 17.6 16.7 40 26.6 64 28.4V256c0 11.7-3.2 22.6-8.7 32h-55.3v16H289c-11.3 9.9-26 16-42.2 16-35.2 0-63.9-28.7-63.9-64zm88 76.3v.4l-24 24-24-24v-.4c7.6 2.4 15.6 3.7 24 3.7s16.4-1.3 24-3.7zm-145.5 49.2c4.6-13.9 16.4-24.1 30.8-26.7l55.9-10.2 34.7 34.7 34.7-34.7 55.9 10.2c14.4 2.6 26.2 12.8 30.8 26.7l11.5 34.5H214.3l-31.2-31.2-34.6 31.3c-3 3-8.3 3-11.3 0l-17.5-17.5 5.7-17.1zM52.1 279.2l36.7 36.7-8.7 8.7-36.7-36.7 8.7-8.7zm140.3 189.1c-7.6 7.5-17.6 11.7-28.3 11.7s-20.7-4.2-28.3-11.7L27.7 360.2c-7.6-7.6-11.7-17.6-11.7-28.3s4.2-20.7 11.7-28.3l4.4-4.4 36.7 36.7-.3.3c-9.4 9.4-9.4 24.6 0 33.9l57.4 57.4c4.5 4.5 10.6 7 17 7s12.4-2.5 16.7-6.8l.6-.5 36.9 36.9-4.7 4.2zm16.4-15L172 416.5l10.6-9.6 36.7 36.7-10.5 9.7zM470.9 0h-112c-13.2 0-24 10.8-24 24v64c0 13.2 10.8 24 24 24h21.4l-8.3 48.2 77.2-48.2h21.7c13.2 0 24-10.8 24-24V24c0-13.2-10.8-24-24-24zm8 88c0 4.4-3.6 8-8 8h-26.3l-50.8 31.8 5.4-31.8h-40.3c-4.4 0-8-3.6-8-8V24c0-4.4 3.6-8 8-8h112c4.4 0 8 3.6 8 8v64z"/>
            <path d="M366.9 32h96v16h-96zM366.9 64h64v16h-64zM446.9 64h16v16h-16zM246.9 24c-104.5 0-194.6 72.3-218 173.2l-15.2-25.3L0 180.1 28.1 227 75 198.9l-8.2-13.7-21.7 13C67.7 105.9 150.7 40 246.9 40c21 0 41.8 3.1 61.6 9.3l4.7-15.3c-21.4-6.6-43.7-10-66.3-10zM491 177.1 444.1 149 416 195.9l13.7 8.2 14-23.3c7.4 21.6 11.1 44.1 11.1 67.2 0 47.6-16.5 94.1-46.4 131l12.4 10.1c32.2-39.7 50-89.8 50-141 0-24.5-3.9-48.4-11.6-71.2l23.5 14.1 8.3-13.9z"/>
            </svg>
    </a>

    <div class="modal modal-sm" id="modal-support">
        <div class="modal-content">
            <div class="modal-title text-center">
                <div class="modal-title__main">Виникли питання?</div>
                <div class="modal-title__subtitle">Заповніть форму і ми звяжемося із вами.</div>
            </div>
			<?php echo do_shortcode( $form_support ); ?>
        </div>
    </div>

<?php endif; ?>

<div class="modal modal-sm" id="modal-forgot">
    <div class="modal-content">
        <div class="modal-title text-center">
            <div class="modal-title__main">Забули пароль?</div>
        </div>
        <form class="form-js forgot-password-form" id="forgot-password-form" novalidate method="post">
            <input type="hidden" name="action" value="forgot_password">
            <div class="form-group">
                <input class="input_st"
                       type="email"
                       placeholder="Ваш е-mail*"
                       name="email"
                       data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                       required
                />
            </div>
            <button class="btn_st w100" type="submit">
                <span>Відправити новий пароль </span>
            </button>
        </form>
    </div>
</div>

<div class="modal modal-sm" id="add-user-modal">
    <div class="modal-content">
        <div class="modal-title text-center">
            <div class="modal-title__main">Введіть email користувача</div>
        </div>
        <form class="form-js add-user-form" id="add-user-form" novalidate method="post">
            <input type="hidden" name="action" value="add_user_email">
            <div class="form-group">
                <input class="input_st"
                       type="email"
                       placeholder="Email*"
                       name="email"
                       data-reg="[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])"
                       required
                />
            </div>
            <button class="btn_st w100" type="submit">
                <span>Відправити запрошення</span>
            </button>
        </form>
    </div>
</div>

<?php if ( $purchased_id && get_post( $purchased_id ) && $_purchased_id !== $purchased_id ):
	$purchased_status     = carbon_get_post_meta( $purchased_id, 'purchased_status' ) ?: 'not_pay';
	$erroripsmessage      = carbon_get_post_meta( $purchased_id, 'portmone_erroripsmessage' ) ?: '';
	$portmone_receipt_url = carbon_get_post_meta( $purchased_id, 'portmone_receipt_url' ) ?: '';
	$str                  = $purchased_status === 'payed' ? ' успішно оплачене' : ' неоплачене';
	?>
    <script>
        var purchased_id = '<?php echo $purchased_id; ?>'
    </script>
    <div class="modal modal-sm" id="dialog-after-pay">
        <div class="modal-content text-center">
            <div class="modal-title">
                <div class="modal-title__main">Ваше замовлення <?php echo $str; ?></div>
                <div class="modal-title__subtitle">
					<?php echo $purchased_status == 'not_pay' ? $erroripsmessage : ''; ?>
                </div>
				<?php if ( $portmone_receipt_url ): ?>
                    <div class="btn_center ">
                        <a class="btn_st" target="_blank" rel="nofollow"
                           href="<?php echo $portmone_receipt_url; ?>">
                            <span>Скачати чек</span>
                        </a>
                    </div>
				<?php endif; ?>
            </div>
        </div>
    </div>
<?php endif; ?>

<?php get_footer(); ?>
