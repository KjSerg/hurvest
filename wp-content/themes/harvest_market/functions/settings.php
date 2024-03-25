<?php
get_template_part( 'functions/inclusions/support-thumbnails' );
get_template_part( 'functions/inclusions/nav-menus' );
get_template_part( 'functions/inclusions/disable-content-editor' );
get_template_part( 'functions/inclusions/wpcf7-setting' );
get_template_part( 'functions/inclusions/custom-mime-types' );
get_template_part( 'functions/inclusions/custom-admin-js' );
get_template_part( 'functions/inclusions/advanced-search' );
//get_template_part('functions/inclusions/remove-taxonomy-permalink');
//get_template_part('functions/inclusions/remove-slug-cptui-permalink');
get_template_part( 'functions/inclusions/hide-admin-bar' );
get_template_part( 'functions/inclusions/only-admin' );
get_template_part( 'functions/inclusions/carbon-fields-customize' );
get_template_part( 'functions/inclusions/add-status-bubble' );
get_template_part( 'functions/inclusions/add-admin-preview' );
get_template_part( 'functions/inclusions/admin-columns' );
get_template_part( 'functions/components/harvy-comment' );
get_template_part( 'functions/components/the-aside' );
get_template_part( 'functions/components/user-components' );
get_template_part( 'functions/components/create-advertisement' );
get_template_part( 'functions/components/the-chat-page' );
get_template_part( 'functions/components/notifications' );
get_template_part( 'functions/components/promo' );
get_template_part( 'functions/components/seller-components' );

add_filter( 'get_the_archive_title', function ( $title ) {
	return preg_replace( '~^[^:]+: ~', '', $title );
} );

add_filter( 'wp_mail_content_type', 'true_content_type' );

function true_content_type( $content_type ) {
	return 'text/html';
}

add_filter( 'wp_mail_charset', 'true_mail_charset' );

function true_mail_charset( $content_type ) {
	return 'utf-8';
}

function post_unpublished( $new_status, $old_status, $post ) {
	$id        = $post->ID;
	$post_type = get_post_type( $id );
	$author_id = get_post_field( 'post_author', $id );
	if ( $post_type == 'applications' && $old_status != 'publish' && $new_status == 'publish' ) {
		$user_seller = carbon_get_user_meta( $author_id, 'user_seller' );
		if ( ! $user_seller ) {
			$user_company_description         = get_content_by_id($id) ?: '';
			$work_time                        = carbon_get_post_meta( $id, 'application_work_time_organization' ) ?: '';
			$application_company_postcode     = carbon_get_post_meta( $id, 'application_company_postcode' ) ?: '';
			$application_company_country      = carbon_get_post_meta( $id, 'application_company_country' ) ?: '';
			$application_company_country_code = carbon_get_post_meta( $id, 'application_company_country_code' ) ?: '';
			$application_company_latitude     = carbon_get_post_meta( $id, 'application_company_latitude' ) ?: '';
			$application_company_longitude    = carbon_get_post_meta( $id, 'application_company_longitude' ) ?: '';
			$application_company_region       = carbon_get_post_meta( $id, 'application_company_region' ) ?: '';
			$application_address              = carbon_get_post_meta( $id, 'application_address' ) ?: '';
			$application_city                 = carbon_get_post_meta( $id, 'application_city' ) ?: '';
			$application_phone                = carbon_get_post_meta( $id, 'application_phone' ) ?: '';
			$company_name                     = get_the_title( $id ) ?: '';
			$company_name                     = str_replace( '[edit]', '', $company_name );
			$text                             = get_content_by_id( $id ) ?: '';
			carbon_set_user_meta( $author_id, 'user_work_time_organization', $work_time );
			carbon_set_user_meta( $author_id, 'user_seller', true );
			carbon_set_user_meta( $author_id, 'user_company_phone', $application_phone );
			carbon_set_user_meta( $author_id, 'user_company_city', $application_city );
			carbon_set_user_meta( $author_id, 'user_company_address', $application_address );
			carbon_set_user_meta( $author_id, 'user_company_name', $company_name );
			carbon_set_user_meta( $author_id, 'user_company_description', $text );
			carbon_set_user_meta( $author_id, 'user_company_postcode', $application_company_postcode );
			carbon_set_user_meta( $author_id, 'user_company_country', $application_company_country );
			carbon_set_user_meta( $author_id, 'user_company_country_code', $application_company_country_code );
			carbon_set_user_meta( $author_id, 'user_company_latitude', $application_company_latitude );
			carbon_set_user_meta( $author_id, 'user_company_longitude', $application_company_longitude );
			carbon_set_user_meta( $author_id, 'user_company_region', $application_company_region );
			$post_author        = get_user_by( 'ID', $author_id );
			$var                = variables();
			$set                = $var['setting_home'];
			$assets             = $var['assets'];
			$url                = $var['url'];
			$personal_area_page = carbon_get_theme_option( 'personal_area_page' );
			$_l                 = $personal_area_page ? get_the_permalink( $personal_area_page[0]['id'] ) : $url;
			$permalink          = $_l . '?route=create';
			$post_data          = array(
				'post_type'    => 'post',
				'post_title'   => $company_name,
				'post_status'  => 'publish',
				'post_author'  => $author_id,
				'post_content' => $user_company_description,
			);
			$_id                = wp_insert_post( $post_data );
			$post               = get_post( $_id );
			if ( $post ) {
				carbon_set_user_meta( $author_id, 'user_post', $_id );
				$_permalink = get_the_permalink( $_id );
				carbon_set_post_meta( $_id, 'author_id', $author_id );
				$post_slug    = get_post_field( 'post_name', $_id );
				$link         = "<a href='$permalink' target='_blank'>Створити оголошення</a>";
				$link_seller  = "<a href='$_permalink' target='_blank'>Ваша сторінка продавця</a>";
				$message_text = "Вітаємо! <br> Ви стали продавцем у нас на сайті. <br> $link <br> $link_seller";
				send_message( $message_text, $post_author->user_email, $company_name . ' офіційний продавець на сайті' );
				update_post_meta( $_id, '_yoast_wpseo_metadesc', $user_company_description );
				if ( $logo = carbon_get_user_meta( $author_id, 'user_company_logo' ) ) {
					set_post_thumbnail( $_id, $logo );
				} else {
					set_post_thumbnail( $_id, 0 );
				}
			}
			$r = create_zoho_account( array(
				'name'        => $company_name,
				'description' => $text,
				'region'      => $application_company_region,
				'phone'       => $application_phone,
				'user_id'     => $author_id,
			) );
		}
	}
	if ( $post_type == 'products' && $old_status != 'publish' && $new_status == 'publish' ) {
		$post_author  = get_user_by( 'ID', $author_id );
		$title        = get_the_title( $id );
		$permalink    = get_the_permalink( $id );
		$link         = "<a href='$permalink' target='_blank'>$title</a>";
		$message_text = "Вітаємо! <br> Ваше оголошення було опубліковане на сайті. <br> Переглянути оголошення: $link";
		send_message( $message_text, $post_author->user_email, 'Оголошення ID:' . $id . ' опубліковане' );
	}
}

add_action( 'transition_post_status', 'post_unpublished', 10, 3 );

add_action( 'save_post', 'save_coupon' );
function save_coupon( $post_id ) {
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}
	if ( get_post_type( $post_id ) !== 'coupon' ) {
		return;
	}
	if ( ! carbon_get_post_meta( $post_id, 'coupon_send_notification' ) ) {
		return;
	}
	$active_users = carbon_get_post_meta( $post_id, 'active_users' );
	$coupon_sent  = carbon_get_post_meta( $post_id, 'coupon_sent' );
	$coupon       = get_the_title( $post_id );
	if ( $active_users ) {
		$users       = array();
		$coupon_sent = $coupon_sent ? explode( ',', $coupon_sent ) : array();
		foreach ( $active_users as $user ) {
			$user_id = $user['id'];
			$user    = get_user_by( 'ID', $user_id );
			if ( $user ) {
				$email = $user->user_email;
				if ( ! in_array( $user_id, $coupon_sent ) ) {
					$coupon_sent[] = $user_id;
					send_message( "Вам надано промокод на скидку '$coupon'. Перейдіть на сайт щоб його примінити.", $email, 'Промокод на скидку' );
				}
			}
		}
		carbon_set_post_meta( $post_id, 'coupon_sent', implode( ',', $coupon_sent ) );
	}
}

add_action( 'admin_enqueue_scripts', 'disable_ajax_on_edit_tags_page' );
function disable_ajax_on_edit_tags_page( $hook ) {
	// Перевірка, чи ми на сторінці редагування тегів
	if ( $hook === 'edit-tags.php' ) {
		// Відключаємо завантаження скрипта, який відповідає за AJAX
		wp_deregister_script( 'wp-ajax-response' );
	}
}

add_filter( 'nsl_register_new_user', function ( $user_id, $provider ) {
	$current_user      = get_user_by( 'ID', $user_id );
	$email             = $current_user->user_email ?: '';
	$display_name      = $current_user->display_name ?: '';
	$first_name        = $current_user->first_name ?: '';
	$last_name         = $current_user->last_name ?: '';
	$user_confirm_city = $_COOKIE['user_confirm_city'] ?? '';
	$user_city         = $_COOKIE['user_city'] ?? '';
	create_zoho_user( array(
		'first_name'  => $first_name ?: $email,
		'last_name'   => $last_name ?: $email,
		'email'       => $email,
		'id'          => $user_id,
		'city'        => $user_confirm_city ?: $user_city,
		'description' => $provider,
	) );
}, 10, 2 );

add_action( 'show_user_profile', 'add_tiktok_field' );
add_action( 'edit_user_profile', 'add_tiktok_field' );
function add_tiktok_field( $user ) {
	?>
    <h3>Інформація про TikTok</h3>
    <table class="form-table">
        <tr>
            <th><label for="tiktok">TikTok</label></th>
            <td>
                <input type="text" name="tiktok" id="tiktok"
                       value="<?php echo esc_attr( get_the_author_meta( 'tiktok', $user->ID ) ); ?>"
                       class="regular-text"/><br/>
                <span class="description"> ім'я користувача TikTok.</span>
            </td>
        </tr>
    </table>
	<?php
}

add_action( 'personal_options_update', 'save_tiktok_field' );
add_action( 'edit_user_profile_update', 'save_tiktok_field' );

function save_tiktok_field( $user_id ) {
	update_user_meta( $user_id, 'tiktok', $_POST['tiktok'] ?? '' );
}