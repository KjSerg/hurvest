<?php

function the_notifications_page() {
	the_header_cabinet();
	$var            = variables();
	$set            = $var['setting_home'];
	$assets         = $var['assets'];
	$url            = $var['url'];
	$url_home       = $var['url_home'];
	$admin_ajax     = $var['admin_ajax'];
	$user_id        = get_current_user_id();
	$permalink      = get_the_permalink() ?: $url;
	$route          = $_GET['route'] ?? '';
	$subpage        = $_GET['subpage'] ?? '';
	$get_product_id = $_GET['product_id'] ?? '';
	$personal_page  = carbon_get_theme_option( 'personal_area_page' );
	$_url           = $personal_page ? get_the_permalink( $personal_page[0]['id'] ) : $url;
	$current_user   = get_user_by( 'ID', $user_id );
	$email          = $current_user->user_email ?: '';
	$display_name   = $current_user->display_name ?: '';
	$first_name     = $current_user->first_name ?: '';
	$last_name      = $current_user->last_name ?: '';
	$name           = $first_name ?: $display_name;
	$user_avatar    = carbon_get_user_meta( $user_id, 'user_avatar' );
	$args           = array(
		'post_type'      => 'notifications',
		'posts_per_page' => - 1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			array(
				'key'   => '_notification_recipient_id',
				'value' => $user_id
			)
		)
	);
	?>
    <div class="create-item-main">
        <div class="sort-wrap">
            <div class="search-wrap"></div>
            <div class="sort-group">
                <div>
                    <div class="form-horizontal">
                        <div class="half"></div>
                        <div class="half">
                            <a class="btn_st b_yelloow remove-notifications" href="#">
                                <span>Виділити всі сповіщення</span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="notification">
			<?php
			$query = new WP_Query( $args );
			if ( $query->have_posts() ):
				while ( $query->have_posts() ):
					$query->the_post();
					the_notification();
				endwhile;
			else:
				?>
                <div class="text-group">
                    <h6>Сповіщень поки немає</h6>
                </div>
			<?php
			endif;
			wp_reset_postdata();
			wp_reset_query();
			?>
        </div>
    </div>
	<?php
}

function the_notification( $id = false ) {
	$id           = $id ?: get_the_ID();
	$title        = get_the_title( $id );
	$text         = carbon_get_post_meta( $id, 'notification_text' );
	$sender_id    = carbon_get_post_meta( $id, 'notification_sender_id' );
	$recipient_id = carbon_get_post_meta( $id, 'notification_recipient_id' );
	$is_read      = carbon_get_post_meta( $id, 'notification_is_read' ) == 'read';
	$sender_user  = get_user_by( 'ID', $sender_id );
	$display_name = $sender_user->display_name ?: '';
	$first_name   = $sender_user->first_name ?: '';
	$last_name    = $sender_user->last_name ?: '';
	$name         = $first_name ?: $display_name;
	?>
    <div class="notification-item">
        <div class="notification-item__top">
            <div class="notification-left">
                <div class="notification-ico <?php echo $is_read ? '' : 'unread'; ?>">
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                         style="enable-background:new 0 0 17 18" viewBox="0 0 17 18">
                                            <path d="M8.5 0C4.8 0 1.8 2.7 1.8 6.1v4.8l-1.5 2c-.5.8-.3 1.8.5 2.3.3.2.6.3 1 .3h13.4c1 0 1.8-.8 1.8-1.7 0-.3-.1-.6-.3-.9l-1.5-2V6.1c0-3.4-3-6.1-6.7-6.1zM8.5 18c1.1 0 2.1-.7 2.5-1.6H6c.3.9 1.4 1.6 2.5 1.6z"
                                                  style="fill:#fff"/>
                                        </svg>
                </div>
                <div class="notification-title">
					<?php echo str_replace( '%user%', $name, $title ); ?>
                </div>
            </div>
            <div class="notification-right">
                <span class="notification-status <?php echo $is_read ? '' : 'unread'; ?>"><?php echo $is_read ? 'Прочитане' : 'Непрочитане'; ?></span>
                <div class="notification-date">
					<?php echo get_the_date( 'd.m.Y H:i', $id ); ?>
                </div>
                <span class="tog_hide_notification"></span>
            </div>
        </div>
        <div class="notification-item__hide">
            <div class="notification-item__hide_content">
                <div class="notification-text">
                    <div class="text-group">
						<?php echo str_replace( '%user%', $name, $text ); ?>
                    </div>
                </div>
                <a class="remove-cart remove-notification" data-id="<?php echo $id; ?>"
                   href="#">
                    Видалити
                    <svg xmlns="http://www.w3.org/2000/svg" xml:space="preserve"
                         style="enable-background:new 0 0 17.3 20" viewBox="0 0 17.3 20">
                                            <path d="M5.8 0c-.5 0-.9.4-.9.9v1.8h-4c-.5 0-.9.4-.9.9s.4.9.9.9h15.6c.5 0 .9-.4.9-.9s-.4-.9-.9-.9h-4V.9c0-.5-.4-.9-.9-.9H5.8zm.9 2.6v-.8h4.1v.8H6.7zM15.5 6.4H1.7v10.2c0 2 1.4 3.5 3.3 3.5h7.2c1.9 0 3.3-1.5 3.3-3.5V6.4zm-8 3.2c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6zm4.1 0c0-.5-.4-.9-.9-.9s-.9.4-.9.9v6.1c0 .5.4.9.9.9s.9-.4.9-.9V9.6z"
                                                  style="fill-rule:evenodd;clip-rule:evenodd;fill:#fff"/>
                                        </svg>
                </a>
            </div>
        </div>
    </div>
	<?php
	carbon_set_post_meta( $id, 'notification_is_read', 'read' );
}